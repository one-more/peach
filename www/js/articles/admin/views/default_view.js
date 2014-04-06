ArticlesView = Backbone.View.extend({
    initialize: function() {
        App.elementLoad('#articles-tabs', function(){
            $('#articles-tabs').tabs()
        })
    }
})

ArticlesView = new ArticlesView;


