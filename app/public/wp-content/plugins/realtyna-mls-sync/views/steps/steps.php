<?php
// Block direct access to the main plugin file.
defined( 'ABSPATH' ) || die( 'Access Denied!' );

$step = ( isset( $_REALTYNA['step'] ) && is_numeric($_REALTYNA['step']) ) ? $_REALTYNA['step'] : 1;
?>
<div class="realtyna_mls_sync" style="text-align:center;">
    <ol>
        <li class="<?php echo ( ( $step == 1 ) ? 'current' : ''  )?>"><?php _e("Requirements" , REALTYNA_MLS_SYNC_SLUG );?></li>
        <li class="<?php echo ( ( $step == 2 ) ? 'current' : ''  )?>"><?php _e("Information" , REALTYNA_MLS_SYNC_SLUG );?></li>
        <li class="<?php echo ( ( $step == 3 ) ? 'current' : ''  )?>"><?php _e("Demo Import" , REALTYNA_MLS_SYNC_SLUG );?></li>
        <li class="<?php echo ( ( $step == 4 ) ? 'current' : ''  )?>"><?php _e("Finalize" , REALTYNA_MLS_SYNC_SLUG );?></li>
    </ol>
</div>
