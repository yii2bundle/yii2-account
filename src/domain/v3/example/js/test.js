

/*event = {

    handlers: [],

    attachHandler: function (eventName, handler) {
        if(this.handlers[eventName] == null) {
            this.handlers[eventName] = [];
        }
        this.handlers[eventName] = handler;
        console.log('Прикреплен обработчик события ' + eventName + '!');
    },

    trigger: function (eventName, params) {
        var handlers = this.handlers[eventName];
        for(var i in handlers) {
            var handler = handlers[i];
            handler.run();
        }
        //this.handlers[eventName][] = handler;
        console.log('Обработано событие ' + eventName + '!');
    },

};*/

autotest = {

    connection: null,

    runByToken: function (login, password) {
        render.clear();

        var promise = auth.login(form.getValueByName('login'), 'Wwwqqq111');
        promise.then(function(identity){
            console.log(identity.token);
            //render.showMessage('Токен получен!');

            var url = form.getWsBaseUrl();
            url = url + '/?authorization=' + identity.token;

            socket.open(url);
            socket.attachEventHandler('onmessage', autotest.onmessage);

            //render.showMessage('Сокет открыт (по токену)!');

            auth.login('tester2', 'Wwwqqq111').then(function(identity){
                //render.showMessage('Аутентификация пройдена успешно!');
                autotest.sendMail("tester1@yuwert.kz", "test subject", "test content");
            }).catch(function(data){
                //обработка ошибки
            });

        });
        promise.catch(function(data){
            //обработка ошибки
        });
    },

    run: function (login, password) {

        //this.runByToken(login, password); return;

        render.clear();

        var url = form.getWsUrl();
        socket.open(url);
        socket.attachEventHandler('onmessage', autotest.onmessage);

        var request = {
            url: "auth",
            type: "POST",
            data: {
                login: login,
                password: password,
            },
            success: function(data) {
                auth.identity = data;
                //render.showMessage('Аутентификация пройдена успешно!');
                autotest.sendMail("tester1@yuwert.kz", "test subject", "test content");
            },
        };
        api.sendRequest(request);
    },

    onmessage: function (event) {
        //console.log('gggggggggggggggggggggggggggg');
        var actual = JSON.parse(event.data);
        var isSucces =
            actual.data.from == "tester2@yuwert.kz" &&
            actual.data.subject == "test subject" &&
            actual.data.short_content == "test content";
        console.log(actual);
        if (isSucces) {
            render.showMessage('Тест успешно пройден!');
            //render.showData(actual);
        } else {
            render.showError('Тест не пройден!');
        }
        socket.close();
    },

    sendMail: function (to, subject, content) {
        var request = {
            url: "mail",
            type: "POST",
            data: {
                to: to,
                subject: subject,
                content: content,
            },
            success: function(data) {
                console.log(data);
            },
            error: function(data) {
                render.showError('Не удалось отправить письмо!');
            },
        };
        api.sendRequest(request);
        //render.showMessage('Писмо отправлено успешно!');
    },

}
