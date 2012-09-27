<?
	include("inc/header.inc.php");

	//Handle MWSt tickbox
	for($i=0;$kt_soll[$i];$i++){
		if(!isset($show_mwst[$i])){
			$show_mwst[$i]=0;
		}
	}
	if($action=="upd"){
		//Do nothing
	} else if($action=="addrow"){
		$show_rows++;
		if($_config_buchungssatz_erstellen_werte_uebernehmen) {
			$new_row = count($kt_soll);
			$kt_soll[$new_row] = $kt_soll[$new_row-1];
			$kt_haben[$new_row] = $kt_haben[$new_row-1];
			$mwst_soll[$new_row] = $mwst_soll[$new_row-1];
      $mwst_haben[$new_row] = $mwst_haben[$new_row-1];
			$show_mwst[$new_row] = $show_mwst[$new_row-1];
			$datum[$new_row] = $datum[$new_row-1];
			$betrag[$new_row] = $betrag[$new_row-1];
     	$waehrung[$new_row] = $waehrung[$new_row-1];
      $belegnr[$new_row] = $belegnr[$new_row-1];
			$beschreibung[$new_row] = $beschreibung[$new_row-1];
      $mwst_satz[$new_row] = $mwst_satz[$new_row-1];
			$mwst_anteil[$new_row] = $mwst_anteil[$new_row-1];
      $mwst_total[$new_row] = $mwst_total[$new_row-1];
			$festeWerte[$new_row] = $festeWerte[$new_row-1];
			$kurs[$new_row] = $kurs[$new_row-1];
		}
	} else if(substr($action,0,3)=="del") {
		$delete = trim(substr($action,3));
		$show_rows--;
	} else if($commit){
		$anz_objekte=count($kt_soll);
		for($i=0;$i<$anz_objekte;$i++){
			$query=mysql_query("SELECT count(*) FROM $buchhaltung"."_Konto WHERE nr='".$kt_soll[$i]."'");
			$query1=mysql_query("SELECT count(*) FROM $buchhaltung"."_Konto WHERE nr='".$kt_haben[$i]."'");
			if(!$datum[$i]) {
				$err[$i]="Bitte geben Sie ein Datum an";
			} else if(!$betrag[$i] && !$err[$i]) {
				 $err[$i]="Bitte geben Sie einen Betrag an";
			} else if(!$kurs[$i] && $waehrung[$i]!=1 && !$err[$i]) {
				 $err[$i]="Bitte geben Sie einen Kurs an";
			} else if(mysql_result($query,0,0)==0) {
				$err[$i]="Das Konto '".$kt_soll[$i]."' existiert nicht";
			} else if(mysql_result($query1,0,0)==0) {
        $err[$i]="Das Konto '".$kt_haben[$i]."' existiert nicht";
      }
		}
		$query=mysql_query("SELECT count(*) FROM $$buchhaltung"."_Konto WHERE nr='$kt_soll'");
		if(count($err)==0) {
			for($i=0;$i<$anz_objekte;$i++) {
				if(!$kurs[$i]){
					$kurs[$i]=1;
				}
				$query=mysql_query("INSERT INTO $buchhaltung"."_Buchungssaetze(datum,beschreibung,kt_haben,kt_soll,betrag,waehrung,kurs,mwst,belegnr) Values('".date_CH_to_EN($datum[$i])."','".$beschreibung[$i]."','".$kt_haben[$i]."','".$kt_soll[$i]."','".$betrag[$i]."','".$waehrung[$i]."','".$kurs[$i]."','".$mwst_satz[$i]."','".$belegnr[$i]."')");
				if(!($msg[$i]=mysql_error()) && $show_mwst[0]) {
					$query=mysql_query("INSERT INTO $buchhaltung"."_Buchungssaetze(datum,beschreibung,kt_haben,kt_soll,betrag,waehrung,kurs,mwst,mwst_feld,belegnr) Values('".date_CH_to_EN($datum[$i])."','$beschreibung[$i]','".$mwst_haben[$i]."','".$mwst_soll[$i]."','".(($betrag[$i]/100)*$mwst_satz[$i])."','".$waehrung[$i]."','".$kurs[$i]."','0','".mysql_insert_id()."','')");
					if(!($msg[$i]=mysql_error())) {
						if(strlen($beschreibung[$i])>0) {
							$msg[$i]="Buchungssatz '".$beschreibung[$i]."' wurde erstellt.";
						} else {
							$msg[$i]="Buchungssatz wurde erstellt.";
						}
					}
				}
			}
			$show_rows=NULL;
			$show_mwst=NULL;
			$datum=NULL;
			$kt_soll=NULL;
			$kt_haben=NULL;
			$betrag=NULL;
			$waehrung=NULL;
			$belegnr=NULL;
			$beschreibung=NULL;
			$mwst_satz=NULL;
			$mwst_anteil=NULL;
			$mwst_total=NULL;
			$festeWerte=NULL;
			$new=1;
		}
	}
