<?php
if ( ! class_exists( 'Houzez_Woo_Payment' ) ) {

	class Houzez_Woo_Payment {

		public function __construct() {
	        
	        add_action( 'woocommerce_remove_cart_item',  array($this, 'woo_cart_updated'), 10, 2 );
	        add_action( 'wp_ajax_houzez_perlist_woo_pay',         array( $this, 'houzez_perlist_woo_pay') );
        	add_action( 'wp_ajax_mopriv_houzez_perlist_woo_pay',  array( $this, 'houzez_perlist_woo_pay') );
        	add_action( 'wp_ajax_houzez_woo_pay_package',         array( $this, 'houzez_woo_pay_package') );
        	add_action( 'wp_ajax_mopriv_houzez_woo_pay_package',  array( $this, 'houzez_woo_pay_package') );
        	add_action( 'houzez_per_listing_woo_payment', array( $this, 'per_listing_while_submission' ), 10, 1 );
        	add_action( 'woocommerce_order_status_completed',       array( $this, 'woo_payment_complete') );
        	add_action( 'woocommerce_order_status_processing',      array( $this, 'woo_payment_complete') );
        	add_filter( 'woocommerce_cart_item_permalink','__return_false');
        	add_action( 'woocommerce_before_single_product',        array( $this, 'product_redirect') );
        	add_action( 'woocommerce_product_query',                array( $this, 'custom_pre_get_posts_query' ) );
		}

		public function houzezWooCommerce() {

	        if( houzez_option('houzez_payment_gateways', 'houzez_custom_gw') == 'gw_woocommerce' ) {
	            return true;
	        } else {
	            return false;
	        }
	    }

	    function woo_cart_updated( $cart_item_key, $cart ) {

		    $product_id = $cart->cart_contents[ $cart_item_key ]['product_id']; 
		    $is_houzez_woocommerce = intval( get_post_meta( $product_id, '_is_houzez_woocommerce', true ) );

		    if( $is_houzez_woocommerce == 1 ) {
		    	wp_delete_post( $product_id );
		    }
		  
		}

	    function per_listing_while_submission( $listing_id ) {

	    	$listing_id   = intval($listing_id);
	    	$is_featured   = 0;

	    	$product_id  = $this->checkIfAlreadyInCart($listing_id);

	    	if( $product_id == 0 ) {
	    		$product_id = $this->houzez_per_listing_payment($listing_id, $is_featured);
	    	}

	    	$cart = WC()->cart->add_to_cart( $product_id, 1, '', [], [ '__booking_data' => '' ] );
	       	
	       	$woo_checkout_url = wc_get_checkout_url();
	    	wp_redirect($woo_checkout_url);
	    	exit();
	    }

	    function houzez_perlist_woo_pay() {

	    	$listing_id   = intval($_POST['listing_id']);
	    	$is_featured   = intval($_POST['is_featured']);

	    	$product_id  = $this->checkIfAlreadyInCart($listing_id);

	    	if( $product_id == 0 ) {
	    		$product_id = $this->houzez_per_listing_payment($listing_id, $is_featured);
	    	}

	    	$cart = WC()->cart->add_to_cart( $product_id, 1, '', [], [ '__booking_data' => '' ] );
	       
	    	return $cart;
	    }

	    function houzez_woo_pay_package() {

	    	$package_id   = intval($_POST['package_id']);

	    	$product_id  = $this->checkIfAlreadyInCart($package_id);

	    	if( $product_id == 0 ) {
	    		$product_id = $this->houzez_package_payment($package_id);
	    	}

	    	$cart = WC()->cart->add_to_cart( $product_id, 1, '', [], [ '__booking_data' => '' ] );
	       
	    	return $cart;
	    }


	    function woo_payment_complete( $order_id ) {   
	        $order    = wc_get_order( $order_id );
	        $products = $order->get_items();

	        foreach( $products as $product ) { 

	            $product_id = $product['product_id'];
	            $order_title = $product['name'];

	            $is_woocommerce = intval( get_post_meta( $product_id, '_is_houzez_woocommerce', true ) );
	            $payment_mode 	= get_post_meta( $product_id, '_is_houzez_payment_mode', true );
	            
	            if( $payment_mode == 'per_listing' ) {

	            	$this->per_listing_payment_completed( $product_id, $order, $order_title );
	            	
	            } else if( $payment_mode == 'package' ) {
	            	$this->package_payment_completed( $product_id, $order, $order_title );
	            }

	            if( $is_woocommerce == 1 ) {
			    	wp_delete_post( $product_id );
			    }
	                  
	        }
	    
	    }

	    function houzez_per_listing_payment($listing_id, $is_featured ) {

	    	$current_user = wp_get_current_user();
			$userID       = get_current_user_id();
			$user_email   = $current_user->user_email;
	        
	        if( $is_featured == 1 ) {

	        	$listing_price = houzez_option('price_featured_listing_submission');
	        	$product_title = sprintf( esc_html__('Upgrade to "Featured" for Listing "%s" with id %s', 'houzez-woo-addon'), get_the_title($listing_id),$listing_id);

	        } else {

	        	$listing_price = houzez_option('price_listing_submission');
	        	$product_title = sprintf( esc_html__('Payment for Listing "%s" with id %s', 'houzez-woo-addon'), get_the_title($listing_id),$listing_id);
	        }
	    	
	        $args = array(
                'post_content'   => '',
                'post_status'    => "publish",
                'post_title'     => $product_title,
                'post_parent'    => '',
                'post_type'      => "product",
                'comment_status' => 'closed'
            );

	        $product_id = wp_insert_post( $args );
	        
	        
	        update_post_meta( $product_id, '_is_houzez_woocommerce', true );
	        update_post_meta( $product_id, '_is_houzez_payment_mode', 'per_listing' );
	        update_post_meta( $product_id, '_virtual', 'yes' );  //no
	        update_post_meta( $product_id, '_sold_individually', 'yes' ); //no
	        update_post_meta( $product_id, '_manage_stock', 'no' ); //no
	        update_post_meta( $product_id, '_featured', 'no' );
	        update_post_meta( $product_id, '_stock_status', 'instock' ); //instock
	        update_post_meta( $product_id, '_visibility', 'visible' );
	        update_post_meta( $product_id, '_downloadable', 'no' ); //no
	        update_post_meta( $product_id, '_invoice_id', $listing_id );
	        update_post_meta( $product_id, '_backorders', 'no' ); //no
	        update_post_meta( $product_id, '_price', $listing_price ); //''
	        update_post_meta( $product_id, '_houzez_listing_id', $listing_id );
	        update_post_meta( $product_id, '_houzez_is_featured', $is_featured );
	        update_post_meta( $product_id, '_houzez_featured_listing_date', current_time( 'mysql' ) );
	        update_post_meta( $product_id, 'houzez_featured_listing_date', current_time( 'mysql' ) );
	        update_post_meta( $product_id, '_houzez_user_id', $userID );
	        update_post_meta( $product_id, '_houzez_user_email', $user_email );
	        
	        update_post_meta( $product_id, '_wc_min_qty_product', 1 );
	        update_post_meta( $product_id, '_wc_max_qty_product', 1 );
	        $data_variation = [
	            'types' => [
	                'name'         => 'types',
	                'value'        => 'service',
	                'position'     => 0,
	                'is_visible'   => 1,
	                'is_variation' => 1,
	                'is_taxonomy'  => 1
	            ]
	        ];
	        update_post_meta( $product_id, '_product_attributes', $data_variation );
	        update_post_meta( $product_id, '_product_version', '4.2.0' );
	        
	        return $product_id;
	        
	    }

	    function houzez_package_payment( $package_id ) {

	    	$current_user = wp_get_current_user();
			$userID       = get_current_user_id();
			$user_email   = $current_user->user_email;

			$pack_price = get_post_meta( $package_id, 'fave_package_price', true );
	        
	        $product_title = sprintf( esc_html__('Payment for package "%s"', 'houzez-woo-addon'), get_the_title($package_id));
	    	
	        $args = array(
                'post_content'   => '',
                'post_status'    => "publish",
                'post_title'     => $product_title,
                'post_parent'    => '',
                'post_type'      => "product",
                'comment_status' => 'closed'
            );

	        $product_id = wp_insert_post( $args );
	        
	        
	        update_post_meta( $product_id, '_is_houzez_woocommerce', true );
	        update_post_meta( $product_id, '_is_houzez_payment_mode', 'package' );
	        update_post_meta( $product_id, '_virtual', 'yes' );  //no
	        update_post_meta( $product_id, '_sold_individually', 'yes' ); //no
	        update_post_meta( $product_id, '_manage_stock', 'no' ); //no
	        update_post_meta( $product_id, '_featured', 'no' );
	        update_post_meta( $product_id, '_stock_status', 'instock' ); //instock
	        update_post_meta( $product_id, '_visibility', 'visible' );
	        update_post_meta( $product_id, '_downloadable', 'no' ); //no
	        update_post_meta( $product_id, '_invoice_id', $package_id );
	        update_post_meta( $product_id, '_backorders', 'no' ); //no
	        update_post_meta( $product_id, '_price', $pack_price ); //''
	        update_post_meta( $product_id, '_houzez_package_id', $package_id );
	        update_post_meta( $product_id, '_houzez_user_id', $userID );
	        update_post_meta( $product_id, '_houzez_user_email', $user_email );
	        
	        update_post_meta( $product_id, '_wc_min_qty_product', 1 );
	        update_post_meta( $product_id, '_wc_max_qty_product', 1 );
	        $data_variation = [
	            'types' => [
	                'name'         => 'types',
	                'value'        => 'service',
	                'position'     => 0,
	                'is_visible'   => 1,
	                'is_variation' => 1,
	                'is_taxonomy'  => 1
	            ]
	        ];
	        update_post_meta( $product_id, '_product_attributes', $data_variation );
	        update_post_meta( $product_id, '_product_version', '4.2.0' );
	        
	        return $product_id;
	        
	    }


	    function checkIfAlreadyInCart($invoice_no) {
           
	       $product_id = 0;

           $args = array(
                'post_type'      => 'product',
                'meta_key'       => '_invoice_id',
                'meta_value'     => $invoice_no,
                'posts_per_page' => 1
            );
          
            $qry = new WP_Query( $args );

            if ( $qry->have_posts() ):
                while ( $qry->have_posts() ): $qry->the_post();
                    $product_id =  get_the_ID();
                endwhile;
            endif;

            return $product_id;
     	}

     	function per_listing_payment_completed( $product_id, $woo_order, $order_title ) {

     		$admin_email  =  get_bloginfo('admin_email');
			$payment_method_title = $woo_order->get_payment_method_title();

			$time = time();
			$date = date('Y-m-d H:i:s',$time);

			$is_featured = get_post_meta( $product_id, '_houzez_is_featured', true );
	        $listing_id = intval( get_post_meta( $product_id, '_houzez_listing_id', true ) );
	        $userID = intval( get_post_meta( $product_id, '_houzez_user_id', true ) );
	        $user_email = get_post_meta( $product_id, '_houzez_user_email', true );

			if( $is_featured == 1 ) {

	            update_post_meta( $listing_id, 'fave_featured', 1 );
	            update_post_meta( $listing_id, 'houzez_featured_listing_date', current_time( 'mysql' ) );
	            $invoice_id = houzez_generate_invoice( $order_title, 'one_time', $listing_id, $date, $userID, 0, 1, '', $payment_method_title );
	            update_post_meta( $invoice_id, 'invoice_payment_status', 1 );

	            $args = array(
	                'listing_title'  =>  get_the_title($listing_id),
	                'listing_id'     =>  $listing_id,
	                'invoice_no' =>  $invoice_id,
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
	                    'post_status'   => 'publish',
	                    'post_date'     => current_time( 'mysql' )
	                );
	                $post_id =  wp_update_post($post );
	            } 

	            $invoice_id = houzez_generate_invoice( $order_title, 'one_time', $listing_id, $date, $userID, 0, 0, '', $payment_method_title );
	            update_post_meta( $invoice_id, 'invoice_payment_status', 1 );

	            $args = array(
	                'listing_title'  =>  get_the_title($listing_id),
	                'listing_id'     =>  $listing_id,
	                'invoice_no' =>  $invoice_id,
	            );

	            /*
	             * Send email
	             * */
	            houzez_email_type( $user_email, 'paid_submission_listing', $args);
	            houzez_email_type( $admin_email, 'admin_paid_submission_listing', $args);
	        }
     	}

     	function package_payment_completed( $product_id, $woo_order, $order_title ) {

     		$admin_email  =  get_bloginfo('admin_email');
			$payment_method_title = $woo_order->get_payment_method_title();

			$time = time();
			$date = date('Y-m-d H:i:s',$time);

	        $package_id = intval( get_post_meta( $product_id, '_houzez_package_id', true ) );
	        $userID = intval( get_post_meta( $product_id, '_houzez_user_id', true ) );
	        $user_email = get_post_meta( $product_id, '_houzez_user_email', true );

	        houzez_save_user_packages_record($userID);
	        if( houzez_check_user_existing_package_status($userID, $package_id) ){
	            houzez_downgrade_package( $userID, $package_id );
	            houzez_update_membership_package($userID, $package_id);
	        }else{
	            houzez_update_membership_package($userID, $package_id);
	        }

	        $invoiceID = houzez_generate_invoice( $order_title, 'one_time', $package_id, $date, $userID, 0, 0, '', $payment_method_title, 1 );
	        update_post_meta( $invoiceID, 'invoice_payment_status', 1 );

	        update_user_meta( $userID, 'houzez_has_stripe_recurring', 0 );

	        $args = array();
	        houzez_email_type( $user_email,'purchase_activated_pack', $args );

     	}

     	function custom_pre_get_posts_query( $query ) {
	        $meta_query = (array) $query->get( 'meta_query' );
	        $meta_query[] = array(
	                'meta_key'      => '_is_houzez_woocommerce',
	                'meta_compare'  => 'NOT EXISTS',
	                'value'         => ''
	               );
	        $query->set( 'meta_query', $meta_query );
	    }

	    function product_redirect() {

	        $is_houzez_custom = get_post_meta( get_the_ID(), '_is_houzez_woocommerce', true );
	        
	        if( $is_houzez_custom == 1 ) {
	            wp_redirect( home_url(), 301 );
	            exit();
	        }
	    }
		
	}
	new Houzez_Woo_Payment();
}