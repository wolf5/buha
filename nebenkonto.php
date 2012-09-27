<?
include("inc/header.inc.php");
if($action=="oben")	{
	$query=mysql_query("SELECT nr,sort,typ FROM $buchhaltung"."_Konto WHERE nebenkonto = '$kontonr' AND typ='1' OR typ='2' ORDER BY sort ASC");
	while(list($nr,$sort,$typ)=mysql_fetch_row($query)) {
		$var="id$nr";
		if($$var) {
			$query2=mysql_query("SELECT sort FROM $buchhaltung"."_Konto WHERE nebenkonto = '$kontonr' AND sort < '$sort' AND typ='$typ' ORDER BY sort DESC LIMIT 0,1");
			list($changeid)=mysql_fetch_row($query2);
			$query2=mysql_query("UPDATE $buchhaltung"."_Konto SET sort='0' WHERE sort='$changeid'");
			$query2=mysql_query("UPDATE $buchhaltung"."_Konto SET sort='$changeid' WHERE sort='$sort'");
			$query2=mysql_query("UPDATE $buchhaltung"."_Konto SET sort='$sort' WHERE sort='0'");
		}
	}
} else if($action=="unten") { 
  $query=mysql_query("SELECT nr,sort,typ FROM $buchhaltung"."_Konto WHERE nebenkonto = '$kontonr' AND typ='1' OR typ='2' ORDER BY sort DESC");
  while(list($nr,$sort,$typ)=mysql_fetch_row($query)) { 
    $var="id$nr";
    if($$var) { 
      $query2=mysql_query("SELECT sort FROM $buchhaltung"."_Konto WHERE nebenkonto = '$kontonr' AND sort > '$sort' AND typ='$typ' ORDER BY sort ASC LIMIT 0,1");
      list($changeid)=mysql_fetch_row($query2);
      $query2=mysql_query("UPDATE $buchhaltung"."_Konto SET sort='0' WHERE sort='$changeid'");
      $query2=mysql_query("UPDATE $buchhaltung"."_Konto SET sort='$changeid' WHERE sort='$sort'");
      $query2=mysql_query("UPDATE $buchhaltung"."_Konto SET sort='$sort' WHERE sort='0'");
    }
	}
}
?>
<html>
<head>
  <title><?=$_config_title ?></title>
  <link rel="stylesheet" href="main.css" type=text/css>
  <script language="JavaScript" type="text/javascript" src="inc/functions.js"></script>
	<script language="javascript" type="text/javascript">
	<!--
		var fenKonto;
		function konto(konto){
			window.open('buchungssaetze.php?kontonr='+konto,'test','width=985,height=100,left=30,top=600,resizable=yes,scrollbars=yes');
			window.open('konto.php?kontonr='+konto,'konto'+konto,'width=950,height=500,left=10,top=10,resizable=yes,scrollbars=yes');
		}
		function namens(){
			if(document.getElementById('namenskonto').value=="erstellen"){
				window.open('namenskonto_erstellen.php','namenskonto_erstellen','width=300,height=200,left=10,top=10');
			} else {
				window.open('namenskonto_loeschen.php','namenskonto_loeschen','width=300,height=200,left=10,top=10');
			}
		}
		function markcheckbox(end,side,checked) {
			for(i=1;i<end;i++) {
				document.getElementById("chkbox["+side+"]["+i+"]").checked = checked;
			}
		}
	//-->
	</script>
</head>
<body style="bgcolor:red;">
<?
	$totalHaben=0;
	$totalSoll=0;
?>
<form method=post action="<?=$PHP_SELF."?kontonr=".$kontonr?>">
<table border=0 width=900 cellpadding=0 cellspacing=0 align=center height=400>
<tr>
	<td colspan=3 width=900 height=2 bgcolor="#000000"></td>
