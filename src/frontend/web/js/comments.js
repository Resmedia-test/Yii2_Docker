$(document).on('click', 'a[href="#replyComment"]', function(){
    var id = $(this).data('id');
    var author = $(this).data('author');

    $('input[name="Comment[reply_id]"]').val(id);

    $('.replyBlock').html('<span class="alert-font">Вы отвечаете пользователю: <a title="Перейти к комментарию" href="#comment'+id+'">'+author+'</a> <a title="Отменить ответ" href="#delete" class="deleteReply">&times;</a></span>');
    $('body').animate({scrollTop : $('.scrollTo').offset().top-50}, 'fast');

    return false;
});

$(document).on('click', '.deleteReply', function(){
    $('input[name="Comment[reply_id]"]').val(0);
    $('.replyBlock').html('');

    return false;
});

$(document).on('click', 'a[href="#deleteComment"]', function(){
    if(confirm("Вы действительно хотите удалить свой комментарий?")) {
        var id = $(this).data('id');

        if (id !== null) {
            $.ajax({
                url: '/comment/delete',
                data: {id: id},
                success: function(){
                    toastr["success"]("Комментарий успешно удален!");
                }
            });

            window.setTimeout(function(){
                $.pjax.reload({container: '#refresh'});
            }, 500);
        }
    }

    return false;
});

$(document).on('click', 'a[href="#likeComment"]', function(){
    var id = $(this).data('id');

    var self = $(this);

    if( self.data('disabled') !== "true" )
    {
        $.ajax({
            url: '/comment/like',
            data: {id: id},
            dataType: 'json',
            success: function(data){
                if(data.likes !== null)
                    self.find('span').text(data.likes);
                    toastr["success"]("Вы успешно оценили комментарий!");

                self.data('disabled', 'true');
            },
            error: function(){
                self.data('disabled', 'true');
            }
        });
    }

    return false;
});

$(document).on('click', 'a[href="#abuseComment"]', function(){
    if(confirm("Вы действительно хотите пожаловаться на данный комментарий?"))
    {
        var id = $(this).data('id');

        $.ajax({
            url:'/comment/abuse',
            data: {id: id},
            success: function(){
                toastr["success"]("Жалоба на комментарий отправлена!");
            }
        });
    }

    return false;
});

/*$(document).on('click', 'a[href="#updateComment"]', function(){
    var id = $(this).data('id');

    $.ajax({
        url: '/comment/get',
        data: {id: id},
        dataType:'json',
        success:function(data){
            if(!data.error)
            {
                $('input[name="Comment[id]"]').val(data.id);
                //$('#redactor2').redactor('set',data.text);
                $('textarea[name="Comment[text]"]').val(data.text);
                $('.updateBlock').slideDown().find('a:first-of-type').text(data.id).attr('href', '#comment'+data.id);


                $('body').animate({scrollTop : $('.scrollTo2').offset().top-50}, 'medium');
            }
            else
            {
                toastr["error"]("Ошибка во время выполнения запроса");
                return false;
            }

        },
        error:function(){
            toastr["error"]("Ошибка во время выполнения запроса");
            return false;
        }
    });

    return false;
});*/

$(document).on('click', 'a[href="#cancelUpdate"]', function(){
    $("#comment-form")[0].reset();

    $('#comment-form .help-block').text('').hide().parent().removeClass('has-error');

    $(".errorForm").hide();
    $('.updateBlock').slideUp();


    return false;
});


$(document).on("beforeSubmit", "#comment-form", function () {
    var form = $(this).serializeObject();
    form.id = $('input[name="Comment[id]"]').val();
    
        $.ajax({
            url: '/comment/create',
            method: 'POST',
            dataType: 'json',
            data: form,
            success: function(data){
                if(data.status == 'success'){
                    toastr["success"]("Комментарий успешно опубликован!");
                    $.pjax.reload({container: '#refresh'});

                    $("#comment-form")[0].reset();

                    $('#comment-form .help-block').each(function(item) {
                        if (!!item) {
                            item.text('').hide();
                            item.parent().removeClass('has-error');
                        }
                    });

                    $(".errorForm").hide();
                    $("#comment-text").redactor("code.set", "");
                }
                else {
                    $.each(data.error, function(key, val) {
                        $('#comment-form .field-'+key).find('.help-block').text(val).show();
                        $('#comment-form .field-'+key).removeClass('has-success').addClass('has-error');
                    });
                }
            },
            error: function(){
                toastr["error"]("Ошибка во время выполнения запроса");
            }
        });

    return false;
}).on('submit', function(e){
    e.preventDefault();
});


$(document).on('change', 'input[type=radio][name=subscription]', function(){ console.log();
    $.ajax({
        type: 'GET',
        url: '/comment/subscribe',
        data: {
            type_id: $(this).val(),
            model: $(this).closest('.subscriptionBlock').data('model'),
            model_id: $(this).closest('.subscriptionBlock').data('model_id')
        }
    });
});

$.fn.serializeObject = function()
{
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};
