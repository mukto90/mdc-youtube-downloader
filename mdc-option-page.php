<?php 
function mdc_option_page(){
	add_menu_page('MDC YouTube Downloader', 'YT Downloader', 'administrator', 'mdc-youtube-downloader', 'mdc_youtube_downloader_options', plugins_url( 'images/icon.png' , __FILE__ ), 61);
	// add_submenu_page('mdc-theme-switcher', 'MedhabiDotCom', 'MedhabiDotCom', 'administrator', 'medhabidotcom', 'medhabidotcom', '');
}
add_action('admin_menu', 'mdc_option_page');
function mdc_youtube_downloader_options(){
	?>
<div class="wrap">
	<h2>MDC YouTube Downloader</h2>
	<?php if($_POST){
	update_option('mdc_download_text', $_POST['mdc_download_text']);
	update_option('mdc_show_thumbnail', $_POST['mdc_show_thumbnail']);
	update_option('mdc_show_quality', $_POST['mdc_show_quality']);
	update_option('mdc_thumbnail_width', $_POST['mdc_thumbnail_width']);
	update_option('mdc_thumbnail_height', $_POST['mdc_thumbnail_height']);
	update_option('mdc_custom_css', $_POST['mdc_custom_css']);
	?>
	<div class="updated settings-error" id="setting-error-settings_updated"> 
		<p><strong>Settings saved.</strong></p>
	</div>
	<?php } ?>
	<form action="" method="post">
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="mdc_download_text">Download Text</label></th>
					<td><input type="text" class="regular-text" value="<?php echo get_option('mdc_download_text');?>" id="mdc_download_text" name="mdc_download_text" placeholder="Example: Download Video" /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="mdc_show_thumbnail">Show Thumbnail</label></th>
					<td><input type="checkbox" value="1" id="mdc_show_thumbnail" name="mdc_show_thumbnail" <?php if(get_option('mdc_show_thumbnail') == 1){echo "checked";}?> /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="mdc_show_quality">Show Video Qualities</label></th>
					<td><input type="checkbox" value="1" id="mdc_show_quality" name="mdc_show_quality" <?php if(get_option('mdc_show_quality') == 1){echo "checked";}?> /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="mdc_thumbnail_width">Thumbnail Size</label></th>
					<td>
						<input type="text" class="regular-text" value="<?php echo get_option('mdc_thumbnail_width');?>" id="mdc_thumbnail_width" name="mdc_thumbnail_width" placeholder="200" style="width: 160px" /> X 
						<input type="text" class="regular-text" value="<?php echo get_option('mdc_thumbnail_height');?>" id="mdc_thumbnail_height" name="mdc_thumbnail_height" placeholder="300" style="width: 160px"  />px
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="mdc_custom_css">Custom CSS</label></th>
					<td>
						<textarea type="text" id="mdc_custom_css" name="mdc_custom_css" style="height: 200px; width: 340px;"><?php if(get_option('mdc_custom_css')){ echo get_option('mdc_custom_css');} else{?>

.mdc_floatleft {
	float: left;
	overflow: hidden;
}
.mdc_floatright {
	overflow: hidden;
}
.mdc_videos_list li{
	list-style: square
}
						<?php }?></textarea>
					</td>
				</tr>
			</tbody>
		</table>
		<p style="font-style: italic">Tips: Create a new page/post and use shortcode <strong>[mdc_youtube_downloader]</strong>.<br />This will generate YouTube downloader form. Copy a URL of any YouTube video, paste it in the form and click Download. You'll then get a list of available video formats.</p>
		<p class="submit">
			<input type="submit" value="Save Changes" class="button button-primary" id="submit" name="submit">
		</p>
	</form>
</div>
<div class="clear"></div>
	<?php
}

function medhabidotcom(){
	?>
	<div class="wrap">
		<h2>MedhabiDotCom</h2>
	</div>
	<?php

}
?>