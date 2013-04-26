<?php
/*
    Plugin Name: SmallPress YouTube Thickbox
    Description: Provides a widget that shows the child pages of the current page. Includes a metabox to override the widget title or label for the current page.
    Author: Ethan Hinson
    Author URI: http://www.bluetentmarketing.com/
    Version: 0.1

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
  * Add a custom shortcode to popup youtube videos in a thickbox overlay.
  *
  * Usage (in WP Editor):
  *	[ytp url="http://www.youtube.com/watch?v=xxxxxxxxxxxxx"]
  *
  */

// Handle the [ytp] shortcode
function do_ytp($att) {
	if( isset($att['url']) ) {
		$id = 'ytp'.rand();
		$oe = json_decode( file_get_contents('http://www.youtube.com/oembed?url='.urlencode($att['url']) ) );
		$href = "#TB_inline?height={$oe->height}&width={$oe->width}&inlineId={$id}";
		return <<<HTML

			<div id="{$id}" style="display:none;">{$oe->html}</div>
			<a class="thickbox" href="{$href}">
				<img src="{$oe->thumbnail_url}"/>
			</a>

HTML;
	}
}
add_shortcode('ytp','do_ytp');

// Add Thickbox to our frontend scripts
function add_ytp(){
    if(!is_admin()){
	    wp_enqueue_script('jquery');
	    wp_enqueue_script('thickbox',null,array('jquery'));
	    wp_enqueue_style('thickbox.css', '/'.WPINC.'/js/thickbox/thickbox.css');
    }
}
add_action('init','add_ytp');

// Add some global JS variables thickbox requires
function head_ytp() {
	$url = get_bloginfo('url');
	echo <<<HTML

	<script type="text/javascript">
	if ( typeof tb_pathToImage != 'string' )
		var tb_pathToImage = "{$url}/wp-includes/js/thickbox/loadingAnimation.gif";
	if ( typeof tb_closeImage != 'string' )
		var tb_closeImage = "{$url}/wp-includes/js/thickbox/tb-close.png";
	</script>

HTML;
}
add_action('wp_head', 'head_ytp');
?>