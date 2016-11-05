$( function(){
	$('.sortable').sortable();

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
			$el_inputs.append( $('#templates').find('.name_input').clone() ).sortable('refresh');
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

	presetsChanged();
});

function editName( $el_preset ){
	showLoading();

	var $el_template = $('#templates').find('.name_input');
	var $el_inputs = $.map( $el_preset.find('.names .name'), function(n){
						$el = $el_template.clone();
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

	var data = {
		item: 'name_preset'
	};

	var preset_name_old = $el.data('preset-name');
	var preset_name_new = $el.find('input.preset_name').val();
	if( typeof preset_name_old !== 'undefined' && preset_name_old != preset_name_new ){
		data['preset'] = preset_name_old;
		data['preset_new'] = preset_name_new;
	}
	else if( typeof preset_name_new !== 'undefined' ){
		data['preset'] = preset_name_new;
	}
	else {
		data['preset'] = '';
	}

	var preset_duplicate = false;
	$el.siblings().each( function(){
		if( data['preset'] == $(this).data('preset-name') ){
			preset_duplicate = true;
			return false;
		}
	});
	if( preset_duplicate ){
		alert( message('error: preset name is duplicated') );
		return false;
	}

	var i = 0;
	var blank = 0;
	var wrote = 0;
	$el.find('.name_input').each( function(){
		var name 	= $(this).find('.input_name input').val();
		var type 	= $(this).find('.input_name_type select').val();
		var display = $(this).find('.input_name_display select').val();
		var short 	= $(this).find('.input_name_short input').val();
		if( name || display == 'short' && short ){
			var path_name = 'names['+i+']';
			data[path_name+'[name]'] 	= name;
			data[path_name+'[type]'] 	= type;
			data[path_name+'[display]'] = display;
			data[path_name+'[short]']	= short;
			++wrote;
			++i;
		}
		else {
			++blank;
		}
	});
	if( wrote == 0 ){
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

	showLoading();
	$.ajax({
		type: 'POST',
		url: './settings',
		data: data,
		beforeSend: function(xhr){
			xhr.setRequestHeader('X-Csrf-Token', $('*[name="_csrfToken"]').val() );
		},
		success: function(data){
			$el.replaceWith(data);
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

function addPreset(){
	var preset = window.prompt( message('please input new preset name') );
	if( preset != null ){
		var exist = false;
		$('.name_preset').each( function(){
			if( $(this).data('preset-name') == preset ){
				exist = true;
				return false;
			}
		});
		if( exist ){
			alert( message('error: preset name is duplicated') );
		}
		else {
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
					$el = $(data).appendTo('#name_presets');
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
		beforeSend: function(xhr){
			xhr.setRequestHeader('X-Csrf-Token', $('*[name="_csrfToken"]').val() );
		},
		success: function(data){
			$el.remove();
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