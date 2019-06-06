;(function($) {

    /**
     * @author  <github.com/tarampampam>
     * @weblog  http://blog.kplus.pro/
     * @project https://github.com/tarampampam/jquery.textmistake
     *
     * @version 0.1
     *
     * @licensy Licensed under the MIT, license text: http://goo.gl/JsVjCF
     */

    $.fn.textmistake = function(options) {
        // Default settings

        var defaults = {
                'l10n': {
                    'title': 'Сообщить об опечатке:',
                    'urlHint': 'Адрес страницы с ошибкой:',
                    'errTextHint': 'Текст с ошибкой:',
                    'yourComment': 'Ваш комментарий или корректная версия:',
                    'userComment': 'Комментарий от пользователя:',
                    'commentPlaceholder': 'Введите комментарий',
                    'cancel': 'Отмена',
                    'send': 'Отправить',
                    'mailSubject': 'Ошибка в тексте на сайте ON!',
                    'mailTitle': 'Ошибка в тексте на сайте',
                    'mailSended': 'Уведомление отправлено',
                    'mailSendedDesc': 'Ваше уведомление успешно отправлено. Спасибо!',
                    'mailNotSended': 'Ошибка при отправке',
                    'mailNotSendedDesc': 'Увы, но ваше сообщение не было отправлено. Извините что так получилось.',
                },
                'debug': true, // fet 'false' if all tested and works fine
                'initCss': true,
                'initHtml': true,

                'overlayColor': '#666',
                'overlayOpacity': 0.5,
                'windowZindex': 10001,
                'hideBodyScroll': true,

                'textLimit': 400,
                'contextLength': 40,
                'closeOnEsc': true,

                'mailTo': 'malinka8.88@mail.ru',
                'mailFrom': 'mistake@'+window.location.hostname,

                'mandrillKey': '',
                'sendmailUrl': '/js/mistake.php',

                'animateSpeed': 0,
                'autocloseTime': 10000,

                // Callbacks
                'onShow': function(state){},
                'onHide': function(state){},
                'onLoadingShow': function(state){},
                'onLoadingHide': function(state){},
                'onCtrlEnter': function(){},
                'onEscPressed': function(){},
                'onSendMail': function(response){},
                'onAjaxDone': function(response){},
                'onAjaxResultError': function(response){},
                'onAjaxSendError': function(response){},
            },
            // Apply user settings to defaults
            settings = $.extend(true, defaults, options),
            log = function(text) {
                if(settings.debug) {
                    var now = new Date().toTimeString().replace(/.*(\d{2}:\d{2}:\d{2}).*/, "$1");
                    return console.log('[' + now + '] ' + text);
                }
            },
            html = $('html').first(),
            head = $('head').first(),
            body = $('body').first();

        // Add styles to head
        /*if(settings.initCss && (head.find('#mt_s').length === 0)) head.append('<style id="mt_s" type="text/css">\

        </style>');*/

        // Add html to body end
        if(settings.initHtml && (body.find('#mt_c').length === 0)) body.append('<div id="mt_c">'+
            '<div class="loading"><div class="spinner"></div><div class="overlay"></div></div>'+
            '<div class="close mt_cl">&times;</div>'+
            '<div class="title feedback"><h4>'+settings.l10n.title+'</h4></div>'+
            '<p class="msg"></p>'+
            '<p class="nowrap"><b>'+settings.l10n.urlHint+'</b><br><span class="url"></span></p>'+
            '<p class="nopadding"><b>'+settings.l10n.errTextHint+'</b></p>'+
            '<span class="block"></span>'+
            '<p>'+settings.l10n.yourComment+'</p>'+
            '<textarea class="form-control" maxlength="256" placeholder="'+settings.l10n.commentPlaceholder+'" /></textarea></p>'+
            '<div class="buttons text-center">'+
            '<a href="#" class="mt_snd btn btn-default">'+settings.l10n.send+'</a>'+
            '</div>'+
            '</div><div id="mt_o"></div>');

        var overlay = body.find('#mt_o'),
            content = body.find('#mt_c'),
            loading = content.find('div.loading').first(),
            title = content.find('div.title').first(),
            message = content.find('p.msg').first(),
            url = content.find('span.url').first(),
            textdata = content.find('span.block').first(),
            comment = content.find('textarea').first(),
            close = content.find('.mt_cl'),
            send = content.find('.mt_snd'),

            autocloseTimer = null,

            // Get selected text
            getSelectionText = function() {
                var text = '';
                if (window.getSelection) {
                    text = window.getSelection().toString();
                } else if (document.selection && document.selection.type != 'Control') {
                    text = document.selection.createRange().text;
                }
                return text;
            },

            // Get all unselected text (return {before:N1,after:N2})
            // http://stackoverflow.com/a/9000719
            getUnselectedText = function(e){var t,n,o,a="",r="";return"undefined"!=typeof window.getSelection?(t=window.getSelection(),t.rangeCount?n=t.getRangeAt(0):(n=document.createRange(),n.collapse(!0)),o=document.createRange(),o.selectNodeContents(e),o.setEnd(n.startContainer,n.startOffset),a=o.toString(),o.selectNodeContents(e),o.setStart(n.endContainer,n.endOffset),r=o.toString()):(t=document.selection)&&"Control"!=t.type&&(n=t.createRange(),o=document.body.createTextRange(),o.moveToElementText(e),o.setEndPoint("EndToStart",n),a=o.text,o.moveToElementText(e),o.setEndPoint("StartToEnd",n),r=o.text),{before:a,after:r}},
            // Make string escape (html chars)
            // http://stackoverflow.com/a/12034334
            escapeHtml = function(string) {
                var entityMap = {"&": "&amp;","<": "&lt;",">": "&gt;",'"': '&quot;',"'": '&#39;',"/": '&#x2F;'};
                return String(string).replace(/[&<>"'\/]/g, function (s) {
                    return entityMap[s];
                });
            },

            // Clear string from any 'invalid' chars and empty spaces
            clearString = function(s) {
                return s.replace(/\s+/g, ' ').replace(/[^a-zа-яё0-9\.\,\ \_\-\(\)\[\]\{\}\`\~\@\#\$\%\^\:\*]/gi, '');
            },

            // Move window to screen center
            centerWindow = function(){
                content.css({
                    'margin-top' : -(content.height()/2 + parseInt(content.css('padding-top'))),
                    'margin-left' : -((content.width()/2) + parseInt(content.css('padding-left')))
                });
            },

            // Show loading splash
            showLoading = function(visible){
                if(typeof visible === 'boolean')
                    if(visible) {
                        if($.isFunction(settings.onLoadingShow)) settings.onLoadingShow(visible); // callback
                        loading.show().find('*').show(); // show 'loading' container
                    } else {
                        if($.isFunction(settings.onLoadingHide)) settings.onLoadingHide(visible); // callback
                        loading.hide(); // hide 'loading'
                    }
            },

            // Show mistake window
            showWindow = function(visible) {
                if(typeof visible === 'boolean')
                    if(visible) { // If we need to show
                        if($.isFunction(settings.onShow)) settings.onShow(visible); // callback
                        if(settings.hideBodyScroll) html.addClass('mistake-open'); // hide body scroll
                        content.find('*').show(); // show all inside objects
                        title.removeClass().addClass('title feedback'); // setup default title classes
                        message.html('').hide(); // reset and hide 'msg' container
                        loading.hide(); // hide 'loading' container
                        overlay.show(settings.animateSpeed); // show overlay
                        centerWindow(); // center message window (text must be setted before this function call)
                        content.show(settings.animateSpeed); // and show window
                        return true;
                    } else {
                        if($.isFunction(settings.onHide)) settings.onHide(visible); // callback
                        if(settings.hideBodyScroll) html.removeClass("mistake-open");
                        content.hide(settings.animateSpeed); // hide window
                        overlay.hide(settings.animateSpeed); // hide overlay
                        return false;
                    }
                return null;
            },

            // Get mistake window state
            windowIsOpen = function() {
                return (overlay.is(':visible') && content.is(':visible'));
            },

            // Modify window for message output, and show this shit
            showMessage = function(caption, text, cssclass){
                if(!overlay.is(':visible')) overlay.show(); // show overlay if needed
                content.find('*').hide(); // hide all inside window
                showLoading(false); // hide loading screen (heed this line for callback)
                content.find('div.close').show(); // show close cross
                title.show().find('h1').html(caption).show(); // setup and show title
                if(cssclass) title.removeClass().addClass('title '+cssclass); // setup title class
                message.html(text).show(); // setup and show message
                centerWindow(); // center window

                if(settings.autocloseTime > 0) {
                    clearInterval(autocloseTimer);
                    autocloseTimer = setInterval(function() {
                        showWindow(false);
                        clearInterval(autocloseTimer);
                    }, settings.autocloseTime);
                }

                if(!content.is(':visible')) content.show(); // show content if needed
            },

            // Make email addr validation
            // http://stackoverflow.com/a/46181/2252921
            validEmail = function(addr) {
                return /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(addr);
            },

            // Send main function
            // ------------------
            sendMail = function(){
                if(!validEmail(settings.mailTo))
                    return log('Email "'+settings.mailTo+'" is not valid');

                if(!validEmail(settings.mailFrom))
                    return log('Declare valid "Mail From" address');

                var mailBody = '<html lang="ru">' +
                    '<head>' +
                    '<meta charset="utf-8" />' +
                    '</head>' +
                    '<body>'+
                    '<div style="max-width: 1000px;min-width:600px;margin: 0 auto;font-family:Helvetica, serif;font-size:16px;\
                +border: 1px solid rgba(153, 153, 153, 0.24);color: rgb(51, 51, 51); -webkit-box-shadow: 0 0 5px 0 rgba(50, 50, 50, 0.75); \
                +-moz-box-shadow:0 0 5px 0 rgba(50, 50, 50, 0.75);box-shadow:0 0 5px 0 rgba(50, 50, 50, 0.75);">'+
                    '<div style="background: rgb(245, 245, 245);padding: 25px;position:relative;min-height: 40px;">'+
                    '<div style="width: 33.33333%; float: left;"> ' +
                    '<span style="margin-top: 10px; font-size: 120%; display: block;">'+'Интернет журнал'+
                    '</span></div>'+
                    '<div style="width: 33.33333%;float: left;">'+
                    '<a title="Перейти на главную" href="'+location.protocol+'//'+location.hostname+'">'+
                    '<img style="width: 70%; margin: -31px auto; display: block;" src="'+location.protocol+'//'+location.hostname+'/img/ON.png" alt="" >'+
                    '</a></div>'+
                    '<div style="width: 33.33333%; float: left;">'+
                    '<a title="FaceBook" href="'+location.protocol+'//'+location.hostname+'/facebook">'+
                    '<img style="float: right;margin-right: 15px;width: 12%;" src="'+location.protocol+'//'+location.hostname+'/img/fb.png" alt="" >'+
                    '</a> <a title="Вконтакте" href="'+location.protocol+'//'+location.hostname+'/vkontakte">'+
                    '<img style="float: right;margin-right: 15px;width: 12%;" src="'+location.protocol+'//'+location.hostname+'/img/vk.png" alt="" >'+
                    '</a> <a title="Twitter" href="'+location.protocol+'//'+location.hostname+'/twitter">'+
                    '<img style="float: right;margin-right: 15px;width: 12%;" src="'+location.protocol+'//'+location.hostname+'/img/tw.png" alt="" >'+
                    '</a> </div> </div> <div id="body" style="padding: 25px">'+
                    '<h4>Здравствуйте!</h4>'+
                    '<style scoped>a {color: #fa5c17;text-decoration: none;} a:hover {color: #666666;} </style>'+
                    '<h4>'+settings.l10n.mailTitle+'</h4>'+"\n"+
                    '<p>'+settings.l10n.urlHint+' <a href="'+url.text()+'" target="_blank">'+url.text()+'</a></p>'+"\n\n"+
                    '<p>'+settings.l10n.errTextHint+'</p>'+"\n"+
                    '<span>'+textdata.html()+'</span>'+"\n\n";
                if(comment.val())
                    mailBody += '<p>'+settings.l10n.userComment+'<br />'+"\n"+'<em>'+comment.val()+'</em></p>';
                mailBody += '</div> ' +
                    '<div id="footer" style="background: #f5f5f5; font-size: 90%; padding: 25px; position:relative;min-height: 50px">'+
                    '<p style="font-size:12px; text-align:center; font-style:italic; color: #999; margin-top: 10px;">'+
                    'Данное сообщение отправлено автоматически на Ваш адрес так как кто-то сообщил об ошибке на сайте ON!.</p>'+
                    '</div> </div></body></html>';

                var apiUrl = '',
                    mailData = {
                        'key': '',
                        'message': {
                            'from_email': settings.mailFrom,
                            'to': [{'email': settings.mailTo, 'type': 'to'}],
                            'autotext': 'true',
                            'subject': clearString(settings.l10n.mailSubject),
                            'html': mailBody
                        }
                    };

                // I think api key length forever eq. 22
                if(settings.sendmailUrl.length > 0){
                    mailData.key = settings.mandrillKey;
                    apiUrl = settings.sendmailUrl;
                }

                // I think api key length forever eq. 22
                if(settings.mandrillKey && settings.mandrillKey.length == 22){
                    mailData.key = settings.mandrillKey;
                    // Docs - https://mandrillapp.com/api/docs/messages.JSON.html#method=send
                    apiUrl = 'https://mandrillapp.com/api/1.0/messages/send.json';
                }

                if(apiUrl.length == 0) {
                    showMessage('Wrong settings', 'Check plugin settings', 'fire');
                    return false;
                }

                $.ajax({
                    type: 'POST',
                    url: apiUrl,
                    data: mailData
                }).done(function(response) {
                    if($.isFunction(settings.onAjaxDone)) settings.onAjaxDone(response); // callback
                    if(((typeof response[0] !== 'undefined') && (response[0].status === 'sent'))
                        || ((typeof response.code !== 'undefined') && (response.code == 1))) {
                        if($.isFunction(settings.onSendMail)) settings.onSendMail(response); // callback
                        showMessage(settings.l10n.mailSended, settings.l10n.mailSendedDesc, 'star');
                    } else {
                        if($.isFunction(settings.onAjaxResultError)) settings.onAjaxResultError(response); // callback
                        showMessage(settings.l10n.mailNotSended, settings.l10n.mailNotSendedDesc, 'fire');
                        log('Request was sended, but server answer is not valid');
                    }
                }).error(function(response){
                    if($.isFunction(settings.onAjaxSendError)) settings.onAjaxSendError(response); // callback
                    showMessage(settings.l10n.mailNotSended, settings.l10n.mailNotSendedDesc, 'fire');
                    log('Ajax request error with status "'+response.status+'"');
                });

                showLoading(true);
            };

        // Event on objects with close .class
        close.on('click', function(){
            showWindow(false);
        });

        // Event on objects with send .class
        send.on('click', function(){
            sendMail();
        });

        // Event on Ctrl + Enter
        body.keydown(function (e) {
            if (e.ctrlKey && e.keyCode == 13) {
                if($.isFunction(settings.onCtrlEnter)) settings.onCtrlEnter(); // callback
                var unselected = getUnselectedText(document.body),
                    atStart = clearString(unselected.before.slice(-settings.contextLength)),
                    atEnd = clearString(unselected.after.slice(0, settings.contextLength)),
                    selectedText = escapeHtml(getSelectionText()).replace(/(\r\n|\n|\r)/gm, ' ');
                if(selectedText.length < 1)
                    return false;
                if(selectedText.length > settings.textLimit) {
                    log('Too many text');
                    return false;
                }

                comment.val(''); // clear comment input

                url.text(window.location.href);
                textdata.html('&hellip;' + atStart + '<strong>' + selectedText + '</strong>' + atEnd + '&hellip;');

                showWindow(true);
            }
        });

        // event on ESC key pressed
        body.keyup(function(e) {
            if (settings.closeOnEsc && windowIsOpen() && e.keyCode == 27) {
                if($.isFunction(settings.onEscPressed)) settings.onEscPressed(); // callback
                showWindow(false);
            }
        });
    };

})(jQuery);
