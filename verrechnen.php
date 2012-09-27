<?
	include("inc/header.inc.php");
	if(isset($del)) {
		unset($verrechnen[$del]);
	}
	if($submit) {
		$i=-1;
		foreach($verrechnen as $id) {
			$i++;
			$query=mysql_query("SELECT kt_haben,betrag,waehrung,kurs,beschreibung,belegnr FROM $buchhaltung"."_Buchungssaetze WHERE id='$id'");
			list($kt_soll,$betrag,$waehrung,$kurs,$beschreibung,$belegnr)=mysql_fetch_row($query);
			$query=mysql_query("SELECT betrag FROM $buchhaltung"."_Buchungssaetze WHERE mwst_feld='$id'");
	    if(mysql_num_rows($query)>0) $betrag+=mysql_result($query,0,0);

			$query=mysql_query("INSERT INTO $buchhaltung"."_Buchungssaetze(datum,beschreibung,kt_haben,kt_soll,betrag,waehrung,kurs,mwst,belegnr) Values('".date_CH_to_EN($datum)."','ZL vom ".date("d.m.Y")." / $beschreibung / $belegnr','".$kt_haben."','".$kt_soll."','".$betrag."','".$waehrung."','".$kurs."','0','$text_belegnr')");
			$new_id = mysql_insert_id();
			if(!($error=mysql_error())) {
				$query=mysql_query("UPDATE $buchhaltung"."_Buchungssaetze SET bezahlt='$new_id' WHERE id='$id'");
				unset($verrechnen[$i]);	
			}	
		}
		if(!$error) {
			unset($verrechnen);
			session_unregister("verrechnen");
			$msg = "Buchungssätze wurden erfolgreich verrechnet";
			require("msg.php");
			die();
		}
	}
	if(!session_is_registered("verrechnen")) {
		$verrechnen=array();
	}
	if(count($verrechnen)>0 && $id){
		$query=mysql_query("SELECT waehrung FROM $buchhaltung"."_Buchungssaetze WHERE id='".$verrechnen[0]."'");
		$waehrung=mysql_result($query,0,0);
		$query=mysql_query("SELECT waehrung FROM $buchhaltung"."_Buchungssaetze WHERE id='$id'");
		$waehrung_neu=mysql_result($query,0,0);
		if($waehrung==$waehrung_neu) {
			if(!in_array($id,$verrechnen)) $verrechnen[]=$id;
		} else {
			$error="Es dürfen nicht mehrere Währungen gleichzeitig verbucht werden!";
		}
	} else if($id) {
		if(!in_array($id,$verrechnen)) $verrechnen[]=$id;
	}
	session_register("verrechnen");
?>
<html>
<head>
  <title><?=$_config_title ?> - Verrechnen</title>
	<link rel="stylesheet" href="main.css" type=text/css>
  <script language="JavaScript" type="text/javascript" src="inc/functions.js"></script>
</head>
<body onLoad="self.focus();<?
if($error) print "alert('Fehler: ".addslashes($error)."');";
?>">
<div style=titel>Offene Posten Verrechnen</div><br>
<?
if(count($verrechnen)>0) {
	print "<table border=0 width=\"100%\">";
	$i=-1;
	foreach($verrechnen as $id) {
		$i++;
		$query=mysql_query("SELECT datum,beschreibung,betrag,waehrung FROM $buchhaltung"."_Buchungssaetze WHERE id='$id'");
		list($datum,$beschreibung,$betrag,$waehrung)=mysql_fetch_row($query);
		$query=mysql_query("SELECT betrag FROM $buchhaltung"."_Buchungssaetze WHERE mwst_feld='$id'");
		if(mysql_num_rows($query)>0) $betrag+=mysql_result($query,0,0);
		print "<tr>
			<td width=90>".date_EN_to_CH($datum)."</td>
			<td><a href=\"$PHP_SELF?del=$i\">$beschreibung</a></td>
			<td width=30>".getWaehrung($waehrung)."</td>
			<td width=50 align=right>".formatPreis($betrag)."</td>
		</tr>\n";
	}
	print "</table>";
}
?>
<br><br>
<form method=post action="verrechnen.php?submit=1">
<table border=0>
<tr>
	<td>Beleg Nr.</td>
	<td><input type=text name=text_belegnr value="Z00"></td>
</tr>
<tr>
	<td>Konto Soll</td>
	<td><?=getKontoList("kt_haben","150",$konto)?></td>
</tr>
<tr>
	<td>Datum</td>
	<td><input type=text name=datum value="<?=date("d.m.Y")?>"></td>
</tr>
<tr>
	<td colspan=2><input type=submit value="Verrechnen"></td>
</tr>
</table>
</form>
</body>
</html>
