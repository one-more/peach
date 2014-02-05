$(function(){
   $('.rhinoslider').rhinoslider(
       {
           effect               : 'transfer',
           controlsPlayPause    : 'false',
           controlsPrevNext     : 'true'
       }
   )

   if($('.rhinoslider img').height() > $(window).height()*0.8) {
       $('.rhinoslider img').height($(window).height()*0.8).css('width', '100%');
   }
})
