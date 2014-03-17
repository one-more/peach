(function(){
    App.Router = Backbone.Router.extend({
        routes: {
            ''                    : 'actionIndex',
            'install'           : 'actionInstall',
            'install/done'      : 'actionDone',
            '*notFound'           : 'actionIndex'
        },

        actionDone : function() {
            $.get(
                'index.php',
                {
                    'class'         : 'installer',
                    'controller'    : 'site',
                    'task'          : 'complete'
                },
                function(data){
                InstallSiteView.render(data);
            })
        },

        actionInstall : function() {

        },

        actionIndex : function() {
            App.goto('/install');
        }
    })
})()
