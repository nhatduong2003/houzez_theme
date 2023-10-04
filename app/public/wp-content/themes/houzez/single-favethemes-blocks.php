<?php
global $post;
get_header(); 
?>

<section class="content-wrap">
    <?php
    while ( have_posts() ): the_post();
    the_content();
    endwhile;
    ?>
</section>
<?php get_footer(); ?>