<?php
session_start();
set_include_path(get_include_path().PATH_SEPARATOR.'./include/');
require_once('phpQuery/phpQuery-onefile.php');
require_once('RandSGen.php');
require_once('Fwok/Word/Syllabler/Spanish.php');


$recortes = file('ini' ,FILE_SKIP_EMPTY_LINES);

 function countSilabas($palabra) {
    if(strlen($palabra)>=0) {
      Fwok_Word_Syllabler_Spanish::setSpain(true);
      Fwok_Word_Syllabler_Spanish::setTl(false);
      Fwok_Word_Syllabler_Spanish::setIgnorePrefix(true);
      $w = new Fwok_Word_Syllabler_Spanish($palabra);
      return $w->getNumberOfSyllables();
    }else{
      return 0;
    }
  }

$a=Array();
$b=Array();
$c=Array();
$d=Array();
$e=Array();
$f=Array();
$g=Array();
$h=Array();
$t="    ";


foreach ($recortes as &$recorte) {
 $str=substr(trim($recorte), -1);
 $recorte=trim(substr(trim($recorte), 0, strlen($recorte)-2));
 $num = (int)$str;

 switch ($num) {
  case 0:
    $a[]=$recorte;
  break;
  case 1:
    $b[]=$recorte;
  break;
  case 2:
    $c[]=$recorte;
  break;
  case 3:
    $d[]=$recorte;
  break;
  case 4:
    $e[]=$recorte;
  break;
  case 5:
    $f[]=$recorte;
  break;
  case 6:
    $g[]=$recorte;
  break;
  default:
    $h[]=$recorte;
  break;
}

}

$ar=$b;
echo "<cualquiera1>\n";
for($i=0;$i<count($ar);$i++){
echo $t.'<value id="'.$i.'">'.trim($ar[$i]).'</value>'."\n";
}
echo "</cualquiera1>\n";
echo "";

$ar=$c;
echo "<cualquiera2>\n";
for($i=0;$i<count($ar);$i++){
echo $t.'<value id="'.$i.'">'.trim($ar[$i]).'</value>'."\n";
}
echo "</cualquiera2>\n";
echo "";

$ar=$d;
echo "<cualquiera3>\n";
for($i=0;$i<count($ar);$i++){
echo $t.'<value id="'.$i.'">'.trim($ar[$i]).'</value>'."\n";
}
echo "</cualquiera3>\n";
echo "";

$ar=$e;
echo "<cualquiera4>\n";
for($i=0;$i<count($ar);$i++){
echo $t.'<value id="'.$i.'">'.trim($ar[$i]).'</value>'."\n";
}
echo "</cualquiera4>\n";
echo "";

$ar=$f;
echo "<cualquiera5>\n";
for($i=0;$i<count($ar);$i++){
echo $t.'<value id="'.$i.'">'.trim($ar[$i]).'</value>'."\n";
}
echo "</cualquiera5>\n";
echo "";

$ar=$g;
echo "<cualquiera6>\n";
for($i=0;$i<count($ar);$i++){
echo $t.'<value id="'.$i.'">'.trim($ar[$i]).'</value>'."\n";
}
echo "</cualquiera6>\n";
echo "";


?>
