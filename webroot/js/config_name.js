var current_script = document.currentScript;
var data_types 	 = JSON.parse(current_script.getAttribute('data-name-types'));
var data_display = JSON.parse(current_script.getAttribute('data-name-display'));
var data_short	 = JSON.parse(current_script.getAttribute('data-name-short'));
var data_display_description = JSON.parse(current_script.getAttribute('data-name-display-description'));

var name_count = 0;
function addName( args ){
	if( typeof args === 'undefined' ){
		args = {};
	}

	var $table_element = $('#name_inputs');

	var $tr_element = $('<div></div>',{'class':'name_input'});

	var $name_cell = $('<div></div>', {'class': 'cell input_name'});
	var $name_element = $('<input/>', {
			'type': 'text',
			'name': 'names[' + name_count + '][name]',
		});
	if( 'name' in args ){
		$name_element.attr('value',args['name']);
	}
	$tr_element.append( $name_cell.append( $name_element ) );

	var $type_cell = $('<div></div>', {'class': 'cell input_name_type'});
	var $type_element = $('<select></select>', {
			'name': 'names[' + name_count + '][type]',
		});
	for( key in data_types ){
		var $type_opt = $('<option value="' + key + '">' + data_types[key] + '</option>', {'value': key });
		if( 'type' in args && args['type'] == key ){
			$type_opt.prop('selected', true );
		}
		$type_element.append( $type_opt );
	}
	$tr_element.append( $type_cell.append( $type_element ) );

//	var display_desc_id = 'display_desc[' + name_count + ']';
	var $display_cell = $('<div></div>', {'class':'cell input_name_display'});
	var $display_element = $('<select></select>', {
			'name': 'names[' + name_count + '][display]',
			'class': 'select_name_display',
		//	'desc_id': display_desc_id,
		});
	for( key in data_display ){
		var $display_opt = $('<option value="' + key + '">' + data_display[key] + '</option>');
		if( 'display' in args && args['display'] == key ){
			$display_opt.prop('selected', true );
		}
		$display_element.append( $display_opt );
	}
	var $display_desc_element = $('<span></span>', {
		//	'id': display_desc_id,
			'class': 'name_display_description'
		});
	$tr_element.append( $display_cell.append( $display_element ).append( $display_desc_element ) );
	setDisplayDescription( $display_element );

	$tr_element.append('<div class="cell name_short_label"><label>' + data_short + '</select></div>');

	var $short_cell = $('<div></div>',{'class':'cell input_name_short'});
	var $short_element = $('<input></input>', {
			'type': 'text',
			'name': 'names[' + name_count + '][short]',
		});
	if( 'short' in args ){
		$short_element.attr('value',args['short']);
	}
	$tr_element.append( $short_cell.append( $short_element ) );

	$table_element.append( $tr_element );

	//post adding name
	setShortEnabled( $display_element );
	$('#name_inputs').trigger('sortupdate');

	++name_count;
}

function setDisplayDescription( $el ){
//	console.log( $el.val() );
	var desc_str = data_display_description[ $el.val() ];
	$desc_el = $el.next();
	$desc_el.html( desc_str );
//	console.log( $desc_el.html() );
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

function updateNamePreview(){
	var str = [];
	var str_short = [];
	$inputs = $('.name_input');
	$inputs.each( function( i, el ){
		var el_str  = $(el).children('.input_name').children('input').val();
		var display = $(el).children('.input_name_display').children('select').val();
	//	console.log( str + ': ' + display );
		if( display != 'private' ){
			if( el_str.length ){
				str.push( el_str );
			}

			if( display == 'short' ){
				var el_short = $(el).children('.input_name_short').children('input').val();
				if( el_short.length ){
					str_short.push( el_short );
				}
			}
			else if( display != 'omit' ){
				if( el_str.length ){
					str_short.push( el_str );
				}
			}
		}
	});

	var html = '';
	if( str.length ){
		html = str.join(' ') + ' => ' + ( (str_short.length) ? str_short.join(' ') : '""' );
	}
	else if( str_short.length ){
		html += str_short.join(' ');
	}
	$('#name_preview').html( html );
}

$( function($){
	$(document).ready( function(){
		names = [{}];
		$.extend(names,JSON.parse(current_script.getAttribute('data-names')) );
		for( el of names ){
			addName( el );
		};
		updateNamePreview();
	});

	$('#name_inputs').sortable();
	$('#name_inputs').on( 'sortstop', function( ev, ui ){
		var $rows = $('.name_input').each( function( i, el ) {
			$(el).find('input,select').each( function( j, i_el ){
				var name_old = $(i_el).attr('name');
				var name_new = name_old.replace(/(names\[)[0-9]*(\])/,'$1'+i+'$2');
				$(i_el).attr('name',name_new);
			});
		});
		name_count = $rows.length;
		updateNamePreview();
	});

	$(document).on( 'change', '.select_name_display', function(){
		console.log( $(this).val() );
		setDisplayDescription( $(this) );
		setShortEnabled( $(this) );
	} );

	$(document).on( 'keyup', '.name_input input', function(){
		updateNamePreview();
	});
	$(document).on( 'change', '.name_input select', function(){
		updateNamePreview();
	});

	$("form").submit( function(event){
		alert( $(this).prop('action') );
	});
});