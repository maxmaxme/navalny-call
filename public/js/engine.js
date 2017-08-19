var host = '/',
    access_token;

function api(method, params, callback, fullCustomCallback) {

    params['access_token'] = access_token;

    $.post(host + 'API/' + method, params)
        .done(function(data) {
            if (fullCustomCallback) {
                callback(data)
            } else {
                if (data['success']) {
                    if (callback)
                        callback(data['result']);

                } else {
                    alert('Ошибка: ' + data['error']);
                }
            }
        });

}

function createTel(str) {
    return "<a href='tel:"+str+"'>"+str+"</a>";
}

function nl2br(str) {
    return (str + '').replace(/\n/g, '<br>');
}


function getCookie(name) {
    var matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}

function setCookie(name, value, options) {
    options = options || {};

    var expires = options.expires;

    if (typeof expires == "number" && expires) {
        var d = new Date();
        d.setTime(d.getTime() + expires * 1000);
        expires = options.expires = d;
    }
    if (expires && expires.toUTCString) {
        options.expires = expires.toUTCString();
    }

    value = encodeURIComponent(value);

    var updatedCookie = name + "=" + value;

    for (var propName in options) {
        updatedCookie += "; " + propName;
        var propValue = options[propName];
        if (propValue !== true) {
            updatedCookie += "=" + propValue;
        }
    }

    document.cookie = updatedCookie;
}

function deleteCookie(name) {
    setCookie(name, "", {
        expires: -1
    })
}