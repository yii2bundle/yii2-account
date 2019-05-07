
form = {

    getData() {
        var data = {};
        data.url = this.getValueByName('url');
        data.login = this.getValueByName('login');
        data.events = this.getValueByName('events');
        return data;
    },

    getValueByName(name) {
        return $("#" + name).val();
    },

    getWsBaseUrl: function() {
        var formData = form.getData();
        var port = 8000;
        var hostString = formData.url + ':' + port;
        return hostString;

    },

    getWsUrl: function() {
        var formData = form.getData();
        var hostString = this.getWsBaseUrl();
        var queryString = 'login=' + formData.login + '&password=Wwwqqq111&events=' + formData.events;
        var url = hostString + '?' + queryString;
        return url;
    },

};
