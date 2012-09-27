<?
	include("inc/header.inc.php");

	$query = mysql_query("SELECT Konto.Name, Kontotypen.id, Kontotypen.Name FROM $buchhaltung"."_Konto Konto,Kontotypen WHERE Konto.typ = Kontotypen.id AND Nr='$kontonr'");
	list($name,$typ,$typ_name)=mysql_fetch_row($query);
?>
<html>
<head>
  <title><?=$_config_title ?> - Konto <?=$name ?></title>
	<link rel="stylesheet" href="main.css" type=text/css>
  <script language="JavaScript" type="text/javascript" src="inc/functions.js"></script>
	<script language="javascript" type="text/javascript">
	<!--
		function buchungssatz(id){
			window.open('buchungssatz_editieren.php?id='+id,'buchungssatz_editieren','width=920,height=220,left=10,top=200');
		}
	//-->
	</script>
</head>
<body onLoad="self.focus()">
<form method=post action="<?=$PHP_SELF."?kontonr=".$kontonr?>">
<table border=0 width="100%">
<tr>
	<td width="50%">
		<input type=text name=von style="width:100px" value="<?=$von?>"> - <input type=text name=bis style="width:100px" value="<?=$bis?>"> <input type=submit value="Go">
	</td>
	<td width="50%"><div class=titel><?=$name?></div></td>
</tr>
</table>
</form>

<?
  $totalHaben=0;
  $totalSoll=0;
?>
<table border=0 width=900 height="85%" cellpadding=0 cellspacing=0>
<tr>
  <td colspan=3 width=900 height=2 bgcolor="#000000"></td>
</tr>
  <td width=449 valign=top style="padding:10px 10px 0px 0px;">
    <?
		if(!(strstr($_config_buchungssaz_erstellen_mwst_haben,$kontonr)===FALSE)) {
	    $query=mysql_query("SELECT id,beschreibung,TRUNCATE(betrag,3),kurs,waehrung FROM $buchhaltung"."_Buchungssaetze WHERE kt_soll ='$kontonr' ".($von && $bis ? " AND datum >= '".date_CH_to_EN($von)."' AND datum <= '".date_CH_to_EN($bis)."'" : "")." ORDER BY Datum");
		} else {
			$query=mysql_query("SELECT id,beschreibung,TRUNCATE(betrag,3),kurs,waehrung FROM $buchhaltung"."_Buchungssaetze WHERE kt_soll ='$kontonr' ".($von && $bis ? " AND datum >= '".date_CH_to_EN($von)."' AND datum <= '".date_CH_to_EN($bis)."'" : "")." AND mwst_feld IS NULL ORDER BY Datum");
		}
    print "<table border=0 width=\"100%\">";
    while(list($id,$beschreibung,$betrag,$kurs,$waehrung)=mysql_fetch_row($query))
		{
      $query2 = mysql_query("SELECT (betrag) FROM $buchhaltung"."_Buchungssaetze WHERE mwst_feld = '$id' AND kt_soll='$kontonr'");
      if(mysql_num_rows($query2)>0) {
        $betrag+=mysql_result($query2,0,0);
      }

      $totalSoll+=($betrag*$kurs);
      print "<tr>
				<td align=left valign=top><a href=\"#\" onclick=\"javascript:buchungssatz('$id');\">$beschreibung</a></td>";
        if($kurs!=1) {
            print "<td align=right valign=top width=100><i>".formatPreis($betrag)." ".getWaehrung($waehrung)."</i></td>
							<td align=right valign=top width=80>".formatPreis($betrag*$kurs)."</td>";
        } else {
          print "<td  colspan=2 align=right valign=top widht=80>".formatPreis($betrag*$kurs)."</td>";
        }
      print "</tr>";
    }
    print "</table>";
    ?>
  </td>
  <td width=2 bgcolor="#000000" rowspan=3></td>
  <td width=449 valign=top style="padding:10px 0px 0px 10px">
    <?
	if(!(strstr($_config_buchungssaz_erstellen_mwst_haben,$kontonr)===FALSE)) 
		$query=mysql_query("SELECT id,beschreibung,(betrag),kurs,waehrung FROM $buchhaltung"."_Buchungssaetze WHERE kt_haben ='$kontonr' ".($von && $bis ? " AND datum >= '".date_CH_to_EN($von)."' AND datum <= '".date_CH_to_EN($bis)."'" : "")." ORDER BY Datum");
 	else
			$query=mysql_query("SELECT id,beschreibung,(betrag),kurs,waehrung FROM $buchhaltung"."_Buchungssaetze WHERE kt_haben ='$kontonr' ".($von && $bis ? " AND datum >= '".date_CH_to_EN($von)."' AND datum <= '".date_CH_to_EN($bis)."'" : "")." AND mwst_feld IS NULL ORDER BY Datum");
		
		print "<table border=0 width=\"100%\">";
    while(list($id,$beschreibung,$betrag,$kurs,$waehrung)=mysql_fetch_row($query))
    {
			 $query2 = mysql_query("SELECT (betrag) FROM $buchhaltung"."_Buchungssaetze WHERE mwst_feld = '$id' AND kt_haben='$kontonr'");
			 if(mysql_num_rows($query2)>0) {
				$betrag+=mysql_result($query2,0,0);
			}
      $totalHaben+=($betrag*$kurs);
      print "<tr>";
				if($kurs!=1) {
						print "<td align=right valign=top width=80>".formatPreis($betrag*$kurs)."</td>
						<td align=right valign=top width=100><i>".formatPreis($betrag)." ".getWaehrung($waehrung)."</i></td>";
				} else {
					print "<td align=right valign=top widht=80>".formatPreis($betrag*$kurs)."</td>
						<td width=100>&nbsp;</td>";
				}
				print "<td align=right valign=top><a href=\"#\" onclick=\"javascript:buchungssatz('$id');\">$beschreibung</a></td>
      </tr>";
    }
    print "</table>";
    ?>
  </td>
</tr>
<tr>
  <td height=30 style="padding:10px 10px 0px 0px" valign=bottom>
		<? 
			if($totalSoll<$totalHaben){
				print "<table border=0 width=\"100%\">
			    <tr>
			      <td>Saldo</td>
			      <td align=right>".formatPreis($totalHaben-$totalSoll)."</td>
			    </tr>
			    </table>";
				$totalSoll=$totalHaben;
			}
		?>
  </td>
  <td height=30 style="padding:10px 0px 0px 10px">
    <? 
			if($totalSoll>$totalHaben){
        print "<table border=0 width=\"100%\">
          <tr>
            <td align=left>".formatPreis($totalSoll-$totalHaben)."</td>
            <td align=right>Saldo</td>
          </tr>
          </table>";
				$totalHaben=$totalSoll;
			}
    ?>
	</td>
</tr>
<tr>
  <td height=30 style="padding:10px 10px 0px 0px">
    <table border=0 width="100%">
    <tr>
      <td style="font-weight:bold;">Total</td>
      <td align=right style="text-decoration:underline;font-weight:bold;"><?=formatPreis($totalSoll);?></td>
    </tr>
    </table>
  </td>
  <td height=30 style="padding:10px 0px 0px 10px">
    <table border=0 width="100%">
    <tr>
      <td style="text-decoration:underline;font-weight:bold;"><?=formatPreis($totalHaben);?></td>
      <td align=right style="font-weight:bold;">Total</td>
    </tr>
    </table>
  </td>
</tr>

</table>

</body>
</html>
