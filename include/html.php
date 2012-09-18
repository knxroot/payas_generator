<?php

set_include_path(get_include_path() . PATH_SEPARATOR . '../library');
header("Content-Type: text/html;charset=UTF-8");

echo "<html>\n";
echo " <header>\n";
echo "  <title>Syllabler 0.1</title>\n";
echo "  <meta http-equiv=\"Content-type\" content=\"text/html; charset=UTF-8\" />\n";
echo " </header>\n\n";
echo " <body>\n";

require_once 'Fwok/Word/Syllabler/Spanish.php';

echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="get" enctype="multipart/form-data" accept-charset="UTF-8">
	<input type="text" name="word" value="' . (isset($_GET['word'])?$_GET['word']:'') .'" />
	<BUTTON name="submit" value="submit" type="submit">Silabizar</BUTTON>
</form>';

if($_REQUEST['word']) {
    $word = $_REQUEST['word'];
    Fwok_Word_Syllabler_Spanish::setSpain(true);
    Fwok_Word_Syllabler_Spanish::setTl(false);
    Fwok_Word_Syllabler_Spanish::setIgnorePrefix(true);
    $w = new Fwok_Word_Syllabler_Spanish($word);
    $typeStressed = array('Aguda', 'Llana', 'Esdrújula', 'Sobre-Esdrújula', 'Ante-Esdrújula');
    echo "<pre>Sílabas: ";
    var_dump(($w->getSyllables()));
    echo "\n";
    echo "Sílaba tónica: ";
    var_dump($w->getStressedSyllable());
    echo "\n";
    echo "Tipo: " . $typeStressed[$w->getStressedType()] . "\n";
    echo "La letra tónica es: " . mb_substr($word, $w->getStressedLetter(), 1) . "\n";
    echo "Número de sílabas: " . $w->getNumberOfSyllables() . "\n";
    echo "Tiene \"tl\"? " . ($w->hasTl()? 'Si':'No') . "\n";
    echo "Tiene algún prefijo? " . ($w->hasPrefix()? 'Si':'No') . "\n\n";
    echo "</pre>";
    //*/
}
echo " </body>\n";
echo "</html>";
