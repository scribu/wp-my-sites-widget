<?php

/*
Plugin Name: My Sites Widget
Description: A widget that displays a list of sites that the current user has access to.
Author: AppThemes
Author URI: http://appthemes.com
Version: 1.0
*/

define( 'MSW_TEXTDOMAIN', 'my-sites-widget' );

class My_Sites_Widget extends WP_Widget {

	function __construct() {
		parent::__construct( 'other_sites', __( 'My Sites', MSW_TEXTDOMAIN ), array(
			'classname' => 'widget_other_sites',
			'description' => __( 'A list of other sites that the user can access', MSW_TEXTDOMAIN )
		) );
	}

	function widget( $args, $instance ) {
		if ( !is_user_logged_in() )
			return;

		extract( $args );

		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'My Sites', MSW_TEXTDOMAIN ) : $instance['title'], $instance, $this->id_base );

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;

		$this->content( $instance );

		echo $after_widget;
	}

	function content( $instance ) {
		$blogs = get_blogs_of_user( get_current_user_id() );

		echo '<ul>';
		foreach ( $blogs as $blog_id => $blog ) {
			if ( $GLOBALS['blog_id'] == $blog_id )
				continue;

			printf( '<li><a href="%s">%s</a></li>', esc_url( get_home_url( $blog_id ) ), $blog->blogname );
		}
		echo '</ul>';
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
		$title = $instance['title'];
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		return $instance;
	}
}

function _msw_init() {
	register_widget( 'My_Sites_Widget' );
}
add_action( 'widgets_init', '_msw_init' );

