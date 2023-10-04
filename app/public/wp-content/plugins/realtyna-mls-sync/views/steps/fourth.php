<?php
// Block direct access to the main plugin file.
defined( 'ABSPATH' ) || die( 'Access Denied!' );

$nextButtonDisabledClass = 'disabled';
$tosLink = 'http://realtyna.com/mls-sync-terms-and-conditions/';

?>

<div class="wrap">

	<form action="admin.php" id="fourth_step_form">
	<input type="hidden" name="realtyna_houzez_nonce" id="realtyna_houzez_nonce" value="<?php echo wp_create_nonce( 'realtyna_houzez_secret_nonce' )?>"/>

	<div class="realtyna_houzez_form">
			<p class="realtyna_mls_sync_step_title">
				<i class="dashicons dashicons-tag"></i> <?php _e("Select the MLS Provider" , REALTYNA_MLS_SYNC_SLUG );?>
			</p>

			<p style="text-align:left;">
				<b>
				<?php
				_e( "Please note, Realtyna has a refund policy in place to ensure client satisfaction. If the client is not approved by your MLS or Realtyna team, <span class='realtyna_success_bg'>we will refund your setup fee and first month of fees</span> for the data connection." , REALTYNA_MLS_SYNC_SLUG );
				?>
				</b>
			</p>

			<p style="text-align:left;">
				<b>
				<?php
				_e( "Please note that the approximate time frame for MLS access approval is <span class='realtyna_success_bg'>14 working days</span>. You will receive an email with additional information about the MLS approval workflow and all necessary instructions for completing the registration process.If you need further assistance, please contact sync@realtyna.net" , REALTYNA_MLS_SYNC_SLUG );
				?>
				</b>
			</p>

			<p>
			<table style="background-color:#fff;" class="realtyna_houzez_table">
				<thead>
					<tr>
						<th width="5%"><?php _e("Select" , REALTYNA_MLS_SYNC_SLUG);?> </th>
						<th><?php _e("MLS Provider" , REALTYNA_MLS_SYNC_SLUG);?></th>
						<th width="15%"><?php _e("Monthly Fee" , REALTYNA_MLS_SYNC_SLUG );?></th>
						<th width="15%"><?php _e("Setup Fee" ,REALTYNA_MLS_SYNC_SLUG);?></th>
					</tr>
				</thead>
				<tbody>
				<?php
				if ( empty($_REALTYNA['providers']) ) :
				?>
					<tr>
						<td colspan="4">
							<?php _e("No MLS Found!" , REALTYNA_MLS_SYNC_SLUG ) ;?>
						</td>
					</tr>
				<?php
				else :

					foreach( $_REALTYNA['providers'] as $provider ):
						if ( empty( $provider['price'] ) || empty( $provider['one_time_price'] ) )
							continue;

						$setupFeeFinal =  $provider['one_time_price'] - $provider['discount'];
					?>
					<tr>
						<td>
							<input type="radio" name="mls_id" id="mls_id_<?php echo $provider['id']?>" value="<?php echo $provider['id']?>" data-name="<?php echo $provider['name']?>" data-slug="<?php echo $provider['short_name']?>" data-currency="<?php echo $provider['price_unit']?>" data-price="<?php echo $provider['price']?>" data-setup="<?php echo $setupFeeFinal?>"/>
						</td>
						<td>
							<label for="mls_id_<?php echo $provider['id']?>">
								<img src="<?php echo $provider['image_url']?>" width="32" height="32" align="absmiddle"/> <?php echo $provider['name']?>
							</label>
						</td>
						<td>
							<?php echo $provider['price_unit'] . " " . $provider['price']?>
						</td>
						<td>
							<?php
							$setupFeeText = "+&nbsp;" ;

							if ( $provider['one_time_price'] != $setupFeeFinal ){

								$setupFeeText .= "<s>" . $provider['price_unit'] . " " . $provider['one_time_price'] . "</s> <br>" . "<span style='color:red;'>" . ( $setupFeeFinal == 0 ? "<b>" . __("Free!" , REALTYNA_MLS_SYNC_SLUG) . "</b>" : $provider['price_unit'] . " " . $setupFeeFinal )  . "<br>" . __("Limited Time Offer!" , REALTYNA_MLS_SYNC_SLUG) . "</span>";

							}else {

								$setupFeeText .= $provider['price_unit'] . " " . $provider['one_time_price'];

							}

							echo $setupFeeText;
							?>
						</td>
					</tr>
				<?php

					endforeach;
				
				endif;
				?>
					<tr style="background-color: #ceebf5;">
						<td>
							<input type="radio" name="mls_id" id="request_for_mls_item" value="0"/>
						</td>
						<td colspan="3">
							<label for="request_for_mls_item">
								<b><?php _e("Not Listed?" , REALTYNA_MLS_SYNC_SLUG ) ;?> <span style="text-decoration:underline;"><?php _e("Request for MLS" , REALTYNA_MLS_SYNC_SLUG );?></span></b>
							</label>
						</td>
					</tr>

				</tbody>
			</table>
			</p>

			<div id="request_for_mls_box" class="realtyna_box_shadow" style="background-color: #ceebf5;padding-top:10px;padding-bottom:10px;border-radius:15px; display:none;">

				<p style="text-align: left;padding: 5px 30px;color: #8f8f8f;">
					<i class="dashicons dashicons-tickets"></i> <b><?php _e("Request for MLS" , REALTYNA_MLS_SYNC_SLUG );?></b>
				</p>

				<hr/>
				<p>
					<b><?php _e("Provider", REALTYNA_MLS_SYNC_SLUG );?></b> :
				</p>

				<p>
					<input type="text" name="realtyna_request_provider" id="realtyna_request_provider" placeholder="<?php _e("like StellarMLS" , REALTYNA_MLS_SYNC_SLUG );?>" required />
				</p>

				<p>
					<b><?php _e("State" , REALTYNA_MLS_SYNC_SLUG );?></b> :
				</p>

				<p>
					<input type="text" name="realtyna_request_state" id="realtyna_request_state" placeholder="<?php _e("like FL" , REALTYNA_MLS_SYNC_SLUG);?>" required />
				</p>

			</div>

			<p style="text-align:left;">
				<input type="checkbox" name="realtyna_client_agree_with_terms" id="realtyna_client_agree_with_terms" required>
				<label for="realtyna_client_agree_with_terms">
					<b>
						<?php printf( __('I agree with <a href="%s" target="_blank">Terms and Conditions of Service</a>' ,  REALTYNA_MLS_SYNC_SLUG ) , $tosLink );?>
					</b>
				</label>
			</p>
			<p style="text-align:left;">
				<input type="checkbox" name="realtyna_mls_member_confirm" id="realtyna_mls_member_confirm" required>
				<label for="realtyna_mls_member_confirm">
					<b>
						<?php _e('I confirm that either me or my client, is an authorized member of this MLS provider.' ,  REALTYNA_MLS_SYNC_SLUG ) ;?>
					</b>
				</label>
			</p>

			<div id="payment_details" class="realtyna_success_bg realtyna_success_text" style="text-align:center; margin:10px;padding:10px; font-weight:bold; display:none;"></div>
			<div id="import_result" style="text-align:center; margin:30px;padding:10px;"></div>

			<p>
				<a class="button button-secondary" href="admin.php?page=<?php echo REALTYNA_MLS_SYNC_SLUG?>&step=3" ><?php _e("Prev Step" , REALTYNA_MLS_SYNC_SLUG);?></a>
				<button type="submit" id="btn_finalize" class="button button-primary" <?php echo $nextButtonDisabledClass;?> data-payment="<?php _e("Finalize Payment" , REALTYNA_MLS_SYNC_SLUG );?>" data-request="<?php _e("Finalize Request" , REALTYNA_MLS_SYNC_SLUG );?>" ><?php _e("Finalize" , REALTYNA_MLS_SYNC_SLUG );?></button>
			</p>

		</div>

	</form>
	
</div>
