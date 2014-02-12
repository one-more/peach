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
    },

    events: {
        'change .select-start-extension'    : 'select_start_extension'
    },

    select_start_extension: function(e) {
        var params = $(e.target).val()

        $.post('index.php?class=simple_admin_template&controller=options&task=save',
            {
                'params' : {'start_extension' : params}
            }
        );
    }
})

window.Layout = new Layout();