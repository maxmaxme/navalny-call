$(function () {
    var access_token = getCookie('access_token') || '';
    if (checkToken(access_token))
        newCall();
    else
        initAuth();
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

function initAuth() {
    $container.html(Mustache.render(mustacheTemplates.auth, {}));
}

function authAction() {
    var $form = $('#authForm'),
        $button = $('.btn', $form),
        login = $('#login', $form).val(),
        password = $('#password', $form).val();

    if (login && password) {
        $button.attr('disabled', true);
        api('auth', {
            login: login,
            password: password
        }, function (result) {

            access_token = result['hash'];

            setCookie('access_token', access_token, {
                expires: 100 * 24 * 3600, // 100 дней
                path: '/'
            });
            newCall();
        });
        $button.attr('disabled', false);
    }

}

function checkToken(token) {
    var ok = false;

    $.ajax({
        url: host + 'API/auth.checkToken?token=' + token,
        type: 'get',
        dataType: 'json',
        async: false,
        success: function(data) {
            if(data['success'] === true)
                ok = true;
        }
    });

    return ok;
}