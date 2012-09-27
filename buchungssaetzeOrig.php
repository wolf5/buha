<?
	include("inc/header.inc.php");

	if($kontonr)
	{
		$query=mysql_query("SELECT Name,typ,show_waehrung FROM $buchhaltung"."_Konto WHERE Nr='$kontonr'");
		list($name,$typ,$show_waehrung)=mysql_fetch_row($query);	
	}
	
		                                                  
?>
<html>
<head>
  <title><?=$_config_title ?></title>
  <link rel="stylesheet" href="main.css" type=text/css>
  <script language="JavaScript" type="text/javascript" src="inc/functions.js"></script>
</head>
<body onload="javascript:self.focus()">
<?// if($kontonr) print "onClose=\"javascript:parent.main.window.konto$kontonr.name()\"";?>
<?
if(!$kontonr){
	print "<table width=\"100%\" border=0 cellpadding=0 cellspacing=0>
		<tr>
			<td class=titel>Buchungss&auml;tze</td>
			<td align=right><a href=\"#\" onclick=\"javascript:window.open('buchungssatz_suchen.php','suchen','width=400,height=220,left=300,top=200');\">Suchen</a></td>
		</tr>
		</table>
		<br>";
}

if(!$sort)
	$sort="Datum";
if($sort=="kurs")
	$sort="Buchungssaetze.kurs";
	//TODO: str_replace weg
