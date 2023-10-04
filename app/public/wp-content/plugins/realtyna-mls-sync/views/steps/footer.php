<?php
/** Block direct access to the main plugin file.*/ 
defined( 'ABSPATH' ) || die( 'Access Denied!' );
?>
<span class="realtyna_floating_button">
        <a href="#" class="rfb-float rfb-btn" id="rfb-menu-share">
                <i class="dashicons dashicons-format-status rfb-floating"></i>
                <div class="rfb-label-container">
                        <div class="rfb-label-text"><?php _e( "Need Help?" , REALTYNA_MLS_SYNC_SLUG ); ?></div>
                        <i class="dashicons dashicons-controls-play rfb-label-arrow"></i>
                </div>
        </a>

        <ul>
                <li>
                        <a href="https://realtyna.com/mls-sync/" class="rfb-btn" target="_blank">
                                <i class="dashicons dashicons-paperclip rfb-floating"></i>
                                <div class="rfb-label-container">
                                        <div class="rfb-label-text"><?php _e( "More Info" , REALTYNA_MLS_SYNC_SLUG ); ?></div>
                                        <i class="dashicons dashicons-controls-play rfb-label-arrow"></i>
                                </div>
                        </a>
                </li>

                <li>
                        <a href="https://support.realtyna.com/index.php?/Default/Tickets/Submit" class="rfb-btn" target="_blank">
                                <i class="dashicons dashicons-tickets-alt rfb-floating"></i>
                                <div class="rfb-label-container">
                                        <div class="rfb-label-text"><?php _e( "Create Ticket" , REALTYNA_MLS_SYNC_SLUG );?></div>
                                        <i class="dashicons dashicons-controls-play rfb-label-arrow"></i>
                                </div>
                        </a>
                </li>

                <li>
                        <a href="mailto:sync@realtyna.net" class="rfb-btn">
                                <i class="dashicons dashicons-email rfb-floating"></i>
                                <div class="rfb-label-container">
                                        <div class="rfb-label-text"><?php _e( "Email Us" , REALTYNA_MLS_SYNC_SLUG );?></div>
                                        <i class="dashicons dashicons-controls-play rfb-label-arrow"></i>
                                </div>
                        </a>
                </li>

                <li>
                        <a href="tel:+13027225393" class="rfb-btn">
                                <i class="dashicons dashicons-phone rfb-floating"></i>
                                <div class="rfb-label-container">
                                        <div class="rfb-label-text"><?php _e( "Call Us" , REALTYNA_MLS_SYNC_SLUG );?></div>
                                        <i class="dashicons dashicons-controls-play rfb-label-arrow"></i>
                                </div>
                        </a>
                </li>
        </ul>
</span>

<footer  class="realtyna_mls_sync_footer">
        <strong>MLS Sync Powered by <a href="https://realtyna.com/" target="_blank">Realtyna&reg Inc.</a></strong>
        <div class="realtyna_logo"></div>
</footer>