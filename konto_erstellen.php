<?
	include("inc/header.inc.php");
	if($submit)
	{
		//Errorhandling
		if(!is_numeric($kontonr)) {
      $error="Bitte geben Sie eine korrekte Konto-Nr. an";
    }
		else if(!$name) {
      $error="Bitte geben Sie einen Kontonamen an";
    }
		else if(!is_numeric($typ)) {
			$error="Bitte geben Sie einen Kontotypen an";
		}
		else {
			$query=mysql_query("SELECT count(*) FROM $buchhaltung"."_Konto WHERE Nr = '$kontonr' OR Name = '$name'");
			list($existiert)=mysql_fetch_row($query);
			if($existiert>0){
				$error="Es existiert bereits ein Konto mit dieser Nummer oder diesem Namen";
			} else {
				$query=mysql_query("SELECT max(sort) FROM $buchhaltung"."_Konto");
				$sort=@mysql_result($query,0,0)+1;
				$query=mysql_query("INSERT INTO $buchhaltung"."_Konto(nr,name,nebenkonto,typ,waehrung,show_waehrung,show_belegnr,show_mwst,show_datum,sort) Values('$kontonr','$name','$nebenkonto','$typ','$waehrung','$show_waehrung','$show_belegnr','$show_mwst','$show_datum','$sort')");
				if(mysql_error())
					$error="Datenbank-Fehler: ".mysql_error();
				else
					$close=1;
			}
		}
}
?>
<html>
<head>
  <title><?=$_config_title ?></title>
  <link rel="stylesheet" href="main.css" type=text/css>
  <script language="JavaScript" type="text/javascript" src="inc/functions.js"></script>
</head>
<body onload="javascript:self.focus();<?
if($close) print "opener.parent.main.location.reload();self.close();";
?>">
<p class=titel>Konto erstellen</p>
<?
if($error)
	print "<span style=\"color:red;font-weight:bold;\">Fehler:</span><span style=\"color:red;\"> $error</span><br><br>";
?>
<form method=post action=<?=$PHP_SELF; ?>>
<table border=0>
<tr>
	<td width=100>Konto Nr.</td>
	<td><input type=text name="kontonr" value="<?=$kontonr?>" style="width:150px;"></td>
</tr>
<tr>
  <td width=100>Kontoname</td>
  <td><input type=text name="name" value="<?=$name?>" style="width:150px;"></td>
</tr>
<tr>
  <td width=100>Nebenkonto</td>
  <td><?=getNebenkontoList("nebenkonto",150,$nebenkonto,"Kein Nebenkonto")?></td>
</tr>
<tr>
  <td width=100>Typ</td>
  <td>
		<?=getKontoTypenList("typ",80,$typ); ?>
	</td>
</tr>
<tr>
  <td width=100>Währung</td>
  <td>
    <?=getWaehrungsList("waehrung",80,$typ); ?>
  </td>
</tr>
<tr>
	<td width=100 valign=top>Felder:</td>
	<td>
		<input type=checkbox name="show_belegnr" value=1 CHECKED> Beleg-Nummer<br>
		<input type=checkbox name="show_mwst" value=1 CHECKED> Mwst.<br>
		<input type=checkbox name="show_datum" value=1 CHECKED> Datum
	</td>
</tr>
<tr>
	<td width=100>&nbsp;</td>
	<td><input type=submit name=submit value="Hinzufügen"></td>
</tr>
</table>
</form>
</body>
</html>
