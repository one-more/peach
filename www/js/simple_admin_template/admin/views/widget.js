var Widgetview = Backbone.View.extend({
    el : '.gridster',

    current_widget: -1,

    events: {
        "click .tool-items>a"           : "widget_action",
        "click .widget-extensions a"    : "widget_action_2",
        "click .widget-list a"          : "widget_action_3",
        'click .remove-widget'          : 'remove_widget',
        'click .add-widget'             : 'add_widget'
    },

    initialize: function() {

        App.on('language:selected', function(){
            var widgets = WidgetModel.attributes;

            $.each(widgets, function(k,v){
                if(v != -1) {
                    var arr = v.split(' ');

                    Widgetview
                        .$el
                        .find('*[data-widget='+k+"]")
                        .children('span')
                        .load(
                            'index.php',
                            {
                                'class'     :arr[0],
                                'method'    :'get_widget',
                                'params'    :arr[1]
                            }
                        );
                }
            })
        })

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
                    position:   'right',
                    hideOnClick: true
                });

                if(v != -1) {
                    var arr = v.split(' ');

                    Widgetview.$el.find('*[data-widget='+k+"]").children('span').load('index.php',
                        {'class':arr[0], 'method':'get_widget', 'params':arr[1], 'ajax':'1',
                        'old_url':location.pathname});
                }
            });
        })
    },

    delegateEvents: function() {
        App.on('document:ready', function(){
            $.each(Widgetview.events, function(k,c){
                var arr = k.split(' ');

                $(document).on(arr[0], arr[1], Widgetview[c]);
            })
        })
    },

    widget_action: function() {
        var className = $(this).children('i').attr('class');

        //if add widget
        if(className == 'icon-plus') {

            Widgetview.current_widget = $(this).data('widget');

            $.post(
                'index.php',
                {'class':'simple_admin_template', 'task':'get_widget_list'},
                function(data) {

                    var layout = $('<div>', {
                        'class': 'modal-backdrop',
                        html: $('<div>', {
                            'class':'modal',
                            html: data
                        })
                    })

                    $('body').append(layout);
                }
            );
        }
        else {
            var widget = $(this).data('widget');

            WidgetModel.set(widget, -1);

            WidgetModel.update();

            $('*[data-widget='+widget+']').children('span').html('');

            $('a[data-widget='+widget+'] i').attr('class', 'icon-plus');
        }
    },

    widget_action_2: function(e) {
        var params = $(e.target).data('extension');

        $('div.modal').load(
            'index.php',
            {'class':'simple_admin_template', 'task':'get_widget_list', 'params':params});
    },

    widget_action_3: function(e) {
        var params = $(e.target).data('widget');

        var extension = $(e.target).data('class');

        var widget = Widgetview.current_widget;

        WidgetModel.set(widget, extension+" "+params);

        WidgetModel.update();

        $('*[data-widget='+widget+'] span').load(
            'index.php',
            {'class':extension, 'method':'get_widget', 'params':params},
            function() {
                App.closeModal();

                $('a[data-widget='+widget+'] i').attr('class', 'icon-trash');
            }
        );
    },

    remove_widget: function() {
        var count = $('.count-widgets-input').val();

        if(count == 4 || count < 4) {
            return;
        }
        else {
            WidgetModel.unset(count);
            WidgetModel.update()

            Layout.gridster.remove_widget($('*[data-widget='+count+']'));

            $('#widget-options-'+count).next('div').remove();
            $('#widget-options-'+count).remove();
            Widgetview.update_grid()

            count--;
            $('.count-widgets-input').val(count);
        }
    },

    add_widget: function() {
        var count = $('.count-widgets-input').val();

        if(count == 9 || count > 9) {
            return;
        }
        else {
            count++;

            var opts = $('<div>', {
                id:         'widget-options-'+count,
                'class':    'toolbar-icons hide',
                html: $('<a>',{
                    'class':        'cursor-pointer',
                    'data-widget':  count,
                    html: $('<i>',{'class':'icon-plus'})
                })
            })

            var tb = $('<div>',{
                id:         'widget-toolbar-'+count,
                'class':    'settings-button',
                width: '19',
                'html' :    $('<img>', {
                    src:'/media/images/icon-cog-small.png'
                })
            })

            var span = $('<span>');

            var li = $('<li>', {
                'data-widget' : count,
                html : tb
            });

            li.append(span);

            Layout.gridster.add_widget.apply(Layout.gridster, [li, 1, 1]);

            $('body').append(opts);

            $('#widget-toolbar-'+count).toolbar({
                content:    '#widget-options-'+count,
                position:   'right',
                hideOnClick: true
            });

            $('.count-widgets-input').val(count)

            WidgetModel.set(count, '-1');

            WidgetModel.update();

            Widgetview.update_grid();
        }
    },

    update_grid: function() {
        $.post('index.php?class=simple_admin_template&task=update_grid',
            {
                'params' : Layout.gridster.serialize()
            })
    }
})

window.Widgetview = new Widgetview;
