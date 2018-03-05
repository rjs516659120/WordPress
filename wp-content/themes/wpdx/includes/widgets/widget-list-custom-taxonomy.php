<?php
// Register 'List Custom Taxonomy' widget
add_action( 'widgets_init', 'init_lc_taxonomy' );
function init_lc_taxonomy() { return register_widget('lc_taxonomy'); }
class lc_taxonomy extends WP_Widget {
	public function __construct() {
        parent::__construct(
            'lc_taxonomy',
            THEME_NAME .__( ' - List Custom Taxonomy', 'wpdx' ),
            array( 'classname' => 'lc-taxonomy-widget' ,'description' => __('Display taxonomy.','wpdx'));
            array( 'width' => 250, 'height' => 350)
            );
    }
	/**
	* This is the Widget
	**/
	public function widget( $args, $instance ) {
		global $post;
		extract($args);
		// Widget options
		$title 	 = apply_filters('widget_title', $instance['title'] ); // Title
		$this_taxonomy = $instance['taxonomy']; // Taxonomy to show
		$hierarchical = !empty( $instance['hierarchical'] ) ? '1' : '0';
		$showcount = !empty( $instance['count'] ) ? '1' : '0';
		if( array_key_exists('orderby',$instance) ){
			$orderby = $instance['orderby'];
		}
		else{
			$orderby = 'count';
		}
		if( array_key_exists('ascdsc',$instance) ){
			$ascdsc = $instance['ascdsc'];
		}
		else{
			$ascdsc = 'desc';
		}
		if( array_key_exists('exclude',$instance) ){
			$exclude = $instance['exclude'];
		}
		else {
			$exclude = '';
		}
		if( array_key_exists('childof',$instance) ){
			$childof = $instance['childof'];
		}
		else {
			$childof = '';
		}
		if( array_key_exists('dropdown',$instance) ){
			$dropdown = $instance['dropdown'];
		}
		else {
			$dropdown = false;
		}
        // Output
		$tax = $this_taxonomy;
		echo $before_widget;
		echo '<div id="lct-widget-'.$tax.'-container" class="widget-list-custom-taxonomy">';
		if ( $title ) echo $before_title . $title . $after_title;
		if($dropdown){
			$taxonomy_object = get_taxonomy( $tax );
			$args = array(
				'show_option_all'    => false,
				'show_option_none'   => '',
				'orderby'            => $orderby,
				'order'              => $ascdsc,
				'show_count'         => $showcount,
				'hide_empty'         => 1,
				'child_of'           => $childof,
				'exclude'            => $exclude,
				'echo'               => 1,
				//'selected'           => 0,
				'hierarchical'       => $hierarchical,
				'name'               => $taxonomy_object->query_var,
				'id'                 => 'lct-widget-'.$tax,
				//'class'              => 'postform',
				'depth'              => 0,
				//'tab_index'          => 0,
				'taxonomy'           => $tax,
				'hide_if_empty'      => true,
				'walker'			=> new lctwidget_Taxonomy_Dropdown_Walker()
			);
			echo '<form action="'. home_url(). '" method="get">';
			wp_dropdown_categories($args);
			echo '<input type="submit" value="go &raquo;" /></form>';
		}
		else {
			$args = array(
					'show_option_all'    => false,
					'orderby'            => $orderby,
					'order'              => $ascdsc,
					'style'              => 'list',
					'show_count'         => $showcount,
					'hide_empty'         => 1,
					'use_desc_for_title' => 1,
					'child_of'           => $childof,
					//'feed'               => '',
					//'feed_type'          => '',
					//'feed_image'         => '',
					'exclude'            => $exclude,
					//'exclude_tree'       => '',
					//'include'            => '',
					'hierarchical'       => $hierarchical,
					'title_li'           => '',
					'show_option_none'   => __('No Categories','wpdx'),
					'number'             => null,
					'echo'               => 1,
					'depth'              => 0,
					//'current_category'   => 0,
					//'pad_counts'         => 0,
					'taxonomy'           => $tax,
					'walker'             => null
				);
			echo '<ul id="lct-widget-'.$tax.'" class="widget-taxonomy">';
			wp_list_categories($args);
			echo '</ul>';
		}
		echo '</div>';
		echo $after_widget;
	}
	/** Widget control update */
	public function update( $new_instance, $old_instance ) {
		$instance    = $old_instance;
		$instance['title']  = strip_tags( $new_instance['title'] );
		$instance['taxonomy'] = strip_tags( $new_instance['taxonomy'] );
		$instance['orderby'] = $new_instance['orderby'];
		$instance['ascdsc'] = $new_instance['ascdsc'];
		$instance['exclude'] = $new_instance['exclude'];
		$instance['expandoptions'] = $new_instance['expandoptions'];
		$instance['childof'] = $new_instance['childof'];
		$instance['hierarchical'] = !empty($new_instance['hierarchical']) ? 1 : 0;
        $instance['count'] = !empty($new_instance['count']) ? 1 : 0;
        $instance['dropdown'] = !empty($new_instance['dropdown']) ? 1 : 0;
		return $instance;
	}
	/**
	* Widget settings
	**/
	public function form( $instance ) {
		//for showing/hiding advanced options; wordpress moves this script to where it needs to go
			wp_enqueue_script('jquery');
			?><script>
			jQuery(document).ready(function(){
				var status = jQuery('#<?php echo $this->get_field_id('expandoptions'); ?>').val();
				if(status == 'expand')
					jQuery('.lctw-expand-options').hide();
				else if(status == 'contract'){
					jQuery('.lctw-all-options').hide();
				}
			});
			function lctwExpand(id){
				jQuery('#' + id).val('expand');
				jQuery('.lctw-all-options').show(500);
				jQuery('.lctw-expand-options').hide(500);
			}
			function lctwContract(id){
				jQuery('#' + id).val('contract');
				jQuery('.lctw-all-options').hide(500);
				jQuery('.lctw-expand-options').show(500);
			}
			</script><?php
		  // instance exist? if not set defaults
		    if ( $instance ) {
				$title  = $instance['title'];
				$this_taxonomy = $instance['taxonomy'];
				$orderby = $instance['orderby'];
				$ascdsc = $instance['ascdsc'];
				$exclude = $instance['exclude'];
				$expandoptions = $instance['expandoptions'];
				$childof = $instance['childof'];
                $showcount = isset($instance['count']) ? (bool) $instance['count'] :false;
                $hierarchical = isset( $instance['hierarchical'] ) ? (bool) $instance['hierarchical'] : false;
                $dropdown = isset( $instance['dropdown'] ) ? (bool) $instance['dropdown'] : false;
		    } else {
			    //These are our defaults
				$title  = '';
				$orderby  = 'count';
				$ascdsc  = 'desc';
				$exclude  = '';
				$expandoptions  = 'contract';
				$childof  = '';
				$this_taxonomy = 'category';//this will display the category taxonomy, which is used for normal, built-in posts
				$hierarchical = true;
				$showcount = true;
				$dropdown = false;
		    }
		// The widget form ?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __( 'Title:','wpdx' ); ?></label>
				<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" class="widefat" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('taxonomy'); ?>"><?php echo __( 'Select Taxonomy:' ,'wpdx'); ?></label>
				<select name="<?php echo $this->get_field_name('taxonomy'); ?>" id="<?php echo $this->get_field_id('taxonomy'); ?>" class="widefat" style="height: auto;" size="4">
			<?php
			$args=array(
			  'public'   => true,
			  '_builtin' => false //these are manually added to the array later
			);
			$output = 'names'; // or objects
			$operator = 'and'; // 'and' or 'or'
			$taxonomies=get_taxonomies($args,$output,$operator);
			$taxonomies[] = 'category';
			$taxonomies[] = 'post_tag';
			$taxonomies[] = 'post_format';
			foreach ($taxonomies as $taxonomy ) { ?>
				<option value="<?php echo $taxonomy; ?>" <?php if( $taxonomy == $this_taxonomy ) { echo 'selected="selected"'; } ?>><?php echo $taxonomy;?></option>
			<?php }	?>
			</select>
			</p>
			<h4 class="lctw-expand-options"><a href="javascript:void(0)" onclick="lctwExpand('<?php echo $this->get_field_id('expandoptions'); ?>')" ><?php _e('More Options...','wpdx') ?></a></h4>
			<div class="lctw-all-options">
				<h4 class="lctw-contract-options"><a href="javascript:void(0)" onclick="lctwContract('<?php echo $this->get_field_id('expandoptions'); ?>')" ><?php _e('Hide Extended Options','wpdx') ?></a></h4>
				<input type="hidden" value="<?php echo $expandoptions; ?>" id="<?php echo $this->get_field_id('expandoptions'); ?>" name="<?php echo $this->get_field_name('expandoptions'); ?>" />
				<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>"<?php checked( $showcount ); ?> />
				<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e( 'Show post counts','wpdx' ); ?></label><br />
				<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('hierarchical'); ?>" name="<?php echo $this->get_field_name('hierarchical'); ?>"<?php checked( $hierarchical ); ?> />
				<label for="<?php echo $this->get_field_id('hierarchical'); ?>"><?php _e( 'Show hierarchy','wpdx' ); ?></label></p>
				<p>
					<label for="<?php echo $this->get_field_id('orderby'); ?>"><?php echo __( 'Order By:' ,'wpdx'); ?></label>
					<select name="<?php echo $this->get_field_name('orderby'); ?>" id="<?php echo $this->get_field_id('orderby'); ?>" class="widefat" >
						<option value="ID" <?php if( $orderby == 'ID' ) { echo 'selected="selected"'; } ?>>ID</option>
						<option value="name" <?php if( $orderby == 'name' ) { echo 'selected="selected"'; } ?>>Name</option>
						<option value="slug" <?php if( $orderby == 'slug' ) { echo 'selected="selected"'; } ?>>Slug</option>
						<option value="count" <?php if( $orderby == 'count' ) { echo 'selected="selected"'; } ?>>Count</option>
						<option value="term_group" <?php if( $orderby == 'term_group' ) { echo 'selected="selected"'; } ?>>Term Group</option>
					</select>
				</p>
				<p>
					<label><input type="radio" name="<?php echo $this->get_field_name('ascdsc'); ?>" value="asc" <?php if( $ascdsc == 'asc' ) { echo 'checked'; } ?>/><?php _e('Ascending','wpdx'); ?></label><br/>
					<label><input type="radio" name="<?php echo $this->get_field_name('ascdsc'); ?>" value="desc" <?php if( $ascdsc == 'desc' ) { echo 'checked'; } ?>/> <?php _e('Descending','wpdx'); ?></label>
				</p>
				<p>
					<label for="<?php echo $this->get_field_id('exclude'); ?>"><?php _e('Exclude (comma-separated list of ids to exclude)','wpdx'); ?></label><br/>
					<input type="text" class="widefat" name="<?php echo $this->get_field_name('exclude'); ?>" value="<?php echo $exclude; ?>" />
				</p>
				<p>
					<label for="<?php echo $this->get_field_id('exclude'); ?>"><?php _e('Only Show Children of (category id)','wpdx'); ?></label><br/>
					<input type="text" class="widefat" name="<?php echo $this->get_field_name('childof'); ?>" value="<?php echo $childof; ?>" />
				</p>
				<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('dropdown'); ?>" name="<?php echo $this->get_field_name('dropdown'); ?>"<?php checked( $dropdown ); ?> />
				<label for="<?php echo $this->get_field_id('dropdown'); ?>"><?php _e( 'Display as Dropdown','wpdx'); ?></label></p>
			</div>
<?php
	}
} // class lc_taxonomy
/* Custom version of Walker_CategoryDropdown */
class lctwidget_Taxonomy_Dropdown_Walker extends Walker {
	var $tree_type = 'category';
	var $db_fields = array ( 'id' => 'term_id', 'parent' => 'parent' );
	function start_el( &$output, $term, $depth = 0, $args = array(), $id = 0 ) {
		$url = get_term_link( $term, $term->taxonomy );
		$text = str_repeat( '&nbsp;', $depth * 3 ) . $term->name;
		if ( $args['show_count'] ) {
			$text .= '&nbsp;('. $term->count .')';
		}
		$class_name = 'level-' . $depth;
		$output.= "\t" . '<option' . ' class="' . esc_attr( $class_name ) . '" value="' . esc_url( $url ) . '">' . esc_html( $text ) . '</option>' . "\n";
	}
}