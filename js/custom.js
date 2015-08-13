$ = new jQuery.noConflict();
$(document).ready(function() {
    $(".mdc_toggle_btn").click(function(){
    	par = $(this).parent();
    	$(".mdc_post_video_content", par).slideToggle();
    })
});