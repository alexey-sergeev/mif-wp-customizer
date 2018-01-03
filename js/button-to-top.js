// 
// Кнопка "Наверх"
//
//

jQuery( document ).ready( function( $ ) {


	var button_to_top = $( '<span class="button_to_top"><i class="fa fa-angle-up fa-2x"></i></span>' ).appendTo( 'body' );

	button_to_top.click( function( event ) {
		event.preventDefault();
		$( 'html, body' ).animate({ scrollTop: 0 }, 600 );
	});



	function show_button_to_top() 
    {
		if ( $( window ).scrollTop() > 800 ) {
            // button_to_top.fadeIn( 150 ) 
            button_to_top.addClass( 'show' );
        } else {
            // button_to_top.stop().fadeOut( 150 );
            button_to_top.removeClass( 'show' );
        }
        
	}

	$( window ).scroll( function() { show_button_to_top(); } );

	show_button_to_top();

});