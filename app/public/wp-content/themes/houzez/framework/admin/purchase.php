<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$houzez_activation = get_option( 'houzez_activation' );
$purchase_code = get_option( 'houzez_purchase_code' );
?>
<div class="houzez-admin-wrapper">
	<?php get_template_part('framework/admin/header'); ?>

	<?php get_template_part('framework/admin/tabs'); ?>

	<div class="admin-houzez-content">
		<h2><?php esc_html_e('Houzez Purchase Verification', 'houzez'); ?></h2>
		<div class="admin-houzez-row">
			
			<div class="admin-houzez-box-wrap">
				
				<div class="admin-houzez-box">
					
					<div class="admin-houzez-box-content">
						
						<p><?php esc_html_e('Enter purchase code to verify your purchase. This will allow you to install plugins, import demo and unlock all features', 'houzez'); ?></p>

						<form id="admin-houzez-form" class="admin-houzez-form">
							
							<?php echo wp_nonce_field( 'envato_api_nonce', 'envato_api_nonce_field' ,true, false ); ?>

							<div class="form-field">
								<?php if( $houzez_activation == 'activated' ) { ?>
				
									<label><?php esc_html_e('Purchase Code', 'houzez'); ?> *</label>
									<?php if( ! empty( $purchase_code ) ) { ?>
									<input id="item_purchase_code" autocomplete="off" readonly class="regular-text" type="text" placeholder="Enter item purchase code." value="<?php echo esc_attr($purchase_code); ?>">
									<?php } ?>
									<input type="hidden" name="action" value="houzez_deactivate_purchase">
									<span class="ps-verified">Verified</span>
								<?php
								} else { ?>
									<label><?php esc_html_e('Purchase Code', 'houzez'); ?> *</label>
									<input id="item_purchase_code" autocomplete="off" class="regular-text" type="text" placeholder="Enter item purchase code.">
									<input type="hidden" name="action" value="houzez_purchase_verify">
								<?php
								} ?>
							</div>

							<div>
								<p>
				                    You can consult <a target="_blank" href="https://favethemes.zendesk.com/hc/en-us/articles/360038085112-Where-Is-My-Purchase-Code-"> this article</a> to learn how to get item purchase code or you can purchase <a href="https://themeforest.net/item/houzez-real-estate-wordpress-theme/15752549" target="_blank">new license</a> from themeforest which will include 6 months free support and lifetime updates.  
				                </p>
							</div>

							<div class="submit">

								<?php if( $houzez_activation == 'activated' ) { ?>
									<button id="houzez-deactivate-code" type="submit" class="button button-primary"><?php esc_html_e('Deactivate', 'houzez'); ?></button>
								<?php
								} else { ?>
									<button id="houzez-purchase-code" type="submit" class="button button-primary"><?php esc_html_e('Verify Purchase', 'houzez'); ?></button>
								<?php
								} ?>
							</div>

							<div class="form-field" id="form-messages"></div>
						</form>
					</div><!-- admin-houzez-box-content -->
					
				</div><!-- admin-houzez-box -->

			</div><!-- admin-houzez-box-wrap -->

		</div><!-- admin-houzez-row -->
	</div>
</div>