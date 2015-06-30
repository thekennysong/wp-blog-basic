
    <section id="topper" style="text-align: center; background: #FF455F; padding: 100px 0;">
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <h4 style="color: #FFADB9; font-size: 25px; font-weight: 300;">
                        <?php
                            if (is_single()) :
                                echo get_the_time('l, F j, Y');
                                echo ' | ';
                                the_category('&middot');
                            else :
                                echo grab_subheading();
                            endif;
                        ?>
                    </h4>
                    <h1 style="color: #fff; margin: 0; font-size: 60px; line-height: 60px;">
                        <?php echo grab_title(); ?>
                    </h1>
                </div>
            </div>
        </div>
    </section>
