var Layout = Backbone.View.extend({
    el: $('#all'),

    initialize: function(){
        this.$el.find('.block').height($(window).height());
    }
});

window.Layout = new Layout();
