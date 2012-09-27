<?
//DB
$_config_db_host="localhost"; //i guess
$_config_db_user="wolf5";
$_config_db_pw="sosecretyoucantevendreamit";
$_config_db_db="wolf5_buha";

//Tabellen
$_config_struktur_konto="nr int auto_increment primary key,nebenkonto TINYINT(1),name varchar(255) NOT NULL,typ smallint NOT NULL,waehrung int NOT NULL,show_waehrung tinyint NOT NULL,show_belegnr tinyint NOT NULL,show_mwst tinyint NOT NULL,show_datum tinyint NOT NULL,sort int NOT NULL";
$_config_struktur_buchungssaetze="id float auto_increment primary key,datum date NOT NULL,kt_soll int NOT NULL,kt_haben int NOT NULL,betrag float NOT NULL,waehrung int DEFAULT 1 NOT NULL,kurs float DEFAULT 1 NOT NULL,mwst float,mwst_feld int,belegnr VARCHAR(255),beschreibung VARCHAR(255),bezahlt mediumint(9)";
$_config_struktur_namenskonto="id int auto_increment primary key,name varchar(255) NOT NULL,position int NOT NULL";
$_config_struktur_nebenkonto="id int auto_increment primary key,name varchar(255) NOT NULL,typ int NOT NULL DEFAULT '0'";

//Buchungssatz erstellen
$_config_buchungsatz_erstellen_mwst_show_default=false;
$_config_buchungsatz_erstellen_mwst_default_value=7.6;
$_config_buchungssatz_erstellen_werte_uebernehmen= true;
$_config_buchungssaz_erstellen_mwst_haben="123,124,125,126,127,128,129,130";

//Datumsformat
$_config_date="%d.%m.%Y";

//Personenkonto
$_config_godmode_db_host="localhost";
$_config_godmode_db_user="wolf5";
$_config_godmode_db_pw="moresecretthanthetruthabout911";
$_config_godmode_db_db="openbits_godmode_openbits";

$_config_personenkonto=true;

//Kontenlisten
//Werte: txt, list
$_config_kontolisten="txt";

$_config_tbl_bgcolor1="CCCCCC";
$_config_tbl_bgcolor2="DDDDDD";
$_config_tbl_bghover="CCFFCC";
$_config_title="Buchhaltung Demo GmbH";

$_config_entrysperpage_findkonto=10;

//Do not change anything below here
$fields=split(",",$_config_struktur_konto);
$_config_struktur_konto_fields_count=count($fields);
for($i=0;$i < $_config_struktur_konto_fields_count ;$i++) {
  if($_config_struktur_konto_fields) $_config_struktur_konto_fields.=",";
  $_config_struktur_konto_fields.=substr($fields[$i],0,strpos($fields[$i]," "));
}
$fields=split(",",$_config_struktur_namenskonto);
$_config_struktur_namenskonto_fields_count=count($fields);
for($i=0;$fields[$i];$i++) {
  if($_config_struktur_namenskonto_fields) $_config_struktur_namenskonto_fields.=",";
  $_config_struktur_namenskonto_fields.=substr($fields[$i],0,strpos($fields[$i]," "));
}

$fields=split(",",$_config_struktur_buchungssaetze);
$_config_struktur_buchungssaetze_fields_count=count($fields);
for($i=0;$fields[$i];$i++) {
  if($_config_struktur_buchungssaetze_fields) $_config_struktur_buchungssaetze_fields.=",";
  $_config_struktur_buchungssaetze_fields.=substr($fields[$i],0,strpos($fields[$i]," "));
}
$fields=split(",",$_config_struktur_nebenkonto);
$_config_struktur_nebenkonto_fields_count=count($fields);
for($i=0;$fields[$i];$i++) {
  if($_config_struktur_nebenkonto_fields) $_config_struktur_nebenkonto_fields.=",";
  $_config_struktur_nebenkonto_fields.=substr($fields[$i],0,strpos($fields[$i]," "));
}

unset($fields);

//Datum
$_config_date_php=str_replace("%","",$_config_date);
?>