</tr>
	<td width=449 valign=top style="padding: 10px 10px 0px 0px;" height="*">
		<?
		$query=mysql_query("SELECT nr,name,waehrung FROM $buchhaltung"."_Konto WHERE typ=1 AND nebenkonto ='$kontonr' ORDER BY sort");
		print "<table border=0 width=\"100%\">";
    for($i=1;(list($konto,$name,$waehrung)=mysql_fetch_row($query));$i++) {
			$betrag=getSollBetrag($konto);
	
			//Namenskonto
			$query2=mysql_query("SELECT id,name FROM $buchhaltung"."_Namenskonto WHERE position='$konto'");
			if(mysql_num_rows($query2)>0){
				list($namenskonto_id,$namenskonto_name)=mysql_fetch_row($query2);
				print "<tr>
					<td colspan=3 style=\"font-weight:bold;\">$namenskonto_name</td>
				</tr>\n";
				$namenskonto=NULL;
			}
			$totalSoll+=$betrag;

      print "<tr>
				<td width=20 valign=top><input type=checkbox name=\"id$konto\" value=1 id=\"chkbox[1][$i]\" onClick=\"javascript:markcheckbox($i,1,this.checked);\"></td>
        <td valign=top><a href=\"javascript:konto('$konto');\">".getKontoFormat($name,$konto)."</a></td>
				<td align=right valign=top width=100>".formatPreis($betrag)."</td>
      </tr>";
    }
		print "</table>";
		?>
	</td>
	<td width=2 bgcolor="#000000" rowspan=3></td>
  <td width=449 valign=top style="padding:10px 0px 0px 10px">
		<table border=0 width="100%">
    <?
		$query=mysql_query("SELECT nr,name,waehrung FROM $buchhaltung"."_Konto WHERE typ=2 AND nebenkonto='$kontonr' ORDER BY sort");
    for($i=1;(list($konto,$name,$waehrung)=mysql_fetch_row($query));$i++) {
			$betrag=getHabenBetrag($konto);

      //Namenskonto
      $query2=mysql_query("SELECT id,name FROM $buchhaltung"."_Namenskonto WHERE position='$konto'");
      if(mysql_num_rows($query2)>0){
        list($namenskonto_id,$namenskonto_name)=mysql_fetch_row($query2);
        print "<tr>
          <td align=right colspan=3 style=\"font-weight:bold;\">$namenskonto_name</td>
        </tr>\n";
        $namenskonto=NULL;
      }

      $totalHaben+=$betrag;
      print "<tr>
      	<td align=left valign=top width=100>".formatPreis($betrag)."</td>";
			print "<td valign=top align=right><a href=\"javascript:konto('$konto');\">".getKontoFormat($name,$konto)."</a></td>
				<td width=20 valign=top><input type=checkbox name=\"id$konto\" value=1  id=\"chkbox[2][$i]\" onClick=\"javascript:markcheckbox($i,2,this.checked);\"></td></tr>";
    }
    print "</table>";

    ?>
  </td>
</tr>
<tr>
  <td height=30 style="padding:10px 10px 0px 0px" valign=bottom>
    <?
      if($totalSoll<$totalHaben){
        print "<table border=0 width=\"100%\" cellpadding=0 cellspacing=0>
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
        print "<table border=0 width=\"100%\" cellpadding=0 cellspacing=0>
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
	<td height=30 style="padding:10px 10px 0px 0px;" valign=bottom>
		<table border=0 width="100%">
		<tr>
			<td style="font-weight:bold;">Total</td>
			<td align=right style="text-decoration:underline;font-weight:bold;"><?=formatPreis($totalSoll);?></td>
		</tr>
		</table>
	</td>
  <td height=30 style="padding:10px 0px 0px 10px" valign=bottom>
    <table border=0 width="100%" style="padding-left:10px;">
    <tr>
      <td style="text-decoration:underline;font-weight:bold;"><?=formatPreis($totalHaben);?></td>
      <td align=right style="font-weight:bold;">Total</td>
    </tr>
    </table>
  </td>
</tr>
</table><br>
<table border=0>
<tr>
	<td>
		Ausgew&auml;hlte Konten:<br>
		<SELECT name="action">
		  <option value="oben">Nach oben</option>
			<option value="unten">Nach unten</option>
		</SELECT>
		<input type=submit name=submit value="OK">
	</td>
	<td>&nbsp;</td>
	<td>
		Namenskonto:<br>
		<SELECT name="namenskonto" id="namenskonto">
			<option value="erstellen">Erstellen</option>
			<option value="loeschen">L&ouml;schen</option>
		</SELECT>
		<input type=button onclick="javascript:namens();" name=submit value="OK">
	</td>
</tr>
</table>
</form>
</body>
</html>