if($sql){
	$sql="AND ".str_replace("waehrung","Buchungssaetze.waehrung",str_replace("\\'","'",urldecode($sql)));
}
if($kontonr!=$_config_buchungssaz_erstellen_mwst_haben) {
	$mwst_feld="AND mwst_feld IS NULL";
} else {
	$mwst_feld="";
}
if($kontonr){
	$query=mysql_query("SELECT typ FROM $buchhaltung"."_Konto WHERE nr='$kontonr'");
	$typ=mysql_result($query,0,0);
	//Fragliches SQL Statement für alle Mwst Buchungssätze -> anzeige
	$query=mysql_query("SELECT Buchungssaetze.id,Buchungssaetze.datum,Buchungssaetze.kt_soll,Buchungssaetze.kt_haben,TRUNCATE(Buchungssaetze.betrag,3),Waehrung.waehrung,TRUNCATE(Buchungssaetze.kurs,7),Buchungssaetze.mwst,Buchungssaetze.belegnr,Buchungssaetze.beschreibung FROM $buchhaltung"."_Buchungssaetze Buchungssaetze,Waehrung WHERE (Buchungssaetze.kt_haben='$kontonr' OR Buchungssaetze.kt_soll='$kontonr') AND Waehrung.id = Buchungssaetze.waehrung $mwst_feld $sql GROUP BY Buchungssaetze.id ORDER BY $sort");
} else {
  $query=mysql_query("SELECT Buchungssaetze.id,Buchungssaetze.datum,Buchungssaetze.kt_soll,Buchungssaetze.kt_haben,TRUNCATE(Buchungssaetze.betrag,3),Waehrung.waehrung,TRUNCATE(Buchungssaetze.kurs,7),Buchungssaetze.mwst,Buchungssaetze.belegnr,Buchungssaetze.beschreibung FROM $buchhaltung"."_Buchungssaetze Buchungssaetze,Waehrung WHERE Waehrung.id = Buchungssaetze.waehrung  $mwst_feld $sql ORDER BY $sort");
}
if(mysql_num_rows($query)>0)
{
	print "$kurs";
	print "<table class=\"csstable\">
	
	<tr>
    <td width=80><b><a href=\"$PHP_SELF?sort=datum&kontonr=$kontonr\">Datum</a></b></td>
    <td width=250><b><a href=\"$PHP_SELF?sort=beschreibung&kontonr=$kontonr\">Beschreibung</a></b></td>
		<td width=150><b><a href=\"$PHP_SELF?sort=kt_soll&kontonr=$kontonr\">Konto Soll</a></b></td>
    <td width=150><b><a href=\"$PHP_SELF?sort=kt_haben&kontonr=$kontonr\"><nobr>Konto Haben</nobr></a></b></td>
    <td width=70><b><a href=\"$PHP_SELF?sort=betrag&kontonr=$kontonr\">Betrag</a></b></td>
    <td width=50><b><a href=\"$PHP_SELF?sort=waehrung&kontonr=$kontonr\">Fx</a></b></td>
    <td width=50><b><a href=\"$PHP_SELF?sort=kurs&kontonr=$kontonr\">Kurs</a></b></td>
    <td width=50><b><a href=\"$PHP_SELF?sort=mwst&kontonr=$kontonr\">Mwst.</a></b></td>
    <td width=70><b><a href=\"$PHP_SELF?sort=belegnr&kontonr=$kontonr\">Beleg Nr.</a></b></td>";
	if($show_waehrung) print "<td width=50><b>FW Total</b></td>";
	if($kontonr) print "<td width=70><b>Total</b></td>";
	print "</tr>\n";
	for($i=0;(list($id,$datum,$kt_soll,$kt_haben,$betrag,$waehrung,$kurs,$mwst,$belegnr,$beschreibung)=mysql_fetch_row($query));$i++) {
		if(($i%2)==0){
      $bgcolor=$_config_tbl_bgcolor1;
    } else {
      $bgcolor=$_config_tbl_bgcolor2;
    }		
		$query2 = mysql_query("SELECT kt_haben,kt_soll,betrag FROM $buchhaltung"."_Buchungssaetze WHERE mwst_feld = '$id'");
		if(mysql_num_rows($query2)>0) {
			list($mwst_haben,$mwst_soll,$mwst_betrag)=mysql_fetch_row($query2);
			$mwst_haben="<br>".getKontoByNr($mwst_haben);
			$mwst_soll="<br>".getKontoByNr($mwst_soll);
			$mwst_betrag="<br>".$mwst_betrag;
		} else {
			$mwst_haben="";
			$mwst_soll="";
			$mwst_betrag="";
		}
		if($mwst==0)
			$mwst="";
		else
			$mwst.="%";
		if(($kontonr == $kt_haben) ){
			$total-=$betrag;
		} else {
			$total+=$betrag;
		}
		print "<tr onmouseover=\"setPointer(this, 'over', '#$bgcolor', '#CCFFCC', '#FFCC99')\" onmouseout=\"setPointer(this, 'out', '#$bgcolor', '#CCFFCC', '#FFCC99')\" onclick=\"window.open('buchungssatz_editieren.php?id=$id','buchungssatz_editieren','width=920,height=220,left=10,top=200')\">
      <td width=80 align=right valign=top bgcolor=\"#$bgcolor\">".date_EN_to_CH($datum)."</td>
      <td width=250 valign=top bgcolor=\"#$bgcolor\">$beschreibung</td>
      <td width=150 align=right valign=top bgcolor=\"#$bgcolor\">".getKontoByNr($kt_soll)."$mwst_soll</td>
			<td width=150 align=right valign=top bgcolor=\"#$bgcolor\">".getKontoByNr($kt_haben)."$mwst_haben</td>
      <td width=70 align=right valign=top bgcolor=\"#$bgcolor\">".formatPreis($betrag)."$mwst_betrag</td>
      <td width=50 align=right valign=top bgcolor=\"#$bgcolor\">$waehrung</td>
      <td width=50 align=right valign=top bgcolor=\"#$bgcolor\">$kurs</td>
      <td width=50 align=right valign=top bgcolor=\"#$bgcolor\">$mwst</td>
      <td width=70 align=right valign=top bgcolor=\"#$bgcolor\">$belegnr</td>";
		if($show_waehrung) print "<td width=50 align=right valign=top bgcolor=\"#$bgcolor\">".formatPreis($kurs*$betrag)."</td>";
		if($kontonr) print "<td width=70 align=right valign=top bgcolor=\"#$bgcolor\">".formatPreis($total)."</td>";
		print "</tr>\n";
	}
	if(($i%2)==0){
	  $bgcolor=$_config_tbl_bgcolor1;
  } else {
    $bgcolor=$_config_tbl_bgcolor2;
  }
	if(!$kontonr && !$sql){
		$saldoEr=getSaldo();
		if($saldoEr>0){
			$saldo_kt_1="Erfolgsrechnung";
			$saldo_kt_2="Bilanz";
		} else {
			$saldo_kt_1="Bilanz";
	    $saldo_kt_2="Erfolgsrechnung";
		}
		$query2=mysql_query("SELECT waehrung From Waehrung WHERE id=1");
		list($saldo_waehrung)=mysql_fetch_row($query2);
		print "<tr>
	   <td width=80 align=right valign=top bgcolor=\"#$bgcolor\">".date("d.m.Y")."</td>
	   <td width=250 valign=top bgcolor=\"#$bgcolor\">Saldo aus Erfolgsrechnung</td>
	   <td width=150 align=right valign=top bgcolor=\"#$bgcolor\">$saldo_kt_1</td>
	   <td width=150 align=right valign=top bgcolor=\"#$bgcolor\">$saldo_kt_2</td>
	   <td width=70 align=right valign=top bgcolor=\"#$bgcolor\">".formatPreis($saldoEr)."</td>
	   <td width=50 align=right valign=top bgcolor=\"#$bgcolor\">$saldo_waehrung</td>
	   <td width=50 align=right valign=top bgcolor=\"#$bgcolor\">1</td>
	   <td width=50 align=right valign=top bgcolor=\"#$bgcolor\"></td>
	   <td width=70 align=right valign=top bgcolor=\"#$bgcolor\"></td>";
		if($kontonr) print "<td width=70 align=right valign=top bgcolor=\"#$bgcolor\"></td>";
		print "</tr>\n";
	}
} else {
	print "Keine Buchungss&auml;tze vorhanden.\n";
}
?>
</table>
</body>
</html>
