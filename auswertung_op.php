<?
	include("inc/header.inc.php");
session_unregister("verrechnen");
?>
<html>
<head>
  <title><?=$_config_title ?> - Offene Posten mit Fälligkeiten</title>
	<link rel="stylesheet" href="main.css" type=text/css>
  <script language="JavaScript" type="text/javascript" src="inc/functions.js"></script>
  <script language="javascript" type="text/javascript">
  <!--
    function openVerrechnen(id){
      window.open('verrechnen.php?id='+id,'verrechnen',"width=500,height=600,left=100,top=100,resizable=yes,scrollbars=yes");
    }
  //-->
  </script>
</head>
<body onLoad="self.focus()">
<div style=titel>Offene Posten mit Fälligkeiten</div><br>
<?
  print "<form method=post action=\"$PHP_SELF?id=$id\">
<table border=0 cellpadding=0>
<tr>
  <td>Anfang:</td>
  <td><input type=text maxlength=10 name=start value=\"$start\"></td>
</tr>
<tr>
  <td>Ende:</td>
  <td><input type=text maxlength=10 name=end value=\"$end\"></td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td><input type=submit value=\"Aktualisieren\"></td>
</tr>
</table>
</form>
<br>";
$sql="SELECT kt.name,bs.beschreibung,bs.betrag,bs.waehrung,bs.datum,bs.id FROM $buchhaltung"."_Konto kt,$buchhaltung"."_Nebenkonto nk, $buchhaltung"."_Buchungssaetze bs WHERE nk.name='Kreditoren' AND nk.id = kt.nebenkonto AND (bs.kt_soll = kt.nr OR bs.kt_haben = kt.nr) AND bs.mwst_feld IS NULL AND bs.bezahlt IS NULL ORDER BY kt.name";

if($start) {
  $sql= str_replace("WHERE","WHERE bs.datum >= '".date_CH_to_EN($start)."' AND",$sql);
}
if($end) {
  $sql= str_replace("WHERE","WHERE bs.datum <= '".date_CH_to_EN($end)."' AND",$sql);
}
$query=mysql_query($sql);
echo mysql_error();
if(mysql_num_rows($query)>0) {
print "<table border=0>\n";
$count=-1;
$total=array();
while(list($name,$text,$betrag,$waehrung,$datum,$bsid)=mysql_fetch_row($query)) {
	if($name != $lastname) {
		if($count>-1) {
			if(count($total)==0) $total[1]=0;
			print "<tr>
				<td>&nbsp;</td>
				<td>Total von $count Posten:</td>
				<td>";
			foreach(array_keys($total) as $key) {
				print getWaehrung($key)."<br>";
			}
			print "</td>
				<td>";
			foreach($total as $key) {
				print formatPreis($key)."<br>";
			}
			print "</td>
					<td>";
			$total_hautpwaehrung=$total[1];
			for($i=2;$i<=20;$i++) {
				if($total[$i]) {
					$total_hautpwaehrung+=($total[$i]*getFx($i,1));
				}
			}
			$gesamttotal+=$total_hautpwaehrung;
			print getWaehrung(1)." ".formatPreis($total_hautpwaehrung);
			print "</td>
				</tr>
			<tr>
				<td colspan=5>&nbsp;</td>
			</tr>";
		}
		print "<tr>
			<td colspan=5><b>$name</b></td>
		</tr>\n";
		$lastname=$name;
		unset($total);
		$count=0;
	}
	$query2=mysql_query("SELECT count(*) FROM $buchhaltung"."_Buchungssaetze WHERE bezahlt='$bsid'");
	if(mysql_result($query2,0,0)==0) {
		//MWST-Betrag holen
	  $query2=mysql_query("SELECT betrag FROM $buchhaltung"."_Buchungssaetze WHERE mwst_feld='$bsid'");
  	if(mysql_num_rows($query2)>0) $betrag+=mysql_result($query2,0,0);

		print "<tr>
			<td width=90>".date_EN_to_CH($datum)."</td>
			<td><a href=\"javascript:openVerrechnen('$bsid');\">$text</a></td>
			<td>".getWaehrung($waehrung)."</td>
			<td>".formatPreis($betrag)."</td>
		</tr>\n";
		$count++;
		$total[$waehrung]+=$betrag;
	}
}
//Das Total für den letzten noch Anzeigen
if($count>-1) {
	if(count($total)==0) $total[1]=0;
	print "<tr>
  <td>&nbsp;</td>
  <td>Total von $count Posten:</td>
  <td>";
    foreach(array_keys($total) as $key) {
    	print getWaehrung($key)."<br>";
    }
    print "</td>
    	<td>";
		foreach($total as $key) {
			print formatPreis($key)."<br>";
		}
      print "</td>
          <td>";
      $total_hautpwaehrung=$total[1];
      for($i=2;$i<=20;$i++) {
        if($total[$i]) {
          $total_hautpwaehrung+=($total[$i]*getFx($i,1));
        }
      }
      $gesamttotal+=$total_hautpwaehrung;
      print getWaehrung(1)." ".formatPreis($total_hautpwaehrung);
print "</td></tr><tr>
		<td colspan=5>&nbsp;</td>
	<tr>";
}
print "</table>\n";
print "<br><b>Total: ".getWaehrung(1)." ".round($gesamttotal,2);
}
?>
</body>
</html>
