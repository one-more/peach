App.Router = Backbone.Router.extend({
    initialize: function() {

    },

    routes : {
        'admin':            'actionIndex',
        'admin/':           'actionIndex',
        'admin/options':    'actionOptions',
        'admin/menu':       'actionMenu',
        '*other':           'actionOther'
    },

    actionIndex: function() {
        App.modelLoad(TemplateModel, function(){
            if(TemplateModel.get('start_extension') != -1) {

                var start_extension = TemplateModel.get('start_extension');

                $('*[data-widget="2"]').load('index.php',
                    {'class':start_extension, 'ajax':'1', 'old_url':location.pathname});
            }
        })
    },

    actionOptions: function(){
        $('*[data-widget="2"]').load('index.php', {'class':'admin', 'controller':'options'});
    },

    actionMenu: function() {
        if(SystemModel.get('menu') != -1) {
            $('*[data-widget="2"]').load('index.php',{'class':AdminModel.get('menu')});
        }
        else {
            App.showNoty('no menu available, please install menu', 'alert');
        }
    },

    actionOther: function() {
        var arr = Backbone.history.fragment.split('/');

        arr.shift();

        var extension   = arr.shift();
        var controller  = arr.shift();

        var data = {};

        if(extension) {
            data.class = extension;

            if(controller) {
                data.controller = controller;
            }

            if(arr.length > 0) {
                data.params = arr;
            }
        }

        $('*[data-widget="2"]').load('index.php', data);
    }
})
