var host = '';

function api(method, params, callback) {

    $.post(host + 'API/' + method, params)
        .done(function(data) {
            if (data['success']) {
                if (callback)
                    callback(data['result']);

            } else {
                alert('Ошибка: ' + data['error']);
            }
        });

}

function createTel(str) {
    return "<a href='tel:"+str+"'>"+str+"</a>";
}

function nl2br(str) {
    return (str + '').replace(/\n/g, '<br>');
}

