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


function test_sidebar_cb_func() {
	echo 'I am sidebar';
}

$obj = new Optioner();

$obj->set_page();

$obj->add_tab(
	array(
		'id' => "first_tab",
		'title' => "First Tab",
		'subtitle' => "First Tab description",
	)
);

// Field: Hello Text.
$obj->add_field(
	'first_tab',
	array(
		'id'      => 'hello_text',
		'type'    => 'text',
		'title'    => __( 'Hello Text', 'optioner' ),
		'description'    => __( 'Text input description', 'optioner' ),
		'placeholder' => 'feri Default Text',
	)
);

// Field: World Text.
$obj->add_field(
	'first_tab',
	array(
		'id'          => 'world_text',
		'type'        => 'color',
		'title'       => __( 'World Text', 'optioner' ),
		'description' => __( 'Text input description', 'optioner' ),
		'default'     => '#ff00ff',
	)
);

$obj->add_tab(
	array(
		'id' => "second_tab",
		'title' => "Second Tab",
	)
);

// Field: Hello Textarea.
$obj->add_field(
	'second_tab',
	array(
		'id'          => 'hello_textarea',
		'type'        => 'textarea',
		'title'       => __( 'Hello Textarea', 'optioner' ),
		'description' => __( 'Textarea input description', 'optioner' ),
		'default'     => 'Default Textarea',

	)
);

// $obj->set_sidebar( __NAMESPACE__ . '\test_sidebar_cb_func' );

$obj->run();


// add_action('optioner_form_top_first_tab', __NAMESPACE__ . '\opt_first_top' );

// function opt_first_top() {
// 	echo 'I am first top';
// }

// add_action('optioner_form_bottom_first_tab', __NAMESPACE__ . '\opt_first_top' );

// function opt_first_bottom() {
// 	echo 'I am first bottom';
// }
