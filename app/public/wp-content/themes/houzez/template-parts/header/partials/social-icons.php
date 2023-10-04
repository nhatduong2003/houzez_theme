<?php if( houzez_option('social-header') != '0' ): 

	$agent_whatsapp = houzez_option('hs-whatsapp');
	$agent_whatsapp_call = str_replace(array('(',')',' ','-'),'', $agent_whatsapp);
?>
<div class="header-social-icons">
	<ul class="list-inline">
		
		<?php if( houzez_option('hs-facebook') != '' ) { ?>
		<li class="list-inline-item">
			<a target="_blank" class="btn-square btn-facebook" href="<?php echo esc_url(houzez_option('hs-facebook')); ?>">
				<i class="houzez-icon icon-social-media-facebook"></i>
			</a>
		</li>
		<?php } ?>

		<?php if( houzez_option('hs-twitter') != '' ){ ?>
		 <li class="list-inline-item">
			<a target="_blank" class="btn-square btn-twitter" href="<?php echo esc_url(houzez_option('hs-twitter')); ?>">
				<i class="houzez-icon icon-social-media-twitter"></i>
			</a>
		</li>
		<?php } ?>

		<?php if( $agent_whatsapp != '' ){ ?>
		 <li class="list-inline-item">
			<a target="_blank" class="btn-square btn-whatsapp" href="https://wa.me/<?php echo esc_attr( $agent_whatsapp_call ); ?>">
				<i class="houzez-icon icon-messaging-whatsapp"></i>
			</a>
		</li>
		<?php } ?>

		<?php if( houzez_option('hs-tiktok') != '' ){ ?>
		 <li class="list-inline-item">
			<a target="_blank" class="btn-square btn-tiktok" href="<?php echo esc_url(houzez_option('hs-tiktok')); ?>">
				<i class="houzez-icon icon-tiktok-1-logos-24"></i>
			</a>
		</li>
		<?php } ?>

		<?php if( houzez_option('hs-telegram') != '' ){ ?>
		 <li class="list-inline-item">
			<a target="_blank" class="btn-square btn-telegram" href="https://telegram.me/<?php echo esc_attr(houzez_option('hs-telegram')); ?>">
				<i class="houzez-icon icon-telegram-logos-24"></i>
			</a>
		</li>
		<?php } ?>

		<?php if( houzez_option('hs-skype') != '' ){ ?>
		 <li class="list-inline-item">
			<a target="_blank" class="btn-square btn-skype" href="skype:<?php echo esc_attr(houzez_option('hs-skype')); ?>?chat/">
				<i class="houzez-icon icon-video-meeting-skype"></i>
			</a>
		</li>
		<?php } ?>

		<?php if( houzez_option('hs-googleplus') != '' ){ ?>
		 <li class="list-inline-item">
			<a target="_blank" class="btn-square btn-google-plus" href="<?php echo esc_url(houzez_option('hs-googleplus')); ?>">
				<i class="houzez-icon icon-social-media-google-plus-1"></i>
			</a>
		</li>
		<?php } ?>

		<?php if( houzez_option('hs-linkedin') != '' ){ ?>
		 <li class="list-inline-item">
			<a target="_blank" class="btn-square btn-linkedin" href="<?php echo esc_url(houzez_option('hs-linkedin')); ?>">
				<i class="houzez-icon icon-professional-network-linkedin"></i>
			</a>
		</li>
		<?php } ?>

		<?php if( houzez_option('hs-instagram') != '' ){ ?>
		 <li class="list-inline-item">
			<a target="_blank" class="btn-square btn-instagram" href="<?php echo esc_url(houzez_option('hs-instagram')); ?>">
				<i class="houzez-icon icon-social-instagram"></i>
			</a>
		</li>
		<?php } ?>

		<?php if( houzez_option('hs-pinterest') != '' ){ ?>
		 <li class="list-inline-item">
			<a target="_blank" class="btn-square btn-pinterest" href="<?php echo esc_url(houzez_option('hs-pinterest')); ?>">
				<i class="houzez-icon icon-social-pinterest"></i>
			</a>
		</li>
		<?php } ?>

		<?php if( houzez_option('hs-youtube') != '' ){ ?>
		 <li class="list-inline-item">
			<a target="_blank" class="btn-square btn-youtube" href="<?php echo esc_url(houzez_option('hs-youtube')); ?>">
				<i class="houzez-icon icon-social-video-youtube-clip"></i>
			</a>
		</li>
		<?php } ?>

		<?php if( houzez_option('hs-yelp') != '' ){ ?>
		 <li class="list-inline-item">
			<a target="_blank" class="btn-square btn-yelp" href="<?php echo esc_url(houzez_option('hs-yelp')); ?>">
				<i class="houzez-icon icon-social-media-yelp"></i>
			</a>
		</li>
		<?php } ?>

		<?php if( houzez_option('hs-behance') != '' ){ ?>
		<li class="list-inline-item">
			<a target="_blank" class="btn-square btn-behance" href="<?php echo esc_url(houzez_option('hs-behance')); ?>">
				<i class="houzez-icon icon-designer-community-behance"></i>
			</a>
		</li>
		<?php } ?>
	</ul>
</div><!-- .header-social-icons -->
<?php endif; ?>