(function($){
    $.fn.peach_slider = function(params) {
        return $(this).each(function() {
            var defaults = {
                width           : $(this).parent().width() || 600,
                height          : false,
                circle          : true,
                fit_height      : true,
                arrows          : true,
                dots            : true
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
            }

            _this.wrap(wrapper);

            wrapper = $('.peach-slider-wrapper');

            _this.attr('class', 'inline-ul margin-padding-0 position-relative')

            _this.children('li').each(function(){
                $(this).width(wrapper.width())
                $(this).children(0).width(wrapper.width())

                if(options.fit_height && $(this).height() > $(window).height()*0.7) {
                    $(this).children(0).height($(window).height() * 0.7).css('width', '100%')
                }
            })

            width = _this.children('li').length * wrapper.width()

            _this.width(width);

            if(options.arrows && childs > 1) {
                var i = $('<i>', {
                    'class' : 'icon-chevron-left'
                });
                var btn = $('<div>', {
                    'class' : 'arrow arrow-left',
                    'html'  : i
                })
                wrapper.append(btn)

                i = $('<i>', {
                    'class' : 'icon-chevron-right'
                })
                btn = $('<div>', {
                    'class' : 'arrow arrow-right',
                    'html'  : i
                })
                wrapper.append(btn);
            }
            else {
                wrapper.append($('<span>',{'class':'arrow-left'}))
                wrapper.append($('<span>',{'class':'arrow-right'}))
            }

            if(options.dots && childs > 1) {
                var ul = $('<ul>', {
                    'class' : 'dots-container'
                })

                wrapper.append(ul);

                var li = '';

                for(var i=0; i<childs; i++) {
                    li = $('<li>', {
                        'data-params'   : i
                    })

                    ul.append(li)
                }

                ul.children().eq(0).addClass('active-dot');

                wrapper.on('click', '.dots-container li:not(.active-dot)', function(e) {
                    var mul     = $(e.target).data('params');
                    var active  = $('.active-dot').data('params');

                    if(mul > active)
                        for(var i=active; i<mul; i++) {
                            $('.arrow-left').trigger('click');
                        }
                    else {
                        for(var i=mul; i<active; i++) {
                            $('.arrow-right').trigger('click');
                        }
                    }

                    $('.active-dot').removeClass('active-dot');
                    $(e.target).addClass('active-dot');
                })

                wrapper.on('click', '.arrow-left', function(){
                    var active = wrapper.find('.active-dot').data('params');

                    if(active+1 < childs) {
                        wrapper.find('.active-dot').removeClass('active-dot');
                        wrapper.find('.dots-container').children().eq(active+1).addClass('active-dot');
                    }
                    else {
                        wrapper.find('.active-dot').removeClass('active-dot');
                        wrapper.find('.dots-container').children().eq(0).addClass('active-dot');
                    }
                })

                wrapper.on('click', '.arrow-right', function() {
                    var active = wrapper.find('.active-dot').data('params');

                    if(active > 0) {
                        wrapper.find('.active-dot').removeClass('active-dot');
                        wrapper.find('.dots-container').children().eq(active-1).addClass('active-dot');
                    }
                    else {
                        wrapper.find('.active-dot').removeClass('active-dot');
                        wrapper.find('.dots-container').children().eq(childs-1).addClass('active-dot');
                    }
                })
            }

            wrapper.on('click', '.arrow-left', function(){

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
                        )
                        _this.dequeue();
                    })
                }
                else {
                    _this.animate({'left' : _this[0].offsetLeft - wrapper.width()});

                    if(current_el+1 == childs) {
                        $('.arrow-left').css('display', 'none');
                    }

                    if(current_el+1 > 1 && $('.arrow-right').css('display') == 'none') {
                        $('.arrow-right').css('display', 'table-cell');
                    }
                }

                current_el++;
            })

            if(!options.circle && current_el == 1) {
                $('.arrow-right').css('display', 'none')
            }

            wrapper.on('click', '.arrow-right', function(){
                if(options.circle) {
                    _this.queue(function(){
                        var tmp = $(_this.children()[childs-1]).detach();
                        _this.css({'left' : _this[0].offsetLeft - wrapper.width()});
                        _this.prepend(tmp);
                        _this.animate({'left' : _this[0].offsetLeft + wrapper.width()});
                        _this.dequeue();
                    })
                }
                else {
                    _this.animate({'left' : _this[0].offsetLeft + wrapper.width()});

                    if(current_el-1 == 1) {
                        $('.arrow-right').css('display', 'none');
                    }

                    if(current_el > 0 && $('.arrow-left').css('display') == 'none') {
                        $('.arrow-left').css('display', 'table-cell')
                    }
                }

                current_el--;
            })
        })
    }
})($)
