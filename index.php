<?php 
/*
Plugin Name: Swedish Word Plugin
Contributors: Fredster22
Description: Add Swedish Word Of The Day to your widgets, posts and pages. Every day you will see a new word in Swedish and it’s translation into English.
Tags: language, swedish, words, swedish language
Tested up to: 4.7
Version: 1.0
Author: swedishwordplugin.com
Author URI: http://swedishwordplugin.com
License: GPLv2
*/


class SwedishWord_Widget extends WP_Widget {
     
    function __construct() {
        parent::__construct(
         
            // base ID of the widget
            'swedishword_widget',
             
            // name of the widget
            __('Swedish Word Widget', 'SwedishWord' ),
             
            // widget options
            array (
                'description' => __( 'Widget to display swedish word with translation.', 'SwedishWord' )
            )
             
        );
    }
     
    function form( $instance ) {
            if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        }
        else {
            $title = __( 'New title', 'text_domain' );
        }
        ?>
        <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <?php 

            }
     
    function update( $new_instance, $old_instance ) {   

        $instance = array();
        $instance['title'] = strip_tags( $new_instance['title'] );
        return $instance;  

    }
     
    function widget( $args, $instance ) {
        
        extract( $args );
        $title = apply_filters( 'widget_title', $instance['title'] );

        echo $before_widget;
        if ( ! empty( $title ) )
            echo $before_title . $title . $after_title;

        wp_enqueue_style( 'swedish-word-style', plugins_url('css/style.css',__FILE__));
        $words = explode("\n",file_get_contents(plugins_url('the-swedish-word-of-the-day/words.txt')));
        $day = date('z')+1;
        $word = explode("|",$words[$day]);
    
    
        echo '
        <aside class="widget" id="daily-swedish-word">
        <h2 class="daily-swedish-word-heading"><a href="http://swedishwordplugin.com" class="swedish-word-copyright">Swedish Word <br> Of The Day</a></h2>
        </aside>
        ';
        echo "<h2 id='swedish-word'>".$word[0]."</h2><p id='swedish-word-translation'>".$word[1]; 
        echo $args['after_widget'];
    
    }
     
}

function swedish_word_widget() {
 
    register_widget( 'SwedishWord_Widget' );
 
}
add_action( 'widgets_init', 'swedish_word_widget' );



function swedish_word_widget_shortcode($atts) {
    
    global $wp_widget_factory;
    
    // extract(shortcode_atts(array(
    //     'widget_name' => FALSE
    // ), $atts));
    
    $widget_name = 'SwedishWord_Widget';
    // $widget_name = wp_specialchars($widget_name);
    if (!is_a($wp_widget_factory->widgets[$widget_name], 'WP_Widget')):
        $wp_class = 'WP_Widget_'.ucwords(strtolower($class));
        
        if (!is_a($wp_widget_factory->widgets[$wp_class], 'WP_Widget')):
            return '<p>'.sprintf(__("%s: Widget class not found. Make sure this widget exists and the class name is correct"),'<strong>'.$class.'</strong>').'</p>';
        else:
            $class = $wp_class;
        endif;
    endif;
    
    ob_start();
    the_widget($widget_name, array(), array('widget_id'=>'arbitrary-instance-swedishword_widget',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => ''
    ));
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
    
}
add_shortcode('swedish_word','swedish_word_widget_shortcode'); 
?>