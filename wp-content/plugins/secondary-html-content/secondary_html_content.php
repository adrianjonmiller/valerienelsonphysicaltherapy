<?php
/**
 Plugin Name: Secondary HTML Content
 Plugin URI: http://www.get10up.com/plugins/secondary-html-content-wordpress/
 Description: Adds <strong>up to 5 additional HTML content blocks</strong> to pages and posts. Perfect for layouts with multiple distinct blocks, such as sidebars or multi-column layouts. 
 Version: 2.0
 Author: Jake Goldman (10up)
 Author URI: http://www.get10up.com

    Plugin: Copyright 2009 10up  (email : jake@get10up.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

//*******************//
//** PLUG-IN SETUP **//
//*******************//

add_action('admin_init','secondary_content_options_init');

function secondary_content_options_init() 
{
	add_filter("plugin_action_links_".plugin_basename(__FILE__), 'secondary_html_plugin_actlinks' );
	register_setting('secondary_html_settings','secondary_html_options','secondary_html_validate');
	
	$content2_options = get_option('secondary_html_options');
	
	if (isset($content2_options['pages']) && $content2_options['pages'] > 0) 
	{
		for ($i=1;$i<=$content2_options['pages'];$i++) {
			$title = ($i > 1 ) ? 'Secondary HTML Content ('.$i.')' : 'Secondary HTML Content';
			add_meta_box('secondary_content_'.$i,$title,'secondary_block_box_inner','page','normal','high',$i);			
		}
		add_action('load-page-new.php','secondary_content_page_editor'); 
		add_action('load-page.php','secondary_content_page_editor');
	}
	
	if (isset($content2_options['posts']) && $content2_options['posts'] > 0) 
	{
		for ($i=1;$i<=$content2_options['posts'];$i++) {
			$title = ($i > 1 ) ? 'Secondary HTML Content ('.$i.')' : 'Secondary HTML Content';
			add_meta_box('secondary_content_'.$i,$title,'secondary_block_box_inner','post','normal','high',$i);	
		}
		add_action('load-post-new.php','secondary_content_page_editor'); 
		add_action('load-post.php','secondary_content_page_editor');
	}
}

function secondary_html_validate($input) 
{
	$input['pages'] = intval($input['pages']);
	$input['posts'] = intval($input['posts']);
	if ($input['inherit'] != 1) $input['inherit'] = 0;
	if ($input['homepage'] != 1) $input['homepage'] = 0;
	if ($input['media'] != 1) $input['media'] = 0;
	
	return $input;
}

function secondary_html_plugin_actlinks( $links ) 
{ 
	// Add a link to this plugin's settings page
 	$settings_link = sprintf( '<a href="options-general.php?page=%s">%s</a>', plugin_basename(__FILE__), __('Settings') ); 
 	array_unshift( $links, $settings_link ); 
 	return $links; 
}

add_action('admin_menu', 'secondary_html_admin_menu');

function secondary_html_admin_menu() 
{
	$plugin_page = add_options_page('Secondary HTML Content Configuration', 'Secondary HTML', 8, __FILE__, 'secondary_html_options_page');
	add_action('admin_head-'.$plugin_page,'secondary_html_config_header');
}

//********************//
//** ADMINISTRATION **//
//********************//

function secondary_block_box_inner($post,$content_block) 
{
	$block = $content_block['args'];
	if ($block == 1) echo '<input type="hidden" name="secondary_content_nonce" id="secondary_content_nonce" value="'.wp_create_nonce("secondary-content-nonce").'" />'."\n";  //only need nonce once
	
	$secondary_content = get_post_meta($post->ID,"_secondary_content");
	$secondary_content = $secondary_content[$block];
	
	echo '<div class="secondary_content">
			<textarea id="secondary_block_'.$block.'" tabindex="2" name="secondary_block['.$block.']" cols="40" rows="10">
		';
	if ($secondary_content = get_post_meta($post->ID,"_secondary_content_".$block,true)) echo apply_filters('the_editor_content',$secondary_content);
	echo '
			</textarea>
		</div>
		';
}

function secondary_content_page_editor_styles() {
	echo '
	<style type="text/css" media="screen">
		#secondary_content_1 .inside, #secondary_content_2 .inside, #secondary_content_3 .inside, #secondary_content_4 .inside, #secondary_content_5 .inside { margin: 0 !important; } 
		.secondary_content .mce_wp_more, #content_code, .secondary_content .mce_fullscreen, #content_add_image, #content_add_video, #content_add_media, #content_add_audio { display: none !important; } 
		.secondary_content .mceResize { top: 0 !important; }
	</style>
	';
}

function secondary_content_source_button($buttons) {	
	array_push($buttons, "code"); //add HTML view
	
	//add media?
	$show_media = get_option('secondary_html_options');
	if ($show_media['media']) array_push($buttons, "separator","add_image","add_video","add_media","add_audio");
	
	return $buttons;
}

