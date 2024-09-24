<?php

header( 'Content-Type: text/plain' );
echo generateConfigs();

function generateConfigs()
{
    $baseUUID = 'cfcf33e1-e6e6-40f3-a05d-50bb423a';
    $ports = [8443, 2053, 2096, 2087, 2083];
    $names = [
        'yousef' => 'یوسف_قبادی',
        'segaro' => 'سگارو',
    ];
    $customDomain = 'www.zula.ir';
    $baseDomain = 'pages-dev.site';
    $configs = [];

    foreach ( $names as $name => $faName ) {
        foreach ( $ports as $port ) {
            $uuid = "{$baseUUID}{$port}";
            $customAddress = randomCase( $customDomain );
            $customSni = randomSubdomain( $baseDomain );
            $customHost = randomSubdomain( $baseDomain );
            $customFp = ( $name === 'segaro' ) ? 'randomized' : 'chrome';
            $customAlpn = 'h2,http/1.1';
            $key = randomString();
            $path = "?{$key}" . ( $name === 'segaro' ? '&ed=2560' : '' );
            $path = urlencode( $path );
            $customName = urlencode( "#{$faName}-{$port}" );
            $configs[] = "vless://{$uuid}@{$customAddress}:{$port}?encryption=none&security=tls&sni={$customSni}&fp={$customFp}&alpn={$customAlpn}&type=ws&host={$customHost}&path=/{$path}#{$customName}";
        }
    }

    return base64_encode( implode( "\n", $configs ) );
}

function randomCase( $str )
{
    $result = '';

    foreach ( str_split( $str ) as $char ) {
        $result .= ( rand( 0, 1 ) > 0.5 ) ? strtoupper( $char ) : strtolower( $char );
    }

    return $result;
}

function randomString( $length = 63 )
{
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $randomString = '';

    for ( $i = 0; $i < $length; $i++ ) {
        $randomString .= $chars[ rand( 0, strlen( $chars ) - 1 ) ];
    }

    return $randomString;
}

function randomSubdomain( $baseDomain )
{
    return randomString() . '.' . randomCase( $baseDomain );
}