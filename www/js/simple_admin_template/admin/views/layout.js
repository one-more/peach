window.Layout = Backbone.View.extend({
    el: $(document),

    initialize: function() {
        Form.add_success_handler('exit-form', function(data) {
            if(data.trim()) {
                var msg = LangModel.get('request_error') ||
                    'request error';
                App.showNoty(msg, 'error');
            }
            else {
                App.loadPage('/admin');
            }
        });

        App.elementLoad('.gridster', function(){
            var width = $(window).width();

            //todo - compute better size
            var size = Math.floor((width / 5) - (width*0.018));

            Layout.gridster = $('.gridster ul').gridster({
                widget_margins: [10,10],
                widget_base_dimensions: [size, size],
                draggable: {
                    stop: function() {
                        $.post('index.php?class=simple_admin_template&task=update_grid',
                            {'params':gridster.serialize()})
                    }
                }
            }).data('gridster');
        })

        App.on('module:installed module:deleted', function(){
            $.post(
                'index.php',
                {
                    'class'         : 'simple_admin_template',
                    'controller'    : 'default',
                    'task'          : 'get_menu'
                },
                function(data) {
                    $('.dropdown-menu').html(data);
                }
            )
        })

        App.on('language:added', function(){
            $.post(
                'index.php',
                {
                    'class'     : 'simple_admin_template',
                    'task'      : 'get_lang_select'
                },
                function(data) {
                    $('.sat-select-lang-select').replaceWith(data);
                }
            )
        })
    },

    events: {
        'change .select-start-extension'    : 'select_start_extension',
        'change .sat-select-lang-select'    : 'select_lang'
    },

    select_start_extension: function(e) {
        var params = $(e.target).val()

        $.post('index.php?class=simple_admin_template&controller=options&task=save',
            {
                'params' : {'start_extension' : params}
            }
        );
    },

    select_lang: function(e) {
        var el = $(e.target);

        var val = el.val();

        var date = new Date();

        var y = date.getFullYear();

        date.setFullYear(y+100);

        App.setCookie('admin_lang', val, {expires: date, path: '/'});

        App.trigger('language:selected');
    }
})

window.Layout = new Layout();