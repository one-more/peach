window.AuthView = Backbone.View.extend({
    el: '#all',

    initialize: function() {

        App.on('document:ready', function(){
            App.goto('/admin');
        })

        Form.add_error_handler('auth-form', function(data) {

            App.showNoty(data.error.message, 'error');
        })

        Form.add_success_handler('auth-form', function(data) {
            if(typeof data == 'object') {
                App.loadPage(data.url);
            }
            else {
                var msg = LangModel.get('request_error') ||
                    'request error';
                App.showNoty(msg, 'error');
            }
        })
    }
});

window.AuthView = new AuthView();
