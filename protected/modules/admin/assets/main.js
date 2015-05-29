/**
 * Created with JetBrains PhpStorm.
 * User: Hett
 * Date: 13.02.13
 * Time: 12:09
 */

// FancyBox init
$(document).ready(function() {
    $(".various").fancybox({
        fitToView	: false,
        height		: 'auto',
        autoSize	: true,
        closeClick	: false,
        arrows : false,
        showNavArrows: false
    });
});

$.form = {

    ajaxy: function(object, afterSuccessSubmit, afterSubmit)
    {
        var form = $(object);
        form.submit(function(){
            $.fancybox.showLoading();
            var t = $(this);
            var data = t.serialize();
            form.find('input,select,textarea,button').attr('disabled', true);
            $.ajax({
                url: t.attr('action'),
                type: 'post',
                data: data,
                cache: false,
                statusCode: {
                    200: function(data){
                        $.fancybox(data);
                    },
                    201: function(data){
                        $.fancybox.close();
                        data = eval('('+data+')');
                        if(data.url) {
                            window.location.href = data.url;
                        }
                    },
                    202: function(data){
                        $.fancybox.close();
                        afterSuccessSubmit(data);
                    },
                    403: function(){
                        window.location.href = '/login.html';
                    }
                },
                error: function(data) {
                    $.fancybox.hideLoading();
                    $.fancybox('Error!');
                },
                success:function(){
                    $.fancybox.hideLoading();
                },
                complete:function(){
                    afterSubmit();
                }
            });
            return false;
        });
    }
};