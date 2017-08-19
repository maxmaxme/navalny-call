$(function () {
    newCall();
});

var $container = $('.call_page');

function drawButtons(result) {

    $container.html(Mustache.render(mustacheTemplates.call, {
        text: nl2br(result.text),
        buttons: result.buttons
    }));
}

function initCall() {
    api('calls.init', {}, function (result) {
        result['text'] = '<a href="tel:' + result['text'] + '">' + result['text'] + '</a>';
        drawButtons(result);
    });
}

function answer(buttonID) {
    api('calls.button', {
        buttonID: buttonID
    }, function (result) {
        drawButtons(result);
    });
}

function completeCall() {
    api('calls.complete', {}, function () {
        newCall();
    });
}

function newCall() {
    $container.html(Mustache.render(mustacheTemplates.newCall, {}));
}