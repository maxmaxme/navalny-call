$(document).ready(function () {

    initTree();

});

function initTree() {
    $('#tree li a')
        .unbind('click')
        .click(function () {
            $('a.current').removeClass('current');
            var a = $(this);

            a.toggleClass('current');
            var li = $(this).closest('li');
            if (!li.next().length) {
                li.find('ul:first > li').addClass('last');
            }

            var ul = $('ul:first', $(this).closest('li'));
            if (ul.length) {
                ul.slideToggle(300);
                var em = $('em:first', this.parentNode);
                em.toggleClass('open');
            }
        });
}


function addScriptButton(_this, scriptID) {


    var $container = $(_this).parent();

    $container.html(Mustache.render(mustacheTemplates.scriptEdit, {
        id: scriptID,
        func: 'addScriptBtnAction'
    }));
}

function addScript(_this, buttonID) {

    var $container = $(_this).parent();

    $container.html(Mustache.render(mustacheTemplates.scriptEdit, {
        id: buttonID,
        func: 'addScriptAction'
    }));
}


function addScriptBtnAction(_this, scriptID) {


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
                $span.html(Mustache.render(mustacheTemplates.treeItem, {
                    text: text,
                    type: 'ScriptBtn',
                    id: result.buttonID
                }));

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

function addScriptAction(_this, buttonID) {


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
                $span.html(Mustache.render(mustacheTemplates.treeItem, {
                    text: 'Я: ' + text,
                    type: 'Script',
                    id: result.scriptID
                }));

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


function deleteScript(_this, scriptID) {
    var $ul = $(_this).closest('ul');
    api('script.deleteScript', {
        scriptID: scriptID
    }, function (result) {
        $(_this).closest('li').remove();

        $ul.append(Mustache.render(mustacheTemplates.addButton, {
            func: 'addScript',
            id: result.buttonID,
            text: '+ Добавить продолжение'
        }));
    });


}
function deleteScriptBtn(_this, buttonID) {
    api('script.deleteScriptBtn', {
        buttonID: buttonID
    }, function () {
         $(_this).closest('li').remove();
    });
}

function editScript(_this, scriptID) {
    $(_this).closest('div').html(Mustache.render(mustacheTemplates.scriptEdit, {
        id: scriptID,
        func: 'editScriptAction',
        value: $('a', $(_this).closest('div')).html().replace(/^(<em class="marker(?: open)?"><\/em>Я: )/g, '')
    }));
}
function editScriptBtn(_this, buttonID) {
    $(_this).closest('div').html(Mustache.render(mustacheTemplates.scriptEdit, {
        id: buttonID,
        func: 'editScriptBtnAction',
        value: $('a', $(_this).closest('div')).html().replace(/^(<em class="marker(?: open)?"><\/em>)/g, '')
    }));
}

function editScriptAction(_this, scriptID) {
    var text = $('input', $(_this).parent()).val();

    api('script.editScript', {
       scriptID: scriptID,
       text: text
    }, function () {

        $(_this).closest('span').html(Mustache.render(mustacheTemplates.treeItem, {
            text: 'Я: ' + text,
            type: 'Script',
            id: scriptID
        }));

        initTree();

    });
}
function editScriptBtnAction(_this, buttonID) {
    var text = $('input', $(_this).parent()).val();

    api('script.editScriptBtn', {
        buttonID: buttonID,
        text: text
    }, function () {

        $(_this).closest('span').html(Mustache.render(mustacheTemplates.treeItem, {
            text: text,
            type: 'ScriptBtn',
            id: buttonID
        }));

        initTree();

    });
}