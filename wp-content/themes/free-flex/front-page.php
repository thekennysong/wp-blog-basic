<?php get_header(); ?>

    <?php grab_slice('topper'); ?>

    <div class="container" style="margin: 0 auto; padding: 100px 15px; background: #fff; border-radius: 5px;">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">

                <?php if (have_posts()) : ?>

                    <?php while (have_posts()) : the_post(); ?>
                        <div class="post" style="border-bottom: solid 2px #FF455F; margin-bottom: 40px; padding: 15px 0 50px;">

                            <h2><a href="<?php echo get_permalink(); ?>"><?php echo get_the_title(); ?></a></h2>
                            <div class="meta">
                                <p><?php the_category('&middot'); ?> | <?php the_time('F j, Y'); ?></p>
                            </div>
                            <div class="excerpt">
                                <?php the_content(); ?>
                            </div>

                            <!-- <a href="<?php echo get_permalink(); ?>" class="btn btn-default">Read More &raquo;</a>
 -->
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