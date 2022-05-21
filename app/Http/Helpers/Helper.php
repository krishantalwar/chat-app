<?php

if (!function_exists('encrypt_decrypt')) {

function encrypt_decrypt($action, $string)
{
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $secret_key = env('SECRET_KEY', 'wiw3g716qXYY29HUzzdOtvSfNkb7n5PN');
    $secret_iv = env('SECRET_IV', 'kIksnotLbVZ71hW4mtnL4RFSyar3l6a8');
    // hash
    $key = hash('sha256', $secret_key);
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    if ($action == 'encrypt') {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if ($action == 'decrypt') {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}
}