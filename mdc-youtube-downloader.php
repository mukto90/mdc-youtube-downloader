<?php
/****
	* Plugin Name: MDC YouTube Downloader
	* Plugin URI: http://wordpress.org/plugins/mdc-youtube-downloader/
	* Description: MDC YouTube Downloader allows visitors to download YouTube videos directly from your WordPress site.
	* Author: Nazmul Ahsan
	* Version: 1.2.1
	* Author URI: http://mukto.medhabi.com
	* Stable tag: 1.2.1
	* License: GPL2+
	* Text Domain: MedhabiDotCom
****/
function mdc_add_stylesheet() {
	if(!get_option('mdc_custom_css')){
		wp_enqueue_style( 'mdc_youtube_downloader_style', plugins_url('css/style.css', __FILE__) );
	}
	else{
		echo "<style>";
		echo get_option('mdc_custom_css');
		echo "</style>";
	}
}
add_action( 'wp_enqueue_scripts', 'mdc_add_stylesheet' );

include "mdc-option-page.php";

function mdc_youtube_downloader(){?>
<form class="form-download" method="post" id="download" action="">
	<input required type="text" name="videoid" id="videoid" size="40" placeholder="Video ID or URL" />
	<input class="btn btn-primary" type="submit" name="type" id="type" value="Download" />
</form>
<br />
<div class="mdc_video_div">
	<?php
	if($_REQUEST['videoid']){
	if(strlen($_REQUEST['videoid']) > 11){
	$video_url = $_REQUEST['videoid'];
	$vid_array = explode('?v=',$video_url);
	$my_id_full = $vid_array['1'];
	$found_id = substr($my_id_full, 0, 11);
	}
	else{
	$found_id = $_REQUEST['videoid'];
	}
	
include_once('includes/curl.php');

if(isset($_REQUEST['videoid'])) {
	$my_id = $found_id;
} else {
	echo '<p>No video id passed in</p>';
	exit;
}

if(isset($_REQUEST['type'])) {
	$my_type =  $_REQUEST['type'];
} else {
	$my_type = 'redirect';
}

if(isset($_REQUEST['debug'])) {
	$debug = TRUE;
} else {
	$debug = FALSE;
}

if ($my_type == 'Download') {
?>
<?php
}
/* First get the video info page for this video id */
$my_video_info = 'http://www.youtube.com/get_video_info?&video_id='. $my_id;
$my_video_info = curlGet($my_video_info);

/* TODO: Check return from curl for status code */

$thumbnail_url = $title = $url_encoded_fmt_stream_map = $type = $url = '';

parse_str($my_video_info);
if(get_option('mdc_show_thumbnail') == 1){
	if(get_option('mdc_thumbnail_height')){
		$height = get_option('mdc_thumbnail_height');
	}
	else{
		$height = "auto";
	}
	if(get_option('mdc_thumbnail_width')){
		$width = get_option('mdc_thumbnail_width');
	}
	else{
		$width = "auto";
	}
	echo '<div class="mdc_floatleft"><img src="'. $thumbnail_url .'" border="0" hspace="2" vspace="2" height="'.$height.'" width="'.$width.'" class="mdc_video_thumb"></div>';
}
$my_title = $title;

if(isset($url_encoded_fmt_stream_map)) {
	$my_formats_array = explode(',',$url_encoded_fmt_stream_map);
} else {
	echo '<p>No encoded format stream found.</p>';
	echo '<p>Here is what we got from YouTube:</p>';
	echo $my_video_info;
}
if (count($my_formats_array) == 0) {
	echo '<p>No format stream map found - was the video id correct?</p>';
	exit;
}

/* create an array of available download formats */
$avail_formats[] = '';
$i = 0;
$ipbits = $ip = $itag = $sig = $quality = '';
$expire = time(); 
/*	all video formats	*/
foreach($my_formats_array as $format) {
// echo "<pre>";
// print_r($avail_formats);
// echo "</pre>";
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

if ($debug) {
	echo '<p>These links will expire at '. $avail_formats[0]['expires'] .'</p>';
	echo '<p>The server was at IP address '. $avail_formats[0]['ip'] .' which is an '. $avail_formats[0]['ipbits'] .' bit IP address. ';
	echo 'Note that when 8 bit IP addresses are used, the download links may fail.</p>';
}
if ($my_type == 'Download') {
	echo '<div class="mdc_floatright"><ul class="mdc_videos_list">';

	/* now that we have the array, print the options */
	for ($i = 0; $i < count($avail_formats); $i++) {
	$format = $avail_formats[$i]['type'];
	$format = explode('/', $format);
	$format = $format[1];
	?>
		<li>
			<?php echo ucfirst($format); if(get_option('mdc_show_quality')){?> (Quality: <?php echo ucfirst($avail_formats[$i]['quality']);?>) <?php }?> - <a href="<?php echo $avail_formats[$i]['url']; ?>" target="_blank" class="mime"><?php if(get_option('mdc_download_text')) {echo get_option('mdc_download_text');} else{ echo "Download";}?></a>
		</li>
	<?php }
	echo '</ul></div>';
?>

<!-- @TODO: Prepend the base URI -->
<!--a href="ytdl.user.js" class="userscript btn btn-mini" title="Install chrome extension to view a 'Download' link to this application on Youtube video pages.">
  Install Chrome Extension
</a-->

<?php

} else {

$format =  $_REQUEST['format'];
$target_formats = '';
switch ($format) {
	case "best":
		/* largest formats first */
		$target_formats = array('38', '37', '46', '22', '45', '35', '44', '34', '18', '43', '6', '5', '17', '13');
		break;
	case "free":
		/* Here we include WebM but prefer it over FLV */
		$target_formats = array('38', '46', '37', '45', '22', '44', '35', '43', '34', '18', '6', '5', '17', '13');
		break;
	case "ipad":
		/* here we leave out WebM video and FLV - looking for MP4 */
		$target_formats = array('37','22','18','17');
		break;
	default:
		/* If they passed in a number use it */
		if (is_numeric($format)) {
			$target_formats[] = $format;
		} else {
			$target_formats = array('38', '37', '46', '22', '45', '35', '44', '34', '18', '43', '6', '5', '17', '13');
		}
	break;
}

/* Now we need to find our best format in the list of available formats */
$best_format = '';
for ($i=0; $i < count($target_formats); $i++) {
	for ($j=0; $j < count ($avail_formats); $j++) {
		if($target_formats[$i] == $avail_formats[$j]['itag']) {
			//echo '<p>Target format found, it is '. $avail_formats[$j]['itag'] .'</p>';
			$best_format = $j;
			break 2;
		}
	}
}

//echo '<p>Out of loop, best_format is '. $best_format .'</p>';
if( (isset($best_format)) && 
  (isset($avail_formats[$best_format]['url'])) && 
  (isset($avail_formats[$best_format]['type'])) 
  ) {
	$redirect_url = $avail_formats[$best_format]['url'];
	$content_type = $avail_formats[$best_format]['type'];
}
if(isset($redirect_url)) {
	header("Location: $redirect_url"); 
}

} // end of else for type not being Download
}
echo "</div>";
}
add_shortcode('mdc_youtube_downloader', 'mdc_youtube_downloader');
?>