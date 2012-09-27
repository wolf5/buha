<?
	include("inc/header.inc.php");

	if($submit2) {
		$query=mysql_query("UPDATE $buchhaltung"."_Konto SET name='$name',nebenkonto='$nebenkonto',typ='$typ',waehrung='$waehrung',show_belegnr='$show_belegnr',show_mwst='$show_belegnr',show_datum='$show_datum' WHERE nr='$konto'");
		if(!mysql_error()){
			$close=1;
		}
		else{
			$error=mysql_error();
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
<p class=titel>Konto Editieren</p>
<?
if($error)
	print "<span style=\"color:red;font-weight:bold;\">Fehler:</span><span style=\"color:red;\"> $error</span><br><br>";
?>
<form method=post action=<?=$PHP_SELF; ?>>
<?
if($submit1 || $submit2){
	if($submit1){
		$query=mysql_query("SELECT name,typ,nebenkonto,waehrung,show_belegnr,show_mwst,show_datum FROM $buchhaltung"."_Konto WHERE nr = '$konto'");
		list($name,$typ,$nebenkonto,$waehrung,$show_belegnr,$show_mwst,$show_datum)=mysql_fetch_row($query);
	}
	if($show_belegnr)
		$show_belegnr=" CHECKED";
	if($show_datum)
    $show_datum=" CHECKED";
	if($show_mwst)
    $show_mwst=" CHECKED";

	print "<table border=0>
<tr>
  <td width=100>Konto Nr.</td>
  <td><input type=text name=\"konto\" value=\"$konto\" style=\"width:150px;\" readonly></td>
</tr>
<tr>
  <td width=100>Kontoname</td>
  <td><input type=text name=\"name\" value=\"$name\" style=\"width:150px;\"></td>
</tr>
<tr>
  <td width=100>Nebenkonto</td>
  <td>".getNebenkontoList("nebenkonto",150,$nebenkonto,"Kein Nebenkonto")."</td>
</tr>
<tr>
  <td width=100>Typ</td>
  <td>".getKontoTypenList("typ",80,$typ)."</td>
</tr>
<tr>
  <td width=100>Währung</td>
  <td>".getWaehrungsList("waehrung",80,$waehrung)."</td>
</tr>
<tr>
  <td width=100 valign=top>Felder:</td>
  <td>
    <input type=checkbox value=1 name=\"show_belegnr\" $show_belegnr> Beleg-Nummer<br>
    <input type=checkbox value=1 name=\"show_mwst\" $show_mwst> Mwst.<br>
    <input type=checkbox value=1 name=\"show_datum\" $show_datum> Datum
  </td>
</tr>
<tr>
  <td width=100>&nbsp;</td>
  <td><input type=submit name=\"submit2\" value=\"Ändern\"></td>
</tr>
</table>";
} else {
	print getKontoList("konto",150,$konto);
	print "<input type=submit name=\"submit1\" value=\"Editieren\">";
}
?>
</form>
</body>
</html>
