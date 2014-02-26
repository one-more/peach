(function($){
    $.fn.peach_slider = function(params) {
        return $(this).each(function() {
            var defaults = {
                width           : $(this).parent().width() || 600,
                height          : false,
                circle          : true,
                fit_height      : true,
                arrows          : true,
                dots            : true,
                autoplay        : {
                    enabled         : false,
                    button          : true,
                    duration        : 2000,
                    progress_bar    : true
                }
            }

            var options = $.extend(defaults, params);

            if(options.dots && !options.circle) {
                options.circle = true;
            }

            var _this = $(this);

            var childs = _this.children('li').length;

            var current_el = 1;

            var wrapper = $('<div>', {
                'class'     : 'peach-slider-wrapper inline-block position-relative overflow-hidden'
            })

            wrapper.css('width', options.width);

            if(options.height) {
                wrapper.css('height', options.height);

                _this.children('li').each(function(){
                    var img = $(this).children('img');
                    $(this).children('img').ready(function(){
                        img.css('height',options.height)
                    })
                })
            }

            _this.wrap(wrapper);

            wrapper = $('.peach-slider-wrapper');

            _this.attr('class', 'inline-ul margin-padding-0 position-relative')

            _this.children('li').each(function(){
                $(this).width(wrapper.width())
                $(this).children('img').width(wrapper.width())

                var img = $(this).children('img');
                $(this).children('img').ready(function(){
                    if(!options.height && options.fit_height && $(this).height()>$(window).height()*0.7){
                        img.height($(window).height() * 0.7)
                    }
                })
            })

            width = _this.children('li').length * wrapper.width()

            _this.width(width);

            //left & right arrows
            if(options.arrows && childs > 1) {
                var i = $('<i>', {
                    'class' : 'icon-chevron-left'
                });
                var btn = $('<div>', {
                    'class' : 'peach-slider-arrow peach-slider-arrow-left',
                    'html'  : i
                })
                wrapper.append(btn)

                i = $('<i>', {
                    'class' : 'icon-chevron-right'
                })
                btn = $('<div>', {
                    'class' : 'peach-slider-arrow peach-slider-arrow-right',
                    'html'  : i
                })
                wrapper.append(btn);
            }
            else {
                wrapper.append($('<span>',{'class':'peach-slider-arrow-left'}))
                wrapper.append($('<span>',{'class':'peach-slider-arrow-right'}))
            }

            //dots
            if(options.dots && childs > 1) {
                var ul = $('<ul>', {
                    'class' : 'peach-slider-dots-container'
                })

                wrapper.append(ul);

                var li = '';

                for(var i=0; i<childs; i++) {
                    li = $('<li>', {
                        'data-params'   : i
                    })

                    ul.append(li)
                }

                ul.children().eq(0).addClass('peach-slider-active-dot');

                wrapper.on('click', '.peach-slider-dots-container li:not(.peach-slider-active-dot)',
                    function(e) {
                    var mul     = $(e.target).data('params');
                    var active  = $('.peach-slider-active-dot').data('params');

                    if(mul > active)
                        for(var i=active; i<mul; i++) {
                            $('.peach-slider-arrow-left').trigger('click');
                        }
                    else {
                        for(var i=mul; i<active; i++) {
                            $('.peach-slider-arrow-right').trigger('click');
                        }
                    }

                    $('.peach-slider-active-dot').removeClass('peach-slider-active-dot');
                    $(e.target).addClass('peach-slider-active-dot');
                })

                wrapper.on('click', '.peach-slider-arrow-left', function(){
                    var active = wrapper.find('.peach-slider-active-dot').data('params');

                    if(active+1 < childs) {
                        wrapper.find('.peach-slider-active-dot').removeClass('peach-slider-active-dot');
                        wrapper.find('.peach-slider-dots-container').children()
                            .eq(active+1).addClass('peach-slider-active-dot');
                    }
                    else {
                        wrapper.find('.peach-slider-active-dot')
                            .removeClass('peach-slider-active-dot');
                        wrapper.find('.peach-slider-dots-container').children().eq(0)
                            .addClass('peach-slider-active-dot');
                    }
                })

                wrapper.on('click', '.peach-slider-arrow-right', function() {
                    var active = wrapper.find('.peach-slider-active-dot').data('params');

                    if(active > 0) {
                        wrapper.find('.peach-slider-active-dot')
                            .removeClass('peach-slider-active-dot');
                        wrapper.find('.peach-slider-dots-container')
                            .children().eq(active-1).addClass('peach-slider-active-dot');
                    }
                    else {
                        wrapper.find('.peach-slider-active-dot').removeClass('peach-slider-active-dot');
                        wrapper.find('.peach-slider-dots-container').children().eq(childs-1)
                            .addClass('peach-slider-active-dot');
                    }
                })
            }

            wrapper.on('click', '.peach-slider-arrow-left', function(){

                if(options.circle) {
                    _this.queue(function() {
                        _this.animate(
                            {'left' : _this[0].offsetLeft - wrapper.width()},
                            "normal",
                            'linear',
                            function() {
                                _this.css({'left' : _this[0].offsetLeft + wrapper.width()});
                                var tmp = $(_this.children()[0]).detach();
                                _this.append(tmp);
                            }
                        ).dequeue();
                    })
                }
                else {
                    _this.animate({'left' : _this[0].offsetLeft - wrapper.width()});

                    if(current_el+1 == childs) {
                        $('.peach-slider-arrow-left').css('display', 'none');
                    }

                    if(current_el+1 > 1 && $('.peach-slider-arrow-right').css('display') == 'none') {
                        $('.peach-slider-arrow-right').css('display', 'table-cell');
                    }
                }

                current_el++;
            })

            if(!options.circle && current_el == 1) {
                $('.peach-slider-arrow-right').css('display', 'none')
            }

            wrapper.on('click', '.peach-slider-arrow-right', function(){
                if(options.circle) {
                    _this.queue(function(){
                        var tmp = $(_this.children()[childs-1]).detach();
                        _this.css({'left' : _this[0].offsetLeft - wrapper.width()});
                        _this.prepend(tmp);
                        _this.dequeue();
                    }).animate(
                            {'left' : _this[0].offsetLeft + wrapper.width()},
                            "normal",
                            "linear",
                             function() {

                             }
                        )
                }
                else {
                    _this.animate({'left' : _this[0].offsetLeft + wrapper.width()});

                    if(current_el-1 == 1) {
                        $('.peach-slider-arrow-right').css('display', 'none');
                    }

                    if(current_el > 0 && $('.peach-slider-arrow-left').css('display') == 'none') {
                        $('.peach-slider-arrow-left').css('display', 'table-cell')
                    }
                }

                current_el--;
            })

            //autoplay
            if(options.autoplay.enabled) {

                //2000 - min duration value
                if(options.autoplay.duration < 2000) {
                    options.autoplay.duration = 2000;
                }

                //play/pause button
                if(options.autoplay.button) {
                    var i = $('<i>', {
                        'class' : 'icon-pause'
                    })

                    var div = $('<div>', {
                        'class' : 'peach-play-btn peach-pause',
                        html    : i
                    })

                    wrapper.append(div);
                }

                function play_interval(){
                    if($('.peach-slider-arrow-left').is('div')) {
                        $('.peach-slider-arrow-left').trigger('click');
                    }
                    else {
                        clearInterval(interval);
                    }
                }

                var interval;

                interval = setInterval(play_interval, options.autoplay.duration)

                wrapper.on('click', '.peach-pause', function(e){
                    if(e.target.tagName == 'DIV') {
                        $(e.target).removeClass('peach-pause').addClass('peach-play');
                        $(e.target).children('i').attr('class', 'icon-play');
                    }
                    else {
                        $(e.target).attr('class', 'icon-play');
                        $(e.target).parent('div').removeClass('peach-pause').addClass('peach-play');
                    }

                    clearInterval(interval);

                    if(pb_interval) {
                        clearInterval(pb_interval);
                    }
                })

                wrapper.on('click', '.peach-play', function(e){
                    if(e.target.tagName == 'DIV') {
                        $(e.target).removeClass('peach-play').addClass('peach-pause');
                        $(e.target).children('i').attr('class', 'icon-pause');
                    }
                    else {
                        $(e.target).attr('class', 'icon-pause');
                        $(e.target).parent('div').removeClass('peach-play').addClass('peach-pause');
                    }

                    interval = setInterval(play_interval, options.autoplay.duration);

                    if(options.autoplay.progress_bar) {
                        pb_interval = setInterval(play_pb, options.autoplay.duration);
                        pb_animate()
                    }
                })

                //progress bar
                if(options.autoplay.progress_bar) {
                    var pb = $('<div>', {
                        'class' : 'peach-slider-pb'
                    })
                    wrapper.append(pb);

                    //todo bug
                    _this.children('li:last').children('img').ready(function(){
                        pb_animate()
                    })

                    function pb_animate() {
                        $('.peach-slider-pb').animate(
                            {'width':'100%'},
                            options.autoplay.duration,
                            "linear",
                            function() {
                                $('.peach-slider-pb').width(0)
                            }
                        );
                    }

                    function play_pb() {
                        if($('.peach-slider-pb').is('div')) {
                            pb_animate();
                        }
                        else {
                            clearInterval(pb_interval)
                        }
                    }

                    var pb_interval;

                    pb_interval = setInterval(play_pb, options.autoplay.duration);
                }
            }
        })
    }
})($)
