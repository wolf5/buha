<html>
<head>
  <title><?=$_config_title ?></title>
  <link rel="stylesheet" href="main.css" type=text/css>
	<script type="text/javascript">
	<!--
		function loadURL(url) {
			opener.parent.main.location.href=url;
			self.close();
		}
	//-->
	</script>
</head>
<body onload="javascript:self.focus();">
<p class=titel>Auswertungen</p>
<a href="javascript:loadURL('auswertung_op.php');">Offene Posten mit Fälligkeiten</a><br>
<a href="javascript:loadURL('auswertung_aktiven.php');">Aktiven</a><br>
<a href="javascript:loadURL('auswertung_passiven.php');">Passiven</a><br>
<a href="javascript:loadURL('auswertung_erfolgsrechnung.php');">Erfolgsrechnung</a><br>
<a href="javascript:loadURL('auswertung_aufwand.php');">Aufwand</a><br>
</body>
</html>
