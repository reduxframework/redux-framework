<?php
/**
 * Template Name: Extendify Template
 * Template Post Type: post, page
 */

$extendifysdkCustomStyles = get_post_meta(
    isset($GLOBALS['post']) ? $GLOBALS['post']->ID : 0,
    'extendify_custom_stylesheet',
    true
);

?>
<?php wp_head(); ?>
<body <?php body_class(); ?>>
    <div class="ep-temp-container ep-container">

        <div class="ep-temp-entry-content">
            <?php
            if (have_posts()) {
                while (have_posts()) {
                    the_post();
                    the_content();
                }
            }
            ?>

        </div>


    </div><!-- #site-content -->
    <style>
    .ep-temp-container {
        margin-left: auto;
        margin-right: auto;
        min-width: 1280px;
    }
    .ep-temp-container .alignfull {
        min-width: 1280px !important;
    }
    @media(min-width: 700px) {
            .ep-temp-container [class*=extendify-] [class*=wp-block] > * {
                margin-top: 0px;
            }
            .ep-temp-container [class*=wp-block] > * .wp-block-button__link {
                border-radius: 0px !important;
            }
            .ep-temp-container .wp-block-image:not(.alignwide):not(.alignfull):not(.alignleft):not(.alignright):not(.aligncenter) {
                margin-top:0px;
            }
            body {background-color: #fff;}
            html, body {
                font-size: 16px !important;
            }
        }
    </style>
</body>

<?php
wp_footer();