function secondary_content_page_editor() {		
	if (get_user_option('rich_editing') == 'true') {
		add_filter('mce_buttons', 'secondary_content_source_button');
		add_action('admin_head','secondary_content_page_editor_styles');
	}
}
 
function secondary_content_box_save($postid) {
	if (!wp_verify_nonce($_POST['secondary_content_nonce'],"secondary-content-nonce")) return $post_id; //nonce
	
	foreach ($_POST['secondary_block'] as $key => $block) {
		$content_textarea = apply_filters('content_save_pre',$block);
		if($content_textarea) update_post_meta($postid, "_secondary_content_".$key, $content_textarea);
		else delete_post_meta($postid,"_secondary_content_".$key);
	}
}

add_action('save_post', 'secondary_content_box_save');


//**********************//
//** FRONT END OUTPUT **//
//**********************//

function get_secondary_content($block = 1, $post_id = NULL) {
	if (is_null($post_id)) {
		global $post;
		$post_id = $post->ID;
		if (!$post_id) return false;
	}
	
	if (!is_singular()) return false;	//only intended for single posts and pages
	if (!is_int($block) || $block > 5 || $block < 1) return false;
	
	$content_options = get_option('secondary_html_options');

	$content_textarea = get_post_meta($post_id,'_secondary_content_'.$block,true);
	
	if (!$content_textarea) {
		if (is_single() || !$content_options['inherit']) return false;
	
		//get ancestors and process them if it has ancestors
		$ancestors = get_post_ancestors($post_id);
		if (get_option('show_on_front') == 'page' && $content2_options['homepage']) $ancestors[] = get_option('page_on_front'); 
	
		if (empty($ancestors)) return false;
	
		foreach ($ancestors as $ancestor) {
			$content_textarea = get_post_meta($ancestor,'_secondary_content_'.$block,true);
	  		if ($content_textarea) break;
	    }
	    if (!$content_textarea) return false;
   }
	
	return apply_filters('the_content',$content_textarea);
}

function the_secondary_content($block = 1, $post_id = NULL) {
	echo get_secondary_content($block, $post_id);
}

//backwards compatibility
function get_the_content_2($post_id = NULL) {
	return get_secondary_content(1,$post_id);
}
function the_content_2($post_id = NULL) {
	echo get_secondary_content(1,$post_id);
}

//************//
//** WIDGET **//
//************//

function widget_content2_block($args) {
	if($args) extract($args);
	
	$content2 = get_the_content_2();
	if (!$content2) return false;
	
	echo "<!-- content 2 block plugin by get10up.com -->\n";
	echo $before_widget.$content2.$after_widget."\n";
	return true;  
}

class SecondaryHTMLContent extends WP_Widget
{
	function SecondaryHTMLContent() {
		$widget_ops = array('classname' => 'content2_block', 'description' => __( "Display a secondary HTML content block.") );
		$this->WP_Widget('secondary-html-content', __('Secondary HTML Content'), $widget_ops);
	}

    function widget($args, $instance) {
		extract($args);
		
		$secondary_content = get_secondary_content($instance['block']);
		if (!$secondary_content) return false;
		
		echo "<!-- secondary html content plugin by get10up.com -->\n";
		echo $before_widget.$secondary_content.$after_widget;
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$block = intval($new_instance['block']);
		$instance['block'] = ($block <= 5 && $block >= 1) ? $block : 1;
		return $instance;
	}

	function form($instance){
		$instance = wp_parse_args((array) $instance, array('block' => 1)); //defaults
	?>
		<p>
			<label for="<?php echo $this->get_field_id('block'); ?>"><?php echo __('Which secondary block? '); ?></label>
			<select name="<?php echo $this->get_field_name('block'); ?>" id="<?php echo $this->get_field_id('block'); ?>">
			<?php
				$content_options = get_option('secondary_html_options');
				if (!isset($content_options['pages'])) $content_areas = 1;
				else $content_areas = ($content_options['pages'] > $content_options['posts']) ? $content_options['pages'] : $content_options['posts'];
			
				for ($i=1;$i<=$content_areas;$i++) {
					echo '<option value="'.$i.'"';
					if ($instance['block'] == $i) echo ' selected="selected"';
					echo ">$i</option>\n";
				}
			?>
			</select>
		</p>
	<?php
	}
}

add_action('widgets_init', create_function('', 'return register_widget("SecondaryHTMLContent");'));


//**************************//
//** PLUGIN CONFIGURATION **//
//**************************//

