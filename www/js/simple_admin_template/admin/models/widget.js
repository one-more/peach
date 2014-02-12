var WidgetModel = Backbone.Model.extend({
    initialize: function() {
        $.post(
            'index.php',
            {'class':'simple_admin_template', 'task':'get_widgets', 'ajax':'1', 'old_url':location.pathname},
            function(data){
                try{
                    var json = (typeof data == 'object' ? data : $.parseJSON(data));

                    WidgetModel.set(json);

                    App.trigger('widgetmodel:loaded');
                }
                catch(exception) {
                    var msg = LangModel.get('load_widget_model') ||
                        'cannot load widget model';
                    App.showNoty(msg, 'error');
                    console.log(exception);
                }
            }
        )
    },
    update: function() {

        $.post(
            'index.php',
            {'class':'simple_admin_template', 'task':'update_widgets', 'params': this.toJSON()},
            function(data) {
                if(data.trim()) {
                    var msg = LangModel.get('update_widget_model') ||
                        'failed to update the widget model';
                    App.showNoty(msg, 'error');
                    console.log(data);
                }
            }
        )
    }
})

window.WidgetModel = new WidgetModel;
