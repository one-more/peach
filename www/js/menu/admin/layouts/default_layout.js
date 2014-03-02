var MenuView = Backbone.View.extend({
    initialize: function() {
        App.elementLoad('#menu-tabs', function(){
            $('#menu-tabs').tabs();
        })
    }
})


window.MenuView = new MenuView;