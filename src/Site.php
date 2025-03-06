<?php declare( strict_types = 1 );

namespace PiotrPress\WordPress\LinkChecker;

use GuzzleHttp\Client;
use GuzzleHttp\Utils;

class Site {
    private string $url;
    private string $user;
    private string $pass;

    public function __construct( string $url ) {
        $this->url = \parse_url( $url, \PHP_URL_SCHEME ) . '://' . \parse_url( $url, \PHP_URL_HOST );
        $this->user = \parse_url( $url, \PHP_URL_USER );
        $this->pass = \parse_url( $url, \PHP_URL_PASS );
    }

    public function getUrl() : string {
        return $this->url;
    }

    public function getPosts( array $excludeTypes = [], array $excludePosts = [] ) : array {
        $types = \array_map( fn( $value ) => $value[ 'rest_base' ], $this->request( 'types' ) );
        $types = self::exclude( $types, [ 'revision', 'attachment', 'nav_menu_item', 'wp_block', 'wp_template', 'wp_template_part', 'wp_navigation', 'wp_font_family', 'wp_font_face', 'wp_global_styles' ] );
        $types = self::exclude( $types, $excludeTypes );

        $posts = [];
        foreach( $types as $type ) foreach( $this->request( $type ) as $post )
            $posts[ $post[ 'link' ] ] = $post[ 'content' ][ 'rendered' ] ?? '';

        return self::exclude( $posts, $excludePosts );
    }

    private function request( string $endpoint ) : array {
        $client = new Client( [ 'base_uri' => $this->url ] );
        $data = [];
        $page = 0;

        do {
            $response = $client->get( '/wp-json/wp/v2/' . $endpoint . '?per_page=100&page=' . ++$page, [
                'auth' => [ $this->user, $this->pass ],
                'verify' => false
            ] );
            $data = \array_merge( $data, Utils::jsonDecode( (string)$response->getBody(), true ) );
        } while( $response->hasHeader( 'X-WP-TotalPages' ) && $response->getHeader( 'X-WP-TotalPages' )[ 0 ] > $page );

        return $data;
    }

    private static function exclude( array $data, array $exclude ) : array {
        return \array_diff_key( $data, \array_flip( $exclude ) );
    }
}