<?
session_start();
include("inc/header.inc.php");
$no_ask = import_no_ask();
$ask = import_count_ask();
$msg="";
if($no_ask) {
	$msg = "$no_ask Buchungss�tze wurden Importiert";
}
if($ask) {
	if($msg) $msg.="\n\n";
	$msg .= "$ask Buchungss�tze stehen zum Import an";
}
?>
<html>
<head>
  <title><?=$_config_title ?></title>
</head>
<frameset rows="*,40" frameborder="0" framespacing="0" border="0">
  <frame src="bilanz.php?msg=<?=urlencode($msg)?>" name=main>
  <frame src="menu.php" name=menu scrolling=no border=0>
</frameset>
</html>
