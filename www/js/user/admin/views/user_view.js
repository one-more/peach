var UserView = Backbone.View.extend({

    el : $(document),

    events : {
        'click .user-edit-btn'          : 'open_edit',
        'click .view-user-btn'          : 'view_user',
        'click .create-user-btn'        : 'create_user',
        'click .user-download-avatar'   : 'download_avatar'
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
    },

    download_avatar: function() {
        $('.user-avatar-loader').one('change', function(e){
            var file = e.target.files[0];

            if(!file.type.match('image.*')) {
                alert('selected file is not an image');
                return;
            }
            else {
                var reader = new FileReader;

                reader.onload = (function(f){
                    return function(e) {
                        var data = e.target.result;

                        $('.user-avatar-img').attr('src', data);

                        $('input[name="avatar"]').val(data);
                    }
                })(file)

                reader.readAsDataURL(file);
            }
        })

        $('.user-avatar-loader').trigger('click');
    }
})

window.UserView = new UserView;
