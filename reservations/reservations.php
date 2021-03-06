<?php
/**
 * Plugin Name: Reservations
 * Plugin URI: http://swiatek.biz
 * Description: Plugin for Billboard reservations
 * Version: 0.1
 * Author: Bartłomiej Świątek
 * Author URI: http://swiatek.biz
 * License: GPL2
 */
 
register_activation_hook( __FILE__, 'bs_reservations_install' );
function bs_reservations_install() {
	global $wpdb;
	$tablename = $wpdb->prefix."tablica";
	if( $wpdb->get_var("SHOW TABLES LIKE '$tablename'") != $tablename ) {
		$sql = "CREATE TABLE $tablename (
			tablica_id INT(11) NOT NULL AUTO_INCREMENT,
			tablica_id_rozmiar INT(11) NOT NULL,
			tablica_miasto VARCHAR(100),
			tablica_ulica VARCHAR(100),
			tablica_wojewodztwo VARCHAR(100),
			tablica_zdjecie TEXT,
			tablica_link TEXT,
			PRIMARY KEY (tablica_id)
		);";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}
	
	$tablename = $wpdb->prefix."rezerwacje";
	if( $wpdb->get_var("SHOW TABLES LIKE '$tablename'") != $tablename ) {
		$sql = "CREATE TABLE $tablename (
			rezerwacja_id INT(11) NOT NULL AUTO_INCREMENT,
			rezerwacja_id_tablicy INT(11) NOT NULL,
			rezerwacja_od DATE,
			rezerwacja_do DATE,
			rezerwacja_typ ENUM('rezerwacja','zajeta') NOT NULL default 'zajeta',
			PRIMARY KEY (rezerwacja_id)
		);";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}
	
	$options = array('12x3');
	add_option( 'bs_reservations_options', $options );
}
register_deactivation_hook( __FILE__, 'bs_reservations_uninstall' );
function bs_reservations_uninstall() {
	delete_option( 'bs_reservations_options');
}

add_action( 'plugins_loaded', 'bs_reservations_message_plugin_setup' );
function bs_reservations_message_plugin_setup() {
	/* Add the footer message action. */
	add_action( 'admin_menu', 'bs_reservations_create_menu');
}
function bs_reservations_create_menu() {
	//create top-level menu
	add_menu_page( 'Zarządzanie tablicami', 'Tablice', 'manage_options', __FILE__,'bs_reservations_zarzadzanie_tablicami' );
	
	//create submenu items
	add_submenu_page( __FILE__, 'Zarządzanie rezerwacjami', 'Rezerwacje', 'manage_options', __FILE__.'_zarzadzanie_rezerwacjami', bs_reservations_zarzadzanie_rezerwacjami );
	add_submenu_page( __FILE__, 'Zarządzanie rozmiarami tablic', 'Rozmiary tablic', 'manage_options', __FILE__.'_zarzadzanie_rozmiarami_tablic', bs_reservations_zarzadzanie_rozmiarami_tablic );
	add_submenu_page( __FILE__, 'Dane kontaktowe', 'Kontakt', 'manage_options', __FILE__.'_dane_kontaktowe', bs_reservations_dane_kontaktowe );
}

//rezerwacje - akcja do biblioteki multimediów
add_action('admin_enqueue_scripts', 'my_admin_scripts');
function my_admin_scripts() {
	if (isset($_GET['page'])){
		wp_enqueue_media();
		wp_register_script('jquery-validation-plugin', WP_PLUGIN_URL.'/reservations/js/jquery.validate.js', array('jquery'));
		wp_enqueue_script('jquery-validation-plugin');
		
		wp_register_script('jquery-validation-settings', WP_PLUGIN_URL.'/reservations/js/validate.settings.js', array('jquery'));
		wp_enqueue_script('jquery-validation-settings');
		
		wp_register_style( 'bs-reservations-css', plugins_url( '/reservations/css/bs-reservations.css' ) );
		wp_enqueue_style( 'bs-reservations-css' );
		
		if ($_GET['page'] == 'reservations/reservations.php') {
			wp_register_script('bs-reservations-image', WP_PLUGIN_URL.'/reservations/js/bs-reservations-image.js', array('jquery'));
			wp_enqueue_script('bs-reservations-image');
		}
		if ($_GET['page'] == 'reservations/reservations.php_zarzadzanie_rezerwacjami') {
		  wp_register_script('bs-jquery-tablesorter', WP_PLUGIN_URL.'/reservations/js/jquery.tablesorter.min.js', array('jquery'));
		  wp_register_script('bs-jquery-tablesorter-pager', WP_PLUGIN_URL.'/reservations/js/jquery.tablesorter.pager.js', array('jquery'));
			wp_register_script('bs-reservations-date', WP_PLUGIN_URL.'/reservations/js/bs-reservations-date.js', array('jquery'));
			
			wp_enqueue_script('jquery-ui-datepicker');
			wp_enqueue_script('bs-jquery-tablesorter');
			wp_enqueue_script('bs-jquery-tablesorter-pager');
			wp_enqueue_script('bs-reservations-date');
			wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
			
		}
	}
}

include_once "includes/zarzadzanie_tablicami.php";
include_once "includes/zarzadzanie_rezerwacjami.php";
include_once "includes/zarzadzanie_rozmiarami_tablic.php";
include_once "includes/dane_kontaktowe.php";
