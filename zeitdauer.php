<?
	include("inc/header.inc.php");

	if($submit)
	{
		if($date_start && $date_end) {
		echo date_CH_to_EN($date_start);

			$_config_zeitdauer_start=date_CH_to_EN($date_start);		
			$_config_zeitdauer_end=date_CH_to_EN($date_end);
			session_register("_config_zeitdauer_start");
			session_register("_config_zeitdauer_end");
		} else {
			session_unregister("_config_zeitdauer_start");
			session_unregister("_config_zeitdauer_end");
		}
		$close=1;
	}
?>
<html>
<head>
  <title><?=$_config_title ?></title>
  <link rel="stylesheet" href="main.css" type=text/css>
  <script language="JavaScript" type="text/javascript" src="inc/functions.js"></script>
</head>
<?
if($close) {
  print "<body onLoad=\"javascript:opener.parent.main.location.reload();self.close()\">";
} else {
  print "<body onload=\"javascript:self.focus()\">";
}
?>
<p class=titel>Zeitdauer angeben</p>
<form method=post action=<?=$PHP_SELF; ?>>
<table border=0 >
<tr>
	<td>Anfang:</td>
	<td><input type=text name="date_start" value="<?=date_EN_to_CH($_SESSION['_config_zeitdauer_start'])?>" style="width:100px">
</tr>
<tr>
  <td>Ende:</td>
	<td><input type=text name="date_end" value="<?=date_EN_to_CH($_SESSION['_config_zeitdauer_end'])?>" style="width:100px">
</tr>
<tr>
	<td colspan=2><input type=submit name=submit value="&Auml;ndern"></td>
</tr>
</table>

</form>
</body>
</html>
