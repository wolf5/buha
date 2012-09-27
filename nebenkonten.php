<?
	include("inc/header.inc.php");
	if($action == "create") {
		$query=mysql_query("INSERT INTO $buchhaltung"."_Nebenkonto(name,typ) VALUES('$nebenkonto','$typ')");
		if(!($error=mysql_error())) {
			$msg="Nebenkonto $nebenkonto erfolgreich erstellt.";
		}
	} else if($action=="delete") {
		$query=mysql_query("SELECT name FROM $buchhaltung"."_Nebenkonto WHERE id='$nebenkonto'");
		$name=mysql_result($query,0,0);
		$query=mysql_query("SELECT count(*) FROM $buchhaltung"."_Konto WHERE nebenkonto='$nebenkonto'");
		if(mysql_result($query,0,0)==0) {
			$query=mysql_query("DELETE FROM $buchhaltung"."_Nebenkonto WHERE id='$nebenkonto'");
			if(!($error=mysql_error())) {
	      $msg="Nebenkonto '$name' erfolgreich gelöscht.";
	    }
		} else {
			$error="Das Nebenkonto '$name' enthält noch ".@mysql_result($query,0,0)." Konten.<br>Bitte Löschen Sie diese zuerst.";
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
if($action) {
	print "<body onLoad=\"javascript:opener.parent.main.location.reload();self.focus();\">";
} else {
	print "<body onload=\"javascript:self.focus()\">";
}
?>
<p class=titel>Nebenkonten</p>
<?
if($error)
	print "<span style=\"color:red;font-weight:bold;\">Fehler:</span><span style=\"color:red;\"> $error</span><br><br>";
if($msg)
	print "$msg<br>";
?>
<form method=post action="<?=$PHP_SELF?>?action=create">
<b>Nebenkonto Erstellen</b><br>
<input type=text style=\"width:150px;\" name=nebenkonto>
<input type=submit name=submit value="Erstellen"><br>
Typ: <?=getKontoTypenList("typ",80,$typ); ?>
</form>

<form method=post action="<?=$PHP_SELF?>?action=delete">
<b>Nebenkonto Löschen</b><br>
<?=getNebenkontoList('nebenkonto',150,$nebenkonto,"")?>
<input type=submit name=submit value="Löschen"><br>
</form>

</body>
</html>
