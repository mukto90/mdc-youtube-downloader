<?php 

class MDC_option_page{

	public function __construct(){
		add_action( 'admin_menu', array($this, 'mdc_option_page') );
		add_action( 'admin_enqueue_scripts', array($this, 'mdc_admin_enqueue_scripts') );
	}

	public function mdc_admin_enqueue_scripts(){
		wp_enqueue_style( 'mdc_admin_custom', plugins_url('../css/admin.css', __FILE__) );
	}

	public function mdc_option_page(){
		add_menu_page('MDC YouTube Downloader', 'YT Downloader', 'administrator', 'mdc-youtube-downloader', array($this, 'mdc_youtube_downloader_options'), plugins_url( '../images/icon.png', __FILE__), '61.35');
	}

	public function mdc_youtube_downloader_options(){ ?>
		<div class="wrap">
			<h2><img src="<?php echo plugins_url( '../images/icon.png', __FILE__); ?>"> MDC YouTube Downloader</h2>
			<div style="clear: left"></div>
			<div class="postbox-container" style="width: 100%">
				<div id="poststuff" class="metabox-holder">
					<div id="normal-sortables" class="meta-box-sortables">
						<div id="mdc_yt_opt_page" class="postbox ">
							<div title="Click to toggle" class="handlediv"><br></div>
							<h3 class="hndle"><span>Default Settings</span></h3>
							<div class="inside">
								<!-- sidebar -->
								<div class="option_page_right">
									<div class="mdc_yt_dl_pro">
										<h3 class="mdc_yt_dl_pro_ttl">Insert downloadable videos into posts or pages?</h3>
										<div class="pro_logo">
											<a href="http://medhabi.com/items/mdc-youtube-downloader-pro/" target="_blank"><img src="<?php echo plugins_url('../images/support.png', __FILE__);?>"></a>
										</div>
										<h3 class="upgrade_today">MDC YouTube Downloade Pro</h3>
										<div class="get_pro_div">
											<a href="http://medhabi.com/items/mdc-youtube-downloader-pro/" target="_blank"><button class="get_pro_btn">UPGRADE</button></a>
											<hr />
											<a href="http://www.medhabi.com/" target="_blank"><img alt="MedhabiDotCom - One Stop Tech Solution" class="mdc_logo" src="http://www.medhabi.com/wp-content/uploads/2014/12/medhabidotcom.png">
											<i>www.medhabi.com</i></a>
										</div>
									</div>
								</div>
								<!-- sidebar ends -->
								<div class="option_page_left">
									<?php
									if(get_option('mdc_yt_setings_updated') != 1){
										update_option('mdc_form_button_text', 'Generate');
										update_option('mdc_form_placeholder_text', 'Type Video URL or ID here');
										update_option('mdc_download_text', 'Download');
										update_option('mdc_allowed_formats', 'webm, mp4, flv, 3gp');
										update_option('mdc_show_thumbnail', 1);
										update_option('mdc_show_quality', 1);
										update_option('mdc_thumbnail_width', 200);
										update_option('mdc_thumbnail_height', 200);
										update_option('mdc_yt_setings_updated', 1);
									}

									if(isset($_POST['mdc_update'])){
										if($_POST['mdc_form_button_text'] != '') update_option('mdc_form_button_text', $_POST['mdc_form_button_text']);
										if($_POST['mdc_form_placeholder_text'] != '') update_option('mdc_form_placeholder_text', $_POST['mdc_form_placeholder_text']);
										if($_POST['mdc_download_text'] != '') update_option('mdc_download_text', $_POST['mdc_download_text']);
										if($_POST['mdc_allowed_formats'] != ''){ update_option('mdc_allowed_formats', $_POST['mdc_allowed_formats']); } else{ update_option('mdc_allowed_formats', 'webm, mp4, flv, 3gp'); };
										update_option('mdc_show_thumbnail', $_POST['mdc_show_thumbnail']);
										update_option('mdc_show_quality', $_POST['mdc_show_quality']);
										if($_POST['mdc_thumbnail_width'] != '') update_option('mdc_thumbnail_width', $_POST['mdc_thumbnail_width']);
										if($_POST['mdc_thumbnail_height'] != '') update_option('mdc_thumbnail_height', $_POST['mdc_thumbnail_height']);
										update_option('mdc_custom_css', $_POST['mdc_custom_css']);
									?>

									<div class="updated settings-error" id="setting-error-settings_updated"> 
										<p><strong>Settings saved.</strong></p>
									</div>
									<?php } ?>
									<form action="" method="post">
										<input type="hidden" name="mdc_update" />
										<table class="form-table">
											<tbody>
												<tr valign="top">
													<th scope="row"><label for="mdc_allow_downloadable">Downloadable Videos into Posts/Pages?</label></th>
													<td><input type="checkbox" value="1" id="mdc_allow_downloadable" name="mdc_allow_downloadable" disabled="" /><span class="mdc_help_icon dashicons dashicons-editor-help" title="Help?"></span><span class="pro_only"><a href="http://medhabi.com/product/mdc-youtube-downloader-pro/" target="_blank">Pro Feature</a></span><br /><small class="hidden mdc_help">(Allow users to insert downloadable videos into posts or pages.)<br /><img src="<?php echo plugins_url('../images/screenshot-4.png', __FILE__);?>"></small></td>
												</tr>
												<tr valign="top">
													<th scope="row"><label for="mdc_form_button_text">Form Button Text</label></th>
													<td><input type="text" class="regular-text" value="<?php echo get_option('mdc_form_button_text');?>" id="mdc_form_button_text" name="mdc_form_button_text" placeholder="Example: Generate Download Links" /><span class="mdc_help_icon dashicons dashicons-editor-help" title="Help?"></span><br /><small class="hidden mdc_help">(Button texts to be used in downloader form.)<br /><img src="<?php echo plugins_url('../images/screenshot-6.png', __FILE__);?>"></small></td>
												</tr>
												<tr valign="top">
													<th scope="row"><label for="mdc_form_placeholder_text">Form Placeholder Text</label></th>
													<td><input type="text" class="regular-text" value="<?php echo get_option('mdc_form_placeholder_text');?>" id="mdc_form_placeholder_text" name="mdc_form_placeholder_text" placeholder="Example: Input Video ID or URL" /><span class="mdc_help_icon dashicons dashicons-editor-help" title="Help?"></span><br /><small class="hidden mdc_help">(Placeholder texts to be used in downloader form.)<br /><img src="<?php echo plugins_url('../images/screenshot-7.png', __FILE__);?>"></small></td>
												</tr>
												<tr valign="top">
													<th scope="row"><label for="mdc_download_text">Download Text</label></th>
													<td><input type="text" class="regular-text" value="<?php echo get_option('mdc_download_text');?>" id="mdc_download_text" name="mdc_download_text" placeholder="Example: Download Video" /><span class="mdc_help_icon dashicons dashicons-editor-help" title="Help?"></span><br /><small class="hidden mdc_help">(Text label to be clicked to download a video, when links are generated.)<br /><img src="<?php echo plugins_url('../images/screenshot-8.png', __FILE__);?>"></small></td>
												</tr>
												<tr valign="top">
													<th scope="row"><label for="mdc_allowed_formats">Allowed File Types</label></th>
													<td><input type="text" class="regular-text" value="<?php echo get_option('mdc_allowed_formats');?>" id="mdc_allowed_formats" name="mdc_allowed_formats" placeholder="Separated with comma" /><span class="mdc_help_icon dashicons dashicons-editor-help" title="Help?"></span><br /><small class="hidden mdc_help">(Allowed file types that can be downloaded. Available formats include: webm, mp4, flv &amp; 3gp.)</small></td>
												</tr>
												<tr valign="top">
													<th scope="row"><label for="mdc_show_quality">Show Video Qualities</label></th>
													<td><input type="checkbox" value="1" id="mdc_show_quality" name="mdc_show_quality" <?php if(get_option('mdc_show_quality') == 1){echo "checked";}?> /><span class="mdc_help_icon dashicons dashicons-editor-help" title="Help?"></span><br /><small class="hidden mdc_help">(If you want to show the quality of each video, check this.)<br /><img src="<?php echo plugins_url('../images/screenshot-9.png', __FILE__);?>"></small></td>
												</tr>
												<tr valign="top">
													<th scope="row"><label for="mdc_show_thumbnail">Show Thumbnail</label></th>
													<td><input type="checkbox" value="1" id="mdc_show_thumbnail" name="mdc_show_thumbnail" <?php if(get_option('mdc_show_thumbnail') == 1){echo "checked";}?> /><span class="mdc_help_icon dashicons dashicons-editor-help" title="Help?"></span><br /><small class="hidden mdc_help">(If you want to show the video thumbnail, check this.)<br /><img src="<?php echo plugins_url('../images/screenshot-10.png', __FILE__);?>"></small></td>
												</tr>
												<tr valign="top" class="thumbnail_row <?php if(get_option('mdc_show_thumbnail') != 1){echo "hidden";}?>">
													<th scope="row"><label for="mdc_thumbnail_width">Thumbnail Size</label></th>
													<td>
														<input type="text" class="regular-text" value="<?php echo get_option('mdc_thumbnail_width');?>" id="mdc_thumbnail_width" name="mdc_thumbnail_width" placeholder="200" style="width: 160px" /> X 
														<input type="text" class="regular-text" value="<?php echo get_option('mdc_thumbnail_height');?>" id="mdc_thumbnail_height" name="mdc_thumbnail_height" placeholder="300" style="width: 160px"  />px <span class="mdc_help_icon dashicons dashicons-editor-help" title="Help?"></span><br /><small class="hidden mdc_help">(Height and Width of the thumbnail respectively. Only digits please.)</small>
													</td>
												</tr>
												<tr valign="top">
													<th scope="row"><label for="mdc_custom_css">Custom CSS</label></th>
													<td>
														<textarea type="text" id="editor" class="css" name="mdc_custom_css" style="height: 200px; width: 340px;"><?php if(get_option('mdc_custom_css')){ echo get_option('mdc_custom_css');}?></textarea>
														<span class="mdc_help_icon dashicons dashicons-editor-help" title="Help?"></span><br /><small class="hidden mdc_help">(If you want to add your own CSS.)</small>
													</td>
												</tr>
											</tbody>
										</table>
										<p class="submit">
											<input type="submit" value="Save Changes" class="button button-primary" id="submit" name="submit">
										</p>
										<div class="clear"></div>
										<hr />
										<p style="font-style: italic"><strong>Notes:</strong></p>
										<ul>
											<li><span class="dashicons dashicons-yes"></span>This Plugin comes with <strike title="Pro Feature"><a href="http://medhabi.com/product/mdc-youtube-downloader-pro/" target="_blank">2 shortcodes</a></strike> a shortcode. <code>[youtube_downloader_form]</code> and <code><strike title="Pro Feature"><a href="http://medhabi.com/product/mdc-youtube-downloader-pro/" target="_blank">[youtube_downloader]</a></strike></code>. You can use YouTube icon ( <img class="yt_sm_icon" src="<?php echo plugins_url( '../images/icon.png', __FILE__);?>"> ) to generate shortcode in TinyMCE editor. <span class="mdc_screenshot_toggle">[Screenshot]</span><br /><img class="hidden mdc_screenshot_img" src="<?php echo plugins_url( '../images/screenshot-2.png', __FILE__);?>"></li>
											<li><span class="dashicons dashicons-yes"></span><code>[youtube_downloader_form]</code> generates a form. Copy a URL of any YouTube video, paste it in the form and click Download. You'll then get a list of download links of available video formats. <span class="mdc_screenshot_toggle">[Screenshot]</span><br /><img class="hidden mdc_screenshot_img" src="<?php echo plugins_url( '../images/screenshot-3.png', __FILE__);?>"></li>
											<li><span class="dashicons dashicons-yes"></span><strike title="Pro Feature"><a href="http://medhabi.com/product/mdc-youtube-downloader-pro/" target="_blank"><code>[youtube_downloader]</code> can be used to insert a downloadable video into your posts/pages. You provide a video URL or ID and it will generate download link(s) of this video for your visitors.</a></strike> <span class="mdc_screenshot_toggle">[Screenshot]</span><br /><img class="hidden mdc_screenshot_img" src="<?php echo plugins_url( '../images/screenshot-4.png', __FILE__);?>"></li>
											<li><span class="dashicons dashicons-yes"></span>These Settings are Default Settings. You can override each of them using shortcode parameters from the popup that appears by clicking shortcode icon. <span class="mdc_screenshot_toggle">[Screenshot]</span><br /><img class="hidden mdc_screenshot_img" src="<?php echo plugins_url( '../images/screenshot-5.png', __FILE__);?>"></li>
										</ul>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="clear"></div>
<?php }
}

new MDC_option_page;