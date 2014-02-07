App.Router = Backbone.Router.extend({
    routes: {
        '*page' : 'actionPage'
    },

    actionPage: function() {

        $(document).find('*[data-position]').html('');

        App.modelLoad(SystemModel, function(){
            if(SystemModel.get('menu') != -1) {
                var url = Backbone.history.fragment.split('/');

                var link = Backbone.history.fragment;

                var params = url[url.length-1];

                $.post('index.php', {'class': SystemModel.get('menu') ,'method':'get_page', 'params':link},
                    function(data) {
                        try {
                            var json = (typeof data == 'object') ? data : $.parseJSON(data);

                            $.each(json, function(k,v) {
                                $('*[data-position='+ v.position+']').load('index.php',
                                    {'class': v.extension, 'controller': v.controller, 'params': params})
                            })
                        }
                        catch(exception) {
                            App.showNoty('error load page', 'error');

                            console.log(exception);
                        }
                    })
            }
        })
    }
})