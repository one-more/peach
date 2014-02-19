window.InstallWidgetView = Backbone.View.extend({

    el: $(document),

    events: {
        'click .installer-remove-module'    : 'show_remove_module',
        'click .installer-remove-icon'      : 'remove_module'
    },
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
    },

    show_remove_module: function() {
        App.makeModal('index.php?class=installer&controller=remove');
    },

    remove_module: function(e) {
        var params = $(e.target).data();
        var data = {
            'class'         : 'installer',
            'controller'    : 'remove',
            'task'          : 'remove',
            'params'        : params
        };

        var msg = LangModel.get('confirm_delete_module') ||
            'delete extension?';
        App.confirm(
            msg,
            function() {
                $.post('index.php', data, function(str){
                    if(str.trim() != '') {
                        msg = LangModel.get('delete_error') ||
                            'an error occurred during deletion';
                        App.showNoty(msg, 'error');
                        console.log(str);
                    }
                    else {
                        msg = LangModel.get('delete_success') ||
                            'deleted successfully';
                        App.showNoty(msg, 'success');

                        App.trigger('module:deleted');
                    }
                    App.closeModal();
                })
            }
        );
    }
})

window.InstallWidgetView = new InstallWidgetView;
