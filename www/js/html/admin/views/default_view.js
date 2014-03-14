var HtmlView = Backbone.View.extend({

    initialize: function() {
        App.elementLoad('#html-tabs', function(){
            $('#html-tabs').tabs()
        })
    },

    el: $(document),

    events: {
        'click .create-html-record-icon'    : 'create_record'
    },

    create_record: function(e) {
        var el      = $(e.target);
        var params  = el.data('params');

        App.makeModal(
            'index.php?class=html&controller=records&task=create'
        )
    }
})

window.HtmlView = new HtmlView;
