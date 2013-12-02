var SiteModel = Backbone.Model.extend({
    initialize: function() {
        $.post('index.php', {'task':'get_options', 'ajax':'1'}, function(data) {
            try {
                var json = $.parseJSON(data);

                SiteModel.set(json);
            }
            catch(Exc) {
                App.showNoty('cannot load site model', 'error');
                console.log(Exc);
            }
        })
    }
})

window.SiteModel = new SiteModel;