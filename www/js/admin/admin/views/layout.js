window.Layout = Backbone.View.extend({
    el: '#all',

    initialize: function() {
        Form.add_success_handler('exit-form', function(data) {
            if(data.trim()) {
                App.showNoty('request error', 'error');
            }
            else {
                App.loadPage('/admin');
            }
        })

        $(window).load(function(){
            var gridster = $('.gridster ul').gridster({
                                widget_margins: [10,10],
                                widget_base_dimensions: [250, 250],
                                draggable: {
                                    stop: function() {
                                        $.post('index.php?class=admin&task=update_grid',
                                            {'params':gridster.serialize()})
                                    }
                                }
                            }).data('gridster');
        })
    }
})

window.Layout = new Layout();