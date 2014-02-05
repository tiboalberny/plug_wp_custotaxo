<?php
/*
Plugin Name: Widget Custom Taxonomies
Plugin URI: http://lagrandrue.ch
Description: Display custom taxonomies
Version: 1.0
Author: La Grand Rue
Author URI: http://lagrandrue.ch
License: GPL
*/
?>

<?php
class widget_custax extends WP_Widget {

	// constructor
	function widget_custax() {
		$widget_ops = array('classname' => 'custom_taxonomies_widget', 'description' => __('Display the list of terms for a chosen custom taxonomy', 'wp_custo_taxo'));
		 parent::WP_Widget(false, $name = __('Custom Taxonomies', 'wp_custo_taxo'), $widget_ops );
	}

	// widget form creation
	function form($instance) {	
	/* ... */
	if( $instance) {
     $title = esc_attr($instance['title']);
     $class = esc_attr($instance['class']);
	 $hide_empty = esc_attr($instance['hide_empty']);
	 $select = esc_attr($instance['select']);
		} else {
			 $title = '';
			 $class = '';
			 $textarea = '';
			 $hide_empty = '';
		}
		?>
		
		<p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title', 'wp_custo_taxo'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
            <label for="<?php echo $this->get_field_id('select'); ?>"><?php _e('Taxonomy', 'wp_custo_taxo'); ?></label>
            <select name="<?php echo $this->get_field_name('select'); ?>" id="<?php echo $this->get_field_id('select'); ?>" class="widefat">
            <?php
			 // Custom Taxo list dropdown
			 
			$args = array(
			  'public'   => true,
			  '_builtin' => false
			  
			); 
			$output = 'objects'; // or objects
			$operator = 'and'; // 'and' or 'or'
			$taxonomies = get_taxonomies( $args, $output, $operator ); 
			if ( $taxonomies ) {
			  foreach ( $taxonomies  as $taxonomy ) {
				echo $tax_lab = $taxonomy->label;
				echo $tax_name = $taxonomy->name;
				 echo '<option value="' . $tax_name . '" id="' . $tax_lab . '"', $select == $tax_name ? ' selected="selected"' : '', '>', $tax_lab, '</option>';
				
			  }
			}

			
           
            ?>
            </select>
        </p>
		<p>
            <label for="<?php echo $this->get_field_id('class'); ?>"><?php _e('Custom class for the widget:', 'wp_custo_taxo'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('class'); ?>" name="<?php echo $this->get_field_name('class'); ?>" type="text" value="<?php echo $class; ?>" />
		</p>
            <p>
            <input id="<?php echo $this->get_field_id('hide_empty'); ?>" name="<?php echo $this->get_field_name('hide_empty'); ?>" type="checkbox" value="1" <?php checked( '1', $hide_empty ); ?> />
            <label for="<?php echo $this->get_field_id('hide_empty'); ?>"><?php _e('Show empty terms ?', 'wp_custo_taxo'); ?></label>
		</p>
        
		<?php
	}

	// widget update
	function update($new_instance, $old_instance) {
		 $instance = $old_instance;
      // Fields
      $instance['title'] = strip_tags($new_instance['title']);
      $instance['class'] = strip_tags($new_instance['class']);
	  $instance['hide_empty'] = strip_tags($new_instance['hide_empty']);
	  $instance['select'] = strip_tags($new_instance['select']);


     return $instance;
	}

	// widget display
	function widget($args, $instance) {
	 extract( $args );
	   // these are the widget options
	   $title = apply_filters('widget_title', $instance['title']);
	   $class = $instance['class'];
	   $hide_empty = $instance['hide_empty'];
	   $select = $instance['select'];
	   echo $before_widget;
	   // Display the widget
	   echo '<div class="widget-text '.$class.'">';

   // Check if title is set
   if ( $title ) {
      echo $before_title . $title . $after_title;
   }

   // Check if class is set
   if( $class ) {
      echo '<p class="wp_custo_taxo_text">'.$class.'</p>';
   }
   
    // Check if hide_empty is checked
   if( $hide_empty AND $hide_empty == '1' ) {
     $args_ch = array( 'hide_empty'=>false );
   }
   else $args_ch="";
   // Display list of terms
    
	$terms = get_terms($select,$args_ch);
	 $count = count($terms);
	 if ( $count > 0 ){
		 echo "<ul>";
		 foreach ( $terms as $term ) {
			$term_link = get_term_link( $term, $select );
			if( is_wp_error( $term_link ) )
				continue;
		   echo '<li><a href="' . $term_link . '">' . $term->name . '</a></li>';
		 }
		 echo "</ul>";
	 }
	
   echo '</div>';
   echo $after_widget;
	}
}

// register widget
add_action('widgets_init', create_function('', 'return register_widget("widget_custax");'));
?>
