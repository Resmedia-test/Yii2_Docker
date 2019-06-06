// listen click, open modal and .load content
$(document).on('click', '.modalForm', function (e){
    if($(this).hasClass('loading'))
        return false;

    $(this).addClass('disabled');

    $.get($(this).attr('href'))
        .done(function(html){
            $("#modalContent").html(html);
            $("#modal").find("form").on('beforeSubmit', submitForm).on('submit', function(e){
                e.preventDefault();
            });

            $('#modal').modal('show');
        })
        .fail(function(){
            toastr["error"]("Error while processing request");
            $('.modalForm.disabled').removeClass('disabled');
        });

    e.preventDefault();
});

$(document).on('hidden.bs.modal', '#modal', function (e) {
    $('.modalForm.disabled').removeClass('disabled');
    //$('#modal-form form').off('submit');

    if ($(e.target).find('a.save-changes').length > 0) {
        $('body').addClass('modal-open');
        return true;
    }

    $(this).find(".modal-header").find("h4").remove();
    $(this).find(".modal-content").find("form").replaceWith('<div class="modal-body"></div>');

    $.pjax.reload({container: '#refreshModal'});
});

// serialize form, render response and close modal
function submitForm() {
    var form = $('#modal').find("form");
    $.post(
        form.attr("action"), // serialize Yii2 form
        form.serialize()
    )
        .done(function(result) {
            try {
                result = $.parseJSON(result);
            } catch (e) {
                $('#modal').modal('hide');

                if($("#refresh").size())
                    $.pjax.reload({container: '#refresh'});
            }

            if(result.status == 'error')
                toastr["error"](result.message);
            else {
                $('#modal').modal('hide');
                $('body').addClass('modal-open-close');

                if($("#refresh").size())
                    $.pjax.reload({container: '#refresh'});
            }
        })
        .fail(function() {
            console.log("server error");
            toastr["error"]("Error while processing request");
        });
    return false;
}

$(function () {
    $('[data-toggle="tooltip"]').tooltip({
        trigger: 'manual'
    });

    $('body').on('click', '[data-toggle="tooltip"]', function(){
        $('[data-toggle="tooltip"]').not($(this)).tooltip('hide');

        if ($(this).parent().find('.tooltip').size())
            $('[data-toggle="tooltip"]').tooltip('hide');
        else
            $(this).tooltip('show');
    });
});

$(document).on('pjax:success', function() {
    $('[data-toggle="tooltip"]').tooltip({
        trigger: 'manual'
    });
});