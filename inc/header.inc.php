<?
session_start();
include("inc/config.inc.php");
include("inc/db.inc.php");
include("inc/func.inc.php");
if(!$buchhaltung && !$create) {
	$query=mysql_query("SELECT id FROM Buchhaltungen WHERE selected=1");
	if(@mysql_num_rows($query)<1) {
		header("Location: buchhaltungen.php?create=1");
	}
	$buchhaltung = mysql_result($query,0,0);
	session_register("buchhaltung");
}
?>
