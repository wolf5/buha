<? 
include("inc/header.inc.php");

?>
<html>
<head>
  <title><?=$_config_title ?></title>
  <link rel="stylesheet" href="main.css" type=text/css>
  <script language="JavaScript" type="text/javascript" src="inc/functions.js"></script>
	<script  type="text/javascript" language="javascript">
	<!--
		function setValue(val){
			opener.document.getElementById('<?=$obj?>').value = val;
			self.close();
		}
	//-->
	</script>
</head>
<body onLoad="self.focus();document.getElementById('term').focus()">
<p class=titel>Konto Suchen</p>

<form method=get action="<?=$PHP_SELF?>">
<input type=text name=term id=term value="<?=$term?>">
<input type=submit name=search value="Suchen">
<input type=hidden name=obj value=<?=$obj?>>
</form>
<?
if(!$start){
  $start=0;
}
$attr="&obj=$obj";
if($term){
	$query=mysql_query("SELECT nr,name FROM $buchhaltung"."_Konto kon WHERE ".formatSearchString($term,array("nr","name"))." ORDER BY nr LIMIT $start,$_config_entrysperpage_findkonto");
	$attr.="&term=$term";
} else {
	$query=mysql_query("SELECT nr,name FROM $buchhaltung"."_Konto kon ORDER BY nr LIMIT $start,$_config_entrysperpage_findkonto");
}
if(@mysql_num_rows($query)>0)
{
  print "<table border=0 cellpadding=3 cellspacing=0 width=\"100%\">
    <tr>
			<td width=50><b>Nr.</b></td>
			<td><b>Name</b></td>
		</tr>";
  for($i=0;list($nr,$name)=mysql_fetch_array($query);$i++) {
		if(($i%2)==0){
      $bgcolor=$_config_tbl_bgcolor1;
    } else {
      $bgcolor=$_config_tbl_bgcolor2;
    }
    print "<tr onmouseover=\"setPointer(this, 'over', '#$bgcolor', '#$_config_tbl_bghover', '')\" onmouseout=\"setPointer(this, 'out', '#$bgcolor', '#$_config_tbl_bghover', '')\" onclick=\"javascript:setValue('$nr');\">
			<td valign=top bgcolor=\"#$bgcolor\"$style width=50>$nr</td>
			<td valign=top bgcolor=\"#$bgcolor\"$style>$name</td>
		</tr>\n";
	}
  print "<tr>
    <td colspan=2 align=center>";
  if($start>0){
    print "<a href=\"$PHP_SELF?start=".($start-$_config_entrysperpage_findkonto)."$attr\"><<<</a>";
  }
	if($term){
	  $query=mysql_query("SELECT count(*) FROM $buchhaltung"."_Konto WHERE ".formatSearchString($term,array("nr","name")));
	} else {
		$query=mysql_query("SELECT count(*) FROM $buchhaltung"."_Konto");
	}
  if(($start+$_config_entrysperpage_findkonto+1)<=mysql_result($query,0,0)) {
    if($start>0){
      print " | ";
    }
    print "<a href=\"$PHP_SELF?start=".($start+$_config_entrysperpage_findkonto)."$attr\">>>></a>";
  }
  print "</td>
    </tr>
		</table>\n";
} else {
	print "Keine Konten gefunden";
}
?>

</body>
</html>
