(function(){
    App.Router = Backbone.Router.extend({
        routes: {
            ''                    : 'actionIndex',
            'installer'           : 'actionInstall',
            'installer/done'      : 'actionDone',
            '*notFound'           : 'actionNotFound'
        },

        actionDone : function() {
            $.get('index.php?task=complete', {}, function(data){
                InstallSiteView.render(data);
            })
        },

        actionInstall : function() {

        },

        actionNotFound : function() {
            //todo ajax=1 don`t substitute automatically
            $.get('index.php', {'class':'installer',ajax:1},  function(data) {
                InstallSiteView.render(data);
            })
        },

        actionIndex : function() {
            App.goto('/installer');
        }
    })
})()
