<?
	include("inc/header.inc.php");
?>
<html>
<head>
  <title><?=$_config_title ?> - Bilanz Ertrag</title>
	<link rel="stylesheet" href="small.css" type=text/css>
  <script language="JavaScript" type="text/javascript" src="inc/functions.js"></script>
</head>
<body>
<?
if(!$bilanz_date) 
	die("<div style=titel>Bilanz</div><br><form method=POST action='$PHP_SELF'>Stichdatum: <input type=text name=bilanz_date style='width:100px'> <input type=submit value='Generieren'></form></body></html>");?>	
<div style=titel>Bilanz per <?=$bilanz_date?></div><br>
<table border=0 cellpadding=0 cellspacing=0 >
<tr>
	<td bgcolor=#CCCCCC width=50 style="padding:2px 2px 2px 2px;border-left:1px solid black;border-top:1px solid black;border-bottom:1px solid black">Konti</td>
	<td bgcolor=#CCCCCC width=200 style="padding:2px 2px 2px 2px;border-top:1px solid black;border-bottom:1px solid black">Bezeichnung</td>
	<td bgcolor=#CCCCCC width=100 style="padding:2px 2px 2px 2px;border-top:1px solid black;border-bottom:1px solid black">Aktuell</td>
	<td bgcolor=#CCCCCC width=100 style="padding:2px 2px 2px 2px;border-top:1px solid black;border-bottom:1px solid black">Vorjahr</td>
	<td bgcolor=#CCCCCC width=100 style="padding:2px 2px 2px 2px;border-top:1px solid black;border-bottom:1px solid black">Budget (aktuell)</td>
	<td bgcolor=#CCCCCC width=70 style="padding:2px 2px 2px 2px;border-top:1px solid black;border-bottom:1px solid black">diff Vorj. %</td>
	<td bgcolor=#CCCCCC width=70 style="padding:2px 2px 2px 2px;border-top:1px solid black;border-bottom:1px solid black;border-right:1px solid black">diff Budg. %</td>
</tr>
<tr>
	<td colspan=7>&nbsp;</td>
</tr>
<tr>
  <td bgcolor=#FFFFFF width=50  style="border-left:1px solid black;border-top:1px solid black">&nbsp;</td>
  <td bgcolor=#FFFFFF width=200 style="border-top:1px solid black;">&nbsp;</td>
  <td bgcolor=#CCCCCC width=100 style="border-top:1px solid black;border-left:1px solid black;">&nbsp;</td>
  <td bgcolor=#FFFFFF width=100 style="border-top:1px solid black;border-left:1px solid black;">&nbsp;</td>
  <td bgcolor=#CCCCCC width=100 style="border-top:1px solid black;border-left:1px solid black;">&nbsp;</td>
  <td bgcolor=#FFFFFF width=70 style="border-top:1px solid black;border-left:1px solid black;">&nbsp;</td>
  <td bgcolor=#CCCCCC width=70 style="border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;">&nbsp;</td>
</tr>
<?
	function printLine($a1,$a2,$a3,$a4,$a5,$a6,$a7) {
		if(!$a1) $a1="&nbsp;";
		if(!$a2) $a2="&nbsp;";
		if(!$a3) $a3="&nbsp;"; 
		if(!$a4) $a4="&nbsp;";
		if(!$a5) $a5="&nbsp;";
		if(!$a6) $a6="&nbsp;";
		if(!$a7) $a7="&nbsp;";
		
		print "<tr>
 		 <td bgcolor=#FFFFFF width=50  style=\"border-left:1px solid black;padding:2px 2px 2px 2px\">$a1</td>
 		 <td bgcolor=#FFFFFF width=200 style=\"padding:2px 2px 2px 2px\">$a2</td>
  		<td bgcolor=#CCCCCC align=right width=100 style=\"padding:2px 2px 2px 2px;border-left:1px solid black;\">$a3</td>
  		<td bgcolor=#FFFFFF width=100 style=\"border-left:1px solid black;padding:2px 2px 2px 2px\">$a4</td>
  		<td bgcolor=#CCCCCC width=100 style=\"border-left:1px solid black;padding:2px 2px 2px 2px\">$a5</td>
  		<td bgcolor=#FFFFFF width=70 style=\"border-left:1px solid black;padding:2px 2px 2px 2px\">$a6</td>
  		<td bgcolor=#CCCCCC width=70 style=\"border-right:1px solid black;border-left:1px solid black;padding:2px 2px 2px 2px\">$a7</td>
		</tr>";
	}
	printLine("","<span style=\"font-size:13px;font-weight:bold\">Aufwand</span>","","","","","");
	printLine("","","","","","","");
	$query=mysql_query("SELECT kt.nr,kt.name,sum(bu.betrag*bu.kurs) FROM $buchhaltung"."_Buchungssaetze bu, $buchhaltung"."_Konto kt WHERE bu.kt_soll=kt.nr AND kt.typ=3 AND (nebenkonto is NULL OR nebenkonto <1)  GROUP BY kt.nr ORDER BY sort");
	for($i=1;list($nr,$name,$betrag)=mysql_fetch_row($query);$i++) {
		$query3=mysql_query("SELECT name FROM $buchhaltung"."_Namenskonto WHERE position='$nr'");
		if(mysql_num_rows($query3)>0) {
			if(strlen($namenskonto)>0) {
				printLine("","<b>$namenskonto</b>","<b>".number_format($total,2,".","'")."</b>","","","","");
				printLine("&nbsp;","","","","","","");
				$total=0;
			}
			$namenskonto=mysql_result($query3,0,0);
		}
		$query2=mysql_query("SELECT sum(bu.betrag*bu.kurs) FROM $buchhaltung"."_Buchungssaetze bu WHERE bu.kt_haben ='$nr'");
		$betrag-=mysql_result($query2,0,0);
		$total+=$betrag;
		$total_1+=$betrag;
		printLine($nr,$name,number_format($betrag,2,".","'"),"","","","");
		if(mysql_num_rows($query)==$i && $namenskonto) printLine("","<b>$namenskonto</b>","<b>".number_format($total,2,".","'")."</b>","","","","");
	}
	$total=0;
	printLine("","<b>Aufwandtotal</b>","<b>".number_format($total_1,2,".","'")."</b>","","","","");

?>
<tr>
  <td bgcolor=#FFFFFF width=50 style="border-left:1px solid black;border-bottom:1px solid black">&nbsp;</td>
  <td bgcolor=#FFFFFF width=200 style="border-bottom:1px solid black;">&nbsp;</td>
  <td bgcolor=#CCCCCC width=100 style="border-bottom:1px solid black;border-left:1px solid black;">&nbsp;</td>
  <td bgcolor=#FFFFFF width=100 style="border-bottom:1px solid black;border-left:1px solid black;">&nbsp;</td>
  <td bgcolor=#CCCCCC width=100 style="border-bottom:1px solid black;border-left:1px solid black;">&nbsp;</td>
  <td bgcolor=#FFFFFF width=70 style="border-bottom:1px solid black;border-left:1px solid black;">&nbsp;</td>
  <td bgcolor=#CCCCCC width=70 style="border-bottom:1px solid black;border-right:1px solid black;border-left:1px solid black;">&nbsp;</td>
</tr>
</table>
</body>
</html>
