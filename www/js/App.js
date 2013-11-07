var App = {};

_.extend(App, Backbone.Events, {

    //fires callback when element is loaded
    elementLoad : function(el, callback) {
        setTimeout(function timer1(){
            if(!$('div').is(el)) {
                timer1();
            }
            else {
                callback();
            }
        }, 20);
    },

    start: function(){
      this.registerEvents();
    },

    registerEvents: function() {
        $(document).on('click', '.disabled', function(e){
            e.preventDefault();

            return;
        })

        $(document).ajaxError(function(){
            //todo make ui popup
            alert('request error');
        })
    },

    module: function(name, definition){
        var args = Array.prototype.slice.call(arguments);
        args.unshift(this);

        return Backbone.Module.create.call(Backbone.Module, this, name, definition);
    }
})
App.start();