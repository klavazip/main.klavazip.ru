<?
// yz@yz.kiev.ua 02-Sep-2013

$code = 404;
$text = "Not Found";

$server_software = explode(" ", $_SERVER[SERVER_SOFTWARE], 2);
$requested_url = $_GET['url'] ? $_GET['url'] : $_SERVER[REQUEST_URI];

header("$_SERVER[SERVER_PROTOCOL] $code $text");
header("Status: $code $text");

echo "<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\">\n";
echo "<HTML><HEAD>\n";
echo "<TITLE>$code $text</TITLE>\n";
echo "</HEAD><BODY>\n";
echo "<H1>$text</H1>\n";
echo "The requested URL $requested_url was not found on this server.<P>\n";
echo "<HR>\n";
echo "<ADDRESS>$server_software[0] Server at $_SERVER[SERVER_NAME] Port $_SERVER[SERVER_PORT]</ADDRESS>\n";
echo "</BODY></HTML>\n";
?>
