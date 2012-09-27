<?
include("inc/header.inc.php");
$query=mysql_query("SELECT id,belegnr,kt_soll,kt_haben FROM 2004_Buchungssaetze");
while(list($id,$belegnr,$soll,$haben)=mysql_fetch_row($query)) {
	$query2=mysql_query("SELECT * FROM 2004_Konto WHERE nr='$soll'");
	echo mysql_error();
	if(mysql_num_rows($query2)==0) print "ID: $id Belegnr: $belegnr Konto: $soll<br>";
	$query2=mysql_query("SELECT * FROM 2004_Konto WHERE nr='$haben'");
	if(mysql_num_rows($query2)==0) print "ID: $id Belegnr: $belegnr Konto: $haben<br>";
}
?>
