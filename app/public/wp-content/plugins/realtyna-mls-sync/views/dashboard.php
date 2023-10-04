<?php
// Block direct access to the main plugin file.
defined( 'ABSPATH' ) || die( 'Access Denied!' );

$pluginURL = plugin_dir_url( REALTYNA_MLS_SYNC_PLUGIN_FILE );
$benchmarkerURL = $pluginURL . 'realtyna/Addons/Benchmarker/';

$nextButtonDisabledClass = 'disabled';
$mlsData = $_REALTYNA['mlsData'];
$idxData = $_REALTYNA['idxData'];
$mlsID = ( is_array( $mlsData ) && isset( $mlsData['id'] )  ) ? $mlsData['id']  : -1 ;
$latestSync = $_REALTYNA['latestSync'] ?? '';
$totalImported = $_REALTYNA['totalImportedListings'] ?? '';
$availableListings = $_REALTYNA['totalAvailableListings'] ?? 0;
//$availableImported = $_REALTYNA['currentImportedListings'] ?? 0;
$dailyImported = $_REALTYNA['totalTodayImportedListings'] ?? 0;

$latestSyncDate = '';

if ( !empty( $latestSync ) ){
	
	$dateObject = new DateTime( "now", wp_timezone() );
	$dateObject->setTimestamp( $latestSync );
	$latestSyncDate =  $dateObject->format('d.m.Y, H:i:s') . ' ' . wp_timezone_string();
	
}


?>

<div class="wrap">

	<form action="admin.php" id="fourth_step_form">
	<input type="hidden" name="realtyna_houzez_nonce" id="realtyna_houzez_nonce" value="<?php echo wp_create_nonce( 'realtyna_houzez_secret_nonce' )?>"/>

	<div class="realtyna_houzez_form">
			<p class="realtyna_mls_sync_step_title">
				<i class="dashicons dashicons-dashboard"></i> <?php echo __("Dashboard" , REALTYNA_MLS_SYNC_SLUG );?>
			</p>

			<div id="request_for_mls_box" class="realtyna_box_shadow" style="background-color: #ceebf5;padding-top:10px;padding-bottom:10px;border-radius:15px;">

				<p>
					<b><?php echo __("Requested Provider", REALTYNA_MLS_SYNC_SLUG );?></b> :
				</p>

				<p>
					<input type="text" name="realtyna_request_provider" id="realtyna_request_provider" value="<?php echo $mlsData['name'];?>" disabled />
				</p>

				<p>
					<b><?php echo __("Current Status", REALTYNA_MLS_SYNC_SLUG );?></b> :
				</p>

				<p>
					<input type="text" name="realtyna_request_provider" id="realtyna_request_provider" value="<?php if ( isset( $mlsData['status'] ) ) {
    echo ucfirst($mlsData['status']) ; } else { echo 'Pending'; }?>" disabled />
				</p>

			</div>

            <hr>

            <?php

            if ( $mlsData['id'] > 0 && strtolower( $mlsData['status'] ) == 'active' ) :
            ?>
            <p id="progress_details" class="realtyna_success_bg realtyna_success_text" style="text-align:center; margin:10px;padding:10px; font-weight:bold;">
                <?php

                    if ( !empty( $totalImported ) ){

                        echo '<span class="dashicons dashicons-info"></span> ' . __( 'Total Transactions: ' , REALTYNA_MLS_SYNC_SLUG ) . $totalImported ;

                    }else{

                        echo '<span class="dashicons dashicons-update"></span> ' . __( 'MLS Sync is in progress... ' , REALTYNA_MLS_SYNC_SLUG );

                    }

                    if ( !empty( $availableListings ) ){

                        echo '<br><span class="dashicons dashicons-info"></span> ' . __( 'Available Listings: ' , REALTYNA_MLS_SYNC_SLUG ) . $availableListings;

                    }
					
                    if ( !empty( $dailyImported ) ){

                        echo '<br><span class="dashicons dashicons-info"></span> ' . __( 'Imported Listings in past 24hrs: ' , REALTYNA_MLS_SYNC_SLUG ) . $dailyImported;

                    }

                    if ( !empty( $latestSync ) ){

                        echo '<br><span class="dashicons dashicons-update"></span> ' . __( 'Latest Sync at ' , REALTYNA_MLS_SYNC_SLUG ) . $latestSyncDate;

                    }
					
                ?>
            </p>
            <?php
            endif;

            if ( $mlsData['id'] == 0 || strtolower( $mlsData['status'] ) == 'paid' ) :
            ?>
			<p id="payment_details" class="realtyna_success_bg realtyna_success_text" style="text-align:center; margin:10px;padding:10px; font-weight:bold;">
                <?php
                    if ( $mlsData['id'] == 0 ){

                        _e( 'Your Request for MLS has been sent successfully!<br>Our team will contact you soon.<br>If you need more information you can contact us: sync@realtyna.net' , REALTYNA_MLS_SYNC_SLUG );

                    }else{

                        printf ( __( "Payment has been processed successfully.<br><br>Please send Broker name, Email, Brokerage Name, (Agent's info if the client is not a broker), Website URL, Staging URL along with this number: (CID : %s ) to sync@realtyna.net <br><br>Our team will contact you for the paperwork of your MLS provider. Please stay tuned.<br>If you need more information you can contact us: sync@realtyna.net" , REALTYNA_MLS_SYNC_SLUG) , $idxData['user_id']  );

                    }
                ?>
            </p>
            <?php
            endif;

            if ( $mlsData['id'] > 0 && strtolower( $mlsData['status'] ) == 'pending' ) :            
            ?>
			<p id="payment_details" class="realtyna_error_bg realtyna_error_text" style="text-align:center; margin:10px;padding:10px; font-weight:bold;">
                <?php
                    if ( isset($_REALTYNA['payment']) && $_REALTYNA['payment'] == 'cancel' ){

                        echo __( "The payment has NOT been processed.<br>You can proceed with payemnt again or contact us: sync@realtyna.net" , REALTYNA_MLS_SYNC_SLUG);

                    }else{

                        echo __( "You have an unpaid invoice, please proceed with the invoice to finalize your order " , REALTYNA_MLS_SYNC_SLUG);

                    }
                ?>
            </p>

            <p> 
                <a class="button button-secondary" href="<?php echo $mlsData['checkout']; ?>" ><?php _e("Proceed With Payment" , REALTYNA_MLS_SYNC_SLUG);?></a>                
            </p>
            <?php
            endif;
            ?>

            <?php
            if ( strtolower( $mlsData['status'] ) == 'pending' || $mlsData['id'] == 0) :
            ?>
			<p>
				<a class="button button-secondary" href="admin.php?page=<?php echo REALTYNA_MLS_SYNC_SLUG ; ?>&reset_mls=1" ><?php _e("Select Another MLS Provider" , REALTYNA_MLS_SYNC_SLUG);?></a>
			</p>
            <?php
            endif;
            ?>

            <div style="padding-top:10px; padding-bottom:10px;font-weight:bold;">

                <p> 
                    <i class=" dashicons dashicons-info"></i> <i><a href="<?php echo $benchmarkerURL;?>" target="_blank"><?php _e("Click here to run a Benchmark Test to check your host" , REALTYNA_MLS_SYNC_SLUG);?></a></i>
                </p>

            </div>


		</div>

	</form>
	
</div>
