var App = {};

_.extend(App, Backbone.Events, {

    _initializers: [],
    router: null,

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
        _.each(this._initializers, function(func){
            if (typeof func == "function"){
                func();
            }
        });

        $.noty.defaults = {
            layout:         'top',
            theme:          'defaultTheme',
            type:           'alert',
            text:           '',
            dismissQueue:   true,
            template:       $('<div>', {
                'class' : 'noty_message',
                'html'  :   $('<span>', {
                    'class' : 'noty_text'
                })
                .after($('<div>', {
                    'class' : 'noty_close'
                }))
            }),
            animation:      {
                open:   {height: 'toggle'},
                close:  {height: 'toggle'},
                easing: 'swing',
                speed:  500
            },
            timeout:        5000,
            force:          false,
            modal:          false,
            maxVisible:     1,
            closeWith:      ['click'],
            callback:       {
                onShow:     function(){},
                afterShow:  function(){},
                onClose:    function(){},
                afterClose: function(){}
            },
            buttons:        false //array of buttons
        };

        var router = App.router = new this.Router();

        Backbone.history.start({pushState: true})

        $.ajaxSetup({
            dataFilter: function(data, type) {
                try{
                    return $.parseJSON(data);
                }
                catch(Exception){
                    return data;
                }
            },
            error: function() {
                App.showNoty('request error', 'error');
            },
            complete: function() {
                App.trigger('dom:loaded');
            }
        });

      this.registerEvents();
    },

    registerEvents: function() {
        $(document).on('click', '.disabled', function(e){
            e.preventDefault();

            return;
        })

        $(document).on('keypress', 'input.error', function(e){
            $(this).removeClass('error');
        })

        $(document).on('click', 'a[href]:not(.disabled, .external)', function(e){
            e.preventDefault();

            App.router.navigate(this.href, {trigger:true});
        })
    },

    addInitializer:function(func){
        this._initializers.push(func);
    },

    module: function(name, definition){
        var args = Array.prototype.slice.call(arguments);
        args.unshift(this);

        return Backbone.Module.create.call(Backbone.Module, this, name, definition);
    },

    showNoty: function(text, type, btns) {
        btns = (typeof btns == 'object')? btns :false;

        var n = noty({text: text, type: type, buttons: btns});

        return n;
    },

    goto: function(location, trigger) {
        trigger = trigger || true;

        App.router.navigate(location, {trigger:trigger});
    }
})

$(window).on('load', function(){
    App.start();
})