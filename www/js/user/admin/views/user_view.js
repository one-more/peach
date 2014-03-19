var UserView = Backbone.View.extend({

    el : $(document),

    events : {
        'click .user-edit-btn'          : 'open_edit',
        'click .view-user-btn'          : 'view_user',
        'click .create-user-btn'        : 'create_user',
        'click .user-delete-btn'        : 'delete_user',
        'click .user-download-avatar'   : 'download_avatar',
        'click .user-create-layout-btn' : 'create_layout'
    },

    initialize: function() {
        App.elementLoad('#user-tabs', function(){
            $('#user-tabs').tabs()
        })
    },

    open_edit: function(e) {
        var data = $(e.target).data('params')

        App.makeModal('index.php?class=user&controller=users&task=edit&params='+data);
    },

    view_user: function(e) {
        var id = $(e.target).data('params');

        App.makeModal(
            'index.php?class=user&controller=users&task=view&params='+id
        );
    },

    create_user: function() {
        App.makeModal('index.php?class=user&controller=users&task=create');
    },

    delete_user: function(e) {
        var id = $(e.target).data('params');

        App.confirm(
            'delete user?',
            function() {
                $.post('index.php?class=user&controller=users&task=delete&params='+id);
            }
        );
    },

    update_users_table: function(){
        $.post('index.php?class=user&controller=users', {}, function(data){
            $('#users-table').replaceWith(data);
        })
    },

    update_options_page: function(){
        $.post(
            'index.php?class=user&controller=options',
            {},
            function(data) {
                $('#user-options-page-form').replaceWith(data);
            }
        );
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

                        if(data.length > 1500000) {
                            App.showNoty(
                                'maximum limit of image exceeded',
                                'error');

                            return;
                        }

                        $('.user-avatar-img').attr('src', data);

                        $('input[name="avatar[]"]').val(data);

                        var last = data.substr(0, $('input[name="avatar[]"]').
                            val().length).length
                        console.log(last);

                        var count = Math.floor(data.length / 500000);
                        var input = '';

                        for(var x = 1; x < count; x++) {
                            input = $('<input>', {
                                'type'      : 'text',
                                'name'      : 'avatar[]',
                                'value'     : data.substr(last, 500000),
                                'class'     : 'invisible',
                                'maxlength' : '1000000'
                            })

                            input.css(
                                {'position' : 'absolute', 'left' : '-8000px'}
                            )

                            $('input[name="avatar[]"]').after(input)
                                last += 500000;
                        }
                    }
                })(file)

                reader.readAsDataURL(file);
            }
        })

        $('.user-avatar-loader').trigger('click');
    },

    create_layout: function(e) {
        var params = $(e.target).data('params');

        App.makeModal('index.php?class=user&controller='+params);
    }
})

window.UserView = new UserView;
