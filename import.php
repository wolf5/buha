<?
include("inc/header.inc.php");
if(!$show_mwst) {
	$mwst_satz=0;
}
if($delete) {
	$query=mysql_query("DELETE FROM Queue WHERE id='$id'");
} else if($import) {
	if(!$datum) {
		$err="Bitte geben Sie ein Datum an";
	} else if(!$betrag && !$err) {
		$err="Bitte geben Sie einen Betrag an";
	} else if(!$kurs && $waehrung!=1 && !$err) {
		$err="Bitte geben Sie einen Kurs an";
	}
	if(count($err)==0) {
		if(!$kurs){
			$kurs=1;
		}
		$query=mysql_query("INSERT INTO $buchhaltung"."_Buchungssaetze(datum,beschreibung,kt_haben,kt_soll,betrag,waehrung,kurs,mwst,belegnr) Values('".date_CH_to_EN($datum)."','".$beschreibung."','".$kt_haben."','".$kt_soll."','".$betrag."','".$waehrung."','".$kurs."','".$mwst_satz."','".$belegnr."')");
		$last_id=mysql_insert_id();
		if(!($msg=mysql_error())) {
			$query=mysql_query("DELETE FROM Queue WHERE id='$id'");
			if($show_mwst) {
	     	$query=mysql_query("INSERT INTO $buchhaltung"."_Buchungssaetze(datum,beschreibung,kt_haben,kt_soll,betrag,waehrung,kurs,mwst,mwst_feld,belegnr) Values('".date_CH_to_EN($datum)."','$beschreibung','".$mwst_haben."','".$mwst_soll."','".(($betrag/100)*$mwst_satz)."','".$waehrung."','".$kurs."','0','$last_id','')");
			}
			unset($mwst_haben,$mwst_soll,$mwst,$show_mwst);
		}
		if(!($msg=mysql_error())) {
				$close = 1;
		}
	}
}
?>
<html>
<head>
  <title><?=$_config_title ?></title>
  <link rel="stylesheet" href="main.css" type=text/css>
  <script language="JavaScript" type="text/javascript" src="inc/functions.js"></script>
	<script language="JavaScript" type="text/javascript">
	<!--
	var lastChange;
	function rechneLastChange() {
		if(lastChange==2){
			rechneBetrag('total');
		} else {
			rechneBetrag('betrag');
		}
	}
	function rechneBetrag(method){
		var satz=document.getElementsByName('mwst_satz')[0];
		var betrag=document.getElementsByName('betrag')[0];
		var anteil=document.getElementsByName('mwst_anteil')[0];
		var total=document.getElementsByName('mwst_total')[0];
		if(method=='betrag' && satz.value && betrag.value){
			val_betrag=parseFloat(betrag.value);
			val_satz=parseFloat(satz.value);
			val_anteil=(val_betrag/parseFloat(100))*val_satz;
			anteil.value=val_anteil;
			total.value=val_betrag+val_anteil;
			lastChange=1;
		} else if(satz.value && total.value) {
			val_satz=parseFloat(satz.value);
			val_total=parseFloat(total.value);
			val_betrag=(val_total*100)/(100+val_satz);
			val_anteil=(val_total*val_satz)/(val_satz+100);
			anteil.value=val_anteil;
			betrag.value=val_betrag;
			lastChange=2;
		}
	}
	function form_submit(arg){
		document.getElementsByName('action')[0].value = arg;
		document.Buhaform.submit();
	}
	//-->
	</script>
</head>
<?
$query=mysql_query("SELECT count(*) FROM Queue");
$import_records=mysql_result($query,0,0);

if($action!="upd") {
 $query=mysql_query("SELECT id,DATE_FORMAT(datum,'$_config_date'),beschreibung,kt_soll,kt_haben,betrag,waehrung,kurs,mwst,belegnr FROM Queue LIMIT 0,1");
 list($id,$datum,$beschreibung,$kt_soll,$kt_haben,$betrag,$waehrung,$kurs,$mwst_satz,$belegnr)=mysql_fetch_row($query);
  if($mwst_satz) {
    $show_mwst=1;
	} 
}

if($import_records==0) {
	if($id) {
		print "<body onload=\"javascript:opener.parent.main.location.reload();self.close();\">";
	} else {
		print "<body>";
	}
	print "<p class=titel>Import</p><br><br><div align=center>Es stehen keine Buchungssätze zum Import an.</div></body></html>";
	die();
} else {
	print "<body onload=\"javascript:self.focus();";
	if($show_mwst) print "rechneBetrag('betrag');";
}
if(!$total) {
  $total=$import_records;
  $start=1;
}

print "\">
<table border=0 cellpadding=0 cellspacing=0 width=\"100%\">
<tr>
	<td class=titel>Import</td>
	<td align=right>Buchungssatz $start/$total</td>
</tr>
</table>\n";

if($err) {
	print "<b>Fehler:</b> $err<br>";
}
if($action || mysql_num_rows($query)>0) {
	print "<form method=get action=\"$PHP_SELF\" name=\"Buhaform\">
	<input type=hidden name=id value=$id>
	<input type=hidden name=action value=\"\">
	<input type=hidden name=total value=$total>
	<input type=hidden name=start value=".($start+1).">
  <input type=hidden name=id value=$id>
	<table border=0 cellpadding=3 cellspacing=0>";
	if($show_mwst){
		$mwst_checked=" CHECKED";
	} else {
		$mwst_checked="";
	}
  //if(!$mwst_satz){
  //  $mwst_satz=$_config_buchungsatz_erstellen_mwst_default_value;
  //}
	print "<tr>
	  <td><b>Datum</b></td>
	  <td><b>Konto Soll</b></td>
	  <td><b>Konto Haben</b></td>
	  <td><b>Betrag</b></td>
	  <td><b>Währung</b></td>
	  <td><b>Kurs</b></td>
  	<td><b><nobr>Beleg Nr.</nobr></b></td>
		<td>&nbsp;</td>
	</tr>";
	print "<tr>
		<td><input type=text name=\"datum\" value=\"".$datum."\" style=\"width:80px;\"></td>
		<td>".getKontoList("kt_soll","150",$kt_soll)."</td>
	  <td>".getKontoList("kt_haben","150",$kt_haben)."</td>
	  <td><input type=text name=\"betrag\" value=\"".$betrag."\" style=\"width:80px\"";
	if($show_mwst){
		print " onKeyUp=\"javascript:rechneBetrag('betrag')\"";
	}
	print "></td>
		<td>".getWaehrungsList("waehrung",70,$waehrung)."</td>
		<td><input type=text name=\"kurs\" value=\"".$kurs."\" style=\"width:80px;\"></td>
		<td><input type=text name=\"belegnr\" value=\"".$belegnr."\" style=\"width:80px;\" maxlength=255></td>";
	if($show_mwst) {
    print "<td class=negativ><input type=checkbox name=\"show_mwst[$ii]\" checked onclick=\"javascript:form_submit('upd');\"> MWSt.</td>";
  } else {
    print "<td><input type=checkbox name=\"show_mwst[$ii]\" onclick=\"javascript:form_submit('upd');\"> MWSt.</td>";
  }
print "</tr>
  <tr>
    <td colspan=3><b>Beschreibung</b></td>";
    //MWST Anteil
      if($show_mwst){
        print "<td class=negativ><input type=text name=\"mwst_anteil\" value=\"".$mwst_anteil."\" style=\"width:80px\"></td>
          <td colspan=4 class=negativ>&nbsp;</td>";
      } else {
        print "<td>&nbsp;</td>
          <td colspan=4>&nbsp;</td>";
      }
  //MWST Anteil
print "</tr>
  <tr>
    <td colspan=2><input type=text name=\"beschreibung\" value=\"".$beschreibung."\" style=\"width:250px\"> </td>
    <td>";

  if($show_mwst){
    print "<td class=negativ><input type=text name=\"mwst_total\" value=\"".$mwst_total."\" style=\"width:80px\" onKeyUp=\"javascript:rechneBetrag('total')\"></td>
      <td align=right class=negativ><b>Satz</b></td>
      <td class=negativ><input type=text name=\"mwst_satz\" value=\"".$mwst_satz."\" style=\"width:80px\" onKeyUp=\"javascript:rechneLastChange()\"></td>
      <td align=right class=negativ>Typ</td>
      <td class=negativ>
        <SELECT name=\"festeWerte\" onChange=\"javascript:document.getElementsByName('mwst_satz')[0].value=this.value;rechneLastChange();\" style=\"width:80px\">
          <option>Wählen</option>";
    $query=mysql_query("SELECT text,mwst FROM Buchungssaetzte_default_mwst");
    while(list($option_text,$option_mwst)=mysql_fetch_row($query)){
      if($festeWerte[$i]==$option_mwst){
        print "<option value=\"$option_mwst\" SELECTED>$option_text</option>\n";
      } else {
        print "<option value=\"$option_mwst\">$option_text</option>\n";
      }
    }
    print"    </SELECT>
      </td>";
  } else {
    print "<td colspan=5>&nbsp;</td>";
  }
  print "<tr>";
  if(!$mwst_haben) $mwst_haben = $_config_buchungssaz_erstellen_mwst_haben;
  if(!$mwst_soll) $mwst_soll = $kt_soll;

  if($show_mwst) {
    print "<td colspan=3>&nbsp;</td>
			<td colspan=2 class=negativ>".getKontoList("mwst_soll","150",$mwst_soll)."</td>
      <td colspan=2 class=negativ>".getKontoList("mwst_haben","150",$mwst_haben)."</td>
      <td class=negativ align=right>&nbsp;</td>";
  } else {
    print "<td colspan=8 align=right>&nbsp;</td>";
  }
	print "</tr>
	<tr>
	  <td colspan=8><input type=submit name=import value=\"Importieren\"> <input type=submit value=\"Löschen\" name='delete'></td>
	</tr>
	</table>
	</form>";
} else {
	print "Buchungssatz nicht vorhanden";
}
?>
</body>
</html>
