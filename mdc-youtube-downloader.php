<?php
/*
	Plugin Name: MDC YouTube Downloader
	Plugin URI: https://wordpress.org/plugins/mdc-youtube-downloader
	Description: Want to insert YouTube videos in your posts and allow visitors to download them? MDC YouTube Downloader is here for you.
	Author: Nazmul Ahsan
	Version: 3.0.0
	Author URI: http://nazmulahsan.me
	Stable tag: 3.0.0
	License: GPL2+
	Text Domain: MedhabiDotCom
*/

define('MDC_YouTube_Downloader_Pro', false);
include_once ('includes/mdc-option-page.php');
include_once ('includes/tinymce-mdc-youtube-downloader.php');

class MDC_YouTube_Downloader{

	public function __construct(){
		add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array($this, 'add_action_links') );
		add_action( 'wp_enqueue_scripts', array($this, 'enqueue_scripts') );
		add_action( 'wp_enqueue_scripts', array($this, 'custom_style') );

		// 2 shortcodes to overcome old version issues
		add_shortcode( 'mdc_youtube_downloader', array($this, 'downloader_form') );
		add_shortcode( 'mdc_downloadable_video', array($this, 'downloadable_video') );
		// old shortcode
		add_shortcode( 'youtube_downloader_form', array($this, 'downloader_form') );
		add_shortcode( 'youtube_downloader', array($this, 'downloadable_video') );
		
