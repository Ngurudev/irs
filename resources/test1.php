<?php
$cipher    = "aes-128-gcm";
$key       = "malah@gmail.com";
$plaintext = "should have been previously generated in a cryptographically safe way, like openssl_random_pseudo_bytes";
$cipher    = "aes-128-gcm";
$key       = "malah@gmail.com";

//$key should have been previously generated in a cryptographically safe way, like openssl_random_pseudo_bytes
if (isset($_POST['send'])) {

    if (in_array($cipher, openssl_get_cipher_methods())) {
        $ivlen      = openssl_cipher_iv_length($cipher);
        $iv         = openssl_random_pseudo_bytes($ivlen);
        $ciphertext = openssl_encrypt($plaintext, $cipher, $key, $options = 0, $iv, $tag);
        //store $cipher, $iv, and $tag for decryption later
        $original_plaintext = openssl_decrypt($ciphertext, $cipher, $key, $options = 0, $iv, $tag);
        echo $original_plaintext . "<br>";
        echo $ciphertext;

        $con = mysqli_connect('localhost', 'root', '', 'test');

        $query = "INSERT  INTO message (cipher_text,plain_text) VALUES('$ciphertext','$plaintext')";
        $send  = mysqli_query($con, $query);
    }
}

if (isset($_POST['decrypt'])) {
    $con   = mysqli_connect('localhost', 'root', '', 'test');
    $query = "SELECT *FROM message WHERE id = 1";
    $send  = mysqli_query($con, $query);
    while ($row = mysqli_fetch_array($send)) {
        $cipher_text = $row['cipher_text'];
    }

    // $ivlen = openssl_cipher_iv_length($cipher);
    // $iv = openssl_random_pseudo_bytes($ivlen);
    $original_plaintext = openssl_decrypt($cipher_text, $cipher, $key, $options = 0, $iv = "", $tag = "");
    echo $original_plaintext;
}

?>