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
                    App.showNoty('cannot load system model', 'error');

                    console.log(exc);
                }
            }
        );
    },

    update: function() {
        $.post(
            'index.php',
            {'class':'system', 'params': this.toJSON(), 'task':'update_options'},
            function(data) {
                if($.trim(data)) {
                    App.showNoty('cannot update system model', 'error');

                    console.log(data);
                }
            }
        );
    }
})

window.SystemModel = new SystemModel;
