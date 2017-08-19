function addScriptButton(_this, scriptID) {


    var $container = $(_this).parent();

    $container.html(Mustache.render(mustacheTemplates.scriptEdit, {
        id: scriptID,
        func: 'saveScriptBtn'
    }));
}

function addScript(_this, buttonID) {

    var $container = $(_this).parent();

    $container.html(Mustache.render(mustacheTemplates.scriptEdit, {
        id: buttonID,
        func: 'saveScript'
    }));
}


function initTree() {
    $('#tree li span')
        .unbind('click')
        .click(function () {
            $('a.current').removeClass('current');
            var a = $('a:first', this.parentNode);
            a.toggleClass('current');
            var li = $(this.parentNode);
            if (!li.next().length) {
                li.find('ul:first > li').addClass('last');
            }
            var ul = $('ul:first', this.parentNode);
            if (ul.length) {
                ul.slideToggle(300);
                var em = $('em:first', this.parentNode);
                em.toggleClass('open');
            }
        });
}

$(document).ready(function () {

    $('#tree li:has("ul")').find('a:first').prepend('<em class="marker"></em>');

    initTree();


});


function saveScriptBtn(_this, scriptID) {


    var $container = $(_this).parent(),
        $span = $container.parent(),
        $ul = $span.parent(),
        $input = $container.find('input'),
        text = $input.val();


    if (scriptID > 0 && text !== '') {
        api('script.addButton',
            {
                scriptID: scriptID,
                text: text
            },
            function (result) {
                $span.html('<a><em class="marker"></em>' + text + '</a>');

                $ul.append(Mustache.render(mustacheTemplates.addButton, {
                    ul: 1,
                    func: 'addScript',
                    id: result.buttonID,
                    text: '+ Добавить продолжение'
                }));

                initTree();
            });
    }
}

function saveScript(_this, buttonID) {


    var $container = $(_this).parent(),
        $span = $container.parent(),
        $ul = $span.parent(),
        $input = $container.find('input'),
        text = $input.val();


    if (buttonID > 0 && text !== '') {
        api('script.add',
            {
                buttonID: buttonID,
                text: text
            },
            function (result) {
                $span.html('<a><em class="marker"></em>Я: ' + text + '</a>');

                $ul.append(Mustache.render(mustacheTemplates.addButton, {
                    ul: 1,
                    func: 'addScriptButton',
                    id: result.scriptID,
                    text: '+ Добавить вариант ответа'
                }));

                initTree();
            });
    }
}
