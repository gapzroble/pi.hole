#!/usr/bin/php
<?php
// create curl resource
$ch = curl_init();

// set url
curl_setopt($ch, CURLOPT_URL, 'http://192.168.254.254/public_info.htm');
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); 
curl_setopt($ch, CURLOPT_TIMEOUT, 2); //timeout in seconds

//return the transfer as a string
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// $output contains the output string
$output = curl_exec($ch);

$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
if ($code == 403) {
   echo '403 Forbidden', PHP_EOL;
   exit(2);
}

// close curl resource to free up system resources
curl_close($ch);   

$data = array();
$tds = explode("<TD align='center' class='tabdata'>", $output);
foreach ($tds as $td) {
   $values = explode('</TD>', $td);

   if (count($values) == 2 && strpos($values[0], '<') === false) {
      echo $values[0], "\n";
      $data[] = $values[0];
   }
}

if (isset($data[4])) {
   echo $data[4], PHP_EOL;
   file_put_contents('.default_gw', $data[4]);

   if ($data[4] != '0.0.0.0') {
      exit(0);
   }

   exit(1);
}

exit(2);
