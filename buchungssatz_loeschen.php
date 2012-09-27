<?
	include("inc/header.inc.php");

	if($submit) {
		$query=mysql_query("DELETE FROM $buchhaltung"."_Buchungssaetze WHERE id='$id'");
		if(!($error=mysql_error()) ) {
			$query=mysql_query("DELETE FROM $buchhaltung"."_Buchungssaetze WHERE mwst_feld='$id'");
			$ok = "Buchungssatz gelöscht.<br><br><a href=\"#\" onclick=\"opener.location.reload();self.close();\">Schliessen</a>";
		}
	}
?>
<html>
<head>
  <title><?=$_config_title ?></title>
  <link rel="stylesheet" href="main.css" type=text/css>
  <script language="JavaScript" type="text/javascript" src="inc/functions.js"></script>
</head>
<body onload="self.focus()">
<p class=titel>Buchungssatz Löschen</p>
<table width="100%" height="120" border=0>
<tr>
	<td align=center>
<?
if($error)
	print "<span style=\"font-weight:bold;color:red\">Fehler:</span> <span style=\"color:red\">$error</span><br><br>";
else if($ok)
	print $ok;
else
{
	$query=mysql_query("SELECT Beschreibung FROM $buchhaltung"."_Buchungssaetze WHERE id='$id'");
	list($name)=mysql_fetch_row($query);
	if($name)
		print "Möchten Sie den Buchungssatz '$name' wirklich Löschen?\n";
	else
		print "Möchten Sie den Buchungssatz '$id' wirklich Löschen?\n";
	print "<br><br><a href=\"$PHP_SELF?submit=1&id=$id\">Ja</a> | <a href=\"#\" onclick=\"self.close()\">Nein</a>";
}
?>
	</td>
</tr>
</table>
</body>
</html>
