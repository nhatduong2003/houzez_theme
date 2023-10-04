<?php get_template_part('template-parts/page/breadcrumb'); ?> 
<div class="d-flex align-items-center">
    <?php get_template_part('template-parts/page/page-title'); ?> 
    <?php 
    if( ! houzez_hide_grid_switcher() ) {
        get_template_part('template-parts/listing/listing-switch-view');
    }?> 
</div><!-- d-flex -->  