var current_script = document.currentScript;
var data_types 	 = JSON.parse(current_script.getAttribute('data-name-types'));
var data_display = JSON.parse(current_script.getAttribute('data-name-display'));
var data_short	 = JSON.parse(current_script.getAttribute('data-name-short'));
var data_display_description = JSON.parse(current_script.getAttribute('data-name-display-description'));

var name_count = 0;
function addName( args ){
	var $table_element = $('#name_inputs tbody');

	var $tr_element = $('<tr></tr>',{'class':'name_input'});

	var $name_cell = $('<td></td>', {'class': 'input_name'});
	var $name_element = $('<input/>', {
			'type': 'text',
			'name': 'names[' + name_count + '][name]',
		});
	if( 'name' in args ){
		$name_element.attr('value',args['name']);
	}
	$tr_element.append( $name_cell.append( $name_element ) );

	var $type_cell = $('<td></td>', {'class': 'input_name_type'});
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

	var display_desc_id = 'display_desc[' + name_count + ']';
	var $display_cell = $('<td></td>', {'class':'input_name_display'});
	var $display_element = $('<select></select>', {
			'name': 'names[' + name_count + '][display]',
			'class': 'select_name_display',
			'desc_id': display_desc_id,
		});
	for( key in data_display ){
		var $display_opt = $('<option value="' + key + '">' + data_display[key] + '</option>');
		if( 'display' in args && args['display'] == key ){
			$display_opt.prop('selected', true );
		}
		$display_element.append( $display_opt );
	}
	var $display_desc_element = $('<span></span>', {
			'id': display_desc_id,
			'class': 'name_display_description'
		});
	$tr_element.append( $display_cell.append( $display_element ).append( $display_desc_element ) );
	setDisplayDescription( $display_element );

	$tr_element.append('<td class="name_short_label"><label>' + data_short + '</select></td>');

	var $short_cell = $('<td></td>',{'class':'input_name_short'});
	var $short_element = $('<input></input>', {
			'type': 'text',
			'name': 'names[' + name_count + '][short]',
		});
	if( 'short' in args ){
		$short_element.attr('value',args['short']);
	}
	$tr_element.append( $short_cell.append( $short_element ) );

	$table_element.append( $tr_element );

	$('.sortable').trigger('sortupdate');

	++name_count;
}

function setDisplayDescription( el ){
	console.log( el.val() );
	desc_str = data_display_description[ el.val() ];
	el.next().html( desc_str );
	console.log( el.next().html() );
}

$( function($){
	$(document).ready( function(){
		names = [{}];
		$.extend(names,JSON.parse(current_script.getAttribute('data-names')) );
		for( el of names ){
			addName( el );
		};
		console.log($('script').attr('src'));
	});

	$('.sortable').sortable();

	$('.sortable').on( 'sortstop', function( ev, ui ){
		var $rows = $('.name_input').each( function( i, el ) {
		//	console.log(i);
		//	console.log(el);
			$(el).find('input,select').each( function( j, i_el ){
				var name_old = $(i_el).attr('name');
				var name_new = name_old.replace(/(names\[)[0-9]*(\])/,'$1'+i+'$2');
				$(i_el).attr('name',name_new);
			//	console.log( name_old + ' -> ' + name_new );
			});
		//	console.log('--');	
		});
		name_count = $rows.length;
	//	console.log(name_count);
	});

	$(document).on( 'click', 'label', function(){
		console.log( $(this).val() );
	} );

	$(document).on( 'change', '.select_name_display', function(){
		console.log( $(this).val() );
		setDisplayDescription( $(this) )
	} );

});
