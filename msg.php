<html>
<head>
  <title><?=$_config_title ?></title>
  <link rel="stylesheet" href="main.css" type=text/css>
  <script language="JavaScript" type="text/javascript" src="inc/functions.js"></script>
</head>
<body<? 
if($reload)
	print " onLoad=\"javascript:opener.location.reload()\"";
?>>
<table width="100%" height="100%" border=0>
<tr>
	<td align=center valign=middle><?=urldecode($msg);?><br><br><br><a href="#" onclick="javascript:self.close();">Schliessen</a></td>
</tr>
</table>
</form>
</body>
</html>
