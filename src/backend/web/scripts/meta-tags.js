/**
 * Created by artemshmanovsky on 19.06.16.
 */
/*$('.nav-tabs a').on('shown.bs.tab', function (event) {
    var tab = $(event.target);

    if (tab.attr('href') == '#meta') {
        $('.modal-content').find('form').yiiActiveForm('remove', 'metatag-title');
        $('.modal-content').find('form').yiiActiveForm('add', {
            "id": "metatag-title",
            "name": "title",
            "container": ".field-metatag-title",
            "input": "#metatag-title",
            "validate": function (attribute, value, messages, deferred, $form) {
                yii.validation.string(value, messages, {
                    "message": "Значение «Заголовок» должно быть строкой.",
                    "max": 80,
                    "tooLong": "Значение «Заголовок» должно содержать максимум 80 символов.",
                    "skipOnEmpty": 1
                });
            }
        });

        $('.modal-content').find('form').yiiActiveForm('remove', 'metatag-keywords');
        $('.modal-content').find('form').yiiActiveForm('add', {
            "id": "metatag-keywords",
            "name": "keywords",
            "container": ".field-metatag-keywords",
            "input": "#metatag-keywords",
            "enableAjaxValidation": true,
            "validate": function (attribute, value, messages, deferred, $form) {
                yii.validation.string(value, messages, {
                    "message": "Значение «Ключевые слова» должно быть строкой.",
                    "max": 200,
                    "tooLong": "Значение «Ключевые слова» должно содержать максимум 200 символов.",
                    "skipOnEmpty": 1
                });
            }
        });

        $('.modal-content').find('form').yiiActiveForm('remove', 'metatag-description');
        $('.modal-content').find('form').yiiActiveForm('add', {
            "id": "metatag-description",
            "name": "description",
            "container": ".field-metatag-description",
            "input": "#metatag-description",
            "enableAjaxValidation": true,
            "validate": function (attribute, value, messages, deferred, $form) {
                yii.validation.string(value, messages, {
                    "message": "Значение «Описание» должно быть строкой.",
                    "max": 250,
                    "tooLong": "Значение «Ключевые слова» должно содержать максимум 250 символов.",
                    "skipOnEmpty": 1
                });
            }
        });
    }
});*/


$(document).ready(function(){



    var $title = $('.meta-title-count');
    var $length_title = $title.val().length;
    $title.parent().find('p').find('.text-title').text(80-$length_title);
    $title.bind('input propertychange', function(){
        if($(this).val().length > 80)
        {
            $(this).val( $(this).val().substring(0, 80) );
        }

        $length_title = $(this).val().length;
        $(this).parent().find('p').find('.text-title').text(80-$length_title);
    });

    var $desc = $('.meta-desc-count');
    var $length_desc = $desc.val().length;
    $desc.parent().find('p').find('.text-desc').text(200-$length_desc);
    $desc.bind('input propertychange', function(){
        if($(this).val().length > 200)
        {
            $(this).val( $(this).val().substring(0, 200) );
        }

        $length_desc = $(this).val().length;
        $(this).parent().find('p').find('.text-desc').text(200-$length_desc);
    });

    var $kw = $('.meta-kw-count');
    var $length = $kw.val().length;
    $kw.parent().find('p').find('.text-kw').text(250-$length);
    $kw.bind('input propertychange', function(){
        if($(this).val().length > 250)
        {
            $(this).val( $(this).val().substring(0, 250) );
        }

        $length = $(this).val().length;
        $(this).parent().find('p').find('.text-kw').text(250-$length);
    });


});

