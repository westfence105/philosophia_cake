var current_script = document.currentScript;
var data_types 	 = JSON.parse(current_script.getAttribute('data-name-types'));
var data_display = JSON.parse(current_script.getAttribute('data-name-display'));
var data_short	 = JSON.parse(current_script.getAttribute('data-name-short'));
var data_display_description = JSON.parse(current_script.getAttribute('data-name-display-description'));

var name_count = 0;
function addName( name, type, display, short ){
	var table_element = document.getElementById('name_inputs');

	var tr_element = table_element.insertRow( table_element.rows.length );
	tr_element.setAttribute('class','name_input');

	var name_element = document.createElement('input');
	name_element.setAttribute('type','text');
	name_element.setAttribute('name','names[' + name_count + '][name]');
	if( name ){
		name_element.setAttribute('value',name);
	}
	var name_cell = tr_element.insertCell( tr_element.cells.length );
	name_cell.appendChild(name_element);
	name_cell.setAttribute('class','input_name')

	var type_element = document.createElement('select');
	type_element.setAttribute('name','names[' + name_count + '][type]');
	for( key in data_types ){
		var type_opt = document.createElement('option');
		type_opt.setAttribute('value',key);
		if( type == key ){
			type_opt.setAttribute('selected','selected');
		}
		type_opt.innerHTML = data_types[key];
		type_element.appendChild(type_opt);
	}
	var type_cell = tr_element.insertCell( tr_element.cells.length );
	type_cell.appendChild(type_element);
	type_cell.setAttribute('class','name_type');

	var display_desc_id = 'display_desc[' + name_count + ']';
	var display_element = document.createElement('select');
	display_element.setAttribute('name','names[' + name_count + '][display]');
	display_element.setAttribute('onChange', 'setDisplayDescription(this, "' + display_desc_id + '");');
	for( key in data_display ){
		var display_opt = document.createElement('option');
		display_opt.setAttribute('value',key);
		if( display == key ){
			display_opt.setAttribute('selected','selected');
		}
		display_opt.innerHTML = data_display[key];
		display_element.appendChild(display_opt);
	}
	var display_desc_element = document.createElement('span');
	display_desc_element.setAttribute('id', display_desc_id );
	display_desc_element.setAttribute('class', 'name_display_description');
	var display_cell = tr_element.insertCell( tr_element.cells.length );
	display_cell.appendChild(display_element);
	display_cell.appendChild(display_desc_element);
	display_cell.setAttribute('class','name_display');
	setDisplayDescription( display_element, display_desc_id );

	var short_label = document.createElement('label');
	short_label.innerHTML = data_short;
	var short_label_cell = tr_element.insertCell( tr_element.cells.length );
	short_label_cell.appendChild(short_label);
	short_label_cell.setAttribute('class','name_short_label');

	var short_element = document.createElement('input');
	short_element.setAttribute('type','text');
	short_element.setAttribute('name','names[' + name_count + '][short]');
	if( short ){
		short_element.setAttribute('value',short);
	}
	var short_cell = tr_element.insertCell( tr_element.cells.length );
	short_cell.appendChild(short_element);
	short_cell.setAttribute('class','name_short');

	++name_count;
}

function setDisplayDescription( sl, id ){
	desc_element = document.getElementById(id);
	desc_element.innerHTML = data_display_description[sl.value];
	console.log(desc_element.innerHTML);
}