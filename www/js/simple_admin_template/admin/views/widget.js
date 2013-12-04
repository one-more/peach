var Widgetview = Backbone.View.extend({
    el : '.gridster',

    events: {
        "click .tool-items>a":      "widget_action",
        "click .modal-backdrop":    "close_modal"
    },

    initialize: function() {
        App.on('widgetmodel:loaded', function(){

            var widgets = WidgetModel.attributes;

            $.each(widgets, function(k,v){

                var opts = $('<div>', {
                    id:         'widget-options-'+k,
                    'class':    'toolbar-icons hide',
                    html: $('<a>',{
                        'class':        'cursor-pointer',
                        'data-widget':  k,
                        html: v == -1 ? $('<i>',{'class':'icon-plus'}) : $('<i>',{'class':'icon-trash'})
                    })
                })

                var tb = $('<div>',{
                    id:         'widget-toolbar-'+k,
                    'class':    'settings-button',
                    width: '19',
                    'html' :    $('<img>', {
                        src:'/media/images/icon-cog-small.png'
                    })
                })

                var span = $('<span>');

                Widgetview.$el.find('*[data-widget='+k+"]").html(tb).append(span);

                $('body').append(opts);

                $('#widget-toolbar-'+k).toolbar({
                    content:    '#widget-options-'+k,
                    position:   'right'
                });
            });
        })
    },

    delegateEvents: function() {
        App.elementLoad('.gridster', function(){
            //todo - при выходе/входе обработчики навешиваются повторно
            $.each(Widgetview.events, function(k,c){
                var arr = k.split(' ');

                $(document).on(arr[0], arr[1], Widgetview[c]);
            })
        })
    },

    widget_action: function() {
        if(!$('div').is('.modal-backdrop')) {
            var className = $(this).children('i').attr('class');

            var widget = $(this).attr('data-widget');

            var layout = $('<div>', {
                'class': 'modal-backdrop',
                html: $('<div>', {
                    'class':'modal',
                    text: 'test'
                })
            })

            $('body').append(layout);
        }
    },

    close_modal: function(){
        $('.modal-backdrop').remove();
    }
})

window.Widgetview = new Widgetview;