function secondary_html_options_page() {
?>
<div class="wrap">
	<div id="icon-options-general" class="icon32"><br /></div>
	<h2>Secondary HTML Content Configuration</h2>

	<div id="poststuff" style="position: absolute; right: 15px; top: 100px; z-index: -1;">
		<div class="postbox" style="width: 190px; min-width: 190px; float: right;">
			<h3 class="hndle" style="cursor: default;">Support us</h3>
			<div class="inside">
				<p>Help support continued development of Secondary HTML Content and other plugins.</p>
				<p>The best thing you can do is refer someone looking for web development or strategy work <a href="http://www.get10up.com">to our company</a>.</p>
				<p>Short of that, please consider mentioning or endorsing our plug-in a post, or any donation you can afford:</p>
				<form method="post" action="https://www.paypal.com/cgi-bin/webscr" style="text-align: left;">
				<input type="hidden" value="_s-xclick" name="cmd"/>
				<input type="hidden" value="3377715" name="hosted_button_id"/>
				<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" name="submit" alt="PayPal - The safer, easier way to pay online!"/> <img height="1" border="0" width="1" alt="" src="https://www.paypal.com/en_US/i/scr/pixel.gif"/><br/>
				</form>
				<p><strong><a href="http://www.get10up.com/plugins/secondary-html-content-wordpress/">Help &amp; support</a></strong></p>
			</div>
		</div>
	</div>
			
		
	<form method="post" action="options.php">
		
	<?php 
		settings_fields('secondary_html_settings');
		$content2_options = get_option('secondary_html_options');
	?>
	
	<h3>Pages</h3>		
		
	<table class="form-table">
		<tr valign="top">
			<th scope="row"><label for="content_pages">Number of extra HTML blocks</label>
			</th>
			<td>
				<select name="secondary_html_options[pages]" id="content_pages">
				<?php
					for ($i=0;$i<6;$i++) {
						echo '<option value="'.$i.'"';
						if ($content2_options['pages'] == $i) echo ' selected="selected"';
						echo '>'.$i.'</option>';
					}	
				?>
				</select>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="inherit">Inherit from ancestors</label></th>
			<td>
				<input type="checkbox" name="secondary_html_options[inherit]" id="inherit" value="1"<?php if ($content2_options['inherit']) echo ' checked="checked"'; ?>/>
				<span class="description">Help tab for more info.</span>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="home_top">Treat home as top ancestor</label></th>
			<td>
				<input type="checkbox" name="secondary_html_options[homepage]" id="home_top" value="1"<?php if ($content2_options['homepage']) echo ' checked="checked"'; ?>/>
				<span class="description">Help tab for more info.</span>	
			</td>
		</tr>
	</table>
	
	<h3>Posts</h3>
		
	<table class="form-table">
		<tr valign="top">
			<th scope="row"><label for="content_posts">Number of extra HTML blocks</label>
			</th>
			<td>
				<select name="secondary_html_options[posts]" id="content_posts">
				<?php
					for ($i=0;$i<6;$i++) {
						echo '<option value="'.$i.'"';
						if ($content2_options['posts'] == $i) echo ' selected="selected"';
						echo '>'.$i.'</option>';
					}	
				?>
				</select>
			</td>
		</tr>
	</table>
	
	<h3>General</h3>
	
	<table class="form-table">
		<tr valign="top">
			<th scope="row"><label for="media_buttons">Add media buttons</label></th>
			<td><input type="checkbox" name="secondary_html_options[media]" id="media_buttons" value="1"<?php if ($content2_options['media']) echo ' checked="checked"'; ?>/></td>
		</tr>
	</table>
	
	<p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
	
	</form>
</div>
  
<?php 
}

function secondary_html_config_header() {
	add_filter('contextual_help','secondary_html_context_help');
}

function secondary_html_context_help() 
{
	echo '
		<h5>Secondary HTML Content</h5>
		<p>Secondary HTML Content is a plug-in by Jake Goldman (10up) that  allows you to add up to 5 additional HTML content areas to pages and posts.</p>
		
		<h5>Configuration Options</h5>
		<p><strong>Inherit from ancestors</strong> - if you would like pages\' HTML content blocks to inherit their values from ancestors (parents, grandparents, etc) when empty, check this option. Useful for section wide blocks.</p>
		<p><strong>Treat home as top ancestor</strong> - when using "inherit from ancestors", this causes the home page to be treated as the top level page. Great for site wide blocks.</p> 
		
		<h5>Support</h5>
		<div class="metabox-prefs">
			<p><a href="http://www.get10up.com/plugins/secondary-html-content-wordpress/" target="_blank">Secondary HTML Content support</a></p>
			<p>This plug-in was developed by <a href="http://www.get10up.com" target="_blank">Jake Goldman of 10up</a>, Web Development &amp; Strategy Experts located in Providence, Rhode Island in the United States. We develop plug-ins because we love working with WordPress, and to generate interest in our business. If you like our plug-in, and know someone who needs web development work, be in touch!</p>
		</div>
	';	
}

//********************//
//upgrade from pre 2.0//
//********************//

function secondary_content_activate() 
{	
	//update post meta name
	global $wpdb;
	$wpdb->query("UPDATE $wpdb->postmeta SET meta_key = '_secondary_content_1' WHERE meta_key = '_content2_textarea'");
}
register_activation_hook(__FILE__, 'secondary_content_activate'); 
?>