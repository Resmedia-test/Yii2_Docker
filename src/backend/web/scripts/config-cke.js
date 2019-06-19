/**
 * Created by Resmedia on 28.08.16.
 */
/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license, 'spellchecker'
 */
CKEDITOR.config.coreStyles_superscript = {
    element: 'span',
    attributes: {'class': 'Superscript'},
    overrides: 'sup'
};

CKEDITOR.editorConfig = function (config) {

    config.uiColor = '#f2f2f2';
    config.removeButtons = 'Save,Smiley,NewPage,Language,Templates,Print,Subscript,Smiley,SpecialChar,Superscript,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField';
    config.scayt_autoStartup = false;
    config.format_tags = 'h1;h2;h3;pre';
    config.enterMode = CKEDITOR.ENTER_BR;
    config.shiftEnterMode = CKEDITOR.ENTER_BR;
    config.colorButton_colors = '#FFFFFF #999999';

    config.toolbarCanCollapse = true;

    config.toolbarGroups = [
        {name: 'editing', groups: ['find', 'selection']},
        {name: 'tools'},
        {name: 'clipboard', groups: ['clipboard', 'undo']},
        {name: 'links'},
        {name: 'insert'},
        {name: 'forms'},
        {name: 'document', groups: ['mode', 'document', 'doctools']},
        {name: 'others'},
        {name: 'basicstyles', groups: ['basicstyles', 'cleanup']},
        {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi']},
        {name: 'styles'},
        {name: 'colors'}
        //{ name: 'about' }

    ];

    $(document).ready(function () {
        CKEDITOR.on('instanceReady', function (ev) {
            ev.editor.on('paste', function (evt) {
                evt.data.dataValue = evt.data.dataValue.replace(/&nbsp;/g, ' ');
                evt.data.dataValue = evt.data.dataValue.replace(/<p><\/p>/g, ' ');
                console.log(evt.data.dataValue);
            }, null, null, 9);
        });
    });

};

