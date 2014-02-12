var SystemModel = Backbone.Model.extend({
    initialize: function() {
        $.post(
            'index.php',
            {'ajax':1, 'old_url':'/', 'class':'system', 'task':'get_options'},
            function(data) {
                try{
                    var json = (typeof data == 'object')? data : $.parseJSON(data);

                    SystemModel.set(json);
                }
                catch(exc) {
                    var msg = LangModel.get('load_system_model') ||
                        'cannot load system model';
                    App.showNoty(msg, 'error');

                    console.log(exc);
                }
            }
        );
    }
})

window.SystemModel = new SystemModel;
