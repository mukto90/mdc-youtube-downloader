<?php
/****
	* Plugin Name: MDC YouTube Downloader
	* Plugin URI: http://medhabi.com/items/mdc-youtube-downloader/
	* Description: MDC YouTube Downloader allows visitors to download YouTube videos directly from your WordPress site.
	* Author: Nazmul Ahsan
	* Version: 2.1.1
	* Author URI: http://nazmulahsan.me
	* Stable tag: 2.1.1
	* License: GPL2+
	* Text Domain: MedhabiDotCom
****/
include_once ('includes/mdc-option-page.php');
include_once ('includes/tinymce-mdc-youtube-downloader.php');

class MDC_YouTube_Downloader{

	public function __construct(){
		add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array($this, 'mdc_add_action_links') );
		add_action( 'wp_enqueue_scripts', array($this, 'mdc_wp_enqueue_scripts') );
		add_action( 'wp_footer', array($this, 'mdc_custom_style') );
		add_shortcode( 'mdc_youtube_downloader', array($this, 'mdc_youtube_downloader') );
		add_shortcode( 'youtube_downloader_form', array($this, 'mdc_youtube_downloader') );
	}
	
	public function mdc_add_action_links ( $links ) {
		$mylinks = array(
			'<a href="' . admin_url( 'admin.php?page=mdc-youtube-downloader' ) . '"><img src="'.plugins_url( 'images/icon.png', __FILE__).'" />Settings</a>',
		);
		return array_merge( $links, $mylinks );
	}

	public function mdc_wp_enqueue_scripts() {
		wp_enqueue_style( 'mdc_custom', plugins_url('css/style.css', __FILE__) );
		wp_enqueue_script( 'mdc_custom', plugins_url('js/custom.js', __FILE__), array(), '4.2.2', true );
	}

	public function mdc_custom_style(){
		$css = "<style>";
		$css .= get_option('mdc_custom_css');
		$css .= "</style>";
		echo $css;	
	}

	public function mdc_youtube_downloader($atts){

		$atts = shortcode_atts(
			array(
				'placeholder'		=>	get_option('mdc_form_placeholder_text'),
				'button_label'		=>	get_option('mdc_form_button_text'),
				'show_thumb'		=>	get_option('mdc_show_thumbnail'),
				'thumb_height'		=>	get_option('mdc_thumbnail_height'),
				'thumb_width'		=>	get_option('mdc_thumbnail_width'),
				'show_quality'		=>	get_option('mdc_show_quality'),
				'label'				=>	get_option('mdc_download_text'),
			),
			$atts,
			'youtube_downloader'
		);
		$placeholder 	= $atts['placeholder'];
		$button_label 	= $atts['button_label'];
		$show_thumb 	= $atts['show_thumb'];
		$thumb_height 	= $atts['thumb_height'];
		$thumb_width 	= $atts['thumb_width'];
		$show_quality 	= $atts['show_quality'];
		$label 			= $atts['label'];

		$output = '<form class="form-download" method="post" id="download" action="">
			<input required type="text" name="videoid" id="videoid" size="40" placeholder="'.$placeholder.'" />
			<input class="btn btn-primary mdc_buttons" type="submit" name="type" id="type" value="'.$button_label.'" />
		</form>
		<br />
		<div class="mdc_video_div">';
		
		if(isset($_REQUEST['videoid'])){
			if(strlen($_REQUEST['videoid']) > 11){
				$video_url = $_REQUEST['videoid'];
				$vid_array = explode('?v=',$video_url);
				$my_id_full = $vid_array['1'];
				$found_id = substr($my_id_full, 0, 11);
			}
			else{
				$found_id = $_REQUEST['videoid'];
			}
			if(isset($_REQUEST['videoid']) && strlen($found_id) == 11){

				if(isset($_REQUEST['videoid'])) {
					$my_id = $found_id;
				} else {
					$output .= '<p>No video id passed in</p>';
					exit;
				}

				if(isset($_REQUEST['type'])) {
					$my_type =  $_REQUEST['type'];
				} else {
					$my_type = 'redirect';
				}

				/* First get the video info page for this video id */
				$my_video_info = 'http://www.youtube.com/get_video_info?&video_id='. $my_id;
				$my_video_info = file_get_contents($my_video_info);

				// get video name
			    $vidID = $_REQUEST['videoid'];
			    $content = file_get_contents("http://youtube.com/get_video_info?video_id=".$found_id);
				parse_str($content, $ytarr);
				$video_title = $ytarr['title'];

				$thumbnail_url = $title = $url_encoded_fmt_stream_map = $type = $url = '';

				parse_str($my_video_info);
				if($show_thumb == 1){
					$output .= '<div class="mdc_floatleft"><img src="'. $thumbnail_url .'" border="0" hspace="2" vspace="2" height="'.$thumb_height.'" width="'.$thumb_width.'" class="mdc_video_thumb"></div>';
				}
				$my_title = $title;

				if(isset($url_encoded_fmt_stream_map)) {
					$my_formats_array = explode(',',$url_encoded_fmt_stream_map);
				} else {
					$output .= '<p>No encoded format stream found.</p>';
					$output .= '<p>Here is what we got from YouTube:</p>';
					$output .= $my_video_info;
				}
				if (count($my_formats_array) == 0) {
					$output .= '<p>No format stream map found - was the video id correct?</p>';
					exit;
				}

				/* create an array of available download formats */
				$avail_formats[] = '';
				$i = 0;
				$ipbits = $ip = $itag = $sig = $quality = '';
				$expire = time(); 
				/*	all video formats	*/
				foreach($my_formats_array as $format) {
					parse_str($format);
					$avail_formats[$i]['itag'] = $itag;
					$avail_formats[$i]['quality'] = $quality;
					$type = explode(';',$type);
					$avail_formats[$i]['type'] = $type[0];
					$avail_formats[$i]['url'] = urldecode($url) . '&signature=' . $sig;
					parse_str(urldecode($url));
					$avail_formats[$i]['expires'] = date("G:i:s T", $expire);
					$avail_formats[$i]['ipbits'] = $ipbits;
					$avail_formats[$i]['ip'] = $ip;
					$i++;
				}

				if ($my_type == $button_label) {
					$output .= '<div class="mdc_floatright">
						<p class="mdc_video_title">'.$video_title.'</p>
						<ul class="mdc_videos_list">';

					/* now that we have the array, print the options */
					for ($i = 0; $i < count($avail_formats); $i++) {
						$format = $avail_formats[$i]['type'];
						$format = explode('/', $format);
						$format = $format[1];

						//show quality?
						if($show_quality != 0){
							$item = ucfirst($format)." (Quality: ".ucfirst($avail_formats[$i]['quality']).")";
						}
						else{
							$item = ucfirst($format);
						}

						// $link = $link.'?forcedownload=1';
						//video link
						$link = $avail_formats[$i]['url'];
						
						$output .= '<li>'.$item.' - <a href="'.$link.'" download="'.$link.'" class="mime">'.$label.'</a></li>';
					}
					$output .= '</ul>
							</div>';
				}
			}//if $found_id = 11
			else{
				$output .= 'Invalid Video ID or URL';
			}
		}
		$output .= "</div>";
		return $output;
	}
}

$obj = new MDC_YouTube_Downloader;