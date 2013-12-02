App.Router = Backbone.Router.extend({
    routes : {
        'admin':            'actionIndex',
        'admin/':           'actionIndex',
        'admin/options':    'actionOptions',
        'admin/menu':       'actionMenu',
        '*other':           'actionOther'
    },

    actionIndex: function() {

    },

    actionOptions: function(){
        $('*[data-widget="2"]').load('index.php', {'class':'admin', 'controller':'options'});
    },

    actionMenu: function() {
        if(AdminModel.get('menu') != -1) {
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
