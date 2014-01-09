var App = {};

_.extend(App, Backbone.Events, {

    _initializers: [],

    router: null,

    js_hooks: [],

    css_hooks: [],

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
            if(model.attributes && !$.isEmptyObject(model.attributes)) {
                clearInterval(interval);

                if(typeof callback == 'function') {
                    callback();
                }
            }
        },50)
    },

    start: function(){

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

            App.loadHooks();
        })

        $(document).on('click', '.modal-backdrop', function(e){
            if(e.target.tagName == 'DIV' && e.target.className == 'modal-backdrop') {
                $(this).remove();
            }
        })

        $(document).on('click', '.error-span', function(){
            $(this).remove();
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

    loadPage: function(url) {
        //todo - сделать ajax загрузку
        location = url;
    },

    alertObj: function(obj) {
        var str = "";

        for(k in obj) {
            str += k+" : "+obj[k]+" \r\n";
        }

        alert(str);
    },

    closeModal: function() {
        $('.modal-backdrop').remove();
    },

    loadHooks: function() {

        var tmp_hooks = [];

        $('body').find('*.css-hook').each(function(){
            var href = $(this).text();

            $(this).remove();

            tmp_hooks.push(href);

            if($.inArray(href, App.css_hooks) == -1) {
                var css = $('<link>', {
                    'rel':  'stylesheet',
                    'href': href
                })

                $('head').append(css);

                App.css_hooks.push(href);
            }
        })


        $('body').find('*.js-hook').each(function(){
            var src = $(this).text();

            $(this).remove();

            tmp_hooks.push(src);

            if($.inArray(src, App.js_hooks) == -1) {
                var js = $('<script>', {
                    'src': src
                })

                $('body').append(js);

                App.js_hooks.push(src);
            }
            else {
                //todo - not very pretty
                $.each(App.js_hooks, function(k,v) {
                    var tmp = $('script[src="'+v+'"]');

                    var src = tmp.attr('src');

                    tmp.remove();

                    $('body').append($('<script>', {'src':src}));
                })
            }
        })

        //purify outdated hooks
        if(tmp_hooks.length > 0) {
            $.each(App.css_hooks, function(k,v){
                if($.inArray(v, tmp_hooks) == -1) {

                    $('link[href="'+v+'"]').remove();

                    delete App.css_hooks[k];
                }
            })

            $.each(App.js_hooks, function(k,v){
                if($.inArray(v, tmp_hooks) == -1) {

                    $('script[src="'+v+'"]').remove();

                    delete App.js_hooks[k];
                }
            })
        }
    },

    makeModal: function(url) {
        if(!$('*').is('.modal-backdrop')) {
            var div = $('<div>', {
                'class' : 'modal-backdrop',
                'html' : $('div', {
                    'class' : 'modal'
                })
            });

            $('body').append(div);

            $('.modal').load(url);
        }
    }
})


$(window).on('load', function(){
    App.start();
})
