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

// $obj = new Optioner();

$my_settings_another = array(
	'page_title'  => 'Another NPF Demo',
	'menu_title'  => 'Another NPF Demo',
	'capability'  => 'administrator',
	'menu_slug'   => 'another-npf-demo-page',
	'option_slug' => 'another_npf_demo_option',

	// tab start
	'tabs' => array(

		'general' => array(
			'id'    => 'general',
			'title' => 'General',
			'sub_heading' => 'General sub heading here',
			'fields' => array(
				'sample_text' => array(
					'id'          => 'sample_text',
					'title'       => 'Another Sample Text',
					'type'        => 'text',
					'default'     => 'default text',
					'description' => 'Description of another sample text.',
					),

				'sample_wysiwyg' => array(
					'id'          => 'sample_wysiwyg',
					'title'       => 'Another Sample Wysiwyg',
					'type'        => 'textarea',
					),
				),

			),
		'header' => array(
			'id'    => 'header',
			'title' => 'Header',
			'sub_heading' => 'Header sub heading here',
			'fields' => array(
				'header_title' => array(
					'id'          => 'header_title',
					'title'       => 'Header Title',
					'type'        => 'text',
					'description' => 'Please Enter Header Title',
					),
				'header_intro' => array(
					'id'          => 'header_intro',
					'title'       => 'Header Intro',
					'type'        => 'textarea',
					'description' => 'Please Enter Header Intro',
					),
				),
			),

		),
	);


$npf_demo_object_another = new Optioner($my_settings_another);
