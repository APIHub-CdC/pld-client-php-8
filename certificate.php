<?php

$pkcs12 = array();
$file_pkcs12 = file_get_contents("/home/user/certificates-july-2023-QA/QA-apple/keystore-4-july-2023.p12");
$valkeypair = openssl_pkcs12_read($file_pkcs12, $pkcs12, "X60q35SkmAjx7sMM");

print($valkeypair);
var_dump($valkeypair);
