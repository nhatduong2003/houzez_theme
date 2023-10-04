<?php
/**
 * Template Name: Stripe Charge Page
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 27/06/16
 * Time: 5:18 AM
 */
require_once( get_template_directory() . '/framework/stripe-php/init.php' );
$allowed_html = array();

$current_user = wp_get_current_user();
$userID       =   $current_user->ID;
$user_email   =   $current_user->user_email;
$admin_email  =  get_bloginfo('admin_email');
$username     =   $current_user->user_login;
$submission_currency = houzez_option('currency_paid_submission');
$thankyou_page_link = houzez_get_template_link('template/template-thankyou.php');
$paymentMethod = 'Stripe';
$time = time();
$date = date('Y-m-d H:i:s',$time);
$api_error = '';

$stripe_secret_key = houzez_option('stripe_secret_key');
$stripe_publishable_key = houzez_option('stripe_publishable_key');
$stripe_api = array(
    "secret_key"      => $stripe_secret_key,
    "publishable_key" => $stripe_publishable_key
);
\Stripe\Stripe::setApiKey($stripe_api['secret_key']);

/*--------------------------------------------------------------
* Webhook Start
---------------------------------------------------------------*/
$payload = @file_get_contents('php://input');
$event_json = json_decode( $payload );

if( ! empty( $payload ) ) {
   
    try {
        $stripe = new \Stripe\StripeClient( $stripe_api['secret_key'] );
        $event =  $stripe->events->retrieve(
          $event_json->id,
          []
        );
        
        // Get stripe customer id
        $customer_stripe_id = $event->data->object->customer;

        if ( 'customer.subscription.deleted' == $event->type ) {

            $customer_args = array(
                'meta_key'     => 'fave_stripe_user_profile',
                'meta_value'   => $customer_stripe_id,
                'meta_compare' => '=',
            );
            $customers = get_users( $customer_args );

            if ( ! empty( $customers ) ) {

                foreach ( $customers as $customer ) {
                    $current_membership = get_user_meta( $customer->ID, 'package_id', true );
                    houzez_stripe_cancel_subscription( $customer->ID, $current_membership );
                }
            }

        } elseif ( 'customer.subscription.created' === $event->type ) {

            $reminder = 0;

            $customer_args = array(
                'meta_key'     => 'fave_stripe_user_profile',
                'meta_value'   => $customer_stripe_id,
                'meta_compare' => '=',
            );
            $customers     = get_users( $customer_args );

            if ( ! empty( $customers ) ) {
                foreach ( $customers as $customer ) {
                    update_user_meta( $customer->ID, 'houzez_user_membership_reminder_mail', $reminder );
                }
            }

        } elseif ( 'invoice.payment_succeeded' === $event->type ) {

            $customer_args = array(
                'meta_key'     => 'fave_stripe_user_profile',
                'meta_value'   => $customer_stripe_id,
                'meta_compare' => '=',
            );
            $customers     = get_users( $customer_args );

            if ( ! empty( $customers ) ) {
                foreach ( $customers as $customer ) {

                    $package_id = get_user_meta( $customer->ID, 'package_id', true );
                    $subscription_id  = get_user_meta( $customer->ID, 'houzez_stripe_subscription_id', true );
                    $subscription     = \Stripe\Subscription::retrieve( $subscription_id );
                    $subscription_due = $subscription->current_period_end;
                    update_user_meta( $customer->ID, 'houzez_stripe_subscription_due', $subscription_due );

                    if( $customer->ID != 0 && $package_id != 0 ) {
                        houzez_save_user_packages_record( $customer->ID, $package_id );
                        if( houzez_check_user_existing_package_status( $customer->ID, $package_id ) ) {
                            houzez_downgrade_package(  $customer->ID, $package_id  );
                            houzez_update_membership_package( $customer->ID, $package_id );
                        } else {
                            houzez_update_membership_package( $customer->ID, $package_id );
                        } 

                        $invoiceID = houzez_generate_invoice( 'package', 'recurring', $package_id, $date, $customer->ID, 0, 0, '', $paymentMethod, 1 );
                        update_post_meta( $invoiceID, 'invoice_payment_status', 1 );

                        $args = array(
                            'recurring_package_name' => get_the_title($package_id),
                            'merchant'               => 'Stripe'
                        );
                        houzez_email_type( $customer->user_email, 'recurring_payment', $args );  
                    
                    } else {
                       // echo 'no user exist';           
                    } 
                }
            }
        }
        elseif ( 'invoice.created' === $event->type ) {

            $customer_args = array(
                'meta_key'     => 'fave_stripe_user_profile',
                'meta_value'   => $customer_stripe_id,
                'meta_compare' => '=',
            );
            $customers     = get_users( $customer_args );

            if ( ! empty( $customers ) ) {
                foreach ( $customers as $customer ) {

                    $membership_id = get_user_meta( $customer->ID, 'package_id', true );
                    $reminder_user = get_user_meta( $customer->ID, 'houzez_user_membership_reminder_mail', true );
                    if ( ! empty( $membership_id ) && ! empty( $reminder_user ) ) {
                        // send payment reminder email
                    }
                    update_user_meta( $customer->ID, 'houzez_user_membership_reminder_mail', 0 );
                }
            }
        }

        http_response_code( 200 );
        exit();

    } catch(\UnexpectedValueException $e) {
      // Invalid payload
      http_response_code(400);
      exit();
    } catch(\Stripe\Exception\SignatureVerificationException $e) {
      // Invalid signature
      http_response_code(400);
      exit();
    }
}

