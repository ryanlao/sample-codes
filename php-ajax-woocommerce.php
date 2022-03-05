<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.4.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header('shop'); 
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

add_action( 'woocommerce_before_main_content_breadcrumb', 'woocommerce_breadcrumb', 20 );
add_action( 'woocommerce_before_shop_loop_result_count', 'woocommerce_result_count', 20 );
add_action( 'woocommerce_before_shop_loop_catalog_ordering', 'woocommerce_catalog_ordering', 30 );

if ( is_active_sidebar( 'off-canvas-widget-area' ) )
{
	$shop_has_sidebar = true;
}

$term = get_queried_object();

if( is_plugin_active( 'advanced-custom-fields/acf.php' ) && $term) {
    $term_desktop_bg = get_field('header_background_desktop', $term);
    $term_mobile_bg = get_field('header_background_mobile', $term);
}

$pc = $_GET['product_cat'] ? explode(',', $_GET['product_cat']):'';

?>
<?php if(isset($term_desktop_bg) && $term_desktop_bg && !$pc): ?>
<style type="text/css">
@media only screen and (min-width: 991px) {
    .header-title {
        background: #18191B url(<?php echo $term_desktop_bg ?>) no-repeat right center;
        background-size: 100%;
    }
}
</style>
<?php endif; ?>
<div class="header-title relative">
    <div class="container">    
        <div class="row">
            <div class="col-12">
                <?php do_action('woocommerce_before_main_content_breadcrumb'); ?>
            </div>
            <div class="col-md-8">
                <div class="term-quick-description">
                    <?php 
                        $term           = get_queried_object();
                        $parent_id      = empty( $term->term_id ) ? 0 : $term->term_id;
                        $categories     = get_terms('product_cat', array('hide_empty' => 0, 'parent' => $parent_id));

                        if( is_plugin_active( 'advanced-custom-fields/acf.php' ) && $term) {
                            $term_desktop_bg = get_field('header_background_desktop', $term);
                            $term_mobile_bg = get_field('header_background_mobile', $term);
                        }
                    ?>

                    <?php if(is_shop() || is_search()) : ?>
                        <h1 class="term-title">
                        <?php
                            if($pc && count($pc) > 1) {
                                echo 'Shop All';
                            } else {
                                echo woocommerce_page_title(); 
                            }
                        ?>
                        </h1>
                    <?php elseif(is_product_category() || is_product_tag()): ?>
                        <h1 class="term-title"><?php echo woocommerce_page_title(); ?></h1>
    					<?php if(get_the_archive_description()): ?>
                        	<div class="intro-text description"><?php echo get_the_archive_description(); ?></div>
                    	<?php endif; ?>
    				<?php elseif ( is_tax() ): ?>
                    <?php $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ); 
                        $tax = get_taxonomy( $term->taxonomy );
                        $title = get_term_meta( $term->term_id, '_term_meta_title', true )?:$term->name;
                            echo '<h1 class="term-title">'.($tax->label == "Product Suggested For Usage" ? $title : $tax->label.': '.$title).'</h1>';
                            if($term->description !='') {
                                echo '<div class="intro-text description">'.htmlspecialchars_decode($term->description, ENT_QUOTES ).'</div>';
                            }
                    ?>
                    <?php endif; ?>
                </div>
                <?php if(!is_search() && !is_shop() && get_the_archive_description()): ?>
                <div class="read-more"><a>Read More</a></div>
                <?php endif; ?>
                <?php if(!is_search() && !is_shop()): ?>
                <div class="form-filter-wrapper">
                    <?php if( is_product_category(array( 165 )) ): ?>
                    <div><?php echo do_shortcode('[label_finder]') ?></div>
                    <div class="form-filter-separator"><span>or</span></div>
                    <?php endif; ?>
                    <?php /*//if(!is_product_category('direct-thermal-labels') && (is_product_category('brother-dk-label-sizes') || is_product_category('dymo-labels') || is_product_category('avery-labels'))): ?>
                    <?php if( !is_product_category(array( 165 )) && is_product_category(array( 2742, 160 )) ): ?>
                    <div><?php echo do_shortcode('[label_model_finder category="'.get_queried_object()->taxonomy.'" id="'.get_queried_object()->term_id.'"]') ?></div>
                    <div class="form-filter-separator"><span>or</span></div>
                    <?php endif;*/ ?>
                    <div class="measurement-finder"><?php echo do_shortcode('[measurement_finder category="'.get_queried_object()->taxonomy.'" id="'.get_queried_object()->term_id.'"]') ?></div>
                    <div class="finder-button-search"><a id="finder-btn" class="button btn-th finder-btn"><i class="fa fa-search"></i> Search</a></div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<div id="primary" class="content-area shop-page<?php echo $shop_has_sidebar ? ' shop-has-sidebar':'';?>">
    <div class="container">
        <div class="row">
    		<div class="col columns">
    		   <div class="before_main_content">
    			   <?php do_action( 'woocommerce_before_main_content'); ?>
    		   </div> 
                <div id="content" class="site-content" role="main">
                    <div class="row d-flex">
					    <div class="col-lg-2 col-md-3 p-0 show-for-large">
						   <div class="shop_sidebar wpb_widgetised_column">
							   <?php do_action('woocommerce_sidebar'); ?>
						   </div>
					    </div>
					    <div class="col-lg-10 col-md-9 columns size-for-medium">
                            <div class="catalog-ordering">
                                <?php if (is_active_sidebar( 'off-canvas-widget-area')) : ?>
                                    <div id="button_offcanvas_sidebar_left">
                                        <span class="filters-text">
                                            <img src="<?php echo get_template_directory_uri().'/images/filter.png'; ?>" />
                                            <?php echo esc_html_e('Filters', 'woocommerce'); ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                                <?php if ( have_posts() ) : ?>
                                    <?php do_action( 'woocommerce_before_shop_loop_catalog_ordering' ); ?>
                                    <?php do_action( 'woocommerce_before_shop_loop_result_count' ); ?>
                                <?php endif; ?>
                            </div>
                			<?php if ( have_posts() ) : ?>
                                <?php do_action( 'woocommerce_before_shop_loop' ); ?>
                                <?php $animateCounter = 0; ?>
                                <?php woocommerce_product_loop_start(); ?>            
                                    <?php while ( have_posts() ) : the_post(); ?>     
                                        <?php $animateCounter++; ?>                       
                                        <?php wc_get_template_part( 'content', 'product' ); ?>
                                    <?php endwhile; // end of the loop. ?>                            
                                <?php woocommerce_product_loop_end(); ?>
        						<div class="woocommerce-after-shop-loop-wrapper">
        							<?php do_action( 'woocommerce_after_shop_loop' ); ?>
        						</div>
                            <?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>
                				<?php wc_get_template( 'loop/no-products-found.php' ); ?>
                            <?php endif; ?>
                			<?php do_action('woocommerce_after_main_content'); ?>
                			</div>
            			</div>
    		         </div>     
                </div>
            </div>
        </div>
    </div>

    <?php
    if(isset($term)) :
        echo '<div class="custom_terms_section pt-5 pb-5">';
        if( is_plugin_active( 'advanced-custom-fields/acf.php' ) && $term):
            // ALL ABOUT SECTION
            $about_heading = get_field('about_heading', $term);
            if(!empty($about_heading)) :
                $about_image = get_field('about_image', $term);
                $about_content = get_field('about_content', $term);
                echo '<div id="about-section" class="terms_section_wrapper">';
                    echo '<div class="container">';
                        echo '<div class="row align-items-start">';
                            if(!empty($about_image)):
                            echo '<div class="col-12 col-md-6 col-lg-5">';
                                echo '<img class="terms_section_image" src="'.$about_image.'" />';
                            echo '</div>';
                            echo '<div class="col-12 col-md-6 col-lg-7">';
                            else :
                                echo '<div class="col-12 col-md-12 col-lg-12">';
                            endif;
                                echo '<h2 class="terms_section_title text-left">'.$about_heading.'</h2>';
                                echo '<div class="terms_section_content">'.$about_content.'</div>';
                                echo '<div id="terms_section_show-more">Show More</div>';
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            endif;

            echo '<div id="terms_section_container">';
                // Column Content (1)
                $show_column_1 = get_field('show_column_1', $term);
                if($show_column_1) :
                    $column_content_1 = get_field('column_content_1', $term);
                    if(!empty($column_content_1) && !empty($column_content_1[0]['heading'])) :
                        $col = 1;
                        foreach($column_content_1 as $column_1) :
                            $col_heading = $column_1['heading'];
                            $col_image = $column_1['image'];
                            $col_title = $column_1['title'];
                            $col_content = $column_1['content'];
                            $show_listing = $column_1['show_listing'];
                            $col_listing = $column_1['listing'];
                            $show_table = $column_1['show_table'];
                            $col_table = $column_1['table'];
                            $show_two_column_dropdown = $column_1['show_two_column_dropdown'];
                            $show_accordion = $column_1['show_accordion'];
                            echo '<div id="col1'.$col.'-section" class="terms_section_wrapper">';
                                echo '<div class="container">';
                                    echo ((!empty($col_heading)) ? '<h2 class="terms_section_heading text-center">'.$col_heading.'</h2>' : '');
                                    echo '<div class="row align-items-start">';
                                        if(!empty($col_image)):
                                            echo '<div class="col-12 col-md-6 col-lg-6">';
                                                echo '<img class="terms_section_image" src="'.$col_image.'" />';
                                            echo '</div>';
                                            echo '<div class="col-12 col-md-6 col-lg-6">';
                                        else :
                                            echo '<div class="col-12 col-md-12 col-lg-12 text-center">';
                                        endif;
                                            echo ((!empty($col_title)) ? '<h4 class="terms_section_title text-left">'.$col_title.'</h4>' : '');
                                            echo '<div class="terms_section_content">';
                                                echo $col_content;
                                                if($show_listing) :
                                                    if($col_listing) :
                                                        echo '<ul class="col-list">';
                                                        foreach($col_listing as $col_list) :
                                                            $col_title = $col_list['title'];
                                                            $col_content = $col_list['content'];
                                                            echo '<li>';
                                                                echo ((!empty($col_title)) ? '<h4>'.$col_title.'</h4>' : '');
                                                                echo ((!empty($col_content)) ? $col_content : '');
                                                            echo '</li>';
                                                        endforeach;
                                                        echo '</ul>';
                                                    endif;
                                                endif;
                                                if($show_table) :
                                                    if($col_table) :
                                                        echo $col_table;
                                                    endif;
                                                endif;
                                            echo '</div>';
                                        echo '</div>';
                                    echo '</div>';
                                    if($show_two_column_dropdown) :
                                        $col_left_column_dropdown = $column_1['left_column_dropdown'];
                                        $col_right_column_dropdown = $column_1['right_column_dropdown'];
                                        echo '<div class="row align-items-start">';
                                            if($col_left_column_dropdown && $col_left_column_dropdown[0]['title']) :
                                                echo '<div class="col-12 col-md-6 col-lg-6">';
                                                    echo '<ul class="two-column-dropdown">';
                                                    foreach($col_left_column_dropdown as $list_left) :
                                                        $list_number = $list_left['list_number'];
                                                        $title = $list_left['title'];
                                                        $content = $list_left['content'];
                                                        echo '<li>';
                                                            echo ((!empty($list_number)) ? '<img src="'.$list_number.'" alt="" />' : '');
                                                            echo '<div>';
                                                                echo '<h4>'.$title.'</h4>';
                                                                echo '<div class="coldp_content">';
                                                                    echo $content;
                                                                echo '</div>';
                                                            echo '</div>';
                                                        echo '</li>';
                                                    endforeach;
                                                    echo '</ul>';
                                                echo '</div>';
                                            endif;
                                            if($col_right_column_dropdown && $col_right_column_dropdown[0]['title']) :
                                                echo '<div class="col-12 col-md-6 col-lg-6">';
                                                    echo '<ul class="two-column-dropdown">';
                                                    foreach($col_right_column_dropdown as $list_right) :
                                                        $list_number = $list_right['list_number'];
                                                        $title = $list_right['title'];
                                                        $content = $list_right['content'];
                                                        echo '<li>';
                                                            echo ((!empty($list_number)) ? '<img src="'.$list_number.'" alt="" />' : '');
                                                            echo '<div>';
                                                                echo '<h4>'.$title.'</h4>';
                                                                echo '<div class="coldp_content">';
                                                                    echo $content;
                                                                echo '</div>';
                                                            echo '</div>';
                                                        echo '</li>';
                                                    endforeach;
                                                    echo '</ul>';
                                                echo '</div>';
                                            endif;
                                        echo '</div>';
                                    endif;
                                    if($show_accordion) :
                                        $accordion_items = $column_1['accordion_items'];
                                        if(count($accordion_items) > 0 && !empty($accordion_items[0]['title'])) :
                                            echo '<div class="row align-items-start">';
                                                $accitem = 1;
                                                echo '<ul class="accordion-items">';
                                                foreach($accordion_items as $accordion_item) :
                                                    $acc_title = $accordion_item['title'];
                                                    $acc_image = $accordion_item['image'];
                                                    $acc_content = $accordion_item['content'];
                                                    echo '<li>';
                                                        echo '<h4>'.$acc_title.'</h4>';
                                                        echo '<div class="detailsContainer">';
                                                            echo ((!empty($acc_image)) ? '<div class="imgContainer"><img src="'.$acc_image.'" alt="'.$acc_title.'" /></div>' : '');
                                                            echo $acc_content;
                                                        echo '</div>';
                                                    echo '</li>';
                                                    $accitem++;
                                                endforeach;
                                                echo '</ul>';
                                            echo '</div>';
                                        endif;
                                    endif;
                                echo '</div>';
                            echo '</div>';
                            $col++;
                        endforeach;
                    endif;
                endif;
                // TWO COLUMNS
                $whyus_heading = get_field('whyus_heading', $term);
                if(!empty($whyus_heading)) :
                    $whyus_intro_text = get_field('whyus_intro_text', $term);
                    $whyus_bottom_text = get_field('whyus_bottom_text', $term);
                    $whyus_left_content = get_field('whyus_left_content', $term);
                    $whyus_right_content = get_field('whyus_right_content', $term);
                    echo '<div id="whyus-section" class="terms_section_wrapper">';
                        echo '<div class="container">';
                            echo '<h2 class="terms_section_title text-center">'.$whyus_heading.'</h2>';
                            echo ((!empty($whyus_intro_text)) ? '<p>'.$whyus_intro_text.'</p>' : '');
                            echo '<div class="row align-items-start">';
                                if($whyus_left_content && count($whyus_left_content) > 0) :
                                    echo '<div class="col-12 col-lg-6">';
                                        echo '<div class="terms_section_list">';
                                            echo '<ul class="terms_section_list_custom_ul">';
                                            foreach($whyus_left_content as $whyus_left) :
                                                echo '<li>';
                                                    echo '<img src="'.(!empty($whyus_left['image']) ? $whyus_left['image'] : get_template_directory_uri().'/images/pros.png').'" />';
                                                    echo '<div>';
                                                        echo ((!empty($whyus_left['title'])) ? '<h4>'.$whyus_left['title'].'</h4>' : '');
                                                        echo ((!empty($whyus_left['content'])) ? '<p>'.$whyus_left['content'].'</p>' : '');
                                                    echo '</div>';
                                                echo '</li>';
                                            endforeach;
                                            echo '</ul>';
                                        echo '</div>';
                                    echo '</div>';
                                endif;
                                if($whyus_right_content && count($whyus_right_content) > 0) :
                                    echo '<div class="col-12 col-lg-6">';
                                        echo '<div class="terms_section_list">';
                                            echo '<ul class="terms_section_list_custom_ul">';
                                            foreach($whyus_right_content as $whyus_right) :
                                                echo '<li>';
                                                    echo '<img src="'.(!empty($whyus_right['image']) ? $whyus_right['image'] : get_template_directory_uri().'/images/pros.png').'" />';
                                                    echo '<div>';
                                                        echo ((!empty($whyus_right['title'])) ? '<h4>'.$whyus_right['title'].'</h4>' : '');
                                                        echo ((!empty($whyus_right['content'])) ? '<p>'.$whyus_right['content'].'</p>' : '');
                                                    echo '</div>';
                                                echo '</li>';
                                            endforeach;
                                            echo '</ul>';
                                        echo '</div>';
                                    echo '</div>';
                                endif;
                            echo '</div>';
                            echo ((!empty($whyus_bottom_text)) ? '<div class="whyus-bottom">'.$whyus_bottom_text.'</div>' : '');
                        echo '</div>';
                    echo '</div>';
                endif;
                // Column Content (2)
                $show_column_2 = get_field('show_column_2', $term);
                if($show_column_2) :
                    $column_content_2 = get_field('column_content_2', $term);
                    if(!empty($column_content_2)) :
                        $col2 = 1;
                        foreach($column_content_2 as $column_2) :
                            $col_heading = $column_2['heading'];
                            $col_image = $column_2['image'];
                            $col_title = $column_2['title'];
                            $col_content = $column_2['content'];
                            $show_listing = $column_2['show_listing'];
                            $col_listing = $column_2['listing'];
                            $show_table = $column_2['show_table'];
                            $col_table = $column_2['table'];
                            $show_two_column_dropdown = $column_2['show_two_column_dropdown'];
                            $show_accordion = $column_2['show_accordion'];
                            echo '<div id="col2'.$col.'-section" class="terms_section_wrapper">';
                                echo '<div class="container">';
                                    echo ((!empty($col_heading)) ? '<h2 class="terms_section_heading text-center">'.$col_heading.'</h2>' : '');
                                    echo '<div class="row align-items-start">';
                                        if(!empty($col_image)):
                                            echo '<div class="col-12 col-md-6 col-lg-6">';
                                                echo '<img class="terms_section_image" src="'.$col_image.'" />';
                                            echo '</div>';
                                            echo '<div class="col-12 col-md-6 col-lg-6">';
                                        else :
                                            echo '<div class="col-12 col-md-12 col-lg-12 text-center">';
                                        endif;
                                            echo ((!empty($col_title)) ? '<h4 class="terms_section_title text-left">'.$col_title.'</h4>' : '');
                                            echo '<div class="terms_section_content">';
                                                echo $col_content;
                                                if($show_listing) :
                                                    if($col_listing) :
                                                        echo '<ul class="col-list">';
                                                        foreach($col_listing as $col_list) :
                                                            $col_title = $col_list['title'];
                                                            $col_content = $col_list['content'];
                                                            echo '<li>';
                                                                echo ((!empty($col_title)) ? '<h4>'.$col_title.'</h4>' : '');
                                                                echo ((!empty($col_content)) ? $col_content : '');
                                                            echo '</li>';
                                                        endforeach;
                                                        echo '</ul>';
                                                    endif;
                                                endif;
                                                if($show_table) :
                                                    if($col_table) :
                                                        echo $col_table;
                                                    endif;
                                                endif;
                                            echo '</div>';
                                        echo '</div>';
                                    echo '</div>';
                                    if($show_two_column_dropdown) :
                                        $col_left_column_dropdown = $column_2['left_column_dropdown'];
                                        $col_right_column_dropdown = $column_2['right_column_dropdown'];
                                        echo '<div class="row align-items-start">';
                                            if($col_left_column_dropdown && $col_left_column_dropdown[0]['title']) :
                                                echo '<div class="col-12 col-md-6 col-lg-6">';
                                                    echo '<ul class="two-column-dropdown">';
                                                    foreach($col_left_column_dropdown as $list_left) :
                                                        $list_number = $list_left['list_number'];
                                                        $title = $list_left['title'];
                                                        $content = $list_left['content'];
                                                        echo '<li>';
                                                            echo ((!empty($list_number)) ? '<img src="'.$list_number.'" alt="" />' : '');
                                                            echo '<div>';
                                                                echo '<h4>'.$title.'</h4>';
                                                                echo '<div class="coldp_content">';
                                                                    echo $content;
                                                                echo '</div>';
                                                            echo '</div>';
                                                        echo '</li>';
                                                    endforeach;
                                                    echo '</ul>';
                                                echo '</div>';
                                            endif;
                                            if($col_right_column_dropdown && $col_right_column_dropdown[0]['title']) :
                                                echo '<div class="col-12 col-md-6 col-lg-6">';
                                                    echo '<ul class="two-column-dropdown">';
                                                    foreach($col_right_column_dropdown as $list_right) :
                                                        $list_number = $list_right['list_number'];
                                                        $title = $list_right['title'];
                                                        $content = $list_right['content'];
                                                        echo '<li>';
                                                            echo ((!empty($list_number)) ? '<img src="'.$list_number.'" alt="" />' : '');
                                                            echo '<div>';
                                                                echo '<h4>'.$title.'</h4>';
                                                                echo '<div class="coldp_content">';
                                                                    echo $content;
                                                                echo '</div>';
                                                            echo '</div>';
                                                        echo '</li>';
                                                    endforeach;
                                                    echo '</ul>';
                                                echo '</div>';
                                            endif;
                                        echo '</div>';
                                    endif;
                                    if($show_accordion) :
                                        $accordion_items = $column_2['accordion_items'];
                                        if(count($accordion_items) > 0 && !empty($accordion_items[0]['title'])) :
                                            echo '<div class="row align-items-start">';
                                                $accitem = 1;
                                                echo '<ul class="accordion-items">';
                                                foreach($accordion_items as $accordion_item) :
                                                    $acc_title = $accordion_item['title'];
                                                    $acc_image = $accordion_item['image'];
                                                    $acc_content = $accordion_item['content'];
                                                    echo '<li>';
                                                        echo '<h4>'.$acc_title.'</h4>';
                                                        echo '<div class="detailsContainer">';
                                                            echo ((!empty($acc_image)) ? '<div class="imgContainer"><img src="'.$acc_image.'" alt="'.$acc_title.'" /></div>' : '');
                                                            echo $acc_content;
                                                        echo '</div>';
                                                    echo '</li>';
                                                    $accitem++;
                                                endforeach;
                                                echo '</ul>';
                                            echo '</div>';
                                        endif;
                                    endif;
                                echo '</div>';
                            echo '</div>';
                            $col2++;
                        endforeach;
                    endif;
                endif;
                // PROS & CONS
                $procons = get_field('procon_heading', $term);
                if(!empty($procons)) :
                    $procons_introtext = get_field('procon_introtext', $term);
                    $procons_bottom_content = get_field('procon_bottom_content', $term);
                    $procons_content = get_field('procons_content', $term);
                    if($procons_content && count($procons_content) > 0) :
                        echo '<div id="proscons-section" class="terms_section_wrapper">';
                            echo '<div class="container">';
                                echo '<h2 class="terms_section_title text-center">'.$procons.'</h2>';
                                echo ((!empty($procons_introtext)) ? '<p>'.$procons_introtext.'</p>' : '');
                                echo '<div class="row align-items-start">';
                                    $mm = 1;
                                    foreach($procons_content as $procons_val) :
                                        $heading = $procons_val['heading'];
                                        $subheading = $procons_val['subheading'];
                                        $show_proscons = $procons_val['show_proscons'];
                                        if($show_proscons) :
                                            $pros_content = $procons_val['pros_content'];
                                            $cons_content = $procons_val['cons_content'];
                                            echo '<div id="proscons-section'.$mm.'" class="terms_custom_section_wrapper">';
                                                echo '<div class="container">';
                                                    echo ((!empty($heading)) ? '<p class="terms_section_excerpt">'.$heading.'</p>' : '');
                                                    echo ((!empty($subheading)) ? '<div class="terms_section_content"><p>'.$subheading.'</p></div>' : '');
                                                    if(count($pros_content) > 0 || count($cons_content) > 0) :
                                                        echo '<div class="row">';
                                                        if(count($pros_content) > 0) :
                                                            if(!empty($cons_content[0]['item'])) :
                                                                echo '<div class="col-12 col-lg-6">';
                                                            else :
                                                                echo '<div class="col-12 col-lg-12">';
                                                            endif;
                                                                echo '<div class="terms_section_list card">';
                                                                    echo '<div class="terms_section_list_title">PROS</div>';
                                                                    echo '<ul>';
                                                                        foreach($pros_content as $pro_item) :
                                                                            $item = $pro_item['item'];
                                                                            $show_content_field = $pro_item['show_content_field'];
                                                                            $content = $pro_item['content'];
                                                                            echo '<li>';
                                                                                echo '<img src="'.get_template_directory_uri().'/images/pros.png" />';
                                                                                if($show_content_field) :
                                                                                    echo $item;
                                                                                    echo $content;
                                                                                else :
                                                                                    echo '<p>'.$item.'</p>';
                                                                                endif;
                                                                            echo '</li>';
                                                                        endforeach;
                                                                    echo '</ul>';
                                                                echo '</div>';
                                                            echo '</div>';
                                                        endif;
                                                        if(count($cons_content) > 0) :
                                                            if(!empty($cons_content[0]['item'])) :
                                                                echo '<div class="col-12 col-lg-6">';
                                                                    echo '<div class="terms_section_list">';
                                                                        echo '<div class="terms_section_list_title">CONS</div>';
                                                                        echo '<ul>';
                                                                            foreach($cons_content as $con_item) :
                                                                                echo '<li>';
                                                                                    echo '<img src="'.get_template_directory_uri().'/images/cons.png" />';
                                                                                    echo '<p>'.$con_item['item'].'</p>';
                                                                                echo '</li>';
                                                                            endforeach;
                                                                        echo '</ul>';
                                                                    echo '</div>';
                                                                echo '</div>';
                                                            endif;
                                                        endif;
                                                        echo '</div>';
                                                    endif;
                                                    $mm++;
                                                echo '</div>';
                                            echo '</div>';
                                        else :
                                            $special_content_left = $procons_val['special_content_left'];
                                            $special_content_right = $procons_val['special_content_right'];
                                            if($special_content_left || $special_content_right) :
                                                echo '<div class="special-content">';
                                                    echo '<div class="container">';
                                                    echo ((!empty($heading)) ? '<p class="terms_section_excerpt">'.$heading.'</p>' : '');
                                                    echo ((!empty($subheading)) ? '<div class="terms_section_content"><p>'.$subheading.'</p></div>' : '');
                                                        echo '<div class="row align-items-start">';
                                                            if($special_content_left && !empty($special_content_left[0]['title'])) :
                                                                if($special_content_right && !empty($special_content_right[0]['title'])) :
                                                                    echo '<div class="col-12 col-md-6 col-lg-6">';
                                                                else :
                                                                    echo '<div class="col-12 col-md-12 col-lg-12">';
                                                                endif;
                                                                    echo '<div class="terms_section_list">';
                                                                        echo '<ul class="terms_section_list_custom_ul">';
                                                                        foreach($special_content_left as $special_left) :
                                                                            echo '<li>';
                                                                                echo '<img src="'.(!empty($special_left['image']) ? $special_left['image'] : get_template_directory_uri().'/images/pros.png').'" />';
                                                                                echo '<div>';
                                                                                    echo ((!empty($special_left['title'])) ? '<h4>'.$special_left['title'].'</h4>' : '');
                                                                                    echo ((!empty($special_left['content'])) ? $special_left['content'] : '');
                                                                                echo '</div>';
                                                                            echo '</li>';
                                                                        endforeach;
                                                                        echo '</ul>';
                                                                    echo '</div>';
                                                                echo '</div>';
                                                            endif;
                                                            if($special_content_right && !empty($special_content_right[0]['title'])) :
                                                                echo '<div class="col-12 col-md-6 col-lg-6">';
                                                                    echo '<div class="terms_section_list">';
                                                                        echo '<ul class="terms_section_list_custom_ul">';
                                                                        foreach($special_content_right as $special_right) :
                                                                            echo '<li>';
                                                                                echo '<img src="'.(!empty($special_right['image']) ? $special_right['image'] : get_template_directory_uri().'/images/pros.png').'" />';
                                                                                echo '<div>';
                                                                                    echo ((!empty($special_right['title'])) ? '<h4>'.$special_right['title'].'</h4>' : '');
                                                                                    echo ((!empty($special_right['content'])) ? $special_right['content'] : '');
                                                                                echo '</div>';
                                                                            echo '</li>';
                                                                        endforeach;
                                                                        echo '</ul>';
                                                                    echo '</div>';
                                                                echo '</div>';
                                                            endif;
                                                        echo '</div>';
                                                    echo '</div>';
                                                echo '</div>';
                                            endif;
                                        endif;
                                    endforeach;
                                echo '</div>';
                                echo ((!empty($procons_bottom_content)) ? '<div class="procons-bottom">'.$procons_bottom_content.'</div>' : '');
                            echo '</div>';
                        echo '</div>';
                    endif;
                endif;
                // Column Content (3)
                $show_column_3 = get_field('show_column_3', $term);
                if($show_column_3) :
                    $column_content_3 = get_field('column_content_3', $term);
                    if(!empty($column_content_3)) :
                        $col3 = 1;
                        foreach($column_content_3 as $column_3) :
                            $col_heading = $column_3['heading'];
                            $col_image = $column_3['image'];
                            $col_title = $column_3['title'];
                            $col_content = $column_3['content'];
                            $show_listing = $column_3['show_listing'];
                            $col_listing = $column_3['listing'];
                            $show_table = $column_3['show_table'];
                            $col_table = $column_3['table'];
                            $show_two_column_dropdown = $column_3['show_two_column_dropdown'];
                            $show_accordion = $column_3['show_accordion'];
                            echo '<div id="col3'.$col.'-section" class="terms_section_wrapper">';
                                echo '<div class="container">';
                                    echo ((!empty($col_heading)) ? '<h2 class="terms_section_heading text-center">'.$col_heading.'</h2>' : '');
                                    echo '<div class="row align-items-start">';
                                        if(!empty($col_image)):
                                            echo '<div class="col-12 col-md-6 col-lg-6">';
                                                echo '<img class="terms_section_image" src="'.$col_image.'" />';
                                            echo '</div>';
                                            echo '<div class="col-12 col-md-6 col-lg-6">';
                                        else :
                                            echo '<div class="col-12 col-md-12 col-lg-12 text-center">';
                                        endif;
                                            echo ((!empty($col_title)) ? '<h4 class="terms_section_title text-left">'.$col_title.'</h4>' : '');
                                            echo '<div class="terms_section_content">';
                                                echo $col_content;
                                                if($show_listing) :
                                                    if($col_listing) :
                                                        echo '<ul class="col-list">';
                                                        foreach($col_listing as $col_list) :
                                                            $col_title = $col_list['title'];
                                                            $col_content = $col_list['content'];
                                                            echo '<li>';
                                                                echo ((!empty($col_title)) ? '<h4>'.$col_title.'</h4>' : '');
                                                                echo ((!empty($col_content)) ? $col_content : '');
                                                            echo '</li>';
                                                        endforeach;
                                                        echo '</ul>';
                                                    endif;
                                                endif;
                                                if($show_table) :
                                                    if($col_table) :
                                                        echo $col_table;
                                                    endif;
                                                endif;
                                            echo '</div>';
                                        echo '</div>';
                                    echo '</div>';
                                    if($show_two_column_dropdown) :
                                        $col_left_column_dropdown = $column_3['left_column_dropdown'];
                                        $col_right_column_dropdown = $column_3['right_column_dropdown'];
                                        echo '<div class="row align-items-start">';
                                            if($col_left_column_dropdown && $col_left_column_dropdown[0]['title']) :
                                                echo '<div class="col-12 col-md-6 col-lg-6">';
                                                    echo '<ul class="two-column-dropdown">';
                                                    foreach($col_left_column_dropdown as $list_left) :
                                                        $list_number = $list_left['list_number'];
                                                        $title = $list_left['title'];
                                                        $content = $list_left['content'];
                                                        echo '<li>';
                                                            echo ((!empty($list_number)) ? '<img src="'.$list_number.'" alt="" />' : '');
                                                            echo '<div>';
                                                                echo '<h4>'.$title.'</h4>';
                                                                echo '<div class="coldp_content">';
                                                                    echo $content;
                                                                echo '</div>';
                                                            echo '</div>';
                                                        echo '</li>';
                                                    endforeach;
                                                    echo '</ul>';
                                                echo '</div>';
                                            endif;
                                            if($col_right_column_dropdown && $col_right_column_dropdown[0]['title']) :
                                                echo '<div class="col-12 col-md-6 col-lg-6">';
                                                    echo '<ul class="two-column-dropdown">';
                                                    foreach($col_right_column_dropdown as $list_right) :
                                                        $list_number = $list_right['list_number'];
                                                        $title = $list_right['title'];
                                                        $content = $list_right['content'];
                                                        echo '<li>';
                                                            echo ((!empty($list_number)) ? '<img src="'.$list_number.'" alt="" />' : '');
                                                            echo '<div>';
                                                                echo '<h4>'.$title.'</h4>';
                                                                echo '<div class="coldp_content">';
                                                                    echo $content;
                                                                echo '</div>';
                                                            echo '</div>';
                                                        echo '</li>';
                                                    endforeach;
                                                    echo '</ul>';
                                                echo '</div>';
                                            endif;
                                        echo '</div>';
                                    endif;
                                    if($show_accordion) :
                                        $accordion_items = $column_3['accordion_items'];
                                        if(count($accordion_items) > 0 && !empty($accordion_items[0]['title'])) :
                                            echo '<div class="row align-items-start">';
                                                $accitem = 1;
                                                echo '<ul class="accordion-items">';
                                                foreach($accordion_items as $accordion_item) :
                                                    $acc_title = $accordion_item['title'];
                                                    $acc_image = $accordion_item['image'];
                                                    $acc_content = $accordion_item['content'];
                                                    echo '<li>';
                                                        echo '<h4>'.$acc_title.'</h4>';
                                                        echo '<div class="detailsContainer">';
                                                            echo ((!empty($acc_image)) ? '<div class="imgContainer"><img src="'.$acc_image.'" alt="'.$acc_title.'" /></div>' : '');
                                                            echo $acc_content;
                                                        echo '</div>';
                                                    echo '</li>';
                                                    $accitem++;
                                                endforeach;
                                                echo '</ul>';
                                            echo '</div>';
                                        endif;
                                    endif;
                                echo '</div>';
                            echo '</div>';
                            $col3++;
                        endforeach;
                    endif;
                endif;
                // FAQ SECTION
                $faqs_content = get_field('faqs_content', $term);
                $faqs_json = array();
                if($faqs_content && !empty($faqs_content[0]['tab_name'])) :
                    echo '<div id="faq-section" class="terms_section_wrapper terms_custom_tabs_wrapper">';
                        echo '<div class="container">';
                            echo '<div class="woocommerce-tabs wc-tabs-wrapper custom-tabs">';
                                echo '<ul class="tabs wc-tabs" role="tablist">';
                                    $i = 1;
                                    foreach($faqs_content as $faq) :
                                        $tab_name = $faq['tab_name'];
                                        $image_code = $faq['image_code'];
                                        if($image_code == 'code') :
                                            $faq_image = $faq['code'];
                                        else :
                                            $faq_image = '<img src="'.$faq['image'].'" alt="" />';
                                        endif;
                                        echo '<li class="term-'. $i .'_tab'. ($i == 1 ? ' active':'') .'" data-id="#xtab-'. $i .'">';
                                            echo $faq_image;
                                            echo $tab_name;
                                        echo '</li>';
                                        $i++;
                                    endforeach;
                                echo '</ul>';
                                $ii = 1;
                                foreach($faqs_content as $faq1) :
                                    $faq_content = $faq1['content'];
                                    
                                    $faq_sec = false;
                                    if(strpos($faq1['tab_name'], 'FAQs') !== false) {
                                        $faq_sec = true;
                                    }
                                        
                                    if(count($faq_content) > 0) :
                                        echo '<div class="entry-content wc-tab" id="xtab-'. $ii .'" style="'. ($ii == 1 ? 'display:block':'') .'">';
                                        foreach($faq_content as $faqContent) :
                                            $heading = $faqContent['heading'];
                                            $intro_text = $faqContent['intro_text'];
                                            $qa_content = $faqContent['qa_content'];
                                            echo ((!empty($heading)) ? '<h3>'.$heading.'</h3>' : '');
                                            echo ((!empty($intro_text)) ? '<p class="mbottom-0">'.$intro_text.'</p>' : '');
                                            // echo '<div class="entry-content wc-tab" id="xtab-'. $ii .'" style="'. ($ii == 1 ? 'display:block':'') .'">';
                                            // echo '<div class="product_custom_section">';
                                            echo '<ul class="product_loop_section1">';
                                            foreach($qa_content as $qaContent) :
                                                $question = $qaContent['question'];
                                                $answer = $qaContent['answer'];
                                                $show_two_column = $qaContent['show_two_column'];
                                                $show_two_column_list = $qaContent['show_two_column_list'];
                                                
                                                if($faq_sec == true && $answer) {
                                                    $faqs_json[] = '{ "@type": "Question","name": "'.$question.'", "acceptedAnswer": { "@type": "Answer","text": '.str_replace('\n', '', json_encode($answer)).' } }';
                                                }
                                                echo '<li>';
                                                    echo '<p class="qa_question">'.$question.'</p>';
                                                    echo '<div class="qa_answer">';
                                                        echo $answer;
                                                        if($show_two_column_list) :
                                                            $column_list_left = $qaContent['column_list_left'];
                                                            $column_list_right = $qaContent['column_list_right'];
                                                            echo '<div class="column-list-wrapper">';
                                                                echo '<div class="row">';
                                                                if(count($column_list_left) > 0) :
                                                                    if(count($column_list_right) > 0 && !empty($column_list_right[0]['title'])) :
                                                                        echo '<div class="col-12 col-sm-12 col-md-6 col-lg-6">';
                                                                    else:
                                                                        echo '<div class="col-12 col-sm-12 col-md-12 col-lg-12">';
                                                                    endif;
                                                                    foreach($column_list_left as $clist_left) :
                                                                        $clist_left_image = $clist_left['image'];
                                                                        $clist_left_title = $clist_left['title'];
                                                                        $clist_left_content = $clist_left['content'];
                                                                        echo '<div class="column-list-item">';
                                                                            echo '<div class="iconContent">';
                                                                                echo '<img src="'.$clist_left_image.'" alt="'.$clist_left_title.'" />';
                                                                            echo '</div>';
                                                                            echo '<div class="itemContent">';
                                                                                echo '<h5>'.$clist_left_title.'</h5>';
                                                                                echo $clist_left_content;
                                                                            echo '</div>';
                                                                        echo '</div>';
                                                                    endforeach;
                                                                    echo '</div>';
                                                                endif;
                                                                if(count($column_list_right) > 0) :
                                                                    echo '<div class="col-12 col-sm-12 col-md-6 col-lg-6">';
                                                                    foreach($column_list_right as $clist_right) :
                                                                        $clist_right_image = $clist_right['image'];
                                                                        $clist_right_title = $clist_right['title'];
                                                                        $clist_right_content = $clist_right['content'];
                                                                        echo '<div class="column-list-item">';
                                                                            echo '<div class="iconContent">';
                                                                                echo '<img src="'.$clist_right_image.'" alt="'.$clist_right_title.'" />';
                                                                            echo '</div>';
                                                                            echo '<div class="itemContent">';
                                                                                echo '<h5>'.$clist_right_title.'</h5>';
                                                                                echo $clist_right_content;
                                                                            echo '</div>';
                                                                        echo '</div>';
                                                                    endforeach;
                                                                    echo '</div>';
                                                                endif;
                                                                echo '</div>';
                                                            echo '</div>';
                                                        endif;
                                                        if($show_two_column) :
                                                            $qa_columns = $qaContent['columns'];
                                                            if(count($qa_columns) > 0) :
                                                                echo '<div class="column-wrapper">';
                                                                foreach($qa_columns as $qac) :
                                                                    $qac_heading = $qac['heading'];
                                                                    $qac_show_text_editor = $qac['show_text_editor'];
                                                                    echo ((!empty($qac_heading)) ? '<h5>'.$qac_heading.'</h5>' : '');
                                                                    if($qac_show_text_editor) :
                                                                        $text_content = $qac['text_content'];
                                                                        echo $text_content;
                                                                    else :
                                                                        $title_column_1 = $qac['title_column_1'];
                                                                        $items_column_1 = $qac['items_column_1'];
                                                                        $title_column_2 = $qac['title_column_2'];
                                                                        $items_column_2 = $qac['items_column_2'];
                                                                        echo '<div class="row">';
                                                                            if(count($items_column_1) > 0 && !empty($items_column_1[0]['item'])) :
                                                                                if(count($items_column_2) > 0 && !empty($items_column_2[0]['item'])) :
                                                                                    echo '<div class="col-12 col-md-6 col-lg-6">';
                                                                                else :
                                                                                    echo '<div class="col-12 col-md-12 col-lg-12">';
                                                                                endif;
                                                                                    echo ((!empty($title_column_1)) ? '<div class="column-title">'.$title_column_1.'</div>' : '');
                                                                                    echo '<ul class="column-item">';
                                                                                    foreach($items_column_1 as $item_1) :
                                                                                        echo '<li>'.$item_1['item'].'</li>';
                                                                                    endforeach;
                                                                                    echo '</ul>';
                                                                                echo '</div>';
                                                                            endif;
                                                                            if(count($items_column_2) > 0 && !empty($items_column_2[0]['item'])) :
                                                                            echo '<div class="col-12 col-md-6 col-lg-6">';
                                                                                echo ((!empty($title_column_2)) ? '<div class="column-title">'.$title_column_2.'</div>' : '');
                                                                                echo '<ul class="column-item">';
                                                                                foreach($items_column_2 as $item_2) :
                                                                                    echo '<li>'.$item_2['item'].'</li>';
                                                                                endforeach;
                                                                                echo '</ul>';
                                                                            echo '</div>';
                                                                            endif;
                                                                        echo '</div>';
                                                                    endif;
                                                                endforeach;
                                                                echo '</div>';
                                                            endif;
                                                        endif;
                                                    echo '</div>';
                                                echo '</li>';
                                            endforeach;
                                            echo '</ul>';
                                            // echo '</div>';
                                            // echo '</div>';
                                        endforeach;
                                        echo '</div>';

                                        /*echo '<div class="entry-content wc-tab" id="xtab-'. $ii .'" style="'. ($ii == 1 ? 'display:block':'') .'">';
                                        echo '<div class="product_custom_section">';
                                        echo '<ul class="product_loop_section">';
                                        foreach($faq_content as $fcontent) :
                                            $faq_title = $fcontent['title'];
                                            $faq_text_content = $fcontent['text_content'];
                                            echo '<li><h4>'.$faq_title.'</h4>';
                                            echo '<div>'.$faq_text_content.'</div></li>';
                                        endforeach;
                                        echo '</ul>';
                                        echo '</div>';
                                        echo '</div>';*/
                                    endif;
                                    $ii++;
                                endforeach;
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                endif;
            echo '</div>';
        endif;
        echo '</div>';
    endif; ?>

</div>

<?php 
    if(count($faqs_json) > 0 && function_exists('google_faq_schema')) {

        add_action('wp_footer', function() use ($faqs_json) { echo google_faq_schema($faqs_json); } , 20, 1);

    }
?>
<?php get_footer('shop'); ?>