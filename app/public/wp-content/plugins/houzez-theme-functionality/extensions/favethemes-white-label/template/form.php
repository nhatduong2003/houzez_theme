<?php
$settings = Favethemes_White_Label::get_settings(); 
?>
<div class="fwl-wrapper">
				
	<div class="fwl-content">
		<h2></h2>
		<div class="fwl-row">
			
			<div class="fwl-box-wrap">
				
				<div class="fwl-box">
					<div class="fwl-box-header">
						<h1><?php esc_html_e('White Label', 'houzez'); ?></h1>
					</div><!-- fwl-box-header -->
					<div class="fwl-box-content">
						
						<p></p>

						<form class="white-label-wrap fwl-form" method="post" action="options.php">
							<?php settings_fields( 'favethemes_branding' ); ?>
							<?php wp_nonce_field( 'favethemes-white-label', 'favethemes-white-label-nonce' ); ?>

							<div class="field-wrap">
								<label for="branding"><?php esc_html_e( 'Theme Branding:', 'favethemes-white-label' ); ?></label>
								<input type="text" class="form-field" name="favethemes_branding[branding]" id="branding" value="<?php echo esc_attr( $settings['branding'] ); ?>">
								<p class="desc"><?php esc_html_e( 'This option replace Houzez in the admin', 'favethemes-white-label' ); ?></p>
							</div>
							
							<div class="field-wrap">
								<label for="theme-name"><?php esc_html_e( 'Theme Name:', 'favethemes-white-label' ); ?></label>
								<input type="text" class="form-field" name="favethemes_branding[name]" id="theme-name" value="<?php echo esc_attr( $settings['name'] ); ?>">
								<p class="desc"><?php esc_html_e( 'This option replace the theme name in Appearance > Themes.', 'favethemes-white-label' ); ?></p>
							</div>

							<div class="field-wrap">
								<label for="theme-author"><?php esc_html_e( 'Theme Author:', 'favethemes-white-label' ); ?></label>
								<input type="text" class="form-field" name="favethemes_branding[author]" id="theme-author" value="<?php echo esc_attr( $settings['author'] ); ?>">
								<p class="desc"><?php esc_html_e( 'This option replace the theme author in Appearance > Themes.', 'favethemes-white-label' ); ?></p>
							</div>

							<div class="field-wrap">
								<label for="author_url"><?php esc_html_e( 'Theme Author URL:', 'favethemes-white-label' ); ?></label>
								<input type="text" class="form-field" name="favethemes_branding[author_url]" id="author_url" value="<?php echo esc_url( $settings['author_url'] ); ?>">
								<p class="desc"><?php esc_html_e( 'This option replace the theme autohr url in Appearance > Themes.', 'favethemes-white-label' ); ?></p>
							</div>

							<div class="field-wrap full-width">
								<label for="theme-description"><?php esc_html_e( 'Theme Description:', 'favethemes-white-label' ); ?></label>
								<textarea class="form-field" name="favethemes_branding[description]" id="theme-description" rows="3"><?php echo esc_attr( $settings['description'] ); ?></textarea>
								<p class="desc"><?php esc_html_e( 'This option replace the theme description in Appearance > Themes.', 'favethemes-white-label' ); ?></p>
							</div>

							<div class="field-wrap">
								<label for="theme-screenshot"><?php esc_html_e( 'Theme Screenshot URL:', 'favethemes-white-label' ); ?></label>
								<div class="favethemes-media-live-preview" style="display:none;">
									<?php
									$preview = $settings['screenshot'];
									if ( $preview ) { ?>
										<img src="<?php echo esc_url( $preview ); ?>" alt="<?php esc_html_e( 'Preview Image', 'favethemes-white-label' ); ?>" />
									<?php } ?>
								</div>
								<div class="favethemes-upload-field">
									<input class="favethemes-media-input form-field" type="text" name="favethemes_branding[screenshot]" value="<?php echo esc_url( $settings['screenshot'] ); ?>">
									<input class="favethemes-screenshot-upload-button button-secondary" type="button" value="<?php esc_html_e( 'Upload', 'favethemes-white-label' ); ?>" />
									<a href="#" class="favethemes-media-remove" style="display:none;"><?php esc_html_e( 'Remove Image', 'favethemes-white-label' ); ?></a>
									<p class="desc"><?php esc_html_e( 'This option replace the theme screenshot in Appearance > Themes. Recommended size: 880x660px', 'favethemes-white-label' ); ?></p>
								</div>
							</div>

							<div class="field-wrap">
								<label for="branding-logo"><?php esc_html_e( 'Branding Logo URL:', 'favethemes-white-label' ); ?></label>
								<div class="favethemes-logo-live-preview" style="display:none;">
									<?php
									$branding_logo = $settings['branding-logo'];
									if ( $branding_logo ) { ?>
										<img src="<?php echo esc_url( $branding_logo ); ?>" alt="<?php esc_html_e( 'Branding Logo', 'favethemes-white-label' ); ?>" />
									<?php } ?>
								</div>
								<div class="favethemes-logo-upload-field">
									<input class="favethemes-logo-input form-field" type="text" name="favethemes_branding[branding-logo]" value="<?php echo esc_url( $settings['branding-logo'] ); ?>">
									<input class="favethemes-logo-upload-button button-secondary" type="button" value="<?php esc_html_e( 'Upload', 'favethemes-white-label' ); ?>" />
									<a href="#" class="favethemes-logo-remove" style="display:none;"><?php esc_html_e( 'Remove Image', 'favethemes-white-label' ); ?></a>
									<p class="desc"><?php esc_html_e( 'This option replace the branding logo in admin panel. Recommended size: 127x24', 'favethemes-white-label' ); ?></p>
								</div>
							</div>

							<div class="field-wrap full-width">
								<label for="themes-hide-customizer">
									<input type="checkbox" id="themes-hide-customizer" name="favethemes_branding[hide_themes_customizer]" value="1" <?php checked( '1', $settings['hide_themes_customizer'] ); ?>>
									<?php esc_html_e( 'Hide The Themes Section in the Customizer', 'favethemes-white-label' ); ?>
								</label>
								<!-- <label for="hide-white-label-page">
									<input type="checkbox" id="hide-white-label-page" name="favethemes_branding[hide_page]" value="1" <?php checked( '1', $settings['hide_page'] ); ?>>
									<?php esc_html_e( 'Hide White Label Page', 'favethemes-white-label' ); ?>
								</label> -->
								<!-- <p class="desc"><?php esc_html_e( 'Check this option to hide this page. Re-activate Houzez Theme Functionality to display this page again.', 'favethemes-white-label' ); ?></p> -->
							</div>

							<div class="field-wrap">
								<input type="submit" name="favethemes_branding_save" id="submit" class="button button-primary" value="<?php esc_attr_e( 'Save Changes', 'favethemes-white-label' ); ?>">
							</div>

							<div class="field-wrap" id="form-messages">
								<?php
								// Updated notice
								if ( isset( $_GET['settings-updated'] ) ) {
								    echo '<div class="settings-updated"><p>Settings updated successfully.</p></div>';
								} ?>
							</div>
							<div style="clear:both;"></div>
						</form>
					</div><!-- fwl-box-content -->
					
				</div><!-- fwl-box -->

			</div><!-- fwl-box-wrap -->

		</div><!-- fwl-row -->
	</div>
</div>