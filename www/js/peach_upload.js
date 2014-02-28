/**
* peach upload plugin V1.0
* 
* @author Nikolaev D.
*
* options:
* url 					- url to handler file
* multiple				- whether user can select one or multiple files
* dnd_area				- id or class of element which allows drag and drop
* filter_func			- this function called for each file. must return true, if file is right
* file_iteration_func	- this function called for each file
* progress_func			- listener for addEventListener 'progress'
* before_send			- before send execute function
* success				- success handler
* error					- error handler
*/
(function(){
	$.fn.peach_upload = function(params) {
		return $(this).each(function(){
			var options = {
				'url' 					: false,
				'multiple'				: true,
				'dnd_area'				: false,
				'filter_func'			: false,
				'file_iteration_func'	: false,
				'progress_func'			: false,
				'before_send'			: $.noop(),
				'success'				: $.noop(),
				'error'					: $.noop()	
			}

			options = $.extend(options, params);

			var list;

			var data = new FormData();

			if(options.dnd_area) {
				var sel = options.dnd_area;
				var el = document.getElementById(sel) || document.getElementsByClassName(sel)[0];
				el.addEventListener('dragover', function(e){
					e.stopPropagation();
					e.preventDefault();
					e.dataTransfer.dropEffect = 'copy';		
				}, false)
				el.addEventListener('drop', function(e){
					e.stopPropagation();
					e.preventDefault();					
					list = e.dataTransfer.files;

					var answ;					
					if(answ = iterate()) {
						send();
					}
				},false)
			}

			$(this).on('click', function(){
				var input = $('<input>', {
					'type'		: 'file',
					'multiple'	: options.multiple,
                    'style'     : 'position:fixed; top:-800%'
				})
				
				input.on('change', function(e){
					list = e.target.files;

					input.detach();

					if(iterate()) {
						send();
					}					
				})
					
				$('body').append(input); 
				
				input.trigger('click');

                setTimeout(function(){
                    input.detach()
                }, 60000 * 5);
			}) 

			function iterate() {
				var ret = true;				
				
				$.each(list, function(i,v){
					var ff = options.filter_func;
					if(typeof ff == 'function') {
						if(!ff(v)) {
							ret = false;
                            return false;
						}
					}
				
					var fi = options.file_iteration_func;
					if(typeof fi == 'function') {
						fi(v);
					}

					data.append('file-'+i, v);		
				})

				return ret;
			}

			function send(){
				if(options.url) {
					$.ajax({
						'url'			: options.url,
						'type'			: 'POST',
						xhr: function(){
							var myXHR = $.ajaxSettings.xhr();
							if(myXHR.upload) {
								if(typeof options.progress_func == 'function') {
									var pf = options.progress_func;
									myXHR.upload.addEventListener('progress', pf, false)	
								}
							}
							return myXHR;		
						},
						'cache'			: false,
						'data'			: data,
						contentType		: false,
						processData		: false,
						beforeSend		: options.before_send,
						success			: options.success,
						error			: options.error
					})
					data = new FormData();	
				}
			}
		})	
	}
})($) 
