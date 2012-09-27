<?
	include("inc/header.inc.php");
	$msg="";
	if($action == "create") {
		$query=mysql_query("CREATE TABLE $buha"."_Konto ($_config_struktur_konto)");
		if(mysql_error()=="") {
			if($konten_erstellen) {
				$query=mysql_query("SELECT $_config_struktur_konto_fields FROM $buchhaltung"."_Konto");
				for($i=0;$i<mysql_num_rows($query);$i++) {
					$str="";
					for($ii=0;$ii<$_config_struktur_konto_fields_count;$ii++) {
						if($str) $str.=",";
						$str.="'".mysql_result($query,$i,$ii)."'";
					}
					$query2=mysql_query("INSERT INTO $buha"."_Konto($_config_struktur_konto_fields) VALUES($str)");
					$error=mysql_error();
				}
			}
			$query=mysql_query("CREATE TABLE $buha"."_Buchungssaetze($_config_struktur_buchungssaetze)");
			if(!($error=mysql_error())) {
				$query=mysql_query("CREATE TABLE $buha"."_Namenskonto($_config_struktur_namenskonto)");
				$error=mysql_error();
				$query=mysql_query("CREATE TABLE $buha"."_Nebenkonto($_config_struktur_nebenkonto)");
        $error=mysql_error();
				if($konten_erstellen) {
					$query = mysql_query("SELECT id, name, typ FROM $buchhaltung"."_Nebenkonto");
        	while(list($id,$name,$typ) = mysql_fetch_row($query)) {
          	$query2 = mysql_query("INSERT INTO $buha"."_Nebenkonto(id,name,typ) VALUES($id,'$name',$typ)");
        	}
				}
			}
		} else {
			$error="Fehler beim erstellen der Tabelle $buha"."_Konto: ".mysql_error();
		}
		if(!$error) {
			$query=mysql_query("INSERT INTO Buchhaltungen(id,startDate,endDate,selected) VALUES('$buha',NOW(),NULL,'1')");
			$msg.="Die Buchhaltung $buha wurde erstellt.<br>";
			$action="select";
		}
	}
	if($action == "select") {
		$query=mysql_query("UPDATE Buchhaltungen SET selected=NULL");
		$query=mysql_query("UPDATE Buchhaltungen SET selected='1' WHERE id='$buha'");
		$query=mysql_query("SELECT id FROM Buchhaltungen WHERE selected='1'");
		if(mysql_num_rows($query)>0) {
			if(mysql_result($query,0,0)==$buha) {
				$buchhaltung=$buha;
				session_register("buchhaltung");
				$close=1;
				$msg.="Die Aktive Buchhaltung wurde zu $buha geändert.<br>";
			} else {
				$error="Die Buchhaltung konnte nicht geändert werden.";
			}
		} else {
      $error="Die Buchhaltung konnte nicht geändert werden.";
		}
	}
	if($action=="endDate") {
		$query=mysql_query("UPDATE Buchhaltungen SET endDate='".date_CH_to_EN($endDate)."' WHERE id='$buchhaltung'");
		if(!($err=mysql_error())) {
			$msg.="Die Buchhaltung $buchhaltung wurde auf den $endDate fixiert.<br>";
		}
	}
	if($create && $action) {
		header("Location: index.php");
	}
?>
<html>
<head>
  <title><?=$_config_title ?></title>
  <link rel="stylesheet" href="main.css" type=text/css>
  <script language="JavaScript" type="text/javascript" src="inc/functions.js"></script>
</head>
<? 
if($close && !$create) {
	print "<body onLoad=\"javascript:opener.parent.main.location.reload();self.iclose()\">";
} else {
	print "<body onload=\"javascript:self.focus()\">";
}
?>
<p class=titel>Buchhaltungen</p>
<?
if($error)
	print "<span style=\"color:red;font-weight:bold;\">Fehler:</span><span style=\"color:red;\"> $error</span><br><br>";
if($msg)
	print "$msg<br>";

$query=mysql_query("SELECT id,endDate,selected FROM Buchhaltungen");
$anz_buchhaltungen=mysql_num_rows($query);
	
if($anz_buchhaltungen>0) {
	print "<form method=post action=\"$PHP_SELF?action=select&create=$create\">
	<b>Aktive Buchhaltung</b><br>";
	$query=mysql_query("SELECT id,endDate,selected FROM Buchhaltungen");
	print "<select name=buha style=\"width:150px\">\n";
	while(list($id,$endDate,$selected)=mysql_fetch_row($query)) {
		if($selected) {
			$selected=" SELECTED";
		} else {
			$selected="";
		}
		if($endDate) $endDate=" (Fixiert)";
		print "<option value=\"$id\"$selected>$id</option>\n";
	}
	print "</SELECT>
	<input type=submit name=submit value=\"Auswählen\">
	</form>";
}

print "<form method=post action=\"$PHP_SELF?action=create&create=$create\">
<b>Buchhaltung erstellen</b><br>
<input type=text style=\"width:150px;\" name=buha>
<input type=submit name=submit value=\"Erstellen\"><br>";
if($buchhaltung) {
	print "<input type=checkbox name=konten_erstellen value=true> Konten übernehmen";
}
print "</form>";

if($anz_buchhaltungen>0) {
	print "<form method=post action=\"$PHP_SELF?action=endDate\">
	<b>Aktive Buchhaltung ($buchhaltung) fixieren</b><br>";
	$query=mysql_query("SELECT DATE_FORMAT(endDate,'$_config_date') FROM Buchhaltungen WHERE id='$buchhaltung'");
	$endDate=@mysql_result($query,0,0);
	print "<input type=text style=\"width:150px;\" name=endDate value=\"$endDate\">
	<input type=submit name=submit value=\"Fixieren\"><br>
	</form>";
}
?>
</body>
</html>
