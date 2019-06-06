var emptyModalContent = $('#modal').find('.modal-content').html();

// listen click, open modal and .load content
$(document).on('click', '.modalForm', function (e) {
    var modal = $('#modal');
    var url = $(this).attr('href');

    if ($(this).hasClass('loading'))
        return false;

    $(this).addClass('disabled');

    if (modal.data('bs.modal').isShown) {
        modal.modal('hide');

        $(document).on('hidden.bs.modal', '#modal', function (e) {
            processModal(url);
        });
    }
    else
        processModal(url);

    e.preventDefault();
});

function processModal(url) {
    var modal = $('#modal');

    $.get(url, function (html) {
        $('#modal').find(".modal-content").html(emptyModalContent);

        modal.find(".modal-body").replaceWith(html);
        $("#modal .modal-content").find("h4").detach().appendTo("#modal .modal-header");

        modal.find("form").on('beforeSubmit', submitForm).on('submit', function (e) {
            e.preventDefault();
        });
        modal.find("form").on('beforeValidate', submitLoading).on('afterValidate', submitReset);

        modal.modal('show');
    });

    $(document).off('hidden.bs.modal', '#modal');
}


$(document).on('hidden.bs.modal', '#modal', function (e) {
    //$('#modal-form form').off('submit');

    $('#modal').find(".modal-header").find("h4").remove();
    $('#modal').find(".modal-content").replaceWith('<div class="modal-header"></div><div class="modal-body"></div><div class="modal-footer"></div>');

    var classes = $('#modal').find('.modal-dialog').attr('data-class');

    if (classes && classes.length) {
        $('#modal').find('.modal-dialog').attr('class', classes);
        $('#modal').find('.modal-dialog').attr('data-class', '');
    }


    //$.pjax.reload({container: '#refreshModal'});
});

// serialize form, render response and close modal
function submitForm(callback) {
    var form = $('#modal').find("form");

    submitLoading();

    $.ajax({
        url: form.attr("action"),
        method: 'POST',
        dataType: 'json',
        data: form.serialize(),
        success: function (result) {
            if (typeof result.redirect !== 'undefined')
                document.location.href = result.redirect;

            form.parent().html(result.message);

            $('#modal').modal('hide');

            $('.modalForm.disabled').removeClass('disabled');

            if ($("#refresh").size())
                $.pjax.reload({container: '#refresh'});
        }, error: function () {
            console.log("server error");
            toastr["error"]("Возникла ошибка при обработке запроса. Обратитесть в ТехПоддержку.");
            //form.replaceWith('Fail').fadeOut();
        }, complete: function () {
            submitReset();

            if (typeof callback != 'undefined') {
                window.location.reload();
            }
        }
    });

    return false;
}

//setting loading state to submit button
function submitLoading() {
    $('#modal').find('.btn-loading').button('loading');
    $('#modal').find('form').yiiActiveForm('resetForm');
}

//setting reset state to submit button
function submitReset() {
    $('#modal').find('.btn-loading').button('reset');
}

jQuery(document).ready(function () {
    $('body').tooltip({selector: 'a, span, button, i'});
    $('[data-toggle="popover"]').popover();
    $('.dropdown-toggle').dropdown();
});

$elements = $('.rotating-content').find('span');
$elements.hide().first().show();

setInterval(function () {
    $elements.filter(':visible').fadeOut('slow', function () {
        $next = $(this).next();
        if ($next.length === 0) {
            $next = $elements.first();
        }
        $next.fadeIn('slow');
    });
}, 3500);

if (!navigator.cookieEnabled) {
    toastr["warning"]("Вам необходимо включить cookie для правильной работы интернет ресурса ON!");
}

function search() {
    window.location = '/search?' + $('#search-form-top').find('input').attr('name') + '=' + $('#search-form-top').find('input').val();
}