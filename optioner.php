<?php
/**
 * Plugin Name: Optioner
 * Description: Minimal option framework.
 * Version: 1.0.0
 * Author: Nilambar Sharma
 * Author URI: https://www.nilambar.net
 * Text Domain: optioner
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package Optioner
 */

namespace Nilambar\Optioner;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'OPTIONER_BASENAME', basename( dirname( __FILE__ ) ) );
define( 'OPTIONER_DIR', rtrim( plugin_dir_path( __FILE__ ), '/' ) );
define( 'OPTIONER_URL', rtrim( plugin_dir_url( __FILE__ ), '/' ) );

require_once OPTIONER_DIR . '/src/Optioner.php';

$obj = new Optioner();

$obj->set_page();

$obj->add_tab(
	array(
		'id'    => 'first_tab',
		'title' => esc_html__( 'First Tab', 'optioner' ),
	)
);

// Field: awesome_heading.
$obj->add_field(
	'first_tab',
	array(
		'id'          => 'awesome_heading',
		'type'        => 'heading',
		'title'       => esc_html__( 'Awesome Section', 'optioner' ),
		'description' => esc_html__( 'This is description of awesome section.', 'optioner' ),
	)
);

// Field: Hello Text.
$obj->add_field(
	'first_tab',
	array(
		'id'          => 'sample_text',
		'type'        => 'text',
		'title'       => esc_html__( 'Sample Text', 'optioner' ),
		'description' => esc_html__( 'Description for sample text', 'optioner' ),
		'placeholder' => esc_html__( 'Enter text', 'optioner' ),
	)
);

// Field: sample_select.
$obj->add_field(
	'first_tab',
	array(
		'id'          => 'sample_select',
		'type'        => 'select',
		'title'       => esc_html__( 'Sample Select', 'optioner' ),
		'description' => esc_html__( 'Description of sample select.', 'optioner' ),
		'allow_null'  => true,
		'choices'     => array(
			'1' => esc_html__( 'First', 'optioner' ),
			'2' => esc_html__( 'Second', 'optioner' ),
			'3' => esc_html__( 'Third', 'optioner' ),
		),
	)
);

// Field: sample_select_no_null.
$obj->add_field(
	'first_tab',
	array(
		'id'          => 'sample_select_no_null',
		'type'        => 'select',
		'title'       => esc_html__( 'Sample Select No Null', 'optioner' ),
		'choices'     => array(
			'1' => esc_html__( 'First', 'optioner' ),
			'2' => esc_html__( 'Second', 'optioner' ),
			'3' => esc_html__( 'Third', 'optioner' ),
		),
	)
);

// Field: radio_horizontal.
$obj->add_field(
	'first_tab',
	array(
		'id'          => 'radio_horizontal',
		'type'        => 'radio',
		'default'     => '1',
		'layout'      => 'horizontal',
		'title'       => esc_html__( 'Radio Horizontal', 'optioner' ),
		'description' => esc_html__( 'Description of radio horizontal.', 'optioner' ),
		'choices'     => array(
			'1' => esc_html__( 'First', 'optioner' ),
			'2' => esc_html__( 'Second', 'optioner' ),
			'3' => esc_html__( 'Third', 'optioner' ),
		),
	)
);

// Field: radio_vertical.
$obj->add_field(
	'first_tab',
	array(
		'id'          => 'radio_vertical',
		'type'        => 'radio',
		'title'       => esc_html__( 'Radio Vertical', 'optioner' ),
		'description' => esc_html__( 'Description of radio vertical.', 'optioner' ),
		'choices'     => array(
			'1' => esc_html__( 'First', 'optioner' ),
			'2' => esc_html__( 'Second', 'optioner' ),
			'3' => esc_html__( 'Third', 'optioner' ),
		),
	)
);

// Field: sample_color.
$obj->add_field(
	'first_tab',
	array(
		'id'          => 'sample_color',
		'type'        => 'color',
		'title'       => esc_html__( 'Sample Color', 'optioner' ),
		'description' => esc_html__( 'Description of sample color.', 'optioner' ),
		'default'     => '#8224e3',
	)
);

// Field: Heading.
$obj->add_field(
	'first_tab',
	array(
		'id'          => 'test_heading2',
		'type'        => 'heading',
		'title'       => esc_html__( 'Good heading2', 'optioner' ),
		'description' => esc_html__( 'Good heading description2', 'optioner' ),
	)
);

// Field: Age.
$obj->add_field(
	'first_tab',
	array(
		'id'          => 'age',
		'type'        => 'text',
		'title'       => esc_html__( 'Age', 'optioner' ),
		'description' => esc_html__( 'Text input description', 'optioner' ),
	)
);


$obj->add_tab(
	array(
		'id'    => 'second_tab',
		'title' => 'Second Tab',
	)
);

// Field: Hello Textarea.
$obj->add_field(
	'second_tab',
	array(
		'id'          => 'hello_textarea',
		'type'        => 'textarea',
		'title'       => esc_html__( 'Hello Textarea', 'optioner' ),
		'description' => esc_html__( 'Textarea input description', 'optioner' ),
		'default'     => 'Default Textarea',

	)
);

$obj->run();
