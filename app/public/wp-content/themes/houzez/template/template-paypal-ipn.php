<?php
/**
 * Template Name: Paypal Webhook ( Recurring Payment )
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 11/09/16
 * Time: 3:30 PM
 */
$token = '';
define('DEBUG',0);

$time = time();
$date = date('Y-m-d H:i:s',$time);

$payload       = file_get_contents( 'php://input' );
$payload_array = explode( '&', $payload );
$myPost        = array();

if ( empty( $payload_array ) ) {
  return false;
}

foreach ($payload_array as $keyval) {
        $keyval = explode( '=', $keyval );
        if ( count($keyval) == 2 ) {
          $myPost[$keyval[0]] = urldecode($keyval[1]);
        }
}
// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';
if( function_exists('get_magic_quotes_gpc') ) {
   $get_magic_quotes_exists = true;
} 

foreach ($myPost as $key => $value) {        
    if( $get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1 ) { 
        $value = urlencode(stripslashes($value)); 
    } else {
        $value = urlencode($value);
    }
    $req .= "&$key=$value";
}

// POST IPN data back to PayPal to validate
$is_paypal_live  =   houzez_option('paypal_api');
$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";

if( $is_paypal_live == 'live' ){
    $paypal_url = "https://www.paypal.com/cgi-bin/webscr";
}
 
$args = array(
  'method' => 'POST',
  'timeout' => 45,
  'redirection' => 5,
  'httpversion' => '1.0',
  'sslverify' => false,
  'blocking' => true,
  'body' =>  $req,
);
     
$response   =   wp_remote_post( $paypal_url, $args ); 
$res        =   '';


if ( is_wp_error( $response ) ) {
    $error_message = $response->get_error_message();
    wp_die($error_message);
} else {
    $res = wp_remote_retrieve_body( $response );
}  

if (strcmp ($res, "VERIFIED") == 0) {

        $allowed_html   =   array();

        $payer_email            =   wp_kses ( esc_html($_POST['payer_email']) ,$allowed_html);
        $amount                 =   wp_kses ( esc_html($_POST['amount']),$allowed_html );
        $recurring_payment_id   =   wp_kses ( esc_html($_POST['recurring_payment_id']),$allowed_html );

        $payment_status         =   wp_kses ( esc_html( $_POST['payment_status'] ),$allowed_html );
        $txn_id                 =   wp_kses ( esc_html ($_POST['txn_id']),$allowed_html );
        $txn_type               =   wp_kses ( esc_html($_POST['txn_type']),$allowed_html ); 
        $receiver_email         =   wp_kses ( esc_html($_POST['receiver_email']),$allowed_html );
        $payer_id               =   wp_kses ( esc_html($_POST['payer_id']),$allowed_html );
        
        $user_id                =   houzez_retrive_user_by_profile($recurring_payment_id);     
        $pack_id                =   get_user_meta($user_id, 'package_id',true);
        $price                  =   get_post_meta($pack_id, 'fave_package_price', true);

        if( $payment_status=='Completed' ) {
        
            // payment already processd
            if( houzez_retrive_invoice_by_taxid($txn_id) ) { 
                exit();
            }

            // user with not profile id
            if( $user_id == 0 ) {
                exit();
            }
            
            // Received payment diffrent than pack value
            if( $amount != $price){
                exit();
            }

            $txn_id = '';

            houzez_save_user_packages_record($user_id, $pack_id);
            houzez_update_membership_package($user_id, $pack_id);


            $args  =array(
                'recurring_package_name' => get_the_title($pack_id),
                'merchant'               => 'Paypal'
            );
            houzez_email_type( $receiver_email, 'recurring_payment', $args );
         
        } else {
           
            if($txn_type == 'recurring_payment_profile_cancel') {
               update_user_meta( $user_id, 'houzez_is_recurring_membership', 0 );
               update_user_meta( $user_id, 'houzez_subscription_detail_status', 'expired' );
               update_user_meta( $user_id, 'houzez_has_stripe_recurring', 0 );
               update_user_meta( $user_id, 'houzez_is_recurring_membership', 0 );
               update_user_meta( $user_id, 'houzez_paypal_recurring_profile_id', '' );
               update_user_meta( $user_id, 'fave_paypal_profile', '' );
            }
        }
 
} else if (strcmp ($res, "INVALID") == 0) {
    exit('invalid exit');    
}