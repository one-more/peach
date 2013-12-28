App.module('Form', function(Form){

    Form._ajax = null;

    Form.success_handlers = [];

    Form.error_handlers = [];

    Form.add_success_handler = function(id, func) {
        this.success_handlers[id] = func;
    }

    Form.add_error_handler = function(id, func) {
        this.error_handlers[id] = func;
    }

    Form.success = function(form, data){
        var id = form.attr('id') || form.attr('className');

        var func = this.success_handlers[id];

        if(typeof func == 'function')
        {
            func(data);
        }
    };

    Form.error = function(form, data){
        var id = form.attr('id') || form.attr('className');

        var func = this.error_handlers[id];

        if(typeof func == 'function')
        {
            func(data);
        }
    };

    Form.send = function(form, params){
        var params = params || {};

        var $form = $(form);
        var action = $form.attr('action');
        var button = $form.find('button[type="submit"]') || $form.find('input[type=submit]');
        var name = $form.attr('name');

        button.attr('disabled', 'true');

        $('.error-span').each(function(){
            $(this).remove();
        })

        var ajax = Form._ajax = $.post(action, $form.serialize(), 'json');

        ajax.success(function(data){
            if(data) {
                if(data.error && !$.isEmptyObject(data.error)){
                    _.each(data.error, function(v,k){
                        $form.find('input[name='+k+']')
                            .addClass('error');

                        try{
                            var el = $form.find('input[name='+k+']');

                            var span = $('<span>', {
                                'class' : 'error-span text-error',
                                'text': v
                            });

                            span.css({
                                'left'  : el.offset().left,
                                'top'   : el.offset().top + el.outerHeight(true) + 10,
                                'width' : el.width()
                            });

                            $('body').append(span);
                        }
                        catch(exception) {
                            console.log(exception);
                        }

                    })
                    Form.error($form, data);
                }
                else {

                    $form.find('input').val('');

                    Form.success($form, data);
                }
            }
        });

        ajax.complete(function(data){
            button.removeAttr('disabled');
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
