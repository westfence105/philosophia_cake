var debug = function(){};
$( function(){
	if( $('#debug')[0] ){
		debug = console.log;
	}

	$('body').append( $('<div></div>',{'id':'loading_image'}).append( $('<img/>',{'src':'img/loading.gif'}) ) );
	$('body').append( $('<div></div>',{'id':'modal_bg'}) );

	setLoadingImagePosition();

	$(window).on( 'resize', setLoadingImagePosition );
});

var loads = 0;
function showLoading(){
	if( loads++ <= 0 ){
		$('#loading_image').css({ display: 'block' });
		$('#modal_bg').css({ display: 'block' });
	}
}

function hideLoading(){
	if( --loads <= 0 ){
		$('#loading_image').css({ display: 'none' });
		$('#modal_bg').css({ display: 'none' });
	}
}

function setLoadingImagePosition(){
	$el_img = $('#loading_image img');
	var img_w = $el_img.data('width');
	var img_h = $el_img.data('height');
	if( typeof img_w === 'undefined' || typeof img_h === 'undefined' ){
		var img = new Image();
		img.src = $el_img.attr('src');
		img_w = img.width;
		img_h = img.height;
	}

	var w = $(window).width();
	var h = $(window).height();

	$('#loading_image').css({
		left: (Math.floor(( w - img_w )/2)) + "px",
		top:  (Math.floor(( h - img_h )/2)) + "px",
		position: 'fixed',
	});
}