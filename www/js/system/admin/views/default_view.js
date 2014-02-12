var SystemDefaultView = Backbone.View.extend({

    el: $(document),

    events: {
      'change .lang-select': "change_lang"
    },

    initialize: function() {
        App.elementLoad('#system-tabs', function(){
            $('#system-tabs').tabs({});
        })

        Form.add_success_handler('add-lang-form', function(data){
            if(typeof data == 'object') {

                var option = $('<option>', {
                    'value' : data.added_lang.key,
                    'text'  : data.added_lang.alias
                })

                $('.lang-select').append(option);

                var msg = LangModel.get('add_lang') ||
                    'language added successfully';
                App.showNoty(msg, 'success');
            }
            else {
                var msg = LangModel.get('cannot_add_lang') ||
                    'cannot add language';
                App.showNoty(msg, 'error');
                console.log(data);
            }
        })
    },

    change_lang: function(e) {
        var params = $(e.target).val();

        $.post(
            'index.php',
            {'class':'system', 'controller':'lang', 'task':'change', 'params':params},
            function(data) {
                if(data.trim()) {
                    var msg = LangModel.get('cannot_change_lang') ||
                        'cannot change language';
                    App.showNoty(msg, 'error');
                }
                else {
                    App.loadPage('/'+Backbone.history.fragment);
                }
            }
        );
    }
})

window.SystemDefaultView = new SystemDefaultView;
