var TemplateModel = Backbone.Model.extend({
    initialize: function() {
        $.post(
            'index.php',
            {'class':'simple_admin_template', 'task':'get_options', 'ajax':'1', 'old_url':location.pathname},
            function(data) {
                try{
                    var json = (typeof data == 'object')? data : $.parseJSON(data);

                    TemplateModel.set(json);
                }
                catch(exception) {
                    App.showNoty('cannot load  template model', 'error');

                    console.log(exception);
                }
            }
        );
    }
})

window.TemplateModel = new TemplateModel;