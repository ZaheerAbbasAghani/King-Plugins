<?php
/**
 * Adds ALLEVENTS widget.
 */
class ALLEVENTS extends WP_Widget {

/**
 * Register widget with WordPress.
 */
	function __construct() {
		parent::__construct(
			'all_events', // Base ID
			esc_html__('All Events', 'bp-event-manager'), // Name
			array('description' => esc_html__('This widget show all events', 'bp-event-manager')) // Args
		);
	}

/**
 * Front-end display of widget.
 *
 * @see WP_Widget::widget()
 *
 * @param array $args     Widget arguments.
 * @param array $instance Saved values from database.
 */
	public function widget($args, $instance) {
		echo $args['before_widget'];
		if (!empty($instance['title'])) {
			echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
		}

//$group_name =  bp_get_group_id();
		$event_data = array();

		$args = array(
			'post_type' => 'bpem_event',
			'posts_per_page' => -1,
		);

		$i = 0;

		$event_query = new WP_Query($args);
		if ($event_query->have_posts()): while ($event_query->have_posts()):
				$event_query->the_post();

				$start_date = get_post_meta(get_the_id(), 'evn_startDate');
				$start_d = date("Y-m-d", strtotime($start_date[0]));
				$start_time = get_post_meta(get_the_id(), 'evn_startTime');
				$start_t = date("H:i:s", strtotime($start_time[0]));
				$end_date = get_post_meta(get_the_id(), 'evn_endDate');
				$end_d = date("Y-m-d", strtotime($end_date[0]));
				$end_time = get_post_meta(get_the_id(), 'evn_endTime');
				$end_t = date("H:i:s", strtotime($end_time[0]));

				$event_data[] = array(

					'title' => get_the_title(),
					'start' => $start_d . 'T' . $start_t,
					'end' => $end_d . 'T' . $end_t,
					'url' => get_the_permalink(),
					'imageurl' => get_the_post_thumbnail_url(get_the_id(), array(200, 200)),
				);

				$i++;
			endwhile;

			wp_reset_postdata();
		endif;

		echo "<div id='bpem-calendar'></div>";

		?>

	<script type="text/javascript">
			jQuery(document).ready(function() {
		var todayDate = jQuery.datepicker.formatDate('yy-mm-dd', new Date());
		jQuery('#bpem-calendar').fullCalendar({
		header: {
		left: 'prev,next today',
		center: 'title',
		right: 'month,agendaWeek,agendaDay'
		},

		defaultDate: todayDate,
		//businessHours: true, // display business hours
		editable: true,
		eventLimit: true,
		navLinks: true,
		events:<?php echo json_encode($event_data); ?>
		,

		//timeFormat: 'H(:mm)'
		eventRender: function(event, eventElement) {
			if (event.imageurl) {
			eventElement.find("div.fc-content").prepend("<img src='" + event.imageurl + "' width='60' height='60'>");
			}
		},
		});

		jQuery('.fc-agendaWeek-button').click(function() {
			var str = jQuery('.fc-toolbar .fc-center').text().replace(/â€”/g, '-');
			jQuery('.fc-toolbar .fc-center h2').text(str);
		});
		});
	</script>
<?php

//echo $args['after_widget'];
	}

/**
 * Back-end widget form.
 *
 * @see WP_Widget::form()
 *
 * @param array $instance Previously saved values from database.
 */
	public function form($instance) {
		$title = !empty($instance['title']) ? $instance['title'] : esc_html__('New title', 'bp-event-manager');
		?>
<p>
<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_attr_e('Title:', 'bp-event-manager');?></label>
<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
</p>
<?php
}

/**
 * Sanitize widget form values as they are saved.
 *
 * @see WP_Widget::update()
 *
 * @param array $new_instance Values just sent to be saved.
 * @param array $old_instance Previously saved values from database.
 *
 * @return array Updated safe values to be saved.
 */
	public function update($new_instance, $old_instance) {
		$instance = array();
		$instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';

		return $instance;
	}

} // class ALLEVENTS