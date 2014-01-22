var SiteView = Backbone.View.extend({
    initialize: function() {
        App.elementLoad('#site-tabs', function(){
            $('#site-tabs').tabs({});
        })
    }
});

window.SiteView = new SiteView;
