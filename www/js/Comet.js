App.module('Comet', function(Comet){
    Comet.initialize = function() {
        Comet.listen();
    };

    Comet.listen = function() {
        $.ajax({
            'url'       : '/comet-daemon.php',
            'type'      : 'post',
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
                console.log('comet error');
                console.log(text);
                console.log(thrown);
                var msg = LangModel.get('comet_request_err') ||
                    'request to comet daemon returned an error';
                App.showNoty(msg, 'error');
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
})
