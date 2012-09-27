<?
	include("inc/header.inc.php");

	if($submit){
		$query=mysql_query("DELETE FROM $buchhaltung"."_Namenskonto WHERE id='$konto'");
		if(mysql_error()){
			$error=mysql_error();
		} else {
			header("Location: msg.php?reload=1&msg=".urlencode("Namenskonto gelöscht"));
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
<p class=titel>Namenskonto Löschen</p>
<?
if($error)
	print "<span style=\"color:red;font-weight:bold;\">Fehler:</span><span style=\"color:red;\"> $error</span><br><br>";
?>
<form method=post action=<?=$PHP_SELF; ?>>
<table border=0>
<tr>
	<td width=100>Namenskonto:</td>
	<td><? 
	  $query=mysql_query("SELECT id,name FROM $buchhaltung"."_Namenskonto");
	  print "<SELECT name=\"konto\" style=\"width:150px;\">\n";
  while(list($id,$name)=mysql_fetch_row($query))
  {
    print "  <option value=\"$id\">$name</option>\n";
  }
  print "</SELECT>\n";
	?></td>
</tr>
<tr>
	<td width=100>&nbsp;</td>
	<td><input type=submit name=submit value="Löschen"></td>
</tr>
</table>
</form>
</body>
</html>
