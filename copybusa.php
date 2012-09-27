<?
include("inc/header.inc.php");
$query = mysql_query("SELECT id, datum, kt_soll, kt_haben, betrag, waehrung, kurs, mwst, belegnr, beschreibung, bezahlt FROM temp WHERE mwst_feld IS NULL");
while(list($id, $datum, $kt_soll,$kt_haben, $betrag, $waehrung, $kurs, $mwst, $belegnr, $beschreibung, $bezahlt) = mysql_fetch_row($query)) {
	
	$query3 = mysql_query("INSERT INTO 2003_Buchungssaetze(datum, kt_soll, kt_haben, betrag, waehrung, kurs, mwst, belegnr, beschreibung, bezahlt) VALUES('$datum', '$kt_soll','$kt_haben', '$betrag', '$waehrung', '$kurs', '$mwst', '$$belegnr','$beschreibung', '$bezahlt')");
	$newid = mysql_insert_id();
	$query2 = mysql_query("SELECT datum, kt_soll, kt_haben, betrag, waehrung, kurs, mwst, belegnr, beschreibung, bezahlt FROM temp WHERE mwst_feld = '$id'");
	if(mysql_num_rows($query2)>0) {
		
		list($datum, $kt_soll,$kt_haben, $betrag, $waehrung, $kurs, $mwst, $belegnr, $beschreibung, $bezahlt) = mysql_fetch_row($query2);
		$query3 = mysql_query("INSERT INTO 2003_Buchungssaetze(datum, kt_soll, kt_haben, betrag, waehrung, kurs, mwst, belegnr, beschreibung, bezahlt,mwst_feld) VALUES('$datum', '$kt_soll','$kt_haben', '$betrag', '$waehrung', '$kurs', '$mwst', '$$belegnr', '$beschreibung', '$bezahlt','$newid')");
	}
}
?>
