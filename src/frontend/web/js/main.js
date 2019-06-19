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

            if ($("#refresh").length)
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

$('#subscription_guest').click(function (e) {
    e.preventDefault();

    if (!$(this).parent().find('.has-success').length) {
        return false;
    }

    var modal = $('#modal');
    var url = '/subscription/form?email=' + encodeURIComponent($(this).parent().find('input').val());
    
    console.log(url);
    if ($(this).hasClass('loading') || $(this).parent().find('.field-subscription-email').hasClass('has-error'))
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
});


jQuery(document).ready(function () {


    //tabs for comments and their hashes
    if (window.location.hash.search('comment')) {
        var id = parseInt(window.location.hash.substr(String('#comment').length));

        if ($('a[href="#comments"]').length && id) {
            $('.nav-tabs a[href="#comments"]').tab('show');

            $('.nav-tabs a[href="#comments"]').on('shown.bs.tab', function (event) {
                $('html, body').animate({
                    scrollTop: $('div[data-key="' + id + '"]').offset().top - 100
                }, 'slow');
            });
        }
    }


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

$('form.poll').on('submit', function () {
    var form = $(this);
    if (Number($(this).find('input[name="answer_id"]:checked').val()) > 0) {
        $.ajax({
            url: form.attr("action"),
            method: 'POST',
            dataType: 'json',
            data: form.serialize(),
            success: function (result) {
                console.log('success');
                console.log(form.find('button[type="submit"]'));
                form.find('button[type="submit"]').attr('disabled', 'disabled');
            }, error: function (result) {
                console.log("server error");
                console.log(result);
                $('#modal .modal-body').html(result.responseText);
                $('#modal').modal('show');
            }
        });
    }
    return false;
});


function subscribe(attr, val) {
    var attr = attr || null;
    var val = val || 0;

    $.ajax({
        url: '/account/subscribe',
        method: 'GET',
        data: {
            attr: attr,
            val: +val
        }
    });
}

$('#vote-option-form').submit(function(){
    $.ajax({
        url: $(this).attr('action'),
        method: 'POST',
        data: $(this).serialize(),
        complete: function(){
            window.location.reload();
        }
    });

    return false;
});

if (!navigator.cookieEnabled) {
    toastr["warning"]("Вам необходимо включить cookie для правильной работы интернет ресурса ON!");
}

$(window).on('load', function () {
    $('.simplebar-scroll-top').addClass('block-visible');
});

function search() {
    window.location = '/search?' + $('#search-form-top').find('input').attr('name') + '=' + $('#search-form-top').find('input').val();
}