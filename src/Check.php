<?php declare( strict_types = 1 );

namespace PiotrPress\WordPress\LinkChecker;

use GuzzleHttp\Client;

class Check {
    const string PATTERN = '/<a\s+href=["\']([^"\']+)["\']|<img\s+src=["\']([^"\']+)["\']/i';

    public function __construct(
        private string $url
    ) {}

    public function getLinks( string $content ) : array {
        $links = [];

        \preg_match_all( self::PATTERN, $content, $matches );
        foreach( \array_unique( \array_filter( \array_merge( $matches[ 1 ], $matches[ 2 ] ) ) ) as $link )
            $links[ $link ] = $this->getStatus( $link );

        return $links;
    }

    private function getStatus( string $url ) : int {
        if( \strpos( $url, 'http' ) !== 0 ) $url = $this->url . '/' . \ltrim( '/', $url );

        try {
            return ( new Client( [
                'allow_redirects' => true,
                'http_errors' => false,
                'timeout' => 5,
                'headers' => [
                    'User-Agent' => 'WordPressLinkChecker/1.0.0',
                ]
            ] ) )->get( $url, [ 'verify' => false ] )->getStatusCode();
        } catch( \Throwable $exception ) {
            return 0;
        }
    }
}