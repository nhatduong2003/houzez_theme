<?php
// Block direct access to the main plugin file.
defined( 'ABSPATH' ) || die( 'Access Denied!' );

$nextButtonDisabledClass = ( !\Realtyna\Sync\Core\App::validateFirstStep() ? ' disabled' : '' );
$credentials = $_REALTYNA['credentials'];

?>

<div class="wrap">

    <form action="admin.php" id="second_step_form">
		<?php
		$realtyna_nonce = wp_create_nonce( 'realtyna_houzez_secret_nonce' );
		?>
		<input type="hidden" name="realtyna_houzez_nonce" id="realtyna_houzez_nonce" value="<?php echo $realtyna_nonce?>">
		<input type="hidden" name="page" value="<?php echo REALTYNA_MLS_SYNC_SLUG?>">
		<input type="hidden" name="step" value="3">
		<input type="hidden" name="second_step_act" value="<?php echo wp_create_nonce("second_step_nonce");?>">

		<div class="realtyna_houzez_form">
			<p class="realtyna_mls_sync_step_title">
				<i class="dashicons dashicons-info"></i> <?php echo __("Client Information" , REALTYNA_MLS_SYNC_SLUG );?>
			</p>

			<p>
				<b><?php echo __("Full Name", REALTYNA_MLS_SYNC_SLUG );?> </b>:
			</p>

			<p>
				<input type="text" name="realtyna_idx_client_name" id="realtyna_idx_client_name" placeholder="<?php echo __("like John Doe" , REALTYNA_MLS_SYNC_SLUG );?>" value="<?php echo ( ( $credentials && isset( $credentials['name'] ) ) ? $credentials['name'] : '' )?>" required>
			</p>

			<p>
				<b><?php echo __("E-Mail" , REALTYNA_MLS_SYNC_SLUG );?></b>:
			</p>

			<p>
				<input type="email" name="realtyna_idx_client_email" id="realtyna_idx_client_email" placeholder="<?php echo __("like you@yoursite.com" , REALTYNA_MLS_SYNC_SLUG);?>" value="<?php echo ( ( $credentials && isset( $credentials['email'] ) ) ? $credentials['email'] : '' )?>" required>
			</p>

			<p>
				<b><?php echo __("Phone Number" , REALTYNA_MLS_SYNC_SLUG );?></b>:
			</p>

			<p>
				<input type="tel" pattern="^([0|\+)?([0-9]{10,12})$" name="realtyna_idx_client_phone" id="realtyna_idx_client_phone" placeholder="<?php echo __("like +13025258222" , REALTYNA_MLS_SYNC_SLUG );?>" value="<?php echo ( ( $credentials && isset( $credentials['phone_number'] ) ) ? $credentials['phone_number'] : '' )?>" required>
			</p>


			<p>
				<b><?php echo __("Your Role" , REALTYNA_MLS_SYNC_SLUG );?></b>:
			</p>

			<p>
				<?php 
				$role = ( $credentials && isset( $credentials['role'] ) ) ? $credentials['role'] : '' ;
				?>
				<select name="realtyna_idx_client_role" id="realtyna_idx_client_role">
					<option value="Realtor" <?php echo ( $role == 'Realtor' ? ' selected' : '' )?>><?php echo __("Realtor" , REALTYNA_MLS_SYNC_SLUG);?></option>
					<option value="WebMaster" <?php echo ( $role == 'WebMaster' ? ' selected' : '' )?>><?php echo __("Web Master" , REALTYNA_MLS_SYNC_SLUG);?></option>
					<option value="Other" <?php echo ( $role == 'Other' ? ' selected' : '' )?>><?php echo __("Other" , REALTYNA_MLS_SYNC_SLUG);?></option>
				</select>
			</p>

			<div id="import_progress" style="margin:10px;"></div>
			<div id="import_result" style="font-weight:bold; margin:30px;"></div>

			<p>
				<a class="button" href="admin.php?page=<?php echo REALTYNA_MLS_SYNC_SLUG?>&step=1" ><?php echo __("Prev Step" , REALTYNA_MLS_SYNC_SLUG );?></a>
				<button type="submit" id="realtya_go_to_third_step" class="button button-primary " <?php echo $nextButtonDisabledClass;?> ><?php echo __("Next Step" , REALTYNA_MLS_SYNC_SLUG );?> </button>
			</p>

		</div>

	</form>

</div>
