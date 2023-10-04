<?php
// Block direct access to the main plugin file.
defined( 'ABSPATH' ) || die( 'Access Denied!' );

$idxOptions = array();
$idxOptions['agent'] = ( !empty( $_REALTYNA['idx_options']['agent'] ) ) ? $_REALTYNA['idx_options']['agent'] : '';
$idxOptions['agency'] = ( !empty( $_REALTYNA['idx_options']['agency'] ) ) ? $_REALTYNA['idx_options']['agency'] : '';
$idxOptions['agent_option'] = ( !empty( $_REALTYNA['idx_options']['agent_option'] ) ) ? $_REALTYNA['idx_options']['agent_option'] : '';
$idxOptions['image_option'] = ( isset( $_REALTYNA['idx_options']['image_option'] ) ) ?  $_REALTYNA['idx_options']['image_option'] : '';

$agentPostType = $_REALTYNA['agent_post_type'] ?? '';
$agencyPostType = $_REALTYNA['agency_post_type'] ?? '';

?>
<div class="wrap">

<form action="admin.php" id="settings_form">
    
    <input type="hidden" name="page" value="<?php echo REALTYNA_MLS_SYNC_SLUG . '-settings'?>">
    <input type="hidden" name="realtyna_houzez_nonce" id="realtyna_houzez_nonce" value="<?php echo wp_create_nonce( 'realtyna_houzez_secret_nonce' )?>">

    <div class="realtyna_houzez_form">
        <p class="realtyna_mls_sync_step_title">
        <i class="dashicons dashicons-admin-generic"></i> <?php _e("Settings" , REALTYNA_MLS_SYNC_SLUG );?>
        </p>

        <p>
            <b><?php _e("What to display in agent information box?" , REALTYNA_MLS_SYNC_SLUG);?></b>
        </p>

        <p>
            <select name="realtyna_idx_selected_agent_option_settings" id="realtyna_idx_selected_agent_option_settings">
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
            <input type="checkbox" name="apply_agent_display_option_to_all" id="apply_agent_display_option_to_all"/>
            <label for="apply_agent_display_option_to_all"><?php _e("set this option to all imported properties" , REALTYNA_MLS_SYNC_SLUG );?></label>
        </p>
        <hr>

        <p>
            <b><?php _e("Select Agent" , REALTYNA_MLS_SYNC_SLUG );?></b>: <a href="post-new.php?post_type=<?php echo $agentPostType;?>"><?php _e("Click here to Add New" , REALTYNA_MLS_SYNC_SLUG);?></a>
        </p>

        <p>
            <select name="realtyna_idx_selected_agent_settings" id="realtyna_idx_selected_agent_settings">
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
            <input type="checkbox" name="apply_agent_to_all" id="apply_agent_to_all"/>
            <label for="apply_agent_to_all"><?php _e("set this agent to all imported properties" , REALTYNA_MLS_SYNC_SLUG );?></label>
        </p>
        <hr>

        <p>
            <b><?php _e("Select Agency" , REALTYNA_MLS_SYNC_SLUG );?></b>: <a href="post-new.php?post_type=<?php echo $agencyPostType;?>"><?php _e("Click here to Add New" , REALTYNA_MLS_SYNC_SLUG);?></a>
        </p>

        <p>
            <select name="realtyna_idx_selected_agency_settings" id="realtyna_idx_selected_agency_settings">
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
            <input type="checkbox" name="apply_agency_to_all" id="apply_agency_to_all"/>
            <label for="apply_agency_to_all"><?php _e("set this agency to all imported properties" , REALTYNA_MLS_SYNC_SLUG );?></label>
        </p>
        <hr>

        <p>
            <b><?php _e("Image Storage & Disk Management Options" , REALTYNA_MLS_SYNC_SLUG );?></b>:
        </p>

        <p>
            <select name="realtyna_idx_images_option_settings" id="realtyna_idx_images_option_settings">
                <option value="2" <?php echo ( ( '2' == $idxOptions['image_option'] ) ? ' selected' : '' );?>><?php _e("Use External for All Images including Feature Images" , REALTYNA_MLS_SYNC_SLUG );?> </option>
                <option value="1" <?php echo ( ( '1' == $idxOptions['image_option'] ) ? ' selected' : '' );?>><?php _e("Download Feature Images Only and Use External for rest of images" , REALTYNA_MLS_SYNC_SLUG )?> </option>
                <option value="0" <?php echo ( ( '0' == $idxOptions['image_option'] ) ? ' selected' : '' );?>><?php _e("Download All Images to Local Storage ( Huge disk space needed )" , REALTYNA_MLS_SYNC_SLUG );?> </option>
            </select>
        </p>
        <p class="realtyna_warning_text">
            <b><?php _e("Note: changes of this option, whould be applied on new properties only." , REALTYNA_MLS_SYNC_SLUG );?></b>
        </p>
        <hr>

        <?php
        if ( $_REALTYNA['idx_import'] !== false ) 
        {
        ?>
        <div>				
            <a class="button " href="javascript:void(0);" id="realtyna_remove_demo_properties" onclick="return false;" ><?php _e("Remove Demo Properties" , REALTYNA_MLS_SYNC_SLUG );?></a>
        </div>
        <?php
        }
        ?>

        <div id="import_progress" style="text-align:center; margin:10px;"></div>
        <div id="import_result" style="text-align:center; margin:30px;padding:10px;"></div>

        <p>
            <button type="button" name="realtyna_submit_settings" id="realtyna_submit_settings" class="button button-primary "><?php _e("Update Settings" , REALTYNA_MLS_SYNC_SLUG);?></button>
        </p>

    </div>

</form>

</div>
