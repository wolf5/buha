<?
	include("inc/header.inc.php");

	if($submit){
		$sql="";
		for($i=1;$i<=3;$i++){
			$var="field$i";
			if($$var!="leer"){
				if($i>1){
					$sql.=" AND ";
				}
				$field="field$i";
				$field=$$field;
				$case="case$i";
				$case=$$case;
				$val="val$i";
				if($case=="LIKE"){
					$val="%".$$val."%";
				} else {
					$val=$$val;
				}
				if($field=="datum" && (strpos($val,"-")===FALSE)){
          $val=date_CH_to_EN($val);
        }
				if(!is_numeric($val)) $val="'$val'";
				$sql.="$field $case $val";
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
		<?
			
			if($sql){
				print "opener.window.location.href='buchungssaetze.php?sql=".urlencode($sql)."'\n";
				print "self.close();";
			}
		?>
/*		if(!document.innerHTML){
			alert("Fehler: Ihr Browser unterstützt die innerHTML Methode leider nicht");
			self.close();
		}
*/		
		function go(id){
			obj = document.getElementById('area'+id);
			sel = document.getElementsByName('field'+id)[0].value;
			if(sel=="leer"){
				obj.innerHTML="";
			} else if(sel=="datum"){
				obj.innerHTML="<SELECT name=case"+id+">\n	<option value='='>Gleich</option>\n	<option value='>'>Grösser</option>\n	<option value='<'>Kleiner</option>\n</SELECT>\n<input type=text name=val"+id+">";
			} else if(sel=="kt_soll"){
				obj.innerHTML = "<input type=hidden name=case"+id+" value='='><?=str_replace("NAME","val\"+id+\"",str_replace("\"","'",str_replace("\n","",getKontoList("NAME",150,"")))); ?>";
			} else if(sel=="kt_haben"){
			obj.innerHTML = "<input type=hidden name=case"+id+" value='='><?=str_replace("NAME","val\"+id+\"",str_replace("\"","'",str_replace("\n","",getKontoList("NAME",150,"")))); ?>";
			} else if(sel=="betrag"){
				obj.innerHTML="<SELECT name=case"+id+">\n <option value='='>Gleich</option>\n <option value='>'>Grösser</option>\n  <option value='<'>Kleiner</option>\n</SELECT>\n<input type=text name=val"+id+">";
			} else if(sel=="waehrung"){
				obj.innerHTML = "<input type=hidden name=case"+id+" value='='><?=str_replace("NAME","val\"+id+\"",str_replace("\"","'",str_replace("\n","",getWaehrungsList("NAME",150,"")))); ?>";
			} else if(sel=="kurs"){
				obj.innerHTML="<SELECT name=case"+id+">\n <option value='='>Gleich</option>\n <option value='>'>Grösser</option>\n  <option value='<'>Kleiner</option>\n</SELECT>\n<input type=text name=val"+id+">";
			} else if(sel=="mwst"){
				obj.innerHTML="<SELECT name=case"+id+">\n <option value='='>Gleich</option>\n <option value='>'>Grösser</option>\n  <option value='<'>Kleiner</option>\n</SELECT>\n<input type=text name=val"+id+">";
			} else if(sel=="belegnr"){
				obj.innerHTML="<SELECT name=case"+id+">\n	<option value='='>Gleich</option>\n <option value='='>gleich</option>\n	<option value='LIKE'>enthält</option>\n</SELECT>\n<input type=text name=val"+id+">";
			}
		}
	//-->
	</script>
</head>
<body onload="self.focus()">
<p class=titel>Buchungssatz Suchen</p>
<form method=post action=<?=$PHP_SELF; ?>>
<table border=0>
<tr>
	<td>
		<SELECT name=field1 onChange="go(1)">
			<option value=leer>Feld wählen</option>
			<option value=datum>Datum</option>
			<option value=kt_soll>Konto Soll</option>
			<option value=kt_haben>Konto Haben</option>
			<option value=betrag>Betrag</option>
			<option value="waehrung">Währung</option>
			<option value="kurs">Kurs</option>
			<option value="mwst">MWSt.</option>
			<option value="belegnr">Beleg Nr.</option>
		</SELECT>
		<span id="area1"></span>
	</td>
</tr>
<tr>
  <td>
    <SELECT name=field2 onChange="go(2)">
      <option value=leer>Feld wählen</option>
      <option value=datum>Datum</option>
      <option value=kt_soll>Konto Soll</option>
      <option value=kt_haben>Konto Haben</option>
      <option value=betrag>Betrag</option>
      <option value="waehrung">Währung</option>
      <option value="kurs">Kurs</option>
      <option value="mwst">MWSt.</option>
      <option value="belegnr">Beleg Nr.</option>
    </SELECT>
    <span id="area2"></span>
  </td>
</tr>
<tr>
  <td>
    <SELECT name=field3 onChange="go(3)">
      <option value=leer>Feld wählen</option>
      <option value=datum>Datum</option>
      <option value=kt_soll>Konto Soll</option>
      <option value=kt_haben>Konto Haben</option>
      <option value=betrag>Betrag</option>
      <option value="waehrung">Währung</option>
      <option value="kurs">Kurs</option>
      <option value="mwst">MWSt.</option>
      <option value="belegnr">Beleg Nr.</option>
    </SELECT>
    <span id="area3"></span>
  </td>
</tr>
</table>
<input type=submit value="Suchen" name=submit>
</form>
</body>
</html>
