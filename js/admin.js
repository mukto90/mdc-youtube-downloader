$ = new jQuery.noConflict();
$(document).ready(function($) {
    // toggle help texts
    $(".mdc_help_icon").click(function(){
        var par = $(this).parent();
        $(".mdc_help", par).slideToggle();
    })

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

    // alter to say- you have to upgrade to pro
    $("#mdc_toogle_mode").click(function(e){
        alert('This Feature is Limited to Premium Version Only. Please Upgrade to Enjoy!');
        e.preventDefault();
    })
});