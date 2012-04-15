$(function() {

	// Declare variables
	var mouse_is_inside = false;

	// Hiding stuffs that should be hidden but needs to be visible if js is turned off
	$('#userBar').hide();

	// Handling showing and hiding of the user menu
	$('#account').hover(function() {
		mouse_is_inside=true;
	},function() {
		mouse_is_inside=false;
	});

    $('#account').click(function(){
    	$('#userBar').show();
    });

    $("body").mouseup(function(){
    	if(!mouse_is_inside)$('#userBar').hide();
    });


}); 