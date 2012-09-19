<?php
/**
 * Clase principal generador de payas
 *
 * @version 0.1 (18 Sep 2012)
 * @link https://github.com/knxroot/payas_generator
 * @author Gustavo Lacoste <gustavo/lacosox.org>
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @package payasGenerator
 */

/*
 * Dependencias de otras clases
 */
//require_once('phpQuery/phpQuery-onefile.php');
//require_once('RandSGen.php');
//require_once('Fwok/Word/Syllabler/Spanish.php');

class payador {

  public $glc_file="include/glcpaya.xml";/*Fichero gramática libre de contexto*/
  public $g_payas="gramatica1";/*Sub gramática a utilizar*/
  
  public function __construct($tematica) {
    $this->tematica=$tematica;
  }

  /* Genera una frase aleatoria de un largo
   * aproximado de $cantSilabas
   */
  public function generarFrase($cantSilabas, $altura="cualquiera") {
  $glc = phpQuery::newDocumentFile($this->glc_file);
  $rsg = new RandSGen($glc, $this->g_payas);
  $rsg->setStartTagName($altura.$cantSilabas);
  $rsg->generateFromStart();
  $frase=$rsg->getGeneratedSentence()->text();

  return $frase;
  }

  /* Toma como base una palabra y retorna un arreglo de palabras que riman con esta
   */
  public function generaRimas($palabra) {

  if(strlen($palabra)>3){
    $palabra=substr($palabra, -3);
  }

  $headers[]  = "User-Agent:Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13";
  $headers[]  = "Accept:text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";
  $headers[]  = "Accept-Language:en-us,en;q=0.5";
  $headers[]  = "Accept-Encoding:gzip,deflate";
  $headers[]  = "Accept-Charset:ISO-8859-1,utf-8;q=0.7,*;q=0.7";
  $headers[]  = "Keep-Alive:115";
  $headers[]  = "Connection:keep-alive";
  $headers[]  = "Cache-Control:max-age=0";

  $fields = array(
            'buscar' => urlencode($palabra),
            'filtro' => urlencode("alfabetico"),
            'complex' => urlencode("simple")
            );

  $fields_string="";
  foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
  rtrim($fields_string, '&');
  $ch = curl_init();//abre la conexion

  //Configura la url y los datos que se enviara en el post a esta
  curl_setopt($ch,CURLOPT_URL, "http://labs.pckz.cl/easyfreestyle/system/comprueba.php");
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_ENCODING, "gzip");
  curl_setopt($ch,CURLOPT_POST, count($fields));
  curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

  $result = curl_exec($ch);
  curl_close($ch); //cierra la conexion

  $pattern = '/<b>(.+?)<\/b>/i';
  preg_match_all($pattern, $result , $m);

  if(!empty($m)) {
    $palabras=Array();
    $max = count($m[1]);
    for($i=1; $i<$max; $i++) {
      $palabras[]=trim($m[1][$i]);
    }
  }

  return $palabras; 
  }

  /* Toma como base una palabra y calcula su cant de sílabas.
   */
  public function countSilabas($palabra) {
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

  /* Genera una décima basada en una palabra
   * Una décima en poesía es una estrofa constituida por 10 versos octosílabos,
   * distribuida en a/b/b/a/a/c/c/d/d/c que significa las estrofas que tienen que rimar.
   */
  public function generarDecima($palabra){
    return "";
  }

  /* Genera una cuarteta basada en una palabra
   * La cuarteta es una estrofa de cuatro versos de arte menor con rima consonante. 
   * Los versos suelen ser octosílabos, y la rima se distribuye siguiendo el esquema 8a 8b 8a 8b.
   */
  public function generarCuarteta($palabra){
  $cuarteta=Array();
  $rimas1=Array();$rimas2=Array();
  $rimas1=$this->generaRimas(stripAccents($palabra));
  if(count($rimas1)<1){$rimas1=$this->generaRimas(stripAccents(substr($palabra, -2)));} 
  if(count($rimas1)<1){$rimas1=$this->generaRimas(stripAccents(substr($palabra, -1)));} 

  while (count($cuarteta)<=3) {

    switch (count($cuarteta)) {
      case 0:
        $cuarteta[]=trim($this->generarFrase(8-$this->countSilabas($palabra),"inicio")." ".$palabra);
        break;
      case 1:
        $SubpalabrasPrimerVerso=explode(" ",preg_replace("/\s+/i", " ", $cuarteta[0]));
        //Quita los articulos como parte de los elementos del arreglo que se ofrece de entrada
        $SubpalabrasPrimerVerso=array_udiff($SubpalabrasPrimerVerso, array("el", "la", "los", "las", "un", "una", "unos", "unas", "lo", "al", "del","y"), 'strcasecmp');
        $tmp=Array();
	foreach ($SubpalabrasPrimerVerso as &$s) {
	 if(strlen($s)>4){
	 $tmp[]=$s;
	 }
	}
        if(count($tmp)<1){$tmp=Array("Chile");}
	$SubpalabrasPrimerVerso=$tmp;
        $irand=array_rand($SubpalabrasPrimerVerso);
        $rimas2=$this->generaRimas(stripAccents(substr($SubpalabrasPrimerVerso[$irand], -3)));
        if(count($rimas2)<1){$rimas2=$this->generaRimas(stripAccents(substr($SubpalabrasPrimerVerso[$irand], -2)));} 
        if(count($rimas2)<1){$rimas2=$this->generaRimas(stripAccents(substr($SubpalabrasPrimerVerso[$irand], -1)));} 
        $irand=array_rand($rimas2);
        $rima2=$rimas2[$irand];
        $cuarteta[]=trim($this->generarFrase(8-$this->countSilabas($rima2))." ".$rima2);
        break;
      case 2:
        //print_r($rimas1);echo array_rand($rimas1);
        $rima1=$rimas1[array_rand($rimas1)];
        $cuarteta[]=trim($this->generarFrase(8-$this->countSilabas($rima1))." ".$rima1);
        break;
      case 3:
        $rima2=$rimas2[array_rand($rimas2)];
        $cuarteta[]=trim($this->generarFrase(8-$this->countSilabas($rima2))." ".$rima2);
        break;
    }
  }
  return $cuarteta;
  }


  /* Dice una paya, uyuii.
   */
  public function decirPaya() {
    /*falta implementar payar en décimas, por ahora sólo es posible payar en cuartetas*/
    return $this->generarCuarteta($this->tematica);
  }

}
?>
