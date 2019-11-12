<?php

/*
Plugin Name:  Link Checker
Description:  Check status codes of links
Plugin URI:   https://valet.io/
Author:       Valet, Milos Milosevic
Version:      1.0
Text Domain:  wpmilos
Domain Path:  /languages
License:      GPL v2 or later
License URI:  https://www.gnu.org/licenses/gpl-2.0.txt
 */

//session_start();

//when we run this function wp_loaded issues!

function test() {

	if (!function_exists('wp_get_nav_menu_items')) {
		require_once ABSPATH . WPINC . '/nav-menu.php';
	}

// Menu name, ID, or slug.
	$menu = 'main';

// Optional. Arguments to pass to get_posts().
	$args = array();

// NOTICE! Understand what this does before running.
	$result = wp_get_nav_menu_items($menu, $args);

	//get 'url' values

	$test = array_column($result, 'url');

	return $test;

}

//add_shortcode('test', 'test');

include "simplehtmldom_1_9/simple_html_dom.php";

//$menu_items = wp_get_nav_menu_items();

//var_dump($menu_items);

//$new_url = new GetMenuLinks();
//print_r($new_url->get_links_from_header());

// rotate
/*
$id = ++$_SESSION['menu_links_id'] % count($menu_links_arr);
$_SESSION['menu_links_id'] = $id;
print_r($menu_links_arr);
// single page we are checking
echo $menu_links_arr[$id] . "<br />";
 */
//$html_export_links = array_walk($menu_links_arr,"file_get_html");
//echo($html_export_links);
//how to include this inside the class? Autoload? spl_autoload_register?
//$html_export_links = file_get_html($menu_links_arr[$id]);

class GetSinglePageLinks {

	public $html_export_links;

	public function get_single_page_html() {

		$html_export_links = file_get_html('http://localhost:8888/project/new-page');

		return $html_export_links;
	}

//$page_links = array();

	public function get_single_page_links_arr() {

// Find all links under the single page
		foreach ($this->get_single_page_html()->find('a') as $single_page_link)

//check if the link is valid
		{
			if (strpos($single_page_link, '://') !== false) {

				$page_links[] = $single_page_link->href;
				return $page_links;
			} else {
				(substr($single_page_link, 0, 1) != '/');
			}
		}

	}

}

$test = new GetSinglePageLinks;

var_dump($test->get_single_page_links_arr());

class CheckHeadersResponse {

	public $url = false;

	public function __construct($url) {
		$this->url = $url;
	}

	public function get_header_response() {
		$headers = get_headers($this->url, 1);

		if ($headers) {
			return $this->url . $headers[0] . '<br />';
		}

		return false;
	}
}

//$new_link = new GetMenuLinks();
//$page_links = $new_link->get_links_from_header();
//$menu_links = test();

//$page_links = Array('https://www.valet.io/', 'https://www.valet.io/about-us/', 'https://www.valet.io/blog/', 'https://www.valet.io/contact/', 'https://dashboard.valet.io');
function proba() {
	$menu_links = test();
	foreach ($menu_links as $page_link) {
		$new_url = new CheckHeadersResponse($page_link);
		var_dump($new_url->get_header_response());
	}

}

//add_shortcode('test', 'proba');

?>