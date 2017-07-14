/* 
 * Spotlight: Javascript
 * 
 */
jQuery(document).ready(function() {
var img_index = 1;
jQuery('#upload_image_button_1').click(function() {
    img_index = 1;
    formfield = jQuery('#upload_image_1').attr('name');
    tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');    
    return false;
});

jQuery('#upload_image_button_2').click(function() {
    img_index = 2;
    formfield = jQuery('#upload_image_1').attr('name');
    tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
    return false;
});

jQuery('#upload_image_button_3').click(function() {
    img_index = 3;
    formfield = jQuery('#upload_image_1').attr('name');
    tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
    return false;
});

jQuery('#upload_image_button_4').click(function() {
    img_index = 4;
    formfield = jQuery('#upload_image_4').attr('name');
    tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
    return false;
});

window.send_to_editor = function(html) {
    imgurl = jQuery('img',html).attr('src');
    var input_id= '#upload_image_'+ img_index ;
    var img_id = '#deal_img_'+img_index ;
    jQuery(img_id).attr('src',imgurl);
    jQuery(input_id).val(imgurl);
    tb_remove();
}

});



