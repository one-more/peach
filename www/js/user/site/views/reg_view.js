var reg_view = Backbone.View.extend({
    el: $(document),

    initialize: function() {
        Form.add_success_handler('user-reg-form', function(data) {
            if(!data.trim())
                var msg = LangModel.get('registered') || 'registration complete';
                var div = $('<div>', {
                    'class' : 'alert alert-success reg-complete-block',
                    'text'  : msg
                })
                $('#user-reg-form').replaceWith(div);

                setTimeout(function(){
                    if($('div').is('.reg-complete-block')) {
                        App.goto('/');
                    }
                }, 1500);
        })
    },

    events: {
        'click .accept-agreement-chbx'   : 'accept_agreement'
    },

    accept_agreement: function() {

        $('.text-agreement-area').slideToggle(
            'normal',
            function() {
                $('.reg-form').removeClass('hide');
            }
        );

    }
})

reg_view = new reg_view;
