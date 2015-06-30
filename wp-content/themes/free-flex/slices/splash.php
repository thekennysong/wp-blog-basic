<style>
    /*====================================
    =            SPLASH POPUP            =
    ====================================*/
    #image-popup-inline, #inline-popup {
        position: relative;
        max-width: 600px;
        margin: 0 auto;
        width: 100%;
    }
    #image-popup-inline a {
        display: block;
        margin: 0 auto;
        width: 100%;
        position: relative;
        max-width: 600px;
    }
    #image-popup-inline a img {
        height: auto;
        width: 100%;
    }
    #image-popup-inline button.mfp-close, #inline-popup button.mfp-close {
        color: #fff;
        top: -44px;
        right: -13px;
    }
    .hide-me {
        display: none;
    }
    #inline-popup .inline-wrap {
        background: #fff;
        padding: 15px;
        text-align: center;
    }
    </style>

    <?php
        $turn_popup_on = get_field('turn_popup_on');

        if (get_field('is_cookied')) :
            $cookie_name = get_field('cookie_name');
            if (isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 1) :
                $turn_popup_on = FALSE;
            else :
    ?>
            <script>
            var docCookies={getItem:function(a){return decodeURIComponent(document.cookie.replace(new RegExp("(?:(?:^|.*;)\\s*"+encodeURIComponent(a).replace(/[\-\.\+\*]/g,"\\$&")+"\\s*\\=\\s*([^;]*).*$)|^.*$"),"$1"))||null},setItem:function(a,b,c,d,e,f){if(!a||/^(?:expires|max\-age|path|domain|secure)$/i.test(a))return!1;var g="";if(c)switch(c.constructor){case Number:g=1/0===c?"; expires=Fri, 31 Dec 9999 23:59:59 GMT":"; max-age="+c;break;case String:g="; expires="+c;break;case Date:g="; expires="+c.toUTCString()}return document.cookie=encodeURIComponent(a)+"="+encodeURIComponent(b)+g+(e?"; domain="+e:"")+(d?"; path="+d:"")+(f?"; secure":""),!0},removeItem:function(a,b,c){return a&&this.hasItem(a)?(document.cookie=encodeURIComponent(a)+"=; expires=Thu, 01 Jan 1970 00:00:00 GMT"+(c?"; domain="+c:"")+(b?"; path="+b:""),!0):!1},hasItem:function(a){return new RegExp("(?:^|;\\s*)"+encodeURIComponent(a).replace(/[\-\.\+\*]/g,"\\$&")+"\\s*\\=").test(document.cookie)},keys:function(){for(var a=document.cookie.replace(/((?:^|\s*;)[^\=]+)(?=;|$)|^\s*|\s*(?:\=[^;]*)?(?:\1|$)/g,"").split(/\s*(?:\=[^;]*)?;\s*/),b=0;b<a.length;b++)a[b]=decodeURIComponent(a[b]);return a}};
            docCookies.setItem('<?php echo get_field('cookie_name'); ?>', 1, new Date(2020, 5, 12));
            </script>
    <?php
            endif;
        endif;
    ?>

    <?php if ($turn_popup_on) : ?>

        <?php if (get_field('lightbox_type') == 'image' && get_field('popup_image')) : ?>

            <?php if (!get_field('is_image_linkable')) : ?>
            <a id="image-popup" class="hide-me" href="<?php echo get_field('popup_image'); ?>">Splash Image</a>
            <script>
            $(function() {
                $('#image-popup').magnificPopup({
                    type: 'image',
                    closeOnContentClick: true,
                    mainClass: 'mfp-img-mobile',
                    image: {
                        verticalFit: true
                    }

                });
                $('#image-popup').click();
            });
            </script>
            <?php else : ?>
            <a id="image-popup-linkable" class="hide-me" href="#image-popup-inline">Splash Image</a>
            <div id="image-popup-inline" class="mfp-hide">
                <div class="image-wrap">
                    <button title="Close (Esc)" type="button" class="mfp-close">&times;</button>
                    <a href="<?php echo get_field('image_url'); ?>">
                        <img src="<?php echo get_field('popup_image'); ?>">
                    </a>
                </div>
            </div>
            <script>
            $(function() {
                $('#image-popup-linkable').magnificPopup({
                    type: 'inline',
                    preloader: false
                });
                $('#image-popup-linkable').click();
            });
            </script>

            <?php endif; ?>

        <?php endif; ?>


        <?php if (get_field('lightbox_type') == 'video') : ?>

            <?php if (!get_field('autoplay_video')) : ?>
            <a id="splash-video" class="hide-me" href="http:://www.youtube.com/watch?v=<?php echo get_field('popup_video_id'); ?>">Splash Video</a>
            <?php else : ?>
            <a id="splash-video" class="hide-me" rel="nofollow" href="http:://www.autoplay.com/watch?v=<?php echo get_field('popup_video_id'); ?>">Splash Video</a>
            <?php endif; ?>
            <script>
            $(function() {
                $('#splash-video').magnificPopup({
                    type: 'iframe',
                    mainClass: 'mfp-fade',
                    removalDelay: 160,
                    preloader: false,
                    fixedContentPos: false
                });
                $('#splash-video').click();
            });
            </script>

        <?php endif; ?>


        <?php if (get_field('lightbox_type') == 'html') : ?>

            <a id="inline-popup-link" class="hide-me" href="#inline-popup">Splash Statement</a>
            <div id="inline-popup" class="mfp-hide">
                <div class="inline-wrap">
                    <button title="Close (Esc)" type="button" class="mfp-close">&times;</button>
                    <?php echo get_field('inline_popup'); ?>
                </div>
            </div>
            <script>
            $(function() {
                $('#inline-popup-link').magnificPopup({
                    type: 'inline',
                    preloader: false
                });
                $('#inline-popup-link').click();
            });
            </script>

        <?php endif; ?>

    <?php endif; ?>