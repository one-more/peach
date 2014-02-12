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
                    var msg = LangModel.get('load_template_model') ||
                        'cannot load  template model';
                    App.showNoty(msg, 'error');

                    console.log(exception);
                }
            }
        );
    },

    update: function() {
        $.post(
            'index.php',
            {
                'class' : 'simple_admin_template',
                'task'  : 'get_options'
            },
            function(data) {
                try {
                    TemplateModel.set(data)
                }
                catch(exc) {
                    var msg = LangModel.get('update_template_model') ||
                        'cannot update template model';
                    App.showNoty(msg, 'error');
                    console.log(data);
                    console.log(exc);
                }
            }
        )
    }
})

window.TemplateModel = new TemplateModel;