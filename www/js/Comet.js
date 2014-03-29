App.module('Comet', function(Comet){
    Comet.initialize = function() {
        if(App.get_mode() == 'admin') {
            Comet.listen();
        }
    };

    Comet.listen = function() {
        $.ajax({
            'url'       : '/comet-daemon.php',
            'type'      : 'post',
            cache       : 'false',
            success     : function(data) {
                data = (typeof data == 'object')? data : $.parseJSON(data);

                switch (data.task) {
                    case 'reload':
                        Comet.listen();
                        break;
                    case 'handle':
                        Comet.handle(data.msgs);
                        Comet.listen();
                        break;
                    default:
                        Comet.listen();
                        break;
                }
            },
            error       : function(xhr, text, thrown) {
                //todo - bug
                if(xhr.status != 404) {
                    console.log('comet error');
                    console.log(text);
                    console.log(xhr);
                    console.log(thrown);
                    var msg = LangModel.get('comet_request_err') ||
                        'request to comet daemon returned an error';
                    App.showNoty(msg, 'error');
                }
                else {
                    console.log('comet error 404');
                    console.log(xhr);
                    console.log(text);
                    console.log(thrown);
                }
            }
        })
    };

    Comet.handle = function(msgs) {
        try{
            $.each(msgs, function(k,v){
                switch (v.task) {
                    case 'delegate':
                        try{
                            window[v.object][v.method].apply(v.object, v.params);
                        }
                        catch(exc){
                            console.log(exc);
                        }
                        break;
                }
            })
        }
        catch(exception) {
            var msg = LangModel.get('comet_handler_exc') ||
                'comet handler has thrown an exception';
            App.showNoty(msg, 'error');
            console.log(exception);
            console.log(msgs);
        }
    }

    window.Comet = Comet;
})
