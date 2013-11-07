App.module('Form', function(Form){

    Form._ajax = null;

    Form.success = function(form){

    };

    Form.error = function(form){

    };

    Form.send = function(form, params){
        var params = params || {};

        var $form = $(form);
        var action = $form.attr('action');
        var button = $form.find('button[type="submit"]');
        var name = $form.attr('name');

        button.attr('disabled', 'true');

        var ajax = Form._ajax = $.post(action, $form.serialize(), 'json');

        ajax.success(function(data){
            if(data) {
                if(data.error){
                    _.each(data.error, function(v,k){
                        $form.find('input[name='+k+']')
                            .attr('placeholder', v)
                            .addClass('error');
                    })
                    Form.error($form);
                }
                else {
                    Form.success($form);
                }
            }
        });

        ajax.complete(function(data){
            button.attr('disabled', 'false');
        })
    };

    Form.initialize = function(){
        $(document).on('submit', 'form.ajax', function(e){
            e.preventDefault();

            var params = $(this).data('params') || {};

            Form.send($(this), params);
        })
    };

    window.Form = Form;
})
