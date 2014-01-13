var UserView = Backbone.View.extend({

    el : $(document),

    events : {
        'click .user-edit-btn'      : 'open_edit',
        'click .view-user-btn'      : 'view_user',
        'click .create-user-btn'    : 'create_user'
    },

    initialize: function() {
        App.elementLoad('#user-tabs', function(){
            $('#user-tabs').tabs()
        })
    },

    open_edit: function() {
        alert(1);
    },

    view_user: function(e) {
        var id = $(e.target).data('params');

        App.makeModal('index.php?class=user&controller=users&task=view&params='+id);
    },

    create_user: function() {
        App.makeModal('index.php?class=user&controller=users&task=create');
    }
})

window.UserView = new UserView;
