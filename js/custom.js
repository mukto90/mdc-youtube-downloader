$ = new jQuery.noConflict();

$(document).ready(function() {

    // ajax generate video links
    $(".mdc_video_form_div form").submit(function(e){

    	$(".mdc_video_output_div").hide();
    	$(".mdc_video_wait_div").show();

    	var videoid = $("#videoid", this).val();
    	var button_label = $("#button_label", this).val();
    	var show_thumb = $("#show_thumb", this).val();
    	var thumb_height = $("#thumb_height", this).val();
    	var thumb_width = $("#thumb_width", this).val();
    	var show_quality = $("#show_quality", this).val();
    	var label = $("#label", this).val();
    	
    	$.ajax({
    		data: {'action' : 'generate_via_ajax', 'videoid' : videoid, 'button_label' : button_label, 'show_thumb' : show_thumb, 'thumb_height' : thumb_height, 'thumb_width' : thumb_width, 'show_quality' : show_quality, 'label' : label},
    		url: ajaxurl,
    		type: 'POST',
    		success:function(ferot){
    			$(".mdc_video_output_div").show();
    			$(".mdc_video_wait_div").hide();
    			$("#videoid").val('');
    			$(".mdc_video_output_div").html(ferot);

                // onclick force download - download form
                $(".force_download").click(function(e){
                    alert('Right click and choose \'Save Link As..\'');
                    e.preventDefault();
                })

    		}
    	})
    	e.preventDefault();
    })
    
    // onclick force download - downloadable video
    if($(".mdc_video_output_div a").is(".force_download")){
        $(".force_download").click(function(e){
            alert('Right click and choose \'Save Link As..\'');
            e.preventDefault();
        })
    }

    
// above this line please
});