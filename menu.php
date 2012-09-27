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
	<td width="75" align=left valign=middle  style="padding: 1 1 1 1"><img src="logo.gif" width=60 height=25></td>
	<td width="20%" align=left><a href="#" onclick="javascript:popup('buchungssatz_erstellen.php','buchungssatz_erstellen',920,250,10,200);"><nobr>Buchungssatz erstellen</a></nobr></td>
  <td width="20%" align=left><a href="#" onclick="javascript:popup('buchungssaetze.php?sort=Datum DESC','buchungssaetze',985,500,10,160);">Journal</a></td>
	<td width="20%" align=left><a href="#" onclick="javascript:popup('import.php','import',920,250,10,200);">Import</a></td>
<!--	<td width="20%" align=left><a href="#" onclick="javascript:popup('auswertung_op.php','statistik',985,500,10,200);">Offene Posten</a></td>
-->
	<td width="20%" align=left><a href="#" onclick="javascript:popup('auswertungen.php','auswertungen',300,300,10,200);">Auswertungen</a></td>
	<td width="*" align=right><a href="menu_konfiguration.php">Konfiguration</a></td>
</table>
</body>
</html>
