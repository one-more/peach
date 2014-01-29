window.UserWidgetView = Backbone.View.extend({
    el: $(document),

    events: {
        'click .user-widget-change-avatar-btn' : 'change_widget_avatar'
    },

    change_widget_avatar: function(e){
        var input = $('<input>', {
            'type' : 'file'
        });

        var data = $(e.target).data('params');

        input.on('change', function(e){
            var file = e.target.files[0];

            if(!file.type.match('image.*')) {
                App.showNoty('selected file is not an image', 'error');
                return;
            }

            var reader = new FileReader();

            reader.onload = (function(f){
                return function(e) {
                    $('.current-user-widget img').attr('src', e.target.result);

                    $.post(
                        'index.php',
                        {
                            'class'         : 'user',
                            'method'        : 'get_widget',
                            'params'        : 'user',
                            'task'          : 'update_avatar',
                            'user-id'       : data,
                            'avatar-data'   : e.target.result
                        }
                    );
                }
            })(file)

            reader.readAsDataURL(file);

            $(this).detach();
        })

        $('body').append(input);

        input.trigger('click');
    },

    update: function() {
        $.post(
            'index.php',
            {
                'class'     : 'user',
                'method'    : 'get_widget',
                'params'    : 'user'
            },
            function(data) {
                data = data.replace(/<script src=".*"><\/script>/, '')
                    .replace(/<link rel="stylesheet" href=".*" \/>/, '');

                $('.current-user-widget').replaceWith(data);
            }
        );
    }
})

window.UserWidgetView = new UserWidgetView;
