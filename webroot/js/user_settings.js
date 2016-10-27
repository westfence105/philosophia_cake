var translations	= {};
var name_types 		= {};
var name_displaying = {};
var display_descs	= {};

$( function(){
	translations	= $('#resources').data('translations');
	name_types 		= $('#resources').data('name-types');
	name_displaying = $('#resources').data('name-displaying');
	display_descs	= $('#resources').data('name-display-description');
	$('.name_input').each( function(){
		if( !$(this).html() ){
			addName($(this));
		}
	});

	$('#name_inputs').sortable();
	$('#name_inputs').on('sortstop', numberNames );

	$(document).on('keyup',  '.name_input input',  updateNamePreview );
	$(document).on('change', '.name_input select', updateNamePreview );
	$(document).on('change', '.input_name_display select', function(){
			setDisplayDescription( $(this) );
			setShortEnabled( $(this) );
		});
	$('.add_name').on('click', addName );
});

function addName( $el ){
	if(!$el[0]){
		$el = $('<div></div>').attr('class','name_input');
		$('#name_inputs').append($el);
	}
	var name 	= $el.attr('data-name');
	var type 	= $el.attr('data-name-type');
	var display = $el.attr('data-name-display');
	var short 	= $el.attr('data-name-short');
	$el.removeAttr('data-name data-name-type data-name-display data-name-short');

	var $el_name = $('<input/>', {'type':'text', 'name':'names[][name]'});
	if( name ){
		$el_name.attr('value', name);
	}
	$el.append( $('<div></div>',{'class':'cell input_name'}).append($el_name) );

	console.log(typeof name_types);
	var $el_type = $('<select></select>',{'name':'names[][type]'});
	$.each( name_types, function( val, str ){
		$el_opt = $('<option></option>',{'value': val }).text( str );
		if( type == val ){
			$el_opt.prop('selected',true);
		}
		$el_type.append($el_opt);
	});
	$el.append( $('<div></div>',{'class':'cell input_name_type'}).append($el_type) );

	var $el_display = $('<select></select>',{'name':'names[][display]'});
	$.each( name_displaying, function( val, str ){
		$el_opt = $('<option></option>',{'value': val }).text( str );
		if( display == val ){
			$el_opt.prop('selected',true);
		}
		$el_display.append($el_opt);
	});
	$el_desc = $('<span></span>',{'class':'name_display_description'});
	$el.append( $('<div></div>',{'class':'cell input_name_display'}).append($el_display).append($el_desc) );

	var $el_short_label = $('<label></label>',{'class':'cell name_short_label'});
	if( translations && 'short' in translations ){
		$el_short_label.text( translations['short'] );
	}
	else {
		$el_short_label.text('Short');
	}
	$el.append( $('<div></div>',{'class':'cell label_name_short'}).append($el_short_label) );

	var $el_short = $('<input/>', {'type':'text', 'name':'names[][short]'});
	if( short ){
		$el_short.attr('value', short);
	}
	$el.append( $('<div></div>',{'class':'cell input_name_short'}).append($el_short) );

	numberNames();
	setDisplayDescription($el_display);
	setShortEnabled($el_display);
}

function numberNames(){
	$('.name_input').each( function ( i, el ){
		$(el).find('input,select').each( function( j, el_input ){
			var name_old = $(el_input).attr('name');
			var name_new = name_old.replace(/(names\[)[0-9]*(\])/,'$1'+i+'$2');
			$(el_input).attr('name',name_new);
		});
	});
	updateNamePreview();
}

function updateNamePreview(){
	var str = [];
	var short = [];
	$('.name_input').each( function(i,el){
		var el_str  = $(el).children('.input_name').children('input').val();
		var display = $(el).children('.input_name_display').children('select').val();
		if( display != 'private' ){
			if( el_str ){
				str.push(el_str);
			}
			if( display == 'display' ){
				short.push(el_str);
			}
			else if( display == 'short' ){
				var el_short = $(el).children('.input_name_short').children('input').val();
				if( el_short ){
					short.push(el_short);
				}
			}
		}
	});
	var text = '';
	if( str.length ){
		text = str.join(' ') + ' => ';
		if( short.length ){
			text += short.join(' ');
		}
		else {
			text += '""';
		}
	}
	else if( short.length ){
		text = short.join(' ');
	}
	$('#name_preview').text(text);
}

function setDisplayDescription( $el ){
	var desc_str = display_descs[ $el.val() ];
	$desc_el = $el.next();
	$desc_el.html( desc_str );
}

function setShortEnabled( $el ){
	$el_short	= $el.parent().siblings('.input_name_short').children('input');
	if( $el.val() == 'short' ){
		$el_short.prop('disabled',false);
	}
	else{
		$el_short.prop('disabled',true);
	}
}