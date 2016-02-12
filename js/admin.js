$ = new jQuery.noConflict();
$(document).ready(function($) {
    // toggle help texts
    $(".mdc_help_icon").click(function(){
        var par = $(this).parent();
        $(".mdc_help", par).slideToggle();
    })

    // toggle screenshot sample
    $(".mdc_screenshot_toggle").click(function(){
        var par = $(this).parent();
        $(".mdc_screenshot_img", par).slideToggle();
    })

    // toggle height, width input
    $("#mdc_show_thumbnail").change(function(){
        if($(this).is(":checked")){
            $(".thumbnail_row").show();
        }
        else{
            $(".thumbnail_row").hide();
        }
    })
});