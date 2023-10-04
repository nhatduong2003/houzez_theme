<?php
/**
 * Template Name: Login & Register
 * User: waqasriaz
 * Date: 1 July 2020
 * Time: 11:47 AM
 */
get_header(); ?>
	
<section class="blog-wrap">
    <div class="container">
    	<div class="page-title-wrap login-page-title">
            <div class="d-flex align-items-center text-center">
                <div class="page-title flex-grow-1">
					<h1><?php the_title(); ?></h1>
				</div><!-- page-title --> 
            </div><!-- d-flex -->  
        </div>
        <div class="row">
            <div class="col-lg-12">                      
                
                <?php if( !is_user_logged_in() ) { ?>
                
                	<?php if( isset( $_GET['verrify-email'] ) && isset( $_GET['token'] ) && $_GET['token'] != '' ) { ?>
		                <div class="login-form-page-wrap">

		                	<?php
		                	$email_verification_token = $_GET['token'];
						    $user_id = intval($_GET['verrify-email']);
						    $template = houzez_get_template_link('template/template-login.php');

						    $stored_token = get_user_meta( $user_id, 'houzez_email_verification_token', true );
						    if ( $email_verification_token == $stored_token ) {
						        update_user_meta( $user_id, 'houzez_email_verified', true );
						        delete_user_meta( $user_id, 'houzez_email_verification_token' );
						        echo esc_html__('We are pleased to inform you that your email address has been successfully verified. You can now log in to your account.', 'houzez');

						        if( houzez_option('header_login') != 0 ) { ?>
								<a href="<?php echo esc_url($template);?>"><?php esc_html_e('Login', 'houzez'); ?></a>
								<?php }

						    } else {
						        echo esc_html__('Invalid verification token.', 'houzez');
						    }
		                	?>
			                
			                
		               </div>
		           <?php } else { ?>

		               <div class="login-form-page-wrap">
			                <div class="login-register-tabs">
			                    <ul class="nav nav-tabs">
			                        <li class="nav-item">
			                            <a class="modal-toggle-1 nav-link active" data-toggle="tab" href="#login-form-tab" role="tab"><?php esc_html_e('Login', 'houzez'); ?></a>
			                        </li>
			                        <?php if( houzez_option('header_register') ) { ?>
			                        <li class="nav-item">
			                            <a class="modal-toggle-2 nav-link" data-toggle="tab" href="#register-form-tab" role="tab"><?php esc_html_e('Register', 'houzez'); ?></a>
			                        </li>
			                    	<?php } ?>
			                    </ul>    
			                </div><!-- login-register-tabs -->
			                <div class="tab-content">
			                    <div class="tab-pane fade login-form-tab active show" id="login-form-tab" role="tabpanel">
			                        <?php get_template_part('template-parts/login-register/login-form'); ?>
			                    </div><!-- login-form-tab -->

			                    <?php if( houzez_option('header_register') ) { ?>
			                    <div class="tab-pane fade register-form-tab" id="register-form-tab" role="tabpanel">
			                       <?php get_template_part('template-parts/login-register/register-form'); ?>
			                   </div><!-- register-form-tab -->
			               		<?php } ?>
			               </div><!-- tab-content -->
		               </div>
           			<?php } ?>
               <?php 
           		} else { 
           			echo '<div class="login-form-page-text">'; 
           			echo '<strong>'.esc_html__('You are already logged in!', 'houzez').'</strong>';
           			echo '</div>';
               }?>
               
           </div><!-- col-lg-12 -->
       </div><!-- row -->
   </div><!-- container -->
</section>

<?php get_footer(); ?>