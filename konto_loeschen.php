<?
	include("inc/header.inc.php");

	if($submit)
	{
		$query=mysql_query("SELECT id FROM $buchhaltung"."_Buchungssaetze WHERE kt_haben='$konto' OR kt_soll='$konto'");
		if(mysql_num_rows($query)==0)
		{
			$query=mysql_query("SELECT name FROM $buchhaltung"."_Konto WHERE nr='$konto'");
			list($name)=mysql_fetch_row($query);
			$query=mysql_query("DELETE FROM $buchhaltung"."_Konto WHERE nr='$konto'");
			$close=1;
		} else {
			$error="Es existieren ".mysql_num_rows($query)." Buchungssaetze für dieses Konto.";
		}
	}
?>
<html>
<head>
  <title><?=$_config_title ?></title>
  <link rel="stylesheet" href="main.css" type=text/css>
  <script language="JavaScript" type="text/javascript" src="inc/functions.js"></script>
</head>
<?
if($close) {
  print "<body onLoad=\"javascript:opener.parent.main.location.reload();self.close()\">";
} else {
  print "<body onload=\"javascript:self.focus()\">";
}
?>
<p class=titel>Konto Löschen</p>
<?
if($error)
	print "<span style=\"color:red;font-weight:bold;\">Fehler:</span><span style=\"color:red;\"> $error</span><br><br>";
?>
<form method=post action=<?=$PHP_SELF; ?>>
<?=getKontoList("konto",150,$konto);?><input type=submit name=submit value="Löschen">
</form>
</body>
</html>
