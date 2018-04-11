/**
 * Created by Administrator on 15-9-7.
 */
$(function(){
    $('.cy-rg1ph').each(function(){
        $(":input").focus(function(){
            $(this).parents('.cy-rg1ph').addClass("focus");
            if($(this).val() ==this.defaultValue){
                $(this).val("");
            }
        }).blur(function(){
            $(this).parents('.cy-rg1ph').removeClass("focus");
            if ($(this).val() == '') {
                $(this).val(this.defaultValue);
            }
        });
    });
})