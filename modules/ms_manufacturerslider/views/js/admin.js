
$(document).ready(function () {
    $('input[name=YBC_MF_AUTO_PLAY]').change(function () {
        if($(this).val() == 1){
            $(this).closest('form').find('.autoplay_slider_item').removeClass('hide');
        }
        else{
            $(this).closest('form').find('.autoplay_slider_item').addClass('hide');
        }
    });
});