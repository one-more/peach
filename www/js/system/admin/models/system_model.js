var SystemModel = Backbone.Model.extend({
    initialize: function() {
        $.post(
            'index.php',
            {'ajax':1, 'old_url':'/admin', 'class':'system', 'task':'get_options'},
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

        App.on('module:installed module:deleted', function(){
            SystemModel.initialize();
        })
    },

    update: function() {
        $.post(
            'index.php',
            {'class':'system', 'params': this.toJSON(), 'task':'update_options'},
            function(data) {
                if($.trim(data)) {
                    var msg = LangModel.get('update_system_model') ||
                        'cannot update system model';
                    App.showNoty(msg, 'error');

                    console.log(data);
                }
            }
        );
    }
})

window.SystemModel = new SystemModel;
