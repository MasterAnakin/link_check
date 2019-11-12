<?php

include "simplehtmldom_1_9/simple_html_dom.php";

class CheckHeadersResponse {
    public $url = false

    public function __construct( $url ) {
        $this->url( $url );
    }

    public function get_header_response() {
        $headers = get_headers( $this->url, 1 );

        if ( $headers ){
            return $headers[0];
        }

        return false;
    }
}


foreach ( $page_links as $page_link ) {
    $new_url = new CheckHeadersResponse( $url, $page_links );
    print_r( $page_link );
    print_r( $new_url->get_header_response() );
}



$html_export_links = file_get_html('https://valet.io');

$page_links = array();

// Find all links under the single page
foreach ($html_export_links->find('a') as $single_page_link)

//check if the link is valid
{
	if (strpos($single_page_link, '://') !== false) {

		$page_links[] = $single_page_link->href;
	} else {
		(substr($single_page_link, 0, 1) != '/');
	}
}