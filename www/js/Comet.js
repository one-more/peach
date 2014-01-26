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
                App.showNoty('request to comet daemon returned an error', 'error');
            }
        })
    };

    Comet.handle = function(msgs) {
        try{
            $.each(msgs, function(k,v){
                switch (v.task) {
                    case 'delegate':
                        window[v.object][v.method].apply(v.object, v.params);
                        break;
                }
            })
        }
        catch(exception) {
            App.showNoty('comet handler exception', 'error');
            console.log(exception);
            console.log(msgs);
        }
    }
})
