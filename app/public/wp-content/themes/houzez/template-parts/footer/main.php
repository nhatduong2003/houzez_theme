<footer class="footer-wrap footer-wrap-v1">
	<?php get_template_part('template-parts/footer/footer'); ?>

	<?php get_template_part('template-parts/footer/footer-bottom-'.houzez_option('ft-bottom')); ?>
</footer>
<?php 
if( houzez_option('backtotop') ) {
	get_template_part('template-parts/footer/back-to-top'); 
}?>