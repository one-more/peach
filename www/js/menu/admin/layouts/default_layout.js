var MenuView = Backbone.View.extend({
    initialize: function() {
        App.elementLoad('#menu-tabs').tabs();
    }
})


window.MenuView = new MenuView;