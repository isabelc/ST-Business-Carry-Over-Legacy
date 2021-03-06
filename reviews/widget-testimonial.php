<?php
/**
 * Adds Smartest Reviews Testimonial widget
 *
 * @package		ST Business Carry Over
 * @subpackage 	Smartest Reviews Module
 * @extends 	WP_Widget
 * @author		Smartest Themes <isa@smartestthemes.com>
 * @license		http://opensource.org/licenses/gpl-license.php GNU Public License
 */
 if ( ! class_exists( 'SmartestReviewsTestimonial' ) ) {
	class SmartestReviewsTestimonial extends WP_Widget {
		public function __construct() {
			parent::__construct(
				'smartest_reviews_testimonial',
				'QBW Reviews Testimonial',
				array( 'description' => __( 'Display a random review as a testimonial.', 'st-business-carry-over' ), )
			);
		}
		/**
		 * Front-end display of widget.
		 */
		public function widget( $args, $instance ) {
		
			$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'Testimonials', 'st-business-carry-over' ) : $instance['title'], $instance, $this->id_base );
			$number = isset( $instance['number'] ) ? $instance['number'] : '';
			
			echo $args['before_widget'];
			if ( $title )
				echo '<h3 class="widget-title">'. $title . '</h3>';
			global $wpdb;
			// get the permalink by page id.
			$reviews_pageurl = get_permalink(get_option('smartest_reviews_page_id'));
			$pre = $wpdb->base_prefix;
			if ( is_multisite() ) { 
				global $blog_id;
				$bi = get_current_blog_id();
				$pre2 = $pre . $bi . '_smareviewsb';
			} else {
				// not Multisite
				$pre2 = $pre . 'smareviewsb';
			}
			if ( ! empty( $number ) )
				$number_testimonials = $number;
			else
				$number_testimonials = 1;
			$getreviews = $wpdb->get_results("SELECT review_text FROM $pre2 WHERE status = 1 LIMIT 0,$number_testimonials");

			if ( empty( $getreviews ) ) {
				//no review yet, lure them to leave one
				echo '<p>'. sprintf(__('Be the first to <a href="%s">leave a review...', 'st-business-carry-over'), $reviews_pageurl).'</a></p>';
			} else {
				foreach ( $getreviews as $getreview ) {
					echo '<blockquote>' . wp_trim_words( $getreview->review_text, 20) . '</blockquote><br />';
				}
				echo '<a href="'.$reviews_pageurl.'">'.__('More...', 'st-business-carry-over').'</a>';
			}
			echo $args['after_widget'];
		}// end widget

		/**
		 * Sanitize widget form values as they are saved.
		 * @return array Updated safe values to be saved.
		 */
		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = strip_tags($new_instance['title'] );
			$instance['number'] = strip_tags( $new_instance['number'] );
			return $instance;
		}

		/**
		 * Back-end widget form.
		 * @param array $instance Previously saved values from database.
		 */
		public function form( $instance ) {
			if ( isset( $instance[ 'title' ] ) ) {
				$title = $instance[ 'title' ];
			} else {
				$title = __( 'Testimonials', 'st-business-carry-over' );
			}
			if ( isset( $instance[ 'number' ] ) ) {
				$number = $instance[ 'number' ];
			} else {
				$number = 1; 
			} ?>
			<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'st-business-carry-over' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
			<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'How many testimonials to show:', 'st-business-carry-over' ); ?></label> 
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" value="<?php echo esc_attr( $number ); ?>" />
		</p>
	<?php }
	}
}
 ?>