
auth = {

    identity: null,

    login: function (login, password, promise) {
        /*var request = {
            url: "auth",
            type: "POST",
            data: {
                login: login,
                password: password,
            },
            success: function(data) {
                //promise
                //auth.identity = data;
                //auth.sendMail("tester1@yuwert.kz", "test subject", "test content");
            },
        };
        api.sendRequest(request);*/

        var promise = new Promise(function(resolve,reject){

            var request = {
                url: "auth",
                type: "POST",
                data: {
                    login: login,
                    password: password,
                },
                success: function(data) {
                    auth.identity = data;
                    resolve(data);
                },
                error: function(data) {
                    reject(data);
                },
            };
            api.sendRequest(request);
        });

        return promise;

        /*promise.then(function(response){
            // обработка результата
        });
        promise.catch(function(error){
            //обработка ошибки
        })*/


    },

    logout: function () {
        auth.identity = null;
    },

};
