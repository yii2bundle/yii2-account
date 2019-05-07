
api = {

    baseUrl: null,

    setBaseUrl: function (baseUrl) {
        this.baseUrl = baseUrl;
    },

    sendRequest: function (request) {
        request.url = this.baseUrl + '/' + request.url;
        if(auth.identity != null) {
            request.headers = {};
            request.headers.Authorization = auth.identity.token;
        }
        $.ajax(request);
    },

};