if(!$show_rows){
	  $show_rows=1;
}
?>
<html>
<head>
  <title><?=$_config_title ?></title>
  <link rel="stylesheet" href="main.css" type=text/css>
	<script language="JavaScript" type="text/javascript">
	<!--
	var lastChange;
	function rechneLastChange(index) {
		if(lastChange==2){
			rechneBetrag('total',index);
		} else {
			rechneBetrag('betrag',index);
		}
	}
	function rechneBetrag(method,index){
		var satz=document.getElementsByName('mwst_satz['+index+']')[0];
		var betrag=document.getElementsByName('betrag['+index+']')[0];
		var anteil=document.getElementsByName('mwst_anteil['+index+']')[0];
		var total=document.getElementsByName('mwst_total['+index+']')[0];
		if(method=='betrag' && satz.value && betrag.value){
			j = betrag.value;
			p = (j.indexOf(".")>=0 ? j.length-j.indexOf(".")-1 : 0);
			j = j.replace(/\./,"");
			val_betrag=j*1;
			k = satz.value;
			p1 = (k.indexOf(".")>=0 ? k.length-k.indexOf(".")-1 : 0);
			k = k.replace(/\./,"");
			val_satz=k*1;
			val_anteil=val_betrag*(val_satz);
			x = Math.pow(10,p)*Math.pow(10,p1)*100
			y = Math.pow(10,p)
			z = Math.pow(10,p1)
			anteil.value=(val_anteil/x);
			total.value=(val_betrag/y)+(val_anteil/x);
			lastChange=1;
		tring();
		        j= betrag.value;
			p = (j.indexOf(".")>=0 ? j.length-j.indexOf(".")-1 : 0);
			j = j.replace(/\./,"");
			val_betrag=j*1;
			k = satz.value;
			p1 = (k.indexOf(".")>=0 ? k.length-k.indexOf(".")-1 : 0);
			k.replace(/\./,"");
			val_satz=k*1;
			val_anteil=val_betrag*(val_satz);
			x = Math.pow(10,p)*Math.pow(10,p1)*100
			y = Math.pow(10,p)
			z = Math.pow(10,p1)
			anteil.value=(val_anteil/x);
			total.value=(val_betrag/y)+(val_anteil);
			lastChange=1;
			} else if(satz.value && total.value) {
			val_satz=parseFloat(satz.value);
			val_total=parseFloat(total.value);
			val_betrag=(val_total*100)/(100+val_satz);
			val_anteil=(val_total*val_satz)/(val_satz+100);
			anteil.value=val_anteil;
			betrag.value=val_betrag;
			val_satz=parseFloat(satz.value);
			val_total=parseFloat(total.value);
			val_betrag=(val_total*100)/(100+val_satz);
			val_anteil=(val_total*val_satz)/(val_satz+100);
			anteil.value=val_anteil;
			betrag.value=val_betrag;
			lastChange=2;
		}
	}
	function rechneAlle(){
		for(i=0;i<=<?=($show_rows-1);?>;i++){
			if(document.getElementsByName('mwst_total['+i+']')[0]){
				if(!document.getElementsByName('mwst_total['+i+']')[0].value || !document.getElementsByName('mwst_satz['+i+']')[0].value){
					rechneBetrag('betrag',i);
				}
			}
		}
	}
	function form_submit(arg){
		document.getElementsByName('action')[0].value = arg;
		document.Buhaform.submit();
	}
	//-->
	</script>
	<script language="JavaScript" type="text/javascript" src="inc/functions.js"></script>