/*--------------------------------------------------------------
* Webhook End
---------------------------------------------------------------*/              

if( isset( $_GET['session_id'] ) && ! empty( $_GET['session_id'] ) && isset($_GET['mode']) && $_GET['mode'] == 'per_listing' ) { 
    $session_id = $_GET['session_id']; 

    $stripe = new \Stripe\StripeClient( $stripe_api['secret_key'] );

    // Fetch the Checkout Session to display the JSON result on the success page 
    try { 
        $stripeSessionInfo = $stripe->checkout->sessions->retrieve($session_id); 

        $userID         = $stripeSessionInfo->metadata->user_id;
        $submission_pay = $stripeSessionInfo->metadata->submission_pay;
        $is_featured    = $stripeSessionInfo->metadata->with_featured;
        $is_upgrade     = $stripeSessionInfo->metadata->is_upgrade;
        $relist_mode    = $stripeSessionInfo->metadata->relist_mode;
        $listing_id     = $stripeSessionInfo->metadata->property_id;
        $payment_status     = $stripeSessionInfo->payment_status;
        
        if( isset( $submission_pay ) && $submission_pay == 1 && $payment_status == 'paid' ) {
            
            if( isset( $is_upgrade ) && $is_upgrade == 1 ) {
                update_post_meta( $listing_id, 'fave_featured', 1 );
                update_post_meta( $listing_id, 'houzez_featured_listing_date', current_time( 'mysql' ) );
                $invoice_id = houzez_generate_invoice( 'Upgrade to Featured', 'one_time', $listing_id, $date, $userID, 0, 1, '', $paymentMethod );
                update_post_meta( $invoice_id, 'invoice_payment_status', 1 );

                $args = array(
                    'listing_title'  =>  get_the_title($listing_id),
                    'listing_id'     =>  $listing_id,
                    'invoice_no'     =>  $invoice_id,
                    'listing_url'    =>  get_permalink($listing_id),
                );

                /*
                 * Send email
                 * */
                houzez_email_type( $user_email, 'featured_submission_listing', $args);
                houzez_email_type( $admin_email, 'admin_featured_submission_listing', $args);

            } else {
                update_post_meta( $listing_id, 'fave_payment_status', 'paid' );

                $paid_submission_status    = houzez_option('enable_paid_submission');
                $listings_admin_approved = houzez_option('listings_admin_approved');

                

                if( $listings_admin_approved != 'yes'  && $paid_submission_status == 'per_listing' ){
                    $post = array(
                        'ID'            => $listing_id,
                        'post_status'   => 'publish'
                    );

                    if( isset($_POST['relist_mode']) &&  $_POST['relist_mode'] != "" ) {
                        $post['post_date'] = current_time( 'mysql' );
                    }

                    $post_id =  wp_update_post($post );
                } else {
                    $post = array(
                        'ID'            => $listing_id,
                        'post_status'   => 'pending'
                    );

                    if( isset( $relist_mode ) &&  $relist_mode != "" ) {
                        $post['post_date'] = current_time( 'mysql' );
                    }

                    $post_id =  wp_update_post($post );
                }


                if( isset( $is_featured ) && $is_featured == 1 ) {
                    update_post_meta( $listing_id, 'fave_featured', 1 );
                    $invoice_id = houzez_generate_invoice( 'Publish Listing with Featured', 'one_time', $listing_id, $date, $userID, 1, 0, '', $paymentMethod );
                } else {
                    $invoice_id = houzez_generate_invoice( 'Listing', 'one_time', $listing_id, $date, $userID, 0, 0, '', $paymentMethod );
                }
                update_post_meta( $invoice_id, 'invoice_payment_status', 1 );

                $args = array(
                    'listing_title'  =>  get_the_title($listing_id),
                    'listing_id'     =>  $listing_id,
                    'invoice_no'     =>  $invoice_id,
                    'listing_url'    =>  get_permalink($listing_id),
                );

                /*
                 * Send email
                 * */
                houzez_email_type( $user_email, 'paid_submission_listing', $args);
                houzez_email_type( $admin_email, 'admin_paid_submission_listing', $args);
            }

            wp_redirect( $thankyou_page_link ); exit;
        }

    } catch(Exception $e) {  
        $api_error = $e->getMessage();  
    } 

} else if( isset( $_GET['is_houzez_membership'] ) && $_GET['is_houzez_membership'] == 1 ) {
    if ( isset($_REQUEST['session_id']) ) {
        $session_id = $_GET['session_id']; 
    
        $stripe = new \Stripe\StripeClient( $stripe_api['secret_key'] );

        try { 
            $stripeSessionInfo = $stripe->checkout->sessions->retrieve($session_id);
            
            $stripeCustomerInfo = $stripe->customers->retrieve($stripeSessionInfo->customer);
            $stripePlanId = $stripeSessionInfo->display_items[0]->plan->id;
            $stripe_customer_id = $stripeCustomerInfo->id;

            $stripeSubscriptionInfo = $stripe->subscriptions->retrieve($stripeSessionInfo['subscription']);

            $subscription_id = $stripeSubscriptionInfo->id;
            $pack_id = $stripeSubscriptionInfo->metadata->package_id;
            $user_id = $stripeSubscriptionInfo->metadata->userID;
            $subscription_current_period_start = $stripeSubscriptionInfo->current_period_start;
            $subscription_current_period_end = $stripeSubscriptionInfo->current_period_end;

            if ( isset($stripeCustomerInfo->id) ) {

                $stripeInvoiceInfo = $stripe->invoices->retrieve($stripeSubscriptionInfo['latest_invoice']);
                $stripeInvoiceNumber = $stripeInvoiceInfo['number'];

                houzez_save_user_packages_record($user_id, $pack_id);
                if( houzez_check_user_existing_package_status($user_id, $pack_id) ) { 
                    houzez_downgrade_package( $user_id, $pack_id );
                    houzez_update_membership_package($user_id, $pack_id);
                } else { 
                    houzez_update_membership_package($user_id, $pack_id);
                }

                $invoiceID = houzez_generate_invoice( 'package', 'recurring', $pack_id, $date, $user_id, 0, 0, '', $paymentMethod, 1 );
                update_post_meta( $invoiceID, 'invoice_payment_status', 1 );

                /*$current_stripe_customer_id =  get_user_meta( $user_id, 'fave_stripe_user_profile', true );
                $is_stripe_recurring        =   get_user_meta( $user_id, 'houzez_has_stripe_recurring',true );
                if ($current_stripe_customer_id !=='' && $is_stripe_recurring == 1 ) {
                    if( $current_stripe_customer_id !== $stripe_customer_id ){
                        houzez_stripe_cancel_subscription();
                    }
                }*/

                update_user_meta( $user_id, 'houzez_subscription_detail_status', 'active');
                update_user_meta( $user_id, 'fave_stripe_user_profile', $stripe_customer_id );
                update_user_meta( $user_id, 'houzez_stripe_subscription_id', $subscription_id );
                update_user_meta( $user_id, 'houzez_stripe_subscription_start', $subscription_current_period_start );
                update_user_meta( $user_id, 'houzez_stripe_subscription_due', $subscription_current_period_end );
                update_user_meta( $user_id, 'houzez_has_stripe_recurring', 1 );
                update_user_meta( $user_id, 'houzez_is_recurring_membership', 1 );

                update_user_meta( $user_id, 'houzez_subscription_order_number', $stripeInvoiceNumber);
                update_user_meta( $user_id, 'houzez_subscription_session_id', $_REQUEST['session_id']);
                update_user_meta( $user_id, 'houzez_subscription_plan_id', $stripePlanId);
                update_user_meta( $user_id, 'houzez_membership_id', $pack_id);
                update_user_meta( $user_id, 'houzez_payment_method', $paymentMethod);

                $args = array();
                houzez_email_type( $user_email,'purchase_activated_pack', $args );

                wp_redirect( $thankyou_page_link ); exit;

            }

            //echo '<pre>';
            //echo $stripe_customer_id.' = '.$user_id.' = '.$subscription_current_period_end;
            //print_r($stripeInvoiceInfo);

        } catch(Exception $e) {  
            $api_error = $e->getMessage();  
        } 
    }

} else if ( isset( $_GET['mode'] ) && $_GET['mode'] == 'simple_package' ) {

  if ( isset($_REQUEST['session_id']) ) {
      $session_id = $_GET['session_id']; 
  
      $stripe = new \Stripe\StripeClient( $stripe_api['secret_key'] );
      try {
          $stripeSessionInfo = $stripe->checkout->sessions->retrieve($session_id); 
          $user_id         = $stripeSessionInfo->metadata->user_id;
          $pack_id   = $stripeSessionInfo->metadata->package_id;
          $payment_status     = $stripeSessionInfo->payment_status; 
          $stripeCustomerInfo = $stripe->customers->retrieve($stripeSessionInfo->customer);
          $stripe_customer_id = $stripeCustomerInfo->id;

          if ( $payment_status == 'paid' ) {
              houzez_save_user_packages_record($user_id, $pack_id);
              if( houzez_check_user_existing_package_status($user_id, $pack_id) ) { 
                  houzez_downgrade_package( $user_id, $pack_id );
                  houzez_update_membership_package($user_id, $pack_id);
              } else { 
                  houzez_update_membership_package($user_id, $pack_id);
              }

              $invoiceID = houzez_generate_invoice( 'package', 'one_time', $pack_id, $date, $user_id, 0, 0, '', $paymentMethod, 1 );
              update_post_meta( $invoiceID, 'invoice_payment_status', 1 );

              update_user_meta( $user_id, 'fave_stripe_user_profile', $stripe_customer_id );
              update_user_meta( $user_id, 'houzez_has_stripe_recurring', 0 );
              update_user_meta( $user_id, 'houzez_is_recurring_membership', 0 );
              update_user_meta( $user_id, 'houzez_simple_package_session_id', $_REQUEST['session_id']);
              update_user_meta( $user_id, 'houzez_payment_method', $paymentMethod);

              $args = array();
              houzez_email_type( $user_email,'purchase_activated_pack', $args );

                wp_redirect( $thankyou_page_link ); exit;
 
             }
 
             //echo '<pre>';
             //echo $stripe_customer_id.' = '.$user_id.' = '.$subscription_current_period_end;
             //print_r($stripeInvoiceInfo);
 
         } catch(Exception $e) {  
             $api_error = $e->getMessage();  
         } 
     }
 }
