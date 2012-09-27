<?
	include("inc/header.inc.php");

	if($submit){
		if(!$name){
			$error="Kein Name angegeben";
		}
		if(!$error){
			$query=mysql_query("INSERT INTO $buchhaltung"."_Namenskonto(name,position) VALUES('$name','$konto')");
			if(mysql_error()){
				$error=mysql_error();
			} else {
				header("Location: msg.php?reload=1&msg=".urlencode("Namenskonto $name erstellt"));
			}
		}
	}
?>
<html>
<head>
  <title><?=$_config_title ?></title>
  <link rel="stylesheet" href="main.css" type=text/css>
  <script language="JavaScript" type="text/javascript" src="inc/functions.js"></script>
</head>
<body onload="javascript:self.focus()">
<p class=titel>Namenskonto erstellen</p>
<?
if($error)
	print "<span style=\"color:red;font-weight:bold;\">Fehler:</span><span style=\"color:red;\"> $error</span><br><br>";
?>
<form method=post action=<?=$PHP_SELF; ?>>
<table border=0>
<tr>
	<td width=100>Erstellen über</td>
	<td><? echo $er;
	if($er!=1) {
		$typ="(kt.typ = 1 OR kt.typ=2)";
	} else {
		$typ="(kt.typ = 3 OR kt.typ=4)";
	}
	  $query=mysql_query("SELECT kt.nr,kt.name FROM $buchhaltung"."_Konto kt LEFT JOIN $buchhaltung"."_Namenskonto nk ON kt.nr = nk.position WHERE nk.position IS NULL AND $typ ORDER BY kt.nr");
	  print "<SELECT name=\"konto\" style=\"width:150px;\">\n";
  while(list($nr,$name)=mysql_fetch_row($query))
  {
    print "  <option value=\"$nr\">$nr $name</option>\n";
  }
  print "</SELECT>\n";
	?></td>
</tr>
<tr>
  <td width=100>Name</td>
  <td><input type=text name="name" value="<?=$name?>" style="width:150px;"></td>
</tr>
<tr>
	<td width=100>&nbsp;</td>
	<td><input type=submit name=submit value="Hinzufügen"></td>
</tr>
</table>
</form>
</body>
</html>