		// since 3.0.0
		add_action( 'wp_head', array($this, 'add_ajaxurl') );
		add_action( 'wp_ajax_generate_via_ajax', array($this, 'generate_via_ajax') );
		add_action( 'wp_ajax_nopriv_generate_via_ajax', array($this, 'generate_via_ajax') );
		
	}


	public function add_action_links ( $links ) {

		$mylinks = array(
			'<a href="' . admin_url( 'admin.php?page=mdc-youtube-downloader' ) . '"><img src="'.plugins_url( 'images/icon.png', __FILE__).'" />Settings</a>',
			'<a href="http://medhabi.com/product/mdc-youtube-downloader-pro/" target="_blank">Get Pro</a>'
		);
		
		return array_merge( $links, $mylinks );

	}

	public function enqueue_scripts() {
		wp_enqueue_style( 'mdc_custom', plugins_url('css/style.css', __FILE__) );
		wp_enqueue_script( 'mdc_custom', plugins_url('js/custom.js', __FILE__), array(), '4.2.2', true );
	}

	public function custom_style(){
		$css = "<style>";
		$css .= get_option('mdc_custom_css');
		$css .= "</style>";
		echo $css;	
	}

	public function downloader_form($atts){

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
		$placeholder = $atts['placeholder'];
		$button_label = $atts['button_label'];
		$show_thumb = $atts['show_thumb'];
		$thumb_height = $atts['thumb_height'];
		$thumb_width = $atts['thumb_width'];
		$show_quality = $atts['show_quality'];
		$label = $atts['label'];

		$output = '
		<div class="mdc_video_form_div">
			<form class="form-download" method="post" id="download" action="">
				<input type="hidden" name="button_label" id="button_label" value="'.$button_label.'" />
				<input type="hidden" name="show_thumb" id="show_thumb" value="'.$show_thumb.'" />
				<input type="hidden" name="thumb_height" id="thumb_height" value="'.$thumb_height.'" />
				<input type="hidden" name="thumb_width" id="thumb_width" value="'.$thumb_width.'" />
				<input type="hidden" name="show_quality" id="show_quality" value="'.$show_quality.'" />
				<input type="hidden" name="label" id="label" value="'.$label.'" />
				<input required type="text" name="videoid" id="videoid" placeholder="'.$placeholder.'" />
				<input class="btn btn-primary mdc_buttons" type="submit" name="type" id="type" value="'.$atts['button_label'].'" />
			</form>
		</div>';
		
		$output .= '<div class="mdc_video_wait_div hidden">';
		$output .= '<img src="'.plugins_url('images/wait.gif', __FILE__).'">';
		$output .= '</div>';
		$output .= '<div class="mdc_video_output_div hidden">';
		// output from AJAX prints here
		$output .= '</div>';
		return $output;
	}


	public function downloadable_video($atts){
		$output = ''; 
		$output .= '<div class="mdc_video_output_div">';
		$output .= '<p>This feature is available in <a href="http://medhabi.com/product/mdc-youtube-downloader-pro/" target="_blank">Pro version</a> only!</p>';
		$output .= '</div>';
		return $output;
	}

	public function gather_data($vid_id){

		if(strlen($vid_id) > 11){
			$video_url = $vid_id;
			$vid_array = explode('?v=',$video_url);
			$my_id_full = $vid_array['1'];
			$valid_id = substr($my_id_full, 0, 11);
		}
		else{
			$valid_id = $vid_id;
		}

		if(isset($vid_id) && strlen($valid_id) == 11){

		    $video_data = file_get_contents("http://youtube.com/get_video_info?video_id=".$valid_id);
		    
			parse_str($video_data, $video_data_array);

			$thumbnail_url = $title = $url_encoded_fmt_stream_map = $type = $url = '';

			$url_encoded_fmt_stream_map = $video_data_array['url_encoded_fmt_stream_map'];
			if(isset($url_encoded_fmt_stream_map)) {

				$my_formats_array = explode(',',$url_encoded_fmt_stream_map);

			}

			$avail_formats[] = '';
			$i = 0;
			$ipbits = $ip = $itag = $sig = $quality = '';
			$expire = time();

			// protected videos like VEVO
			if( ! is_array($my_formats_array) ){
				return 'This video is protected and cannot be downloaded!';
			}

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

			$video = array();

			$video['video_id'] = $valid_id;
			$video['video_title'] = $video_data_array['title'];
			$video['thumb_url'] = $video_data_array['iurl'];
			$video['avail_formats'] = $avail_formats;
			$video['length_seconds'] = $video_data_array['length_seconds'];	//unused - for next versions
			$video['avg_rating'] = $video_data_array['avg_rating'];			//unused - for next versions
			$video['keywords'] = $video_data_array['keywords'];				//unused - for next versions
			$video['author'] = $video_data_array['author'];					//unused - for next versions
			$video['view_count'] = $video_data_array['view_count'];			//unused - for next versions
				
		}

		return $video;
	}

	public function add_ajaxurl(){ ?>
		<script type="text/javascript"> //<![CDATA[
			ajaxurl = '<?php echo admin_url( 'admin-ajax.php'); ?>';
		//]]> </script>
	<?php }

	public function generate_via_ajax(){
		if(isset($_POST['videoid'])){
			$post_vid = $_POST['videoid'];
			$button_label = $_POST['button_label'];
			$show_thumb = $_POST['show_thumb'];
			$thumb_height = $_POST['thumb_height'];
			$thumb_width = $_POST['thumb_width'];
			$show_quality = $_POST['show_quality'];
			$label = $_POST['label'];

			$output = '';
			
			$output .= $this->generate_html($post_vid, $show_thumb, $thumb_height, $thumb_width, $show_quality, $label);
			
			echo $output;

			die();
		}
	}

	public function generate_html($post_vid, $show_thumb, $thumb_height, $thumb_width, $show_quality, $label){
		$html = '';
		$gather_data = $this->gather_data($post_vid);
		if( !isset($gather_data['avail_formats']) || ! is_array($gather_data['avail_formats']) ){
			$html .= '<p>The video URL/ID given is either invalid or can not be downloaded!</p>';
		} else{
			$video_title = $gather_data['video_title'];
			$video_id = $gather_data['video_id'];
			$html .= '<h3><a href="https://www.youtube.com/watch?v='.$video_id.'" target="_blank">'. $video_title .'</a></h3>';
			$class = 'no-thumb-showing';
			if($show_thumb == 1){
				$class = 'thumb-showing';
				$thumb_url = $gather_data['thumb_url'];
				$html .= '
					<div class="'.$class.'">
						<img src="'.$thumb_url.'" height="'.$thumb_height.'" width="'.$thumb_width.'" alt="'.$video_title.'" title="'.$video_title.'" /> 
					</div>';
			}
			$html .= '<div class="'.$class.'">';
			$html .= '<ul>';
			foreach ($gather_data['avail_formats'] as $format) {
				$dl_url = $format['url'];
				
				$title = '';
				
				$type = $format['type'];

				if( substr( $type, 6) == 'webm' ){
					$extension = 'webm';
				} elseif( substr( $type, 6) == 'mp4' ){
					$extension = 'mp4';
				} elseif( substr( $type, 6) == 'x-flv' ){
					$extension = 'flv';
				} elseif( substr( $type, 6) == '3gpp' ){
					$extension = '3gp';
				}

				$title .= ucfirst( $extension ) . ' ';
				if($show_quality == 1){
					$title .= ucfirst( $format['quality'] ) . ' ';
				}

				$file_name = str_replace(' ', '-', $video_title) . '.' . $extension;
				
				$title .= '<a class="dl_url force_download" href="'.$dl_url.'" download="'.$file_name.'">'.$label.'</a>';
				
				// $allowed_formats = array('webm', 'mp4', 'flv', '3gp');
				$allowed_formats = explode(',', str_replace(' ', '', get_option('mdc_allowed_formats')));
				if( get_option('mdc_allowed_formats') == '' || ( count($allowed_formats) > 0 && in_array( $extension, $allowed_formats ) ) ){
					$html .= '<li>'.$title.'</li>';
				}
			}
			$html .= '</ul>';
			$html .= '</div>';
		}
		return $html;
	}
	
	// above this line please
}

new MDC_YouTube_Downloader;