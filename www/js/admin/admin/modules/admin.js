var Admin = {}

_.extend(Admin, Backbone.Events, {
    registerEvents: function() {
        Admin.on('options:changed', function() {
            AdminModel.update();
        })
    }
})