App.Router = Backbone.Router.extend({
    routes : {
        'admin':            'actionIndex',
        'admin/options':    'actionOptions',
        'admin/menu':       'actionMenu',
        '*other':           'actionOther'
    },

    actionIndex: function() {

    },

    actionOptions: function(){

    },

    actionMenu: function() {

    },

    actionOther: function() {

    }
})
