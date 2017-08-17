function addScriptButton(_this) {


    var $container = $(_this).parent(),
        scriptID = $(_this).data('script-id');

    $container.html('<div class="input"><input data-script-id="' + scriptID + '"><button onclick="saveScriptBtn(this)">save</button></div>');
}

function addScript(_this) {

    var $container = $(_this).parent(),
        buttonID = $(_this).data('button-id');

    $container.html('<div class="input"><input data-button-id="' + buttonID + '"><button onclick="saveScript(this)">save</button></div>');
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


function saveScriptBtn(_this) {


    var $container = $(_this).parent(),
        $span = $container.parent(),
        $ul = $span.parent(),
        $input = $container.find('input'),
        scriptID = $input.data('script-id'),
        text = $input.val();


    if (scriptID > 0 && text !== '') {
        api('script.addButton',
            {
                'scriptID': scriptID,
                'text': text
            },
            function (result) {
                $span.html('<a><em class="marker"></em>' + text + '</a>');

                $ul.append('<ul>' +
                    '<li>' +
                    '   <span><a class="PlusBtn" onclick="addScript(this)" data-button-id="' + result.buttonID + '">+</a></span>' +
                    '</li></ul>');

                initTree();
            });
    }
}

function saveScript(_this) {


    var $container = $(_this).parent(),
        $span = $container.parent(),
        $ul = $span.parent(),
        $input = $container.find('input'),
        buttonID = $input.data('button-id'),
        text = $input.val();


    if (buttonID > 0 && text !== '') {
        api('script.add',
            {
                'buttonID': buttonID,
                'text': text
            },
            function (result) {
                $span.html('<a><em class="marker"></em>Я: ' + text + '</a>');

                $ul.append('<ul><li><span><a class="PlusBtn" onclick="addScriptButton(this)" data-script-id="' + result.scriptID + '">+</a></span></li></ul>');

                initTree();
            });
    }
}
