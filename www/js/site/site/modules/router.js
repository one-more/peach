App.Router = Backbone.Router.extend({
    routes: {
        ''          : 'actionIndex',
        '*NotFound' : 'actionPage'
    },

    actionIndex: function(){

        $(document).find('*[data-position]').html('');

        App.modelLoad(SystemModel, function(){
            if(SystemModel.get('menu') != -1) {
                $.post('index.php', {'class': SystemModel.get('menu') ,'method':'get_page'}, function(data) {
                    try {
                        var json = (typeof data == 'object')? data : $.parseJSON(data);

                        $.each(json, function(k,v) {
                            $('*[data-position='+ v.position+']').load('index.php',
                                {'class': v.extension, 'controller': v.controller, 'params': v.params})
                        })
                    }
                    catch(exception) {
                        App.showNoty('error load page', 'error');
                        console.log(exception);
                    }
                })
            }
        })
    },

    actionPage: function() {

        $(document).find('*[data-position]').html('');

        if(SystemModel.get('menu') != -1) {
            var url = Backbone.history.fragment.split('/');

            var page = url.shift();

            var params = [];

            params['url']       = page;
            params['options']   = url;

            $.post('index.php', {'class': SystemModel.get('menu') ,'method':'get_page', 'params':params},
                function(data) {
                    try {
                        var json = $.parseJSON(data);

                        $.each(json, function(k,v) {
                            $('*[data-position='+ v.position+']').load('index.php',
                                {'class': v.extension, 'controller': v.controller, 'params': v.params})
                        })
                    }
                    catch(exception) {
                        App.showNoty('error load page', 'error');
                        console.log(exception);
                    }
            })
        }
    }
})