<?php
$secret_key = 's0me_s3cret_4ey';
$app_folder = '/var/www/html/app';
$log_file   = $app_folder.'/githook.log';
$path_export='export PATH=/usr/local/sbin:/usr/local/bin:/sbin:/bin:/usr/sbin:/usr/bin &&'
$bash_script=$path_export." cd $app_folder && git pull ";


$timestamp  = date('Y-m-d H:i:s');
$status     = "";

$hubSignature = $_SERVER['HTTP_X_HUB_SIGNATURE'];
// Split signature into algorithm and hash
list($algo, $hash) = explode('=', $hubSignature, 2);
// Get payload
$requestBody = file_get_contents('php://input');
// Calculate hash based on payload and the secret
$payloadHash = hash_hmac($algo, $requestBody, $secret_key);

$postBody = $_POST['payload'];
$payload = json_decode($postBody);

if ($_SERVER['REQUEST_METHOD'] === 'POST'    
        && $hash === $payloadHash)
   {    
      exec($bash_script, $output, $return);
      if ($return != 0) {
           $status = "FAIL: ".implode(" ",$output).$return;
      } else {
            $status = "OK";
      }
   } else {
      $status = "FAIL";
   }
// write to log a status and a final commit number from 'after'  
file_put_contents($log_file, $timestamp." ".$status." ".$payload->after."\n", FILE_APPEND | LOCK_EX);

?>
