<?php
$con = mysqli_connect('localhost', 'root', '', 'test');
//$key previously generated safely, ie: openssl_random_pseudo_bytes
$key       = "m@gmail.co6m";
$plaintext = "previously generated safely, ie: openssl_random_pseudo_bytes nine id 5";
if (isset($_POST['send'])) {
 # code...
 $ivlen          = openssl_cipher_iv_length($cipher = "AES-128-CBC");
 $iv             = openssl_random_pseudo_bytes($ivlen);
 $ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
 $hmac           = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
 $ciphertext     = base64_encode($iv . $hmac . $ciphertext_raw);
 echo $ciphertext . '<br>';

 $query = "INSERT  INTO message (cipher_text,plain_text) VALUES('$ciphertext','$plaintext')";
 $send  = mysqli_query($con, $query);
}
if (isset($_POST['decrypt'])) {
//decrypt later....
 $query = "SELECT *FROM message WHERE id = 5";
 $send  = mysqli_query($con, $query);
 while ($row = mysqli_fetch_array($send)) {
  $cipher_text = $row['cipher_text'];
 }

 $c                  = base64_decode($cipher_text);
 $ivlen              = openssl_cipher_iv_length($cipher = "AES-128-CBC");
 $iv                 = substr($c, 0, $ivlen);
 $hmac               = substr($c, $ivlen, $sha2len = 32);
 $ciphertext_raw     = substr($c, $ivlen + $sha2len);
 $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
 $calcmac            = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
 if (hash_equals($hmac, $calcmac)) { // timing attack safe comparison {
  echo $original_plaintext . "\n";
 } else {
  echo "Check your key";
 }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
</head>

<body>
    <form method="post">
        <input type="submit" name="send">
        <input type="submit" name="decrypt" value="decrypt">
    </form>
</body>

</html>
</body>

</html>
