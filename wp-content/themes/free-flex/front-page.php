<?php get_header(); ?>

    <?php grab_slice('topper'); ?>

    <div class="container" style="margin: 0 auto; padding: 100px 15px; background: #fff; border-radius: 5px;">
        <div class="row">
            <div class="col-md-12">

                <?php if (have_posts()) : ?>

                    <?php while (have_posts()) : the_post(); ?>
                        <?php $image = wp_get_attachment_url( get_post_thumbnail_id($post->ID)); ?>
                        <div class="background" style="background-image: url('<?php echo $image; ?>');">
                            <div class="darken"></div>
                            <div class="main-info">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-10 col-md-offset-1">
                                            <h3 class="the-title"><?php echo get_the_title(); ?></h3>
                                            <div class="meta">
                                                <p><?php the_category('&middot'); ?> | <?php the_time('m.d.y'); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="post">
                                    <div class="excerpt">
                                        <?php the_content(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php endwhile; ?>

                    <!-- BEGIN PAGINATION HERE -->
                    <?php echo paginate_links(); ?>
                    <!-- END PAGINATION HERE -->

                <?php else : ?>

                        <?php if (is_search()) : ?>

                            <h2>No results found</h2>
                            <p>Sorry, that search returned no results.</p>

                        <?php else : ?>

                            <h2>Page not found</h2>
                            <p>Sorry, we can't find the page you are looking for.</p>

                        <?php endif; ?>

                <?php endif; ?>

            </div>
        </div>
    </div>







<?php get_footer(); ?>