App.Router = Backbone.Router.extend({
    routes: {
        '*page' : 'actionPage'
    },

    actionPage: function() {

        App.modelLoad(SystemModel, function(){
            if(SystemModel.get('menu') != -1) {
                var url = location.pathname.split('/');

                var link = location.pathname;

                var params = url[url.length-1];

                $.post(
                    'index.php',
                    {
                        'class': SystemModel.get('menu'),
                        'method':'get_page',
                        'params':link
                    },
                    function(data) {
                        try {
                            var json = (typeof data == 'object') ? data :
                                $.parseJSON(data);

                            if(json.length > 0) {

                                $('*[data-position]').each(function(){
                                    $(this).html('');
                                })


                                $.each(json, function(k,v) {
                                    params['id'] = v.id;

                                    $('*[data-position="'+ v.position+'"]')
                                        .load(
                                            'index.php',
                                            {
                                                'class'         : v.class,
                                                'controller'    : v.controller,
                                                'params'        : {
                                                    'id'        : v.id,
                                                    'params'    : params
                                                }
                                            }
                                        )
                                })
                            }
                            else {
                                App.trigger('page:not_found');
                            }

                        }
                        catch(exception) {
                            var msg = LangModel.get('load_page_err') ||
                                'error load page';

                            App.showNoty(msg, 'error');

                            console.log(exception);
                        }
                    })
            }
        })
    },

    reload: function() {
        App.router.actionPage();
    }
})