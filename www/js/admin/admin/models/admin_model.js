var AdminModel = Backbone.Model.extend({
    initialize: function() {
        $.post('index.php', {'class':'admin', 'task':'get_options', 'ajax':'1'},
        function(data) {
            try {
                var json =  (typeof data == 'object') ? data : $.parseJSON(data);

                AdminModel.set(json);
            }
            catch(exception) {
                App.showNoty('cannot load admin model', 'error');
                console.log(exception);
            }
        })
    },

    update: function() {
        this.initialize();
    }
})

window.AdminModel = new AdminModel;
