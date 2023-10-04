<?php
// Block direct access to the main plugin file.
defined( 'ABSPATH' ) || die( 'Access Denied!' );

$nextButtonDisabledClass = ( empty( $_REALTYNA['idx_options'] ) || ( $_REALTYNA['idx_options'] === false ) ) ? ' disabled' : '' ;

$idxOptions['agent'] = ( !empty( $_REALTYNA['idx_options']['agent'] ) ) ? $_REALTYNA['idx_options']['agent'] : '';
$idxOptions['agency'] = ( !empty( $_REALTYNA['idx_options']['agency'] ) ) ? $_REALTYNA['idx_options']['agency'] : '';
$idxOptions['agent_option'] = ( !empty( $_REALTYNA['idx_options']['agent_option'] ) ) ? $_REALTYNA['idx_options']['agent_option'] : '';
$idxOptions['image_option'] = ( isset( $_REALTYNA['idx_options']['image_option'] ) ) ?  $_REALTYNA['idx_options']['image_option'] : '';

$agentPostType = $_REALTYNA['agent_post_type'] ?? '';
$agencyPostType = $_REALTYNA['agency_post_type'] ?? '';

?>

<div class="wrap">

	<form action="admin.php" id="third_step_form">
		
		<input type="hidden" name="page" value="<?php echo REALTYNA_MLS_SYNC_SLUG?>">
		<input type="hidden" name="step" value="4">
		<input type="hidden" name="realtyna_houzez_nonce" id="realtyna_houzez_nonce" value="<?php echo wp_create_nonce( 'realtyna_houzez_secret_nonce' )?>">
		<input type="hidden" name="third_step_act" value="<?php echo wp_create_nonce("third_step_nonce");?>">

		<div class="realtyna_houzez_form">
			<p class="realtyna_mls_sync_step_title">
				<i class="dashicons dashicons-database-import"></i> <?php _e("Demo Import" , REALTYNA_MLS_SYNC_SLUG );?>
			</p>

			<p>
				<b><?php _e("Demo Import Settings" , REALTYNA_MLS_SYNC_SLUG );?></b>:
			</p>

			<p>
				<b><?php _e("What to display in agent information box?" , REALTYNA_MLS_SYNC_SLUG);?></b>
			</p>

			<p>
				<select name="realtyna_idx_selected_agent_option" id="realtyna_idx_selected_agent_option">
				<?php
				foreach( $_REALTYNA['agents_display_options'] as $displayOption => $displayValue ):
					$selected = ( $displayOption == $idxOptions['agent_option'] ) ? ' selected' : '';
				?>
					<option value="<?php echo $displayOption?>" <?php echo $selected?>><?php echo $displayValue?></option>
				<?php
				endforeach;
				?>
				</select>
			</p>
			
			<p>
				<b><?php _e("Select Agent" , REALTYNA_MLS_SYNC_SLUG );?></b>: <?php if ( $agentPostType ) { ?><a href="post-new.php?post_type=<?php echo $agentPostType;?>"><?php _e("Click here to Add New", REALTYNA_MLS_SYNC_SLUG);?></a> <?php } ?>
			</p>

			<p>
				<select name="realtyna_idx_selected_agent" id="realtyna_idx_selected_agent">
				<?php
				foreach( $_REALTYNA['agents'] as $agent ):
					$selected = ( $agent->ID == $idxOptions['agent'] ) ? ' selected' : '';
				?>
					<option value="<?php echo $agent->ID?>" <?php echo $selected?>><?php echo $agent->post_title?></option>
				<?php
				endforeach;
				?>
				</select>
			</p>

			<p>
				<b><?php _e("Select Agency" , REALTYNA_MLS_SYNC_SLUG );?></b>: <?php if ( $agencyPostType ) { ?><a href="post-new.php?post_type=<?php echo $agencyPostType;?>"><?php _e("Click here to Add New", REALTYNA_MLS_SYNC_SLUG);?></a><?php } ?>
			</p>

			<p>
				<select name="realtyna_idx_selected_agency" id="realtyna_idx_selected_agency">
				<?php
				foreach( $_REALTYNA['agencies'] as $agency ):
					$selected = ( $agency->ID == $idxOptions['agency'] ) ? ' selected' : '';
				?>
					<option value="<?php echo $agency->ID?>" <?php echo $selected?>><?php echo $agency->post_title?></option>
				<?php
				endforeach;
				?>
				</select>
			</p>

			<p>
				<b><?php _e("Image Storage & Disk Management Options" , REALTYNA_MLS_SYNC_SLUG );?></b>:
			</p>

			<p>
				<select name="realtyna_idx_images_option" id="realtyna_idx_images_option">
					<option value="2" <?php echo ( ( '2' == $idxOptions['image_option'] ) ? ' selected' : '' );?>><?php _e("Use External for All Images including Feature Images" , REALTYNA_MLS_SYNC_SLUG );?> </option>
					<option value="1" <?php echo ( ( '1' == $idxOptions['image_option'] ) ? ' selected' : '' );?>><?php _e("Download Feature Images Only and Use External for rest of images" , REALTYNA_MLS_SYNC_SLUG )?> </option>
					<option value="0" <?php echo ( ( '0' == $idxOptions['image_option'] ) ? ' selected' : '' );?>><?php _e("Download All Images to Local Storage ( Huge disk space needed )" , REALTYNA_MLS_SYNC_SLUG );?> </option>
				</select>
			</p>

			<div>				
			<?php
				if ( $_REALTYNA['idx_import'] == false)  :
			?>
				<a class="button " href="#" id="realtyna_idx_demo_import" onclick="return false;" ><?php _e("Demo Import" , REALTYNA_MLS_SYNC_SLUG );?></a>
				<?php
				else:
			?>
				<b class="realtyna_success_text"><?php _e("Demo Properties Already Imported!" , REALTYNA_MLS_SYNC_SLUG );?></b>
			<?php
				endif;
			?>
			</div>

			<div id="import_progress" style="text-align:center; margin:10px;"></div>
			<div id="import_result" style="text-align:center; margin:30px;padding:10px;"></div>

			<p>
				<a class="button" href="admin.php?page=<?php echo REALTYNA_MLS_SYNC_SLUG?>&step=2" ><?php _e("Prev Step" , REALTYNA_MLS_SYNC_SLUG );?> </a>
				<button type="submit" name="realtya_go_to_fourth_step" id="realtya_go_to_fourth_step" class="button button-primary " <?php echo $nextButtonDisabledClass?> ><?php _e("Next Step" , REALTYNA_MLS_SYNC_SLUG);?></button>
			</p>

		</div>

	</form>
	
</div>
