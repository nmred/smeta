<?php
//$fp = stream_socket_client("tcp://127.0.0.1:8649", $errno, $errstr);
//if (!$fp) {
//	echo "ERROR: $errno - $errstr<br />\n";
//} else {
//	fwrite($fp, "\n");
//	echo fread($fp, 26);
//	fclose($fp);
//}
$socket = stream_socket_server("tcp://0.0.0.0:8649", $errno, $errstr);
if (!$socket) {
  echo "$errstr ($errno)<br />\n";
} else {
  while ($conn = stream_socket_accept($socket)) {
echo stream_socket_recvfrom($conn, 4096);
    fclose($conn);
  }
  fclose($socket);
}
?>
