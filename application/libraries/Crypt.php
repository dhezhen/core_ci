<?php
class Crypt {

	public static function process($action, $value) {
	    $output = false;

	    $encrypt_method = "AES-256-CBC";
	    $secret_key = 'BIjb2017!!';
	    $secret_iv = 'World Class Comopany';
	    // hash
	    $key = hash('sha256', $secret_key);
	    
	    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
	    $iv = substr(hash('sha256', $secret_iv), 0, 16);
	    if ( $action == 'encrypt' ) {
	        $output = openssl_encrypt($value, $encrypt_method, $key, 0, $iv);
	        $output = base64_encode($output);
	    } else if( $action == 'decrypt' ) {
	        $output = openssl_decrypt(base64_decode($value), $encrypt_method, $key, 0, $iv);
	    }

	    return $output;
	}

}
