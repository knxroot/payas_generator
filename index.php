<?php
/**
 * Index generador de payas
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

ini_set('display_errors', '0');
session_start();
set_include_path(get_include_path().PATH_SEPARATOR.'./include/');
require_once('phpQuery/phpQuery-onefile.php');
require_once('RandSGen.php');
require_once('Fwok/Word/Syllabler/Spanish.php');
require_once('utils.php');
require_once('payador.php');


$payaHtmlArray=Array();
if (isset($_GET["base"])){
$base=$_GET["base"];
$roboPayador=new payador($base);
$payaHtmlArray=$roboPayador->decirPaya();
}else{
$base="";
}

function printPayaHtml($payaHtmlArray){
  if(count($payaHtmlArray)>=4){
    echo ucfirst($payaHtmlArray[0]).'<br>'.$payaHtmlArray[1].'<br>'.$payaHtmlArray[2].'<br>'.$payaHtmlArray[3].'.';
  }else{
    echo "Escribe un término";
  }
}

//print_r($payaHtmlArray);
//print_r($roboPayador->silabador("empanada"));
//echo $paya_txt;
include('template.php');
?>
