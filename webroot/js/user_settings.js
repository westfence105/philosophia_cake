var translations	= {};
var name_types 		= {};
var name_displaying = {};
var display_descs	= {};

$( function(){
	translations	= $('#resources').data('translations');
	name_types 		= $('#resources').data('name-types');
	name_displaying = $('#resources').data('name-displaying');
	display_descs	= $('#resources').data('name-display-description');

	$('.sortable').sortable();
//	$('.sortable').on('sortstop', numberNames );

	$(document).on('keyup', '.name_input input',  updateNamePreview );
	$(document).on('change', '.name_input select', updateNamePreview );
	$(document).on('change', '.input_name_display select', function(){
			setDisplayDescription( $(this) );
			setShortEnabled( $(this) );
		});
	$('.add_name').on('click', addNameInput );
	$('.edit_name').on('click', editName );
	$(document).on('click', '.name_preset .save', sendName );
});

function editName(){
	var $el_preset = $(this).parents('.name_preset');

	var $el_preset_name = $el_preset.find('div.preset_name');
	var preset_name = $el_preset_name.data('preset-name');
	$el_preset_name.find('span.preset_name').replaceWith( 
		$('<input/>',
			{
				'type': 'text',
				'name': 'preset_name',
				'value': preset_name,
				'maxlength': 6,
			}
		));

	var $el_names = $el_preset.find('.names');
	var names = [];
	$el_names.children('.name').each( function(){
		names.push({
			'name': 	$(this).data('name'),
			'type': 	$(this).data('name-type'),
			'display': $(this).data('name-display'),
			'short': 	$(this).data('name-short'),
		});
	});
	$el_names.children().remove();
	$el_names.append( $('<div></div>',{'class':'name_preview'}) );
	var $el_name_inputs = $('<div></div>', {'class':'name_inputs'}).sortable();
	$.each( names, function(){
		addNameInput( $el_name_inputs, this );
	});
	$el_names.append( $el_name_inputs );

	$el_save = $('<input/>', {
					'type'	: 'button',
					'class'	: 'save',
					'value'	: ('save' in translations) ? translations['save'] : 'save' ,
				});
	$('<div></div>',{'class':'save_name'}).append($el_save).appendTo($el_names);

	$el_names.attr('style','width: 100%;');
	$el_preset.find('div.edit_preset').attr('style','display:none;');
}

function addNameInput( $parent, data ){
	if( !$parent[0] ){
		$parent = $(this).parents('.name_preset').find('.name_inputs');
		if( !$parent[0] ){
			return false;
		}
	}
	if( !data ){
		data = {};
	}
//	console.log(data);

	var $el = $('<div></div>',{'class':'name_input'});
	$parent.append( $el );

	var $el_name = $('<input/>', {'type':'text', 'name':'name.name'});
	if( 'name' in data ){
		$el_name.attr('value', data['name'] );
	}
	$el.append( $('<div></div>',{'class':'cell input_name'}).append($el_name) );

	var $el_type = $('<select></select>',{'name':'name.type'});
	$.each( name_types, function( val, str ){
		$el_opt = $('<option></option>',{'value': val }).text( str );
		if( 'type' in data && data['type'] == val ){
			$el_opt.prop('selected',true);
		}
		$el_type.append($el_opt);
	});
	$el.append( $('<div></div>',{'class':'cell input_name_type'}).append($el_type) );

	var $el_display = $('<select></select>',{'name':'name.display'});
	$.each( name_displaying, function( val, str ){
		$el_opt = $('<option></option>',{'value': val }).text( str );
		if( 'display' in data && data['display'] == val ){
			$el_opt.prop('selected',true);
		}
		$el_display.append($el_opt);
	});
	$el_desc = $('<span></span>',{'class':'description'});
	$el.append( $('<div></div>',{'class':'cell input_name_display'}).append($el_display).append($el_desc) );

	var $el_short_label = $('<label></label>',{'class':'cell name_short_label'});
	if( translations && 'short' in translations ){
		$el_short_label.text( translations['short'] );
	}
	else {
		$el_short_label.text('Short');
	}
	$el.append( $('<div></div>',{'class':'cell label_name_short'}).append($el_short_label) );

	var $el_short = $('<input/>', {'type':'text', 'name':'name.short'});
	if( 'short' in data ){
		$el_short.attr('value', data['short']);
	}
	$el.append( $('<div></div>',{'class':'cell input_name_short'}).append($el_short) );

	$( function(){
		$el.find('input,select').change();
		$parent.sortable('refresh');
	});
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
	var $parent = $(this).parents('.name_preset');
	var str = [];
	var short = [];
	$parent.find('.name_input').each( function(i,el){
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
	$parent.find('.name_preview').text(text);
}

function setDisplayDescription( $el ){
	var desc_str = display_descs[ $el.val() ];
	$el.siblings('.description').html( desc_str );
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

function sendName(){
	var $el = $(this).parents('.name_preset');

	var preset_name = $el.find('.preset_name input').val();
	console.log(preset_name);

	var names = [];
	$el.find('.name_input').each( function(){
		names.push({
			'name'	  : $(this).find('.input_name input').val(),
			'type'	  : $(this).find('.input_name_type select').val(),
			'display' : $(this).find('.input_name_display select').val(),
			'short'	  : $(this).find('.input_name_short input').val(),
		});
	});
	
	var data = {
		'names': {},
	};
	data['names'][preset_name] = names;
	console.log( JSON.stringify(data) );
}