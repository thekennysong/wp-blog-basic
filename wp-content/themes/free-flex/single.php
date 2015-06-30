<?php get_header(); while (have_posts()) : the_post(); ?>

    <?php grab_slice('topper'); ?>

    <article class="container" style="margin: auto; padding: 100px 15px; background: #fff; border-radius: 5px;">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <?php the_content(); ?>
            </div>
        </div>
    </article>

<?php endwhile; get_footer(); ?>