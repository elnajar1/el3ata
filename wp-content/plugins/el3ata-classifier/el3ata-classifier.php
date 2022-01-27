<?php
/**
 * Plugin Name: el3ata Content Classifier Widget 
 * Plugin URI: https://github.com/arunbasillal/WordPress-Starter-Plugin
 * Description: Organizing the categorys
 * Author: Mahmoud
 * Author URI: 
 * Version: 1.0
 */
 

class My_Widget_Categories extends WP_Widget {

    function My_Widget_Categories() {
        $widget_ops = array( 'classname' => 'widget_categories', 'description' => __( "My list or dropdown of categories" ) );
        $this->WP_Widget('my_categories', __('My Categories'), $widget_ops);
    }

    function widget( $args, $instance ) {
        extract( $args );

        $title = apply_filters('widget_title', empty( $instance['title'] ) ? __( 'Categories' ) : $instance['title']);
        $c = $instance['count'] ? '1' : '0';
        $h = $instance['hierarchical'] ? '1' : '0';
        $d = $instance['dropdown'] ? '1' : '0';

        echo $before_widget;
        if ( $title )
            echo $before_title . $title . $after_title;

        $cat_args = array('orderby' => 'name', 'show_count' => $c, 'hierarchical' => $h);

        if ( $d ) {
            $cat_args['show_option_none'] = __('Select Category');
            wp_dropdown_categories(apply_filters('widget_categories_dropdown_args', $cat_args));
?>

<script type='text/javascript'>
/* <![CDATA[ */
    var dropdown = document.getElementById("cat");
    function onCatChange() {
        if ( dropdown.options[dropdown.selectedIndex].value > 0 ) {
            location.href = "<?php echo get_option('home'); ?>/?cat="+dropdown.options[dropdown.selectedIndex].value;
        }
    }
    dropdown.onchange = onCatChange;
/* ]]> */
</script>

<?php
        } else {
?>
        <ul>
<?php
        $cat_args['title_li'] = '';
        wp_list_categories(apply_filters('widget_categories_args', $cat_args));
?>
        </ul>
<?php
        }

        echo $after_widget;
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['count'] = $new_instance['count'] ? 1 : 0;
        $instance['hierarchical'] = $new_instance['hierarchical'] ? 1 : 0;
        $instance['dropdown'] = $new_instance['dropdown'] ? 1 : 0;

        return $instance;
    }

    function form( $instance ) {
        //Defaults
        $instance = wp_parse_args( (array) $instance, array( 'title' => '') );
        $title = esc_attr( $instance['title'] );
        $count = isset($instance['count']) ? (bool) $instance['count'] :false;
        $hierarchical = isset( $instance['hierarchical'] ) ? (bool) $instance['hierarchical'] : false;
        $dropdown = isset( $instance['dropdown'] ) ? (bool) $instance['dropdown'] : false;
?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

        <p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('dropdown'); ?>" name="<?php echo $this->get_field_name('dropdown'); ?>"<?php checked( $dropdown ); ?> />
        <label for="<?php echo $this->get_field_id('dropdown'); ?>"><?php _e( 'Show as dropdown' ); ?></label><br />

        <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>"<?php checked( $count ); ?> />
        <label for="<?php echo $this->get_field_id('count'); ?>"><?php _e( 'Show post counts' ); ?></label><br />

        <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('hierarchical'); ?>" name="<?php echo $this->get_field_name('hierarchical'); ?>"<?php checked( $hierarchical ); ?> />
        <label for="<?php echo $this->get_field_id('hierarchical'); ?>"><?php _e( 'Show hierarchy' ); ?></label></p>
<?php
    }

}

add_action('widgets_init', create_function('', "register_widget('My_Widget_Categories');"));


ck!

/*,,,,,, 9999.
<?php
    function jjycjn_display_link( $cat, $posts, $probs ){
        echo '<a href="'. get_category_link($cat->term_id) .'">'. $cat->name .'</a><span class="post_count"> ('. $posts->found_posts + $probs->found_posts .')</span>';
    }
?>
<aside class="widget widget_categories">
<h3 class="widget-title"><span class="widget-title-tab">Categories</span></h3>  
    <?php if( $categories = get_categories( array('parent' => 0, 'hide_empty' => 0, 'orderby' => 'term_order' ) ) ):
        echo '<ul>';
        foreach($categories as $cat):
            $cat_posts = new WP_Query( array( 'cat' => $cat->term_id, 'post_type' => 'post' ) );
            $cat_probs = new WP_Query( array( 'cat' => $cat->term_id, 'post_type' => 'probsoln' ) ); ?>
            <li class="cat-item"><?php jjycjn_display_link( $cat, $cat_posts, $cat_probs ); ?>
            <?php if( $sub_categories = get_categories( array('parent' => $cat->term_id, 'hide_empty' => 0, 'orderby' => 'term_order' ) ) ):
                echo '<ul class="children">';
                foreach($sub_categories as $sub_cat):
                    $sub_cat_posts = new WP_Query( array( 'cat' => $sub_cat->term_id, 'post_type' => 'post' ) );
                    $sub_cat_probs = new WP_Query( array( 'cat' => $sub_cat->term_id, 'post_type' => 'probsoln' ) ); ?>
                    <li class="cat-item"><?php jjycjn_display_link( $sub_cat, $sub_cat_posts, $sub_cat_probs ); ?>
                    <?php if( $sub_sub_categories = get_categories( array('parent' => $sub_cat->term_id, 'hide_empty' => 0, 'orderby' => 'term_order' ) ) ):
                        echo '<ul class="children">';
                        foreach ($sub_sub_categories as $sub_sub_cat):
                            $sub_sub_cat_posts = new WP_Query( array ( 'cat' => $sub_sub_cat->term_id, 'post_type' => 'post' ) );
                            $sub_sub_cat_probs = new WP_Query( array ( 'cat' => $sub_sub_cat->term_id, 'post_type' => 'probsoln' ) ); ?>
                            <li class="cat-item"><?php jjycjn_display_link( $sub_sub_cat, $sub_sub_cat_posts, $sub_sub_cat_probs ); ?>
                        <?php endforeach;
                        echo '</ul>';     
                        endif;
                    echo '</li>';     
                endforeach;
                echo '</ul>';   
                endif;
            echo '</li>';
        endforeach;
        echo '</ul>';
    endif; ?>
</aside>

