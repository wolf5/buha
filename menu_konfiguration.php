<html>
<head>
  <title><?=$_config_title ?></title>
  <link rel="stylesheet" href="main.css" type=text/css>
  <script language="JavaScript" type="text/javascript" src="inc/functions.js"></script>
	<script language="javascript" type="text/javascript">
	<!--
		function popup(url,name,width,height,left,top){
			window.open(url,name,"width="+width+",height="+height+",left="+left+",top="+top+",resizable=yes,scrollbars=yes");
		}
	//-->
	</script>
</head>
<body marginwidth=0 marginheight=0>
<table border=0 width="100%" height="100%">
<tr>
	<td width="75" valign=middle  style="padding: 1 1 1 1"><img src="logo.gif" width=60 height=25></td>
	<td width="20%"><a href="#" onclick="javascript:popup('nebenkonten.php','nebenkonten',400,300,300,200);">Nebenkonten</a></td>
	<td width="20%"><a href="#" onclick="javascript:popup('buchhaltungen.php','buchhaltungen',400,300,300,200);">Buchhaltungen</a></td>
	<td width="20%" align=center><a href="#" onclick="javascript:popup('konto_erstellen.php','konto_erstellen',300,300,300,200);">Konto erstellen</a></td>
  <td width="20%" align=center><a href="#" onclick="javascript:popup('konto_editieren.php','konto_editieren',300,300,300,200);">Konto editieren</a></td>
	<td width="20%" align=center><a href="#" onclick="javascript:popup('konto_loeschen.php','konto_loeschen',300,300,300,200);">Konto l&ouml;schen</a></td>
<!--  <td width="20%" align=center><a href="#" onclick="javascript:popup('zeitdauer.php','zeitdauer',300,300,300,200);">Zeitdauer</a></td>-->
	<td width="100" align=right><a href="menu.php">Funktionen</a></td>
	
</table>
</body>
</html>
