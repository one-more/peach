var App = {};

_.extend(App, Backbone.Events, {

    _initializers: [],

    router: null,

    js_hooks: [],

    css_hooks: [],

    event_handlers: [],

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

        //modify function [on] just a little
        $.fn.on = function(types, selector, data, fn, /*INTERNAL*/ one) {

            if(typeof selector == 'string' && typeof types == 'string')
            {
                //not to duplicate events handlers
                var arr = jQuery._data($(this)[0]).events;
                var hnd = data || fn;
                var dup = false;
                if(arr) {
                    $.each(arr, function(k,v) {
                        $.each(v, function(k1, v1) {
                            if(v1.selector == selector &&
                                v1.handler.toString() == hnd.toString()) {
                                dup = true;
                                return;
                            }
                        })
                    })
                }
                if(dup) {
                    return;
                }
            }

            var origFn, type;

            // Types can be a map of types/handlers
            if ( typeof types === "object" ) {
                // ( types-Object, selector, data )
                if ( typeof selector !== "string" ) {
                    // ( types-Object, data )
                    data = data || selector;
                    selector = undefined;
                }
                for ( type in types ) {
                    this.on( type, selector, data, types[ type ], one );
                }
                return this;
            }

            if ( data == null && fn == null ) {
                // ( types, fn )
                fn = selector;
                data = selector = undefined;
            } else if ( fn == null ) {
                if ( typeof selector === "string" ) {
                    // ( types, selector, fn )
                    fn = data;
                    data = undefined;
                } else {
                    // ( types, data, fn )
                    fn = data;
                    data = selector;
                    selector = undefined;
                }
            }
            if ( fn === false ) {
                fn = returnFalse;
            } else if ( !fn ) {
                return this;
            }

            if ( one === 1 ) {
                origFn = fn;
                fn = function( event ) {
                    // Can use an empty set, since event contains the info
                    jQuery().off( event );
                    return origFn.apply( this, arguments );
                };
                // Use same guid so caller can remove using origFn
                fn.guid = origFn.guid || ( origFn.guid = jQuery.guid++ );
            }
            return this.each( function() {
                jQuery.event.add( this, types, fn, data, selector );
            });
        }

        $.ajaxSetup({
            dataFilter: function(data) {
                try{
                    return $.parseJSON(data);
                }
                catch(Exception){
                    return data;
                }
            },
            error: function(xhr, status, text) {
                App.showNoty('request error', 'error');
                console.log(text);
            },
            complete: function(xhr) {
                var header = xhr.getResponseHeader('Content-type');

                if(header && header.indexOf('text/html') != -1) {
                    var text = xhr.responseText;

                    if(text.match(/<.*>/)) {
                        App.trigger('dom:loaded');
                    }
                }
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
            if(App.router) {
                e.preventDefault();

                App.router.navigate($(this).attr('href'), {trigger:true});
            }
        })

        App.on('dom:loaded', function(){
            _.each(this._initializers, function(func){
                if (typeof func == "function"){
                    func();
                }
            });

            App.loadHooks();

            App.loadDaemons();

            App.loadEditor();
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

    confirm: function(text, ok_callback, cancel_callback){
        noty({
            text    : text,
            layout  : 'topCenter',
            buttons : [
                {
                    addClass:'btn', text:'ok', onClick: function($noty){
                        $noty.close();
                        if(typeof ok_callback == 'function') {
                            ok_callback();
                        }
                    }
                },
                {
                    addClass: 'btn', text : 'cancel', onClick: function($noty) {
                        $noty.close();
                        if(typeof cancel_callback == 'function') {
                            cancel_callback();
                        }
                    }
                }
            ]
        });
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

    alert: function(msg){
        alert(msg);
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

            $.getScript(src);

        })
    },

    makeModal: function(url) {
        if(!$('*').is('.modal-backdrop')) {
            var div = $('<div>', {
                'class' : 'modal-backdrop',
                'html' : $('<div>', {
                    'class' : 'modal'
                })
            });

            $('body').append(div);

            $('.modal').load(url);

            $('.modal').css(
                {
                    'max-height'    : $(window).height()*0.85,
                    'overflow-y'    : 'auto',
                    'overflow-x'    : 'hidden'
                }
            )
        }
    },

    updatePage: function() {
        var arr = Backbone.history.fragment.split('/');

        if(arr.length >= 2) {
            arr.shift();
            var params = {
                'class'         : arr.shift()
            }

            if(arr.length > 0) {
                params['controller'] = arr.shift()
            }

            if(arr.length > 0) {
                params['params'] = arr.shift()
            }

            $('*[data-widget=2]').load('index.php', params)
        }
    },

    setCookie: function(name, value, options) {
        options = options || {};

        var expires = options.expires;

        if (typeof expires == "number" && expires) {
            var d = new Date();
            d.setTime(d.getTime() + expires*1000);
            expires = options.expires = d;
        }
        if (expires && expires.toUTCString) {
            options.expires = expires.toUTCString();
        }

        value = encodeURIComponent(value);

        var updatedCookie = name + "=" + value;

        for(var propName in options) {
            updatedCookie += "; " + propName;
            var propValue = options[propName];
            if (propValue !== true) {
                updatedCookie += "=" + propValue;
            }
        }

        document.cookie = updatedCookie;
    },

    deleteCookie: function(name) {
        App.setCookie(name, '', {expires: -1});
    },

    getCookie: function(cookie_name) {
        var results = document.cookie.match ( '(^|;) ?' + cookie_name + '=([^;]*)(;|$)' );

        if ( results )
            return ( decodeURIComponent( results[2] ) );
        else
            return null;
    },

    loadDaemons: function() {
        $.getScript('index.php?class=admin&method=get_daemons_js');
    },

    loadEditor: function() {
        $.getScript('index.php?class=admin&method=get_editor_js');

        $.post(
            'index.php',
            {'class':'admin', 'method':'get_editor_css'},
            function(data) {
                if(data.trim() != '') {
                    var link = $("link[data-type=editor-css]");

                    var css = $('<link>', {
                        'rel'       : 'stylesheet',
                        'href'      : data,
                        'data-type' : 'editor-css'
                    })

                    if(link.length == 0) {
                        $('head').append(css);
                    }
                    else {
                        link.replaceWith(css);
                    }
                }
            }
        )
    }
})


$(window).on('load', function(){
    App.start();
})
