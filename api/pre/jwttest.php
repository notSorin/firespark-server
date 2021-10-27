<?php
	ini_set('display_errors', '1');
	require '../vendor/autoload.php';
	use \Firebase\JWT\JWT;

	$key = 'key';
	$iat = time();
	$exp = $iat + 60 * 60;

	$payload = array
	(
		'iss' => 'firespark',
		'aud' => 'firespark',
		'iat' => $iat,
		'exp' => $exp
	);

	$jwt = JWT::encode($payload, $key, 'HS512');

	print $jwt . "\n\n";

	$decoded = JWT::decode($jwt, $key, array('HS512'));

	print_r($decoded);
?>