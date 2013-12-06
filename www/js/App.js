var App = {};

_.extend(App, Backbone.Events, {

    _initializers: [],
    router: null,

    //fires callback when element is loaded
    elementLoad : function(el, callback) {
        var interval = setInterval(function(){
            if($('*').is(el)) {
                if(typeof callback == 'function') {
                    callback();
                }
                clearInterval(interval);
            }
        }, 50);
    },

    modelLoad: function(model, callback) {
        var interval = setInterval(function(){
            if(model.attributes) {
                clearInterval(interval);

                if(typeof callback == 'function') {
                    callback();
                }
            }
        },50)
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

        if(this.Router)
        {
            var router = App.router = new this.Router();
        }

        Backbone.history.start({pushState: true})

        $.ajaxSetup({
            dataFilter: function(data) {
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
            },
            beforeSend: function() {
                this.url += (this.url.indexOf('?') > -1 ? '&' : '?') + 'ajax=1&old_url='+location.pathname;
            }
        });

      this.registerEvents();

      App.trigger('document:ready');
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

            App.router.navigate($(this).attr('href'), {trigger:true});
        })

        App.on('dom:loaded', function(){
            _.each(this._initializers, function(func){
                if (typeof func == "function"){
                    func();
                }
            });
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
    },

    loadPage: function(url, callback) {
        $('body').load(url, {}, function(){

            App.trigger('dom:loaded');

            App.start();

            if(typeof callback == 'function') {
                callback();
            }
        })
    },

    alertObj: function(obj) {
        var str = "";

        for(k in obj) {
            str += k+" : "+obj[k]+" \r\n";
        }

        alert(str);
    }
})


$(window).on('load', function(){
    App.start();
})
