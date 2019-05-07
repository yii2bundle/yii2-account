

socket = {

    connection: null,
    url: null,
    handlers: {},

    getUrl: function () {
        return this.url;
    },

    open: function (url) {
        this.url = url;
        this.close();
        this.connection = new WebSocket(url);

        this.connection.onopen = this.onopen;
        this.connection.onerror = this.onerror;
        this.connection.onclose = this.onclose;
        this.connection.onmessage = this.onmessage;

        console.log('Сокет открыт! URL - ' + url);
    },

    close: function () {
        if(this.connection !== null) {
            //socket.trigger('onclose', 'event');
            this.connection.close();
            //delete this.connection;
            this.connection = null;
            this.handlers = {};
            console.log('Сокет закрыт!');
        } else {
            console.log('Сокет уже был закрыт ранее!');
        }
    },

    attachEventHandler: function (name, handler) {
        if(!isset(this.handlers[name])) {
            this.handlers[name] = [];
        }
        this.handlers[name].push(handler);
        //this.connection[name] = handler;
        console.log('Прикреплен обработчик события!');
    },

    trigger: function (name, event) {
        var handlers = this.handlers[name];
        for(var i in handlers) {
            var handler = handlers[i];
            handler(event);
            console.log(handler);
        }

        //this.connection[name] = handler;
        console.log('Вызван обработчик события!');
    },

    onopen: function (event) {
        socket.trigger('onopen', event);
    },

    onerror: function (event) {
        socket.trigger('onerror', event);
    },

    onclose: function (event) {
        socket.trigger('onclose', event);
    },

    onmessage: function (event) {
        socket.trigger('onmessage', event);
    },

};






