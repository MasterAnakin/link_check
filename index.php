<?php

/*
Plugin Name:  Link Checker
Description:  Check status codes of links
Plugin URI:   https://valet.io/
Author:       Josh & Milos
Version:      1.0
Text Domain:  wpmilos
Domain Path:  /languages
License:      GPL v2 or later
License URI:  https://www.gnu.org/licenses/gpl-2.0.txt
 */

session_start();

include_once "simplehtmldom_1_9/simple_html_dom.php";

class GetMenuLinks {

	public function get_wp_nav_menu_links() {

		if (!function_exists('wp_get_nav_menu_items')) {
			require_once ABSPATH . WPINC . '/nav-menu.php';
		}

		// Menu name, ID, or slug.
		$menu_name = 'main';

		// Optional. Arguments to pass to get_posts().
		$args = array();

		// function for getting menu
		$menu_items = wp_get_nav_menu_items($menu_name, $args);

		//get 'url' values
		$menu_links = array_column($menu_items, 'url');

		return $menu_links;

	}

	public function get_single_menu_link() {

		//get all menu links from the get_wp_nav_menu_links method
		$menu_links_arr = $this->get_wp_nav_menu_links();

		// rotate - loop trough the array of links
		$id = ++$_SESSION['menu_links_id'] % count($menu_links_arr);
		$_SESSION['menu_links_id'] = $id;

		// display single page we are checking
		echo $menu_links_arr[$id] . "<br />";

		$single_menu_link = $menu_links_arr[$id];
		return $single_menu_link;

	}
}

class GetAllLinksFromSinglePage extends GetMenuLinks {

	public $html_export_links;

	/*
		//this is for testing only, let it stay for now
		public function __construct() {
			add_shortcode('testme', array($this, 'get_single_page_links_arr'));
		}
	*/

	public function get_single_page_html() {

		//getting HTML content from the single page
		$single_page_html = file_get_html($this->get_single_menu_link());

		return $single_page_html;
	}

	public function get_single_page_links_arr() {

		// Find all links under the single page HTML, get only links from that single page HTML
		foreach ($this->get_single_page_html()->find('a') as $single_page_link) {

			//check if the link is valid{
			if (strpos($single_page_link, '://') !== false) {
				//get array of links
				$page_links[] = $single_page_link->href;
			} else {
				(substr($single_page_link, 0, 1) != '/');
			}
		}
		// return array of links
		return $page_links;
	}

}

class CheckHeadersResponse {

	public $url = false;

	//get single URL from the single page from the array of get_single_page_links_arr
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

function run_this() {

	//calling class and array of links from the single page
	$new_link = new GetAllLinksFromSinglePage();
	$page_links = $new_link->get_single_page_links_arr();

	//looping trough the array of links from the single page
	foreach ($page_links as $page_link) {
		//each link is forwarded to check headers method and displayed back
		$new_url = new CheckHeadersResponse($page_link);
		print_r($new_url->get_header_response());
	}

}

add_shortcode('crazy2', 'run_this');

//array_diff $page_links - array from database and repat
//$newArray = array_slice($page_links, 0, 5, true);
//store results in db

//run on cron

?>