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
                    App.showNoty('cannot load widget model', 'error');
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
                    App.showNoty('failed to update the widget model', 'error');
                    console.log(data);
                }
            }
        )
    }
})

window.WidgetModel = new WidgetModel;
