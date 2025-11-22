<?php declare( strict_types = 1 );

namespace PiotrPress\WordPress\LinkChecker;

use GuzzleHttp\Client;

class Check {
    const string PATTERN = '/<a\s+href=["\']([^"\']+)["\']|<img\s+src=["\']([^"\']+)["\']/i';

    public function __construct(
        private string $url,
        private Client $client = new Client()
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

        try { return $this->client->get( $url, [ 'verify' => false, 'timeout' => 5 ] )->getStatusCode(); }
        catch( \Throwable $exception ) { return 0; }
    }
}