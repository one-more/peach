(function(){
    App.Router = Backbone.Router.extend({
        routes: {
            'install'       : 'actionInstall',
            'install/done'  : 'actionDone'
        },

        actionDone : function() {
            $.get('index.php?task=complete', function(data){
                InstallSiteView.render(data);
            })
        },

        actionInstall : function() {

        }
    })
})()
