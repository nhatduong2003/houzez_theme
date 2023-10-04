<?php
// Block direct access to the main plugin file.
defined( 'ABSPATH' ) || die( 'Access Denied!' );

$pluginURL = plugin_dir_url( REALTYNA_MLS_SYNC_PLUGIN_FILE );
$benchmarkerURL = $pluginURL . 'realtyna/Addons/Benchmarker/';

$requirementsAreMet = $_REALTYNA['requirements-are-met'] ?? false;
$requirements = $_REALTYNA['requirements-list'] ;
$targetProductSelected = $_REALTYNA['targetProductSelected'] ?? false;
$targetProductOption = $_REALTYNA['targetProductOption'] ?? '';
$supportedThemes = $_REALTYNA['supported-themes'] ?? false;

$nextButtonDisabledClass = ( $requirementsAreMet ? '' : ' disabled' );

?>

<div class="wrap">
<form action="admin.php" id="first_step_form">
		<?php
		$realtyna_nonce = wp_create_nonce( 'realtyna_houzez_secret_nonce' );
		?>
		<input type="hidden" name="realtyna_houzez_nonce" id="realtyna_houzez_nonce" value="<?php echo $realtyna_nonce?>">
		<input type="hidden" name="page" value="<?php echo REALTYNA_MLS_SYNC_SLUG?>">
		<input type="hidden" name="step" value="2">
		<input type="hidden" name="first_step_act" value="<?php echo wp_create_nonce("first_step_nonce");?>">

        <div class="realtyna_houzez_form">
            <p class="realtyna_mls_sync_step_title">
                <i class="dashicons dashicons-plugins-checked"></i> <?php _e("Check Requirements" , REALTYNA_MLS_SYNC_SLUG);?>
            </p>

            <p style="text-align:left;">
                <select  name="realtyna_idx_target_product" id="realtyna_idx_target_product" required>
                    <option value=""><?php _e("Select desired Product to Sync with " , REALTYNA_MLS_SYNC_SLUG); ?></option>
                <?php
                    if ( !empty( $supportedThemes ) && is_array( $supportedThemes ) ){

                        //

                        foreach( $supportedThemes as $theme ){

                            if ( method_exists( $theme , 'isActive' ) && method_exists( $theme , 'getName' ) && method_exists( $theme , 'strtolowerName' )  ){

                                if ( $theme::isActive() ){

                                    $selected = ( $targetProductOption ==  $theme::strtolowerName() ) ? ' selected="selected" ' : '' ;

                                    echo '<option value="' . $theme::strtolowerName() . '" ' . $selected . '>' . $theme::getName() . ' ' . __("Theme" , REALTYNA_MLS_SYNC_SLUG ) . '</option>';
                                    break;

                                }
                            }

                        }

                    }
                ?>
                </select>
            </p>

            <?php
                foreach ( $requirements as $requirement ):
            ?>
                <p style="text-align:left;">
                    <i class="dashicons <?php echo ( $requirement['result'] ) ? ' dashicons-yes realtyna_success_text ' : ' dashicons-no realtyna_error_text'  ?>"></i>
                    <b><?php echo $requirement['label']?></b>&nbsp;<?php _e("current value" , REALTYNA_MLS_SYNC_SLUG );?> : <i><?php echo $requirement['current_value']?></i>
                </p>
                <p style="text-align:left; padding-left:30px; color:#666;">
                    <i class="dashicons dashicons-info"></i>
                    <span><?php echo $requirement['hint']?>  <?php echo ( ( !empty( $requirement['manual'] ) && !$requirement['result'] ) ? '[<a href="'. $requirement['manual'] .'" target="_blank">' . __( 'manual' , REALTYNA_MLS_SYNC_SLUG ) . '</a> ]' : '' ); ?></span>
                </p>

                <hr>

            <?php
                endforeach;
            
            ?>
            
            <?php
            if ( $requirementsAreMet ) {
            ?>
                <p class="realtyna_success_bg" style="padding-top:10px; padding-bottom:10px;"> 
                    <i class="realtyna_success_text dashicons dashicons-yes"></i> <?php _e("Requirements are met!" , REALTYNA_MLS_SYNC_SLUG);?>
                </p>
            <?php
            }else{
            ?>
                <p class="realtyna_error_bg" style="padding-top:10px; padding-bottom:10px;"> 
                    <i class="realtyna_error_text dashicons dashicons-no"></i> <?php _e("Requirements not met!" , REALTYNA_MLS_SYNC_SLUG );?>
                </p>
            <?php
            }
            
            ?>
            
            <div style="padding-top:10px; padding-bottom:10px;font-weight:bold;">

                <p style="text-align:left;">
                    <span style="color:#f00"> <?php _e("Important: " , REALTYNA_MLS_SYNC_SLUG);?> </span>
                    <?php
                        _e( 'Organic MLS Sync will pull the actual data to your server. This means you should have a powerful hosting to keep the property data reside on your server.' , REALTYNA_MLS_SYNC_SLUG );
                    ?>
                </p>

                <p> 
                    <i class=" dashicons dashicons-info"></i> <?php _e("You can check our hosting examples " , REALTYNA_MLS_SYNC_SLUG);?> <a href="https://realtyna.com/hosting" target="_blank" title="Realtyna Hosting for RealEstate Sites"><?php _e("here" , REALTYNA_MLS_SYNC_SLUG );?></a>
                </p>

                <p>

                <p> 
                    <i class=" dashicons dashicons-info"></i> <i style="color:#f00"><?php _e("Do not try to use this plug-in with limited or weak hosting." , REALTYNA_MLS_SYNC_SLUG);?></i>
                </p>

                <p> 
                    <i class=" dashicons dashicons-info"></i> <i><a href="<?php echo $benchmarkerURL;?>" target="_blank"><?php _e("Click here to run a Benchmark Test to check your host" , REALTYNA_MLS_SYNC_SLUG);?></a></i>
                </p>

            </div>

            <p>
				<button type="submit" id="realtya_go_to_second_step" class="button button-primary " <?php echo $nextButtonDisabledClass;?> ><?php _e("Next Step" , REALTYNA_MLS_SYNC_SLUG );?> </button>
            </p>

        </div>
    </form>
</div>
