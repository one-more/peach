ArticlesCreateUpdateView = Backbone.View.extend({
    initialize: function(){

        var interval = setInterval(function(){
            if($.fn.selectize && Switchery) {
               var cs = $('select[name=category]').selectize({

                   persist: false,

                   sortField: 'text',

                   create: function(text) {

                        var select = cs[0].selectize;
                        var arr = cs[0].selectize.options;
                        var max = 1;
                        var dup = false;
                        $.each(arr, function(k,v){
                            if(v.value > max) {
                                max = v.value;
                            }

                            if(v.text == text) {
                                dup = v.value;
                            }
                        })

                        if(!dup) {
                            $.post(
                                'index.php',
                                {
                                    'class'         : 'articles',
                                    'controller'    : 'categories',
                                    'task'          : 'create',
                                    'params'        : text
                                }
                            )

                            return {
                                value   : Number(max)+1,
                                text    : text
                            }
                        }
                        else {
                            select.setValue(dup);

                            return {}
                        }
                    }
                })

                $.post(
                    'index.php',
                    {
                        'class'         : 'articles',
                        'controller'    : 'tags',
                        'task'          : 'get_tags'
                    },
                    function(data) {

                        var params = data || [];
                        var options = [];

                        params.forEach(function(k,v) {
                            options.push({
                                text: k.name,
                                value: k.name
                            })
                        })


                        $('input[name=tags]').selectize({
                            delimiter: ',',
                            plugins: ['remove_button'],
                            create: function(text) {

                                $.post(
                                    'index.php',
                                    {
                                        'class'         : 'articles',
                                        'controller'    : 'tags',
                                        'task'          : 'create',
                                        'params'        : text
                                    }
                                )

                                return {
                                    value: text,
                                    text: text
                                }
                            },
                            options: options
                        })
                    }
                )

                new Switchery(document.querySelector('#chbx-pub'))

                clearInterval(interval)
            }
        }, 50)
    }
})

ArticlesCreateUpdateView = new ArticlesCreateUpdateView;
