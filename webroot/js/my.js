var DEBUG = false;
$( function(){
	DEBUG = $('#debug')[0];
});

function debug( val ){
	if( DEBUG && typeof val !== 'undefined' ){
		console.log(val);
	}
}