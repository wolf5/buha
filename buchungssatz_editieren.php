<?
	include("inc/header.inc.php");

	//Wenn es der MWST. satz ist, wird der ganze angezeigt
	$query=mysql_query("SELECT mwst_feld FROM $buchhaltung"."_Buchungssaetze WHERE id='$id' AND  mwst_feld IS NOT NULL");
	echo mysql_error();
	if(mysql_num_rows($query)>0) {
		$id = mysql_result($query,0,0);
	}

	if(!$show_mwst) {
		$mwst_satz=0;
	}
	if($edit){
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
			$query=mysql_query("UPDATE $buchhaltung"."_Buchungssaetze SET datum='".date_CH_to_EN($datum)."',beschreibung='$beschreibung',kt_haben='$kt_haben',kt_soll='$kt_soll',betrag='$betrag',waehrung='$waehrung',kurs='$kurs',mwst='$mwst_satz',belegnr='$belegnr' WHERE id='$id'");
			if(!$mwst_satz) {
				$query=mysql_query("DELETE FROM $buchhaltung"."_Buchungssaetze WHERE mwst_feld='$id'");
			} else {
				$query=mysql_query("SELECT count(*) FROM $buchhaltung"."_Buchungssaetze WHERE mwst_feld='$id'");
				if(mysql_result($query,0,0)==1) {
					$query=mysql_query("UPDATE $buchhaltung"."_Buchungssaetze SET datum='".date_CH_to_EN($datum)."',beschreibung='$beschreibung',kt_soll='".$mwst_soll."',kt_haben='".$mwst_haben."',betrag='".(($betrag/100)*$mwst_satz)."',waehrung='".$waehrung."',kurs='".$kurs."' WHERE mwst_feld='$id'");
				} else {
					$query=mysql_query("INSERT INTO $buchhaltung"."_Buchungssaetze(datum,beschreibung,kt_haben,kt_soll,betrag,waehrung,kurs,mwst,mwst_feld,belegnr) Values('".date_CH_to_EN($datum)."','$beschreibung','".$mwst_haben."','".$mwst_soll."','".(($betrag/100)*$mwst_satz)."','".$waehrung."','".$kurs."','0','$id','')");
				}
			}
			if(!($err=mysql_error())) {
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
			val_betrag=(betrag.value*1);
			val_satz=(satz.value*1);
			val_anteil=(val_betrag/100)*val_satz;
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
    function popup(url,name,width,height,left,top){
      window.open(url,name,"width="+width+",height="+height+",left="+left+",top="+top+",resizable=yes,scrollbars=yes");
    }
	//-->
	</script>
</head>
<?
if(!$action) {
  $query=mysql_query("SELECT DATE_FORMAT(datum,'$_config_date'),beschreibung,kt_soll,kt_haben,betrag,waehrung,kurs,mwst,belegnr FROM $buchhaltung"."_Buchungssaetze WHERE id='$id'");
  list($datum,$beschreibung,$kt_soll,$kt_haben,$betrag,$waehrung,$kurs,$mwst_satz,$belegnr)=mysql_fetch_row($query);
	if($mwst_satz){
		$query2=mysql_query("SELECT kt_haben,kt_soll FROM $buchhaltung"."_Buchungssaetze WHERE mwst_feld='$id'");
		list($mwst_haben,$mwst_soll)=mysql_fetch_row($query2);
	}
}
if(!isset($show_mwst) && $mwst_satz>0){
  $show_mwst = true;
}
if(!$mwst_satz && $show_mwst){
    $mwst_satz=$_config_buchungsatz_erstellen_mwst_default_value;
}

print "<body onload=\"javascript:";
if($close) {
	print "self.close();";
} else {
	print "self.focus();";
	if($show_mwst) print "rechneBetrag('betrag');";
}
print "\">
<p class=titel>Buchungssatz Ändern</p>\n";
if($err) {
	print "<b>Fehler:</b> $err<br>";
}
$query2=mysql_query("SELECT endDate FROM Buchhaltungen WHERE id='$buchhaltung'");
if(strtotime(mysql_result($query2,0,0))>=time()) {
	$readonly=true;
}
if($action || mysql_num_rows($query)>0) {
	if(!$readonly) {
		print "<form method=get action=\"$PHP_SELF\" name=\"Buhaform\">";
	}
	print"<input type=hidden name=id value=$id>
	<input type=hidden name=action value=\"\">
	<table border=0 cellpadding=3 cellspacing=0>";
	if($show_mwst){
		$mwst_checked=" CHECKED";
	} else {
		$mwst_checked="";
	}
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
	  <td><input type=text name=\"betrag\" value=\"".$betrag."\" id=\"betrag\" style=\"width:80px\"";
	if($show_mwst){
		print " onKeyUp=\"javascript:rechneBetrag('betrag')\"";
	}
	print " onKeyDown=\"javascript:document.getElementById('fw_show').value = document.getElementById('kurs[$ii]').value * this.value\"></td>
		<td>".getWaehrungsList("waehrung",70,$waehrung)."</td>
		<td><input type=text name=\"kurs\" id=\"kurs\" value=\"".$kurs."\" style=\"width:80px;\" onChange=\"javascript: document.getElementById('fw_show').value = this.value * document.getElementById('betrag').value\"></td>
		<td><input type=text name=\"belegnr\" value=\"".$belegnr."\" style=\"width:80px;\" maxlength=255></td>";
	if($show_mwst) {
    print "<td class=negativ><input type=checkbox name=\"show_mwst\" checked onclick=\"javascript:form_submit('upd');\"> MWSt.</td>";
  } else {
    print "<td><input type=checkbox name=\"show_mwst\" onclick=\"javascript:form_submit('upd');\"> MWSt.</td>";
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
  if(!$mwst_soll) $mwst_soll = $_config_buchungssaz_erstellen_mwst_haben;
	if(!$fw_show) $fw_show = $kurs * $betrag;
  if($show_mwst) {
    print "<td colspan=3>CHF: <input type=text id=fw_show name=fw_show value=\"$fw_show\" onChange=\"javascript:document.getElementById('kurs').value = this.value / document.getElementById('betrag').value\"></td>
			<td colspan=2 class=negativ>".getKontoList("mwst_soll","150",$mwst_soll)."</td>
      <td colspan=2 class=negativ>".getKontoList("mwst_haben","150",$mwst_haben)."</td>
      <td class=negativ align=right>&nbsp;</td>";
  } else {
    print "<td colspan=8>CHF: <input type=text id=fw_show name=fw_show value=\"$fw_show\" onChange=\"javascript:document.getElementById('kurs').value = this.value / document.getElementById('betrag').value\"></td>";
  }
	print "</tr>
	<tr>
	  <td >
			<input type=submit name=edit value=\"Ändern\"> 
			<input type=button value=\"Löschen\" onclick=\"javascript:location.href='buchungssatz_loeschen.php?id=$id'\">
		</td>
		<td colspan=7 align=right>
			<a href=\"javascript:popup('buchungssatz_erstellen.php?datum[0]=".urlencode($datum)."&kt_soll[0]=".urlencode($kt_soll)."&kt_haben[0]=".urlencode($kt_haben)."&betrag[0]=".urlencode($betrag)."&waehrung[0]=".urlencode($waehrung)."&kurs[0]=".urlencode($kurs)."&belegnr[0]=".urlencode($belegnr)."&show_mwst[0]=".urlencode($show_mwst)."&mwst_haben[0]=".urlencode($mwst_haben)."&mwst_soll[0]=".urlencode($mwst_soll)."&mwst_satz[0]=".urlencode($mwst_satz)."&mwst_anteil[0]=".urlencode($mwst_anteil)."&mwst_total[0]=".urlencode($mwst_total)."&kurs[0]=".urlencode($kurs)."&beschreibung[0]=".urlencode($beschreibung)."','buchungssatz_erstellen',920,250,10,200);self.close();\">Buchungssatz als Vorlage verwenden</a></td>
	</tr>
	</table>
	</form>";
} else {
	print "Buchungssatz nicht vorhanden";
} 
?>
</body>
</html>
