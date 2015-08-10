jQuery(document).ready(function(){
  jQuery(' #header #rightbanner .content #menu img').click(function(){
  	jQuery(' #suckerfishmenu').toggle();
  });
/*function debouncer( func , timeout ) {
   var timeoutID , timeout = timeout || 200;
   return function () {
      var scope = this , args = arguments;
      clearTimeout( timeoutID );
      timeoutID = setTimeout( function () {
          func.apply( scope , Array.prototype.slice.call( args ) );
      } , timeout );
   }
}


jQuery( window ).resize( debouncer( function ( e ) {
    location.reload();
} ) );
  	*/

});