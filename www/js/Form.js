App.module(Form, function(Form){
    Form.success = function(){

    };

    Form.error = function(){

    };

    Form.send = function(){

    };

    Form.initialise = function(){
        $(document).on('submit', 'form.ajax', function(e){
            e.preventDefault();

            var params = $(this).data('params') || {};

            Form.send($(this), params);
        })
    };

    window.Form = Form;
})
