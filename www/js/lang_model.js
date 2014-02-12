window.LangModel = Backbone.Model.extend({
    initialize: function() {
        $.post(
            'index.php',
            {
                'class'         : 'noop',
                'controller'    : 'lang',
                'task'          : 'get_model',
                'ajax'          : '1',
                'old_url'       : '/admin'
            },
            function(data) {
                try {
                    var json = (typeof data == 'object') ? data : $.parseJSON(data);

                    LangModel.set(json);
                }
                catch(exc) {
                    App.showNoty('cannot load lang model', 'error');
                    console.log(exc);
                    console.log(data);
                }
            }
        )
    }
})

window.LangModel = new LangModel();
