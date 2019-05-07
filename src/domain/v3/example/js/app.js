
app = {

    onError: function(event) {
        console.log(event);
        render.showError('Не удается подключиться к сокету!');
        socket.close();
    },

    onOpen: function(event) {
        render.clear();
        console.log(event);
        var text = 'Соединение установлено! Ожидаю события!';
        text = text + '<br/><code>' + socket.getUrl() + '</code>';
        render.showMessage(text);
        render.showOpenStatus();
    },

    onClose: function(event) {
        console.log(event);
        if (event.wasClean) {
            //render.showMessage('Соединение закрыто');
            render.showCloseStatus();
        } else {
            render.showError('Обрыв соединения! Код: ' + event.code); // например, "убит" процесс сервера
            //render.showAbortStatus();
            render.showCloseStatus();
        }
        socket.close();
    },

    onMessage: function(event) {
        console.log(event);
        var data = JSON.parse(event.data);
        var text = 'Новое событие<br/> ' + '<pre>' + JSON.stringify(data, "", 4) + '</pre>';
        render.showMessage(text);
        console.log(data);
    },

    closeConnection: function() {
        socket.close();
        render.clear();
    },

    openConnection: function(url) {
        render.showProgressStatus();
        socket.open(url);
        socket.attachEventHandler('onopen', this.onOpen);
        socket.attachEventHandler('onerror', this.onError);
        socket.attachEventHandler('onclose', this.onClose);
        socket.attachEventHandler('onmessage', this.onMessage);
    },

};

forgeUrlFromForm = function() {
    var formData = form.getData();
    var port = 8000;
    var hostString = formData.url + ':' + port;
    var queryString = 'login=' + formData.login + '&password=Wwwqqq111&events=' + formData.events;
    var url = hostString + '?' + queryString;
    return url;
};

function isset( somevar ){
    return ( typeof( somevar ) != 'undefined' && somevar !== null ) ;
}