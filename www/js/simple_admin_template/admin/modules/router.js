App.Router = Backbone.Router.extend({
    initialize: function() {
        App.on('language:selected', function(){
            $('.navbar').load('index.php .navbar');

            App.router.actionOther();
        })

        App.on('module:installed module:deleted', function(){
            App.router.actionOther();
        })
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

                App.router.navigate('/admin/'+start_extension, {trigger:true});
            }
        })
    },

    actionOptions: function(){
        $('*[data-widget="2"]').load('index.php', {'class':'admin', 'controller':'options'});
    },

    actionMenu: function() {
        if(SystemModel.get('menu') != -1) {
            $('*[data-widget="2"]').load('index.php',{'class':SystemModel.get('menu')});
        }
        else {
            var msg = LangModel.get('no_menu') ||
                'no menu available, please install menu';
            App.showNoty(msg, 'alert');
            App.router.actionOther();
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
