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
                    App.showNoty('cannot update template model', 'error');
                    console.log(data);
                    console.log(exc);
                }
            }
        )
    }
})

window.TemplateModel = new TemplateModel;