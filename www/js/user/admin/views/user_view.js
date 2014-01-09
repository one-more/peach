var UserView = Backbone.View.extend({

    el : $(document),

    events : {
        'click .user-edit-btn' : 'open_edit'
    },

    initialize: function() {
        App.elementLoad('#user-tabs', function(){
            $('#user-tabs').tabs()
        })
    },

    open_edit: function() {
        alert(1);
    }
})

window.UserView = new UserView;
