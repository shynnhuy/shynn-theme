<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

// Define variables to use them in the config below, if needed
$misc = us_config( 'elements_misc' );

// Structure template for all usage cases
return array(

	// Shows element's name in the editors UI
	'title' => 'Element name',

	// Shows element's description in the WPB "Add New Element" window
	'description' => 'Element description',

	// Defines tab in the WPB "Add New Element" window
	'category' => 'Post Elements',

	// Defines icon in the WPB "Add New Element" window
	'icon' => 'icon-wpb-graph',

	// Load JS file in the WPB element editing window
	'admin_enqueue_js' => 'site/framework/plugins-support/js_composer/js/us_icon_view.js',

	// Defines JS class to apply custom appearance in the WPB editor UI
	'js_view' => 'ViewUsIcon',

	// Sets element's settings and default values
	'params' => array(

		// Common params, which can be used in all options types
		'option_name' => array(

			// Shows name of option, can be absent
			'title' => 'Option name',

			// Sets type of option control. See all available types below
			'type' => 'text',

			// Shows description of option. Its appearance depends on "desc_" class
			'description' => 'Option description',

			// Sets default value
			'std' => '',

			// Adds css classes to customize appearance of option in the editing window
			'classes' => '',

			// Sets appearance of option via 2 or 3 columns in the editing window
			'cols' => 2,

			// Sets display conditions depending on other option's values
			'show_if' => array( 'some_option', '=', 'some_value' ),

			// Combines several options into separate tab in the editing window
			'group' => 'Tab Name',

			// Sets where the option can be used
			'context' => array( 'header', 'grid', 'shortcode', 'widget' ),

			// Shows option's name and value in the editors UI
			'admin_label' => TRUE,

			// Shows option's value inside a <div> in the editors UI
			'holder' => 'div',

			// Outputs the option depending on "if" condition, e.g. "plugin is active"
			'place_if' => class_exists( 'woocommerce' ),
		),

		/************ OPTIONS TYPES ************/

		// TEXT: single line text field with free user input, based on <input type="text">
		'option_name' => array(
			'type' => 'text',
			'placeholder' => '', // shows text inside a field
			'std' => '', // string
		),

		// TEXTAREA: multiple lines text field with free user input, based on <textarea>
		'option_name' => array(
			'type' => 'textarea',
			'placeholder' => '', // shows text inside a field
			'std' => '', // string
		),

		// SELECT: single selection between several values, based on <select>
		'option_name' => array(
			'type' => 'select',
			'is_advanced' => TRUE, // adds search field while selecting, based on "select2" script
			'options' => array( // shows possible values for selection
				'key1' => 'Value Name',
				'key2' => 'Value Name',
				'key3' => array( // sets <optgroup> for several values
					'optgroup' => TRUE,
					'title' => 'Values Group Name',
				),
			),
			'std' => 'key1', // string
		),

		// RADIO: single selection between several values, based on <input type="radio">
		'option_name' => array(
			'type' => 'radio',
			'options' => array( // shows possible values for selection
				'key1' => 'Value Name',
				'key2' => 'Value Name',
				'key3' => 'Value Name',
			),
			'std' => 'key1', // string
		),

		// CHECKBOXES: multiple selection between several values, based on several <input type="checkbox">
		'option_name' => array(
			'type' => 'checkboxes',
			'options' => array( // shows possible values for selection
				'key1' => 'Value Name',
				'key2' => 'Value Name',
				'key3' => 'Value Name',
			),
			'std' => array( 'key2' ), // array
		),

		// SWITCH: ON/OFF switch, based on a single <input type="checkbox">
		'option_name' => array(
			'type' => 'switch',
			'switch_text' => '', // shows text after switch, text is also clickable
			'std' => FALSE, // bool
		),

		// ICON: icon selection with preview, based on combined controls
		'option_name' => array(
			'type' => 'icon',
			'std' => 'fas|star', // string: "set|name"
		),

		// LINK: text field with checkboxes, based on combined controls
		'option_name' => array(
			'type' => 'link',
			'std' => array(), // array
			'shortcode_std' => '', // empty string for shortcode param
		),

		// COLOR: color picker, based on custom controls
		'option_name' => array(
			'type' => 'color',
			'std' => '#fff', // string: HEX or RGBA value
		),

		// UPLOAD: shows button with selection files from WordPress Media Library
		'option_name' => array(
			'type' => 'upload',
			'is_multiple' => TRUE, // enables slection of several files, default is FALSE
			'button_label' => 'Set image', // sets text on the button
			'extension' => 'png,jpg,jpeg,gif,svg', // sets available file types
		),

		// HEADING: used as visual separator between options
		'option_name' => array(
			'type' => 'heading',
		),

		// EDITOR: WordPress Classic Editor, used in shortcodes only
		'option_name' => array(
			'type' => 'editor',
			'std' => '', // string
		),

		// HTML: used for code input, based on <textarea>
		'option_name' => array(
			'type' => 'html',
			'encoded' => TRUE, // encodes the value to the base64
			'std' => '', // string
		),

		// USOF Design Options: adds special control for box properties: position, margin, border, padding
		'option_name' => array(
			'type' => 'design_options',
			'states' => array( 'default', 'tablets', 'mobiles' ), // separates box properties for every responsive state
			'with_position' => TRUE, // enables "position" property with "Position: absolute" switcher
			'std' => array(), // array
		),
		
		// WPB Design Options: adds special control for box properties: margin, border, padding and some additional options
		'option_name' => array(
			'type' => 'css_editor',
		),

		// GROUP: Group of several items. Every item may have all other option types. Group allows to add/delete/reorder items
		'option_name_group' => array(
			'type' => 'group',
			'is_sortable' => TRUE, // enables ordering items in the editing window
			'params' => array( // items with their settings and default values
				'item_name_1' => array(
					'type' => 'image',
					'std' => '',
				),
				'item_name_2' => array(
					'type' => 'text',
					'std' => '',
				),
			),
			'std' => array(), // array
		),

	),
);
