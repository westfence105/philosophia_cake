$( function(){
	$('.sortable').sortable();

	showLoading();
	$.ajax({
		type: 'GET',
		url: './api/1.0/users/names.json',
		beforeSend: function(xhr){
			xhr.setRequestHeader('X-Csrf-Token', $('*[name="_csrfToken"]').val() );
		},
		success: function(data){
			debug(data);
			var $el_presets = $('#name_presets');
			var template = _.template( $('#template_name_preset').text() );
			var $presets = $.map( data, genPreset );
			$el_presets.append($presets);
			presetsChanged();
		},
		error: function(XMLHttpRequest,textStatus,errorThrown){
			alert( message('internal error occurred') );
			debug('-- Error --');
			debug(errorThrown);
			debug(textStatus);
			debug('-----------');
		},
		complete: function(){
			hideLoading();
		}
	});

	$(document).on('sortstop', '.name_inputs', updateNamePreview );
	$(document).on('change', '.name_input select', updateNamePreview );
	$(document).on('change keyup',  '.name_input input',  updateNamePreview );
	$(document).on('change', '.input_name_display select', function(){
			setDisplayDescription( $(this) );
			setShortEnabled( $(this) );
		});
	$(document).on('change keyup', 'input.preset_name', function(){
			var val = $(this).val();
			$el_preview = $(this).siblings('.preset_name_preview');
			if( val.length == 0 ){
				$el_preview.text('');
			}
			else if( ([2,3,5,6]).indexOf(val.length) >= 0 ){
				if( val ){
					$.ajax({
						type: 'GET',
						url: './resources/language-name',
						data: {
							lang: val,
						},
						success: function(data){
							$el_preview.text(' => '+ data );
						},
						error: function(XMLHttpRequest,textStatus,errorThrown){
							debug('error: '+errorThrown);
							$el_preview.text('');
						}
					});
				}
				else {
					$el_preview.text('');
				}
			}
		});
	$(document).on('click', '.edit_name', function(){
			var $el_preset = $(this).closest('.name_preset');
			editName( $el_preset );
			$el_preset.find('.name_inputs').sortable();
			namesChanged($el_preset);
		});
	$(document).on('click', '.add_name', function(){
			var $el_preset = $(this).closest('.name_preset');
			var $el_inputs = $el_preset.find('.name_inputs');
			$el_inputs.append( $('#template_name_input').html() ).sortable('refresh');
			namesChanged($el_preset);
		});
	$(document).on('click', '.remove_name', function(){
			var $el_preset = $(this).closest('.name_preset');
			$(this).closest('.name_input').remove();
			namesChanged($el_preset);
		});
	$(document).on('click', '.name_preset .save', sendName );
	$(document).on('click', '.name_preset .cancel', function(){
			$el_preset = $(this).closest('.name_preset');
			$el_preset.find('.name_inputs').empty();
			nameEditCompleted();
		});

	$(document).on('click', '#add_preset', addPreset );
	$(document).on('click', '.remove_preset input', function(){
			removeNamePreset( $(this).closest('.name_preset') );
		});
});

function genPreset( names, preset ){
	showLoading();

	var data = {
			preset: preset,
			names:   names,
		};
	var template = _.template( $('#template_name_preset').text() );
	var $el_preset = $( template( data ) );
	$.ajax({
		type: 'GET',
		url: './resources/language-name',
		data: {
			lang: preset,
		},
		context : $el_preset,
		success: function(data){
			$(this).find('span[name="preset_name"]').text(data);
		},
		error: function(XMLHttpRequest,textStatus,errorThrown){
			debug('error: '+errorThrown);
		},
		complete: function(){
			hideLoading();
		}
	});
	return $el_preset;
}

function editName( $el_preset ){
	showLoading();

	$el_preset.find('input.preset_name').val($el_preset.data('preset-name'));
	var $el_template = $('#template_name_input');
	var $el_inputs = $.map( $el_preset.find('.names .name'), function(n){
						$el = $($el_template.html());
						var name 	= $(n).data('name');
						var type 	= $(n).data('name-type');
						var display = $(n).data('name-display');
						var short 	= $(n).data('name-short');
						if( name ){
							$el.find('.input_name input').val(name);
						}
						if( type ){
							$el.find('.input_name_type select').val(type);
						}
						if( display ){
							$el.find('.input_name_display select').val(display);
						}
						if( short ){
							$el.find('.input_name_short input').val(short);
						}
						return $el;
					});
//	debug($el_inputs);
	$('div.add_preset, div.edit_preset, div.remove_preset').hide();
	$el_preset.find('div.preset_name, div.names').hide();
	$el_preset.find('[class^="name_editor"]').show();
	$el_preset.find('div.name_inputs').append($el_inputs);

	hideLoading();
}

function nameEditCompleted(){
	$('div.add_preset, .name_preset > :not([class^="name_editor"])').show();
	$('[class^="name_editor"]').hide();
	presetsChanged();
}

function updateNamePreview(){
	var $parent = $(this).closest('.name_preset');
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
	var $el = $(this).closest('.name_preset');

	var preset_name_old = $el.data('preset-name');
	var preset_name_new = $el.find('input.preset_name').val();

	var preset_duplicate = false;
	$el.siblings().each( function(){
		if( $(this).data('preset-name') == preset_name_new ){
			preset_duplicate = true;
			return false; //means 'break;'
		}
	});
	if( preset_duplicate ){
		alert( message('error: preset name is duplicated') );
		return false;
	}

	var names = [];
	var blank = 0;
	$el.find('.name_input').each( function(){
		var name 	= $(this).find('.input_name input').val();
		var type 	= $(this).find('.input_name_type select').val();
		var display = $(this).find('.input_name_display select').val();
		var short 	= $(this).find('.input_name_short input').val();	
		if( name || ( display == 'short' && short ) ){
			names.push({
				'name':    name,
				'type':    type,
				'display': display,
				'short':   short,
			});
		}
		else {
			++blank;
		}
	});
	if( names.length == 0 ){
		if( $el.siblings().length ){
			var ret = confirm( message('nothing names entered.') + '\n' + message(' remove this preset?') );
			if( ret ){
				removeNamePreset($el);
			}
		}
		else {
			alert( message('nothing names entered') );
		}
		return false;
	}
	else if( blank ){
		var ret = confirm( message('empty field(s) are exist. are you sure to remove them?') );
		if( ! ret ){
			return false;
		}
	}

	var data = { 'names': names };
	if( preset_name_old != preset_name_new ){
		data['preset'] = preset_name_new;
	}

	showLoading();
	$.ajax({
		type: 'PUT',
		url: './api/1.0/users/names/' + preset_name_old + '.json',
		data: data,
		context: $el,
		beforeSend: function(xhr){
			xhr.setRequestHeader('X-Csrf-Token', $('*[name="_csrfToken"]').val() );
		},
		success: function(data){
			debug(data);
			if( data instanceof Object && 'names' in data && 'preset' in data ){
				$el.replaceWith( genPreset( data['names'], data['preset'] ) );
				nameEditCompleted();
				presetsChanged();
			}
		},
		error: function(XMLHttpRequest,textStatus,errorThrown){
			alert( message('internal error occurred') );
			debug('-- Error --');
			debug(errorThrown);
			debug('-----------');
		},
		complete: function(){
			hideLoading();
		}
	});
}

function addPreset(){
	var again = true;
	while( again ){
		again = false;

		var preset = window.prompt( message('please input new preset name') );
		if( preset == '' ){
			alert( message('error: preset name expected') );
			again = true;
		}

		var exist = false;
		$('.name_preset').each( function(){
			if( $(this).data('preset-name') == preset ){
				exist = true;
				return false;
			}
		});
		if( exist ){
			alert( message('error: preset name is duplicated') );
			again = true;
		}
	}

	if( preset != null ){
		showLoading();
		$.ajax({
			type: 'POST',
			url: './settings',
			data: {
				item: 'new_preset',
				preset: preset
			},
			beforeSend: function(xhr){
				xhr.setRequestHeader('X-Csrf-Token', $('*[name="_csrfToken"]').val() );
			},
			success: function(data){
				$names = $.map( data['name'], genPreset );
				$('#name_presets')
				$el.find('.edit_name').click();
			},
			error: function(XMLHttpRequest,textStatus,errorThrown){
				alert( message('internal error occurred') );
				debug('-- Error --');
				debug(errorThrown);
				debug('-----------');
			},
			complete: function(){
				hideLoading();
			}
		});
	}
}

function removeNamePreset( $el ){
	if( $('.name_preset').length <= 1 ){
		alert( message('1 or more preset(s) required.') );
		return false;
	}
	else if( ! window.confirm( message('are you sure to remove this preset?') ) ){
		return false;
	}

	showLoading();
	$.ajax({
		type: 'POST',
		url: './settings',
		data: {
			item: 'remove_preset',
			preset: $el.data('preset-name')
		},
		context: $el,
		beforeSend: function(xhr){
			xhr.setRequestHeader('X-Csrf-Token', $('*[name="_csrfToken"]').val() );
		},
		success: function(data){
			$(this).remove();
			nameEditCompleted();
			presetsChanged();
		},
		error: function(XMLHttpRequest,textStatus,errorThrown){
			alert( message('internal error occurred') );
			debug('-- Error --');
			debug(errorThrown);
			debug('-----------');
		},
		complete: function(){
			hideLoading();
		}
	});
}

function presetsChanged(){
	var count = $('.name_preset').length;
	if( 1 < count ){
		$('div.remove_preset').show();
	}
	else {
		$('div.remove_preset').hide();
	}
}

function namesChanged( $el ){
	$el.find('input,select').change();

	var $el_remove = $el.find('div.button_remove_name');
	if( $el.find('.name_input').length > 1 ){
		$el_remove.css('visiblity','visible');
	}
	else {
		$el_remove.css('visiblity','hidden');
	}
}