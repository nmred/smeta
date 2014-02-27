<?php
$fp = stream_socket_client("tcp://127.0.0.1:8649", $errno, $errstr);
if (!$fp) {
	echo "ERROR: $errno - $errstr<br />\n";
} else {
	fwrite($fp, "\n");
	echo fread($fp, 26);
	fclose($fp);
}
?>