</head>
<?
$height=150+($show_rows*140);
print "<body onload=\"javascript:self.focus();";
if($new) {
	print "opener.parent.main.location.reload();";
}
if($action=="addrow"||isset($delete)){
	print "window.resizeTo(document.body.offsetWidth,($height > (screen.height -40) ? (screen.height-40) : $height));";
	if($show_rows>2){
		print "window.moveTo(10,10);";
	}
} else if($new && $anz_objekte>1){
	print "window.resizeTo(document.body.offsetWidth,250);"; 
}
print "rechneAlle();\">
<p class=titel>Buchungssatz erstellen</p>\n";
for($i=0;$i<count($msg);$i++){
	if($msg[$i]) {
		print $msg[$i]."<br>";
	}
}
print "<form method=get action=$PHP_SELF name=\"Buhaform\">
<input type=hidden name=show_rows value=$show_rows>
<input type=hidden name=action value=\"\">
<table border=0 cellpadding=3 cellspacing=0>";
for($i=0,$ii=0;$ii<$show_rows;$i++){
	if($i == $delete && isset($delete)) {
		continue;
	}
	if(!$datum[$i]){
		$datum[$i]=date("d.m.Y");
	}
	if(!$mwst_satz[$i]){
		$mwst_satz[$i]=$_config_buchungsatz_erstellen_mwst_default_value;
	}
	if($show_mwst[$i] && $_config_buchungsatz_erstellen_mwst_default_value){
		$show_mwst[$i]=true;
	}
	if($show_mwst[$i]){
		$mwst_checked=" CHECKED";
	} else {
		$mwst_checked="";
	}
	if($_config_belegnr_increment && !$belegnr[$i]) {
		$query2=mysql_query("SELECT sum(belegnr) as maxnr FROM 03_Buchungssaetze GROUP BY id ORDER BY maxnr DESC LIMIT 0,1");
		$belegnr[$i]= @mysql_result($query2,0,0)+1;
	}
	print "<tr>
		<td colspan=8>";
	if($ii>0) {
		print "<hr noshade width=\"100%\">";
	}
	print "</td>
			</tr>";
  if($err[$i]){
    print "<tr>
      <td colspan=8><b>Fehler:</b> ".$err[$i]."</b></td>
    </tr>";
  }
	print "<tr>
	  <td><b>Datum</b></td>
	  <td><b>Konto Soll</b></td>
	  <td><b>Konto Haben</b></td>
	  <td><b>Betrag</b></td>
	  <td><b>Währung</b></td>
	  <td><b>Kurs</b></td>
  	<td><b><nobr>Beleg Nr.</nobr></b></td>
	</tr>";
	print "<tr>
		<td><input type=text name=\"datum[$ii]\" value=\"".$datum[$i]."\" style=\"width:80px;\"></td>
		<td>".getKontoList("kt_soll[$ii]","150",$kt_soll[$i])."</td>
	  <td>".getKontoList("kt_haben[$ii]","150",$kt_haben[$i])."</td>
	  <td><input type=text name=\"betrag[$ii]\" id=\"betrag[$ii]\" value=\"".$betrag[$i]."\" style=\"width:80px\"";
	if($show_mwst[$i]){
		print " onKeyUp=\"javascript:rechneBetrag('betrag',$ii)\"";
	}
	print " onKeyDown=\"javascript:document.getElementById('fw_show[$ii]').value = document.getElementById('kurs[$ii]').value * this.value\"></td>
		<td>".getWaehrungsList("waehrung[$ii]",70,$waehrung[$i])."</td>
		<td><input type=text name=\"kurs[$ii]\" id=\"kurs[$ii]\" value=\"".$kurs[$i]."\" style=\"width:80px;\" onChange=\"javascript:document.getElementById('fw_show[$ii]').value = this.value * document.getElementById('betrag[$ii]').value\"></td>
		<td><input type=text name=\"belegnr[$ii]\" value=\"".$belegnr[$i]."\" style=\"width:80px;\" maxlength=255></td>";
	if($show_mwst[$i]) {
		print "<td class=negativ><input type=checkbox name=\"show_mwst[$ii]\" checked onclick=\"javascript:form_submit('upd');\"> MWSt.</td>";
	} else {
		print "<td><input type=checkbox name=\"show_mwst[$ii]\" onclick=\"javascript:form_submit('upd');\"> MWSt.</td>";
	}
	print "</tr>
	<tr>
		<td colspan=3><b>Beschreibung</b></td>";
		//MWST Anteil
      if($show_mwst[$i]){
        print "<td class=negativ><input type=text name=\"mwst_anteil[$ii]\" value=\"".$mwst_anteil[$i]."\" style=\"width:80px\"></td>
					<td colspan=4 class=negativ>&nbsp;</td>";
      } else {
				print "<td>&nbsp;</td>
					<td colspan=4>&nbsp;</td>";
			}
	print "</tr>
	<tr>
	  <td colspan=2><input id=jee type=text name=\"beschreibung[$ii]\" value=\"".$beschreibung[$i]."\" style=\"width:250px\"> </td>
		<td>";
	if($show_mwst[$i]){
		print "<td class=negativ><input type=text name=\"mwst_total[$ii]\" value=\"".$mwst_total[$i]."\" style=\"width:80px\" onKeyUp=\"javascript:rechneBetrag('total',$ii)\"></td>
			<td align=right class=negativ><b>Satz</b></td>
		  <td class=negativ><input type=text name=\"mwst_satz[$ii]\" value=\"".$mwst_satz[$i]."\" style=\"width:80px\" onKeyUp=\"javascript:rechneLastChange($ii)\"></td>
			<td align=right class=negativ>Typ</td>
			<td class=negativ>
				<SELECT name=\"festeWerte[$ii]\" onChange=\"javascript:document.getElementsByName('mwst_satz[$ii]')[0].value=this.value;rechneLastChange($ii);\" style=\"width:80px\">
					<option>Wählen</option>";
		$query=mysql_query("SELECT text,mwst FROM Buchungssaetzte_default_mwst");
		echo mysql_error();
		while(list($option_text,$option_mwst)=mysql_fetch_row($query)){
			if($festeWerte[$i]==$option_mwst){
				print "<option value=\"$option_mwst\" SELECTED>$option_text</option>\n";
			} else {
				print "<option value=\"$option_mwst\">$option_text</option>\n";
			}
		}
		print"		</SELECT>
			</td>";
	} else {
		print "<td colspan=5>&nbsp;</td>";
	}
	print "<tr>";
	if(!$mwst_haben[$i]) $mwst_haben[$i] = $_config_buchungssaz_erstellen_mwst_haben;
	if(!$mwst_soll[$i]) $mwst_soll[$i] = $_config_buchungssaz_erstellen_mwst_haben;

	if($show_mwst[$i]) {
		print "<td colspan=3>CHF: <input type=text id=fw_show[$ii] name=fw_show[$ii] value=\"$fw_show[$ii]\" onChange=\"javascript:document.getElementById('kurs[$ii]').value = this.value / document.getElementById('betrag[$ii]').value\"></td>
			<td colspan=2 class=negativ>".getKontoList("mwst_soll[$ii]","150",substr($mwst_soll[$i],0,(strpos($mwst_soll[$i],",")<1 ? strlen($mwst_soll[$i]) : strpos($mwst_soll[$i],","))))."</td>
			<td colspan=2 class=negativ>".getKontoList("mwst_haben[$ii]","150",substr($mwst_haben[$i],0,(strpos($mwst_haben[$i],",")<1 ? strlen($mwst_haben[$i]) : strpos($mwst_haben[$i],","))))."</td>
			<td class=negativ align=right>";
	} else {
		print "<td colspan=3>CHF: <input type=text id=fw_show[$ii] name=fw_show[$ii] value=\"$fw_show[$ii]\" onChange=\"javascript:document.getElementById('kurs[$ii]').value = this.value / document.getElementById('betrag[$ii]').value\"></td><td colspan=5 align=right>";
	}
	if($show_rows>1) {
		print "<input type=button onclick=\"javascript:form_submit('del".$ii."');\" value=\"-\" style=\"width:20px;\"> ";
	}
	if($show_rows==($ii+1)) {
		print "<input type=button value=\"+\" style=\"width:20px;\" onclick=\"javascript:form_submit('addrow');\">";
	}
	print "</td>
		</tr>";
	$ii++;
}
?>
<tr>
	<td colspan=8><input type=submit name=commit value="Hinzufügen"></td>
</tr>
</table>
</form>
</body>
</html>
