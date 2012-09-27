<?


function getFx($waehrung,$waehrung1){
        if($waehrung==$waehrung1){
                return 1;
        } else {
                $query=mysql_query("SELECT yahoo_fx FROM Waehrungen WHERE id='$waehrung'");
                if($query && mysql_num_rows($query)>0) {
                        $fx_1=mysql_result($query,0,0);
                } else {
                        return 0;
                }
                $query=mysql_query("SELECT yahoo_fx FROM Waehrungen WHERE id='$waehrung1'");
                if($query && mysql_num_rows($query)>0) {
      $fx_2=mysql_result($query,0,0);
    } else {
      return false;
    }
                $file=implode("\n",file("http://de.finance.yahoo.com/waehrungsrechner/convert?amt=1&from=$fx_1&to=$fx_2"));
                $file=split("Zum Portfolio",substr($file,strpos($file,"Briefkurs",1000)));
                $fx = str_replace(",",".",substr($file[0], strpos($file[0],",",20) - 1 , 6));

               if(is_numeric($fx)){
                        return $fx;
                } else {
                        print "Achtung! Fx-berechnung meldet Fehler!";
                        return false;
                }
        }
}




























function getDateDelimiter($field,$prefix,$postfix) {
        if($_SESSION['_config_zeitdauer_start'] && $_SESSION['_config_zeitdauer_end']) {
                return "$prefix $field >= '".$_SESSION['_config_zeitdauer_start']."' AND $field <= '".$_SESSION['_config_zeitdauer_end']."' $postfix";
        }
}
function date_EN_to_CH($date){
  if($date=="0000-00-00")
                return "";
        else if(strpos($date,"-")==FALSE)
                return $date;
        else
    return date("d.m.Y",strtotime($date));
}
function date_CH_to_EN($date){

        if(!$date)
                return "0000-00-00";
        if(strpos($date,".")==FALSE)
                return $date;
        $tag = substr($date,0,strpos($date,"."));
        $monat =  substr($date,strpos($date,".")+1,strrpos($date,".")-strpos($date,".")-1);
        $jahr = substr($date,strrpos($date,".")+1,4);
        return "$jahr-$monat-$tag";
}
function formatPreis($preis)
{
        return  number_format($preis,2,".","'");
        //return sprintf("%0.2f",$preis);
}
function getKontoTypenList($name,$width,$selected)
{
        global $buchhaltung;
        $select="<SELECT name=\"$name\" style=\"width:$width"."px\">\n";
        $query=mysql_query("SELECT id,Name FROM Kontotypen");
        while(list($id,$name)=mysql_fetch_row($query))
        {
                if($id==$selected)
                        $select.="        <option value=\"$id\" SELECTED>$name</option>\n";
                else
                        $select.="  <option value=\"$id\">$name</option>\n";
        }
        $select.="</SELECT>\n";
        return $select;
}
function getSollBetrag($konto)
{
        global $buchhaltung;
        $query=mysql_query("SELECT sum(betrag*kurs) FROM $buchhaltung"."_Buchungssaetze WHERE kt_soll='$konto'");
        $query1=mysql_query("SELECT sum(betrag*kurs) FROM $buchhaltung"."_Buchungssaetze WHERE kt_haben='$konto'");
        list($soll)=mysql_fetch_row($query);
        list($haben)=mysql_fetch_row($query1);
        return $soll-$haben;
}
function getHabenBetrag($konto)
{
        global $buchhaltung;
  $query=mysql_query("SELECT sum(betrag*kurs) FROM $buchhaltung"."_Buchungssaetze WHERE kt_soll='$konto'");
  $query1=mysql_query("SELECT sum(betrag*kurs) FROM $buchhaltung"."_Buchungssaetze WHERE kt_haben='$konto'");
  list($soll)=mysql_fetch_row($query);
  list($haben)=mysql_fetch_row($query1);
  return $haben - $soll;
}
function getSaldo(){
        global $buchhaltung;
        $query=mysql_query("SELECT sum(bu.betrag*bu.kurs) FROM $buchhaltung"."_Buchungssaetze bu, $buchhaltung"."_Konto kt WHERE kt.typ='3' AND bu.kt_soll=kt.nr;");
        $query1=mysql_query("SELECT sum(bu.betrag*bu.kurs) FROM $buchhaltung"."_Buchungssaetze bu, $buchhaltung"."_Konto kt WHERE kt.typ='3' AND bu.kt_haben=kt.nr;");
        $aktiv=mysql_result($query,0,0)-mysql_result($query1,0,0);
        $query=mysql_query("SELECT sum(bu.betrag*bu.kurs) FROM $buchhaltung"."_Buchungssaetze bu, $buchhaltung"."_Konto kt WHERE kt.typ='4' AND bu.kt_soll=kt.nr;");
  $query1=mysql_query("SELECT sum(bu.betrag*bu.kurs) FROM $buchhaltung"."_Buchungssaetze bu, $buchhaltung"."_Konto kt WHERE kt.typ='4' AND bu.kt_haben=kt.nr;");
  $passiv=(mysql_result($query,0,0)-mysql_result($query1,0,0))*-1;
        return $aktiv-$passiv;
}
function getKontoList($name,$width,$default)
{
        global $buchhaltung,$_config_kontolisten;
        if($_config_kontolisten=="list") {
          $query=mysql_query("SELECT nr, name FROM $buchhaltung"."_Konto ORDER BY nr");
          $list="<SELECT name=\"".$name."\" id=\"$name\" style=\"width:".$width."px;\">\n";
          while(list($nr,$name)=mysql_fetch_row($query)) {
                        if($nr==$default)
                    $list.="  <option value=\"$nr\" SELECTED>$nr $name</option>\n";
                        else
                                $list.="  <option value=\"$nr\">$nr $name</option>\n";
          }
          $list.="</SELECT>\n";
          return $list;
        } else {
                return "<input type=text name=\"$name\" id=\"$name\" value=\"$default\" style=\"width:".($width-20)."px;text-align:right;\"><input type=button value=\"?\" onclick=\"javascript:findkonto('$name')\" style=\"width:20px;\">";
        }
}
function getNebenkontoList($name,$width,$default,$default_text) {
  global $buchhaltung;
  $query=mysql_query("SELECT id, name FROM $buchhaltung"."_Nebenkonto ORDER BY id");
  $list="<SELECT name=\"".$name."\" style=\"width:".$width."px;\">";
        if($default_text) $list.="<option>$default_text</option>\n";
  while(list($id,$name)=mysql_fetch_row($query)) {
    if($id==$default)
      $list.="  <option value=\"$id\" SELECTED>$name</option>\n";
    else
      $list.="  <option value=\"$id\">$name</option>\n";
  }
  $list.="</SELECT>\n";
  return $list;
}
function getWaehrungsList($name,$width,$default)
{
        global $buchhaltung;
  $query=mysql_query("SELECT id,Waehrung FROM Waehrung");
  $list="<SELECT name=\"".$name."\" style=\"width:".$width."px;\">\n";
  while(list($id,$waehrung)=mysql_fetch_row($query))
  {
                if($id==$default)
            $list.="  <option value=\"$id\" SELECTED>$waehrung</option>\n";
                else
                        $list.="  <option value=\"$id\">$waehrung</option>\n";
  }
  $list.="</SELECT>\n";
        return $list;
}
function getWaehrung($id) {
        $query=mysql_query("SELECT Waehrung FROM Waehrung WHERE id='$id'");
        if(mysql_num_rows($query)>0) {
                return mysql_result($query,0,0);
        } else {
                return false;
        }
}
function getKontoByNr($nr)
{
        global $buchhaltung;
        $query=mysql_query("SELECT Name FROM $buchhaltung"."_Konto WHERE Nr = '$nr'");
        list($name)=mysql_fetch_row($query);
        return "$name ($nr)";
}
function getKontoByName($name)
{
        global $buchhaltung;
  $query=mysql_query("SELECT Nr FROM $buchhaltung"."_Konto WHERE Name = '$name'");
  list($nr)=mysql_fetch_row($query);
  return "$name ($nr)";
}
function getKontoFormat($name,$nr)
{
        return "$name ($nr)";
}
function import_no_ask() {
        global $_config_struktur_buchungssaetze_fields,$_config_struktur_buchungssaetze_fields_count,$buchhaltung;
        $query=mysql_query("SELECT $_config_struktur_buchungssaetze_fields FROM Queue WHERE no_ask='1'");
        for($i=0;$i<mysql_num_rows($query);$i++) {
                $str="";
                for($ii=1;$ii<$_config_struktur_buchungssaetze_fields_count;$ii++) {
                        if($str) $str.=",";
                        if(mysql_result($query,$i,$ii)=="") {
                                $str.="NULL";
                        } else {
                                $str.="'".mysql_result($query,$i,$ii)."'";
                        }
                }
                $query2=mysql_query("INSERT INTO $buchhaltung"."_Buchungssaetze(".str_replace("id,","",$_config_struktur_buchungssaetze_fields).") VALUES($str)");
                if(!mysql_error()) {
                        $query2=mysql_query("DELETE FROM Queue WHERE id='".mysql_result($query,$i,0)."'");
                } else {
                        $err= mysql_error();
                }
        }
        return $i;
}
function import_count_ask() {
        $query=mysql_query("SELECT count(*) FROM Queue WHERE no_ask='0'");
        return mysql_result($query,0,0);
}
function formatSearchString($term,$fields){
  $term=split(" ",$term);
  for($i=0;$term[$i];$i++){
    if($str){
      $str .= "AND ";
    }
    $str.="( ";
    for($ii=0;$fields[$ii];$ii++){
      if($ii>0){
        $str.="OR ";
      }
      if(strpos($term[$i],"*")===FALSE) {
        $str .= $fields[$ii]." LIKE '%".$term[$i]."%' ";
      } else {
        $str .= $fields[$ii]." LIKE '".str_replace("*","%",$term[$i])."' ";
      }
    }
    $str.=") ";
  }
  return $str;
}
?>