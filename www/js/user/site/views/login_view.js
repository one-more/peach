var UserLoginView = Backbone.View.extend({
    initialize: function() {
        Form.add_success_handler('user-login-form', function(data){
            switch (data.task) {
                case 'reload':
                    App.reload();
                    break;
                case 'show_noty':
                    App.showNoty(data.params, 'error');
                    break;
            }
        })
    }
})

window.UserLoginView = new UserLoginView;
