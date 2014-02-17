window.InstallWidgetView = Backbone.View.extend({
    initialize: function() {
        $('.installer-install-module').peach_upload({
            'url'           : 'index.php?class=installer&controller=module&task=load',
            'dnd_area'      : 'install-widget',
            'filter_func'   : function(f) {
                if(f.type == 'application/x-php') {
                    return true;
                }
                else {
                    var msg = LangModel.get('type_phar') ||
                        'file must be in phar format';
                    App.showNoty(msg, 'error');
                    return false;
                }
            },
            'success'       : function(data){
                if(data.trim() != '') {
                    var msg = LangModel.get('installation_error') ||
                        'an error occurred during installation';
                    App.showNoty(msg, 'error');
                    console.log(data);
                }
                else {
                    var msg = LangModel.get('install_success') ||
                        'installed successfully';
                    App.showNoty(msg, 'success');

                    App.trigger('module:installed');
                }
            }
        })
    }
})

window.InstallWidgetView = new InstallWidgetView;
