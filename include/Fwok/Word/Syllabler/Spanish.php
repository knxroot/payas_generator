<?php

// Declaring the codification of file like PEAR standards
declare(encoding = 'utf-8');

/**
 * This file is a class for separate a word in syllables
 *
 * With this library you can separate spanish words in syllables
 *
 * PHP version 5
 *
 * LICENSE: You can show the license in {@link http://www.fwok.org/license}
 *
 * @category   Fwok
 * @package    Fwok_Word
 * @subpackage Fwok_Word_Syllabler
 * @author     Gabriel Trabanco <gtrabanco@fwok.org>
 * @copyright  {@link http://www.fwok.org/copyright Copyriht advisor}
 * @license    {@link http://www.fwok.org/license}
 * @version    SVN: $Id$
 * @link       http://php.fwok.org/package/Fwok_Word_Syllabler
 * @see        Fwok_Word_Syllabler_Abstract
 * @since      File available since first version
 */

/*
 * Dependencies of other classes
 */
require_once 'Fwok/Word/Syllabler/Abstract.php';

/**
 * This class provide a funcionality to work with spanish words
 *
 * This class provide a funcionality to split spanish words in syllables,
 * get the stressed syllable and letter and how many syllables it has.
 *
 * @category   Fwok
 * @package    Fwok_Word
 * @subpackage Fwok_Word_Syllabler
 * @author     Gabriel Trabanco <gtrabanco@fwok.org>
 * @copyright  {@link http://www.fwok.org/copyright Copyriht advisor}
 * @license    {@link {@link http://www.fwok.org/license}
 * @version    SVN: $Id$
 * @link       http://php.fwok.org/package/Fwok_Word_Syllabler
 * @see        Fwok_Word_Syllabler_Abstract
 * @since      File available since first version
 */
class Fwok_Word_Syllabler_Spanish extends Fwok_Word_Syllabler_Abstract
{


	/**
	 * Protected var for Spain exceptions
	 */
	protected static $_spain = true;


	/**
	 * Protected var for tl exceptions
	 * $_spain and this are incompatible together
	 */
	protected static $_tl = false; //true joined, off separated.
	
	/**
	 * Protected var for ignore (true) or not prefixes
	 */
	protected static $_ignorePrefix = true;

	/**
	 * Protected var with neccesary prefixes to advertise
	 * array of prefixes to advert that can produce a differents divisions
	 */
	protected static $_prefixes = array('sub');

    /**
	 * Protected var for know if the word has a tl group of consonants
	 */
	protected $_hasTl = false;
	
	/**
	 * Advert over one prefix
	 */
	protected $_prefixAdvert = '';


    /**
	 * Public function to setup how to run this class
	 * @return void
	 */
	public static function setSpain($bool) {
		self::$_spain = $bool;
	}


	/**
	 * Public function to setup how to run this class with tl
	 * @return void
	 */
	public static function setTl($bool) {
		self::$_spain = $bool;
	}


	/**
	 * Public function to setup if you want to ignore the prefix of words
	 * @param bool $bool
	 * @return void
	 */
	public static function setIgnorePrefix($bool)
	{
		self::$_ignorePrefix = $bool;
	}


	/**
	 * Public function to setup one or an array of prefixes
	 * "sub" prefix is added by default
	 * @param string|array prefix or array of prefixes
	 * @return void
	 */
	public static function setPrefix($prefix)
	{
		if(is_array($prefix)) {
			foreach($prefix as $p)
				self::$_prefixes [] = $prefix;
		} else {
			self::$_prefixes [] = $prefix;
		}
	}


	/**
	 * Construct to Set a word
	 *
	 * @param string $this->_word
	 */
	public function __construct($word)
	{
	 	//Setting the word
	 	$this->_word = $this->_formatString($word);
	 	
	 	//Using UTf-8
	 	mb_internal_encoding('UTF-8');
	 	
	 	$this->_log('Adding the word "' . $word . '" to syllablice it', 6);
	}


	/**
	 * Function to know if the word has a tl group of consonants
	 * @return bool
	 */
	public function hasTl()
	{
		if(empty($this->_divisions)) $this->_run();

		return $this->_hasTl;
	}


	/**
	 * Function to know if the word has a tl group of consonants
	 * @return empty or completed string with prefix
	 */
	public function hasPrefix()
	{
		if(empty($this->_divisions)) $this->_run();

		return $this->_prefixAdvert;
	}


    /**
	 * Function to format a string (because of the accents we need this)
	 *
	 * Función para formatear un string (la necesitamos por los acentos)
	 *
	 * @param string $string
	 * @return string
	 */
	protected function _formatString($string)
	{
		static $upperChars = array('A', 'Á', 'B', 'C', 'D', 'E', 'É', 'F', 'G', 'H',
		                           'I', 'Í', 'J', 'K', 'L', 'M', 'N', 'Ñ', 'O', 'Ó',
		                           'P', 'Q', 'R', 'S', 'T', 'U', 'Ú', 'Ü', 'V', 'W',
		                           'X', 'Y', 'Z');


		static $lowerChars = array('a', 'á', 'b', 'c', 'd', 'e', 'é', 'f', 'g', 'h',
		                           'i', 'í', 'j', 'k', 'l', 'm', 'n', 'ñ', 'o', 'ó',
		                           'p', 'q', 'r', 's', 't', 'u', 'ú', 'ü', 'v', 'w',
		                           'x', 'y', 'z');

        $this->_log("Formating string and converting to lower character \"{$string}\"", 7);
        
        $string = mb_convert_encoding($string, 'UTF-8');
        
        /*
        $coding_orig = mb_detect_encoding($string, "auto");
        $string = mb_convert_encoding($string, 'UTF-8', "auto");
        $coding = mb_detect_encoding($string, "auto");
        
        $this->_log("The original codification of string is {$coding_orig}", 7);
        $this->_log("The current codification of string is {$coding}", 7);
        //*/

		return trim(str_replace($upperChars, $lowerChars, $string)); //I use this method to do this
		                                                             //because strtolower do not
		                                                             //convert accents
	}	


	/**
	 * Function to check if one letter has an accent
	 *
	 * Función para comprobar si una letra tiene o no acento
	 *
	 * @param char $char
	 * @return bool
	 */
	protected function _has_accent($char)
	{
		//static $accents = array('á', 'Á', 'é', 'É', 'í', 'Í', 'ó', 'Ó', 'ú', 'Ú');
		$accents = 'áÁéÉíÍóÓúÚ';
		
		$this->_log("Checking if the letter \"{$char}\" has an accent", 6);

		if(mb_strlen($char) !== 1)
			return false;

        $this->_log("The letter \"{$char}\" has an accent.", 6);
		
		if(!empty($char) and mb_strpos($accents, $char) !== false) return true;
		
		return false;
		//return in_array($letter, $accents, true);
	}


	/**
	 * Function to check if one letter is a strong vowel
	 *
	 * Función para comprobar si una letra es una vocal fuerte o media
	 *
	 * @param char $char
	 * @return bool
	 */
	protected function _is_strong_vowel($char)
	{
		/*
		static $strongVowels = array('a','á', 'A', 'Á',
                                    'e', 'é', 'E', 'É',
                                    'o', 'ó', 'O', 'Ó',
                                    'í', 'Í', 'ú', 'Ú');
        //*/
        
        static $strongVowels = 'aáAÁeéEÉoOóÓíÍúÚ';
	 	
	 	$this->_log("Checking if the vowel \"{$char}\" is a strong vowel.", 6);
	 	
	 	if(mb_strlen($char) !== 1)
	 		return false;
	 	
	 	$this->_log("The vowel \"{$char}\" is a strong vowel.", 6);
	 	
	 	if(!empty($char) and mb_strpos($strongVowels, $char) !== false) return true;
	 	
	 	return false;
	}
	
	
	/**
	 * Function to check if one letter is a weak vowel
	 *
	 * Función para comprobar si una letra es una vocal débil
	 *
	 * @param char $char
	 * @return bool
	 */
	protected function _is_weak_vowel($char)
	{
		//static $weakVowels = array('i', 'I', 'u', 'U', 'ü', 'Ü');
		static $weakVowels = 'iIuUüÜ';

	 	$this->_log("Checking if the vowel \"{$char}\" is a weak vowel.", 6);

	 	if(mb_strlen($char) !== 1)
	 		return false;

	 	$this->_log("The vowel \"{$char}\" is a weak vowel.", 6);

	 	//return in_array($char, $weakVowels, true);
	 	if(!empty($char) and mb_strpos($weakVowels, $char) !== false) return true;
	 	
	 	return false;
	}
	
	
	/**
	 * Function to check if one letter is vowel
	 *
	 * Función para comprobar si una letra es una vocal
	 *
	 * @param char $char
	 * @return bool
	 */
	protected function _is_vowel($char)
	{
        static $vowels = 'aáAÁeéEÉiIíÍoOóÓuUúÚ';
        $this->_log("Checking if \"{$char}\" is a vowel.", 7);

		if(!empty($char) and mb_strpos($vowels, $char) !== false) return true;
		
		return false;
	}
	
	
	/**
	 * Function to check if one letter is a consonant
	 *
	 * Función para comprobar si una letra es consonante
	 *
	 * @param char $char
	 * @return bool
	 */
	protected function _is_consonant($char)
	{
		/*
		static $consonants = array( 'b', 'B', 'c', 'C', 'd', 'D', 'f', 'F',
                                    'g', 'G', 'h', 'H', 'j', 'J', 'k', 'K',
                                    'l', 'L', 'm', 'M', 'n', 'N', 'ñ', 'Ñ',
                                    'p', 'P', 'q', 'Q', 'r', 'R', 's', 'S',
                                    't', 'T', 'v', 'V', 'w', 'W', 'x', 'X',
                                    'y', 'Y', 'z', 'Z');
        //*/
        
        static $consonants = 'bBcCdDfFgGhHjJkKlLmMnNñÑpPqQrRsStTvVwWxXyYzZ';

		$this->_log("Checking if \"{$char}\" is a consonant.", 6);

		//I could do this checking that it isn't a vowel but I want to return false if
		//the user want to check a number or something different from a letter
		//and it is converted to Hex value because of the Ñ and ñ
		//return in_array($char, $consonants, true);
		if(!empty($char) and mb_strpos($consonants, $char) !== false) return true;
		
		return false;
	}
	
	/**
	 * Function to check if one group of two syllables is an hiatus
	 *
	 * Función para comprobar si un grupo de dos sílabas es un hiato
	 *
	 * @param string $letter 2 letters
	 * @return bool
	 */
	protected function _is_hiatus($letters)
	{
		$this->_log("Checking if the \"{$letters}\" are an hiatus.", 6);
		
		$c1  = mb_substr($letters, 0, 1);
		$c2  = mb_substr($letters, 1, 1);

		if($this->_is_strong_vowel($c1) and $this->_is_strong_vowel($c2))
			return true;

		return false;
	}
	
	/**
	 * Function to check if one group of two syllables is a diphthong
	 *
	 * Función para comprobar si un grupo de dos sílabas es un diptongo
	 *
	 * @param string $letters 2 letters
	 * @return bool
	 */
	protected function _is_diphthong($letters)
	{
		$this->_log("Checking if the \"{$letters}\" are a diphthong.", 6);

		$c1  = mb_substr($letters, 0, 1);
		$c2  = mb_substr($letters, 1, 1);
		
		if(($this->_is_strong_vowel($c1) and $this->_is_weak_vowel($c2)) or
		   ($this->_is_weak_vowel($c1) and $this->_is_strong_vowel($c2)) or
		   ($this->_is_weak_vowel($c1) and $this->_is_weak_vowel($c2)))
			return true;
		
		return false;
	}


	/**
	 * Function to check if one group of three vowels is a triphthong
	 *
	 * Función para comprobar si un grupo silábico de 3 sílabas es un triptongo
	 *
	 * @param string $lettes
	 * @return bool
	 */
	protected function _is_triphthong($letters)
	{
		$this->_log("Checking if the \"{$letters}\" are a triphthong.", 6);

		$bool = false; //Return value at the end of function


		//For better understable code and unicode
		$c1  = mb_substr($letters, 0, 1);
		$c2  = mb_substr($letters, 1, 1);
		$c3  = mb_substr($letters, 2, 1);


		if( $this->_is_strong_vowel($c1) and
		    $this->_is_weak_vowel($c2) and
		    $this->_is_strong_vowel($c3) )
			$bool = true;
		
		return $bool;
	}
	
	
	/**
	 * Function to check if it is a consonant of group exceptions
	 *
	 * Función para comprobar si es un grupo de consonantes de las excepciones
	 *
	 * @param string $letters 2 letters
	 * @return bool
	 */
	protected function _is_double_consonant_exception($letters, $tl = false)
	{
		static $exceptions = array(	'bl', 'cl', 'fl', 'gl', 'kl', 'pl', 'll',
									'br', 'cr', 'dr', 'gr', 'kr', 'pr', 'tr', 'rr',
									'ch');

		$this->_log("Checking if \"{$letters}\" is any double consonant extension wich are always together.", 6);
		$this->_log("The \"tl\" exception is " . ($tl?'enabled':'disabled'), 6);

		//First the exception of tl. This only for advise the user.
		if($letters === 'tl') {
			echo "Tiene tl pero: $tl " . $letters. "\n";
			var_export($tl);
			$this->_hasTl = true;
		}
		
		return in_array($letters, $exceptions, true) or ($tl and $letters === 'tl');
	}


	/**
	 * Function to add a value into $array if not exists
	 *
	 * Función para añadir un valor a un array si no existe
	 *
	 * @param mixed $value
	 * @param array &$array its a reference to the array
	 * @param bool $strict use or not strict comparation
	 */
	protected function _addToArray($value, array &$array, $strict = true)
	{
		if(!in_array($value, $array, $strict))
			$array [] = $value;
	}


	/**
	 * Function to process a word
	 * @param string $word
	 * @param bool $spain exceptions
	 * @param bool $tl if you want "tl" always join on syllables
	 * @return array divisions
	 */
	protected function _runDivision($word, $spain = true, $tl = false)
	{
		$len  = mb_strlen($word);

		$i    = 0; //Counter

		$switch = false; //if it is a word of exception we don't need begin the loop
		
		$divisions = array(0); //Return value


		//First the exceptions words for Spain
		if($spain and $word === 'guión') {
			//Guión
			$switch = true; //For don't begin the loop
			$divisions = array(0, 3, 5);
		}


		if($spain and  $word === 'truhán') {
			//Truhán
			$switch = true; //For don't begin the loop
			$divisions = array(0, 3, 6);
		}


		//Check if the last letter is an y, because if it is, it will run as a vowel
		//If it is we replace it because it don't take effect over the syllabler counter
		if($word[$len-1] === 'y')
			$word[$len-1] = 'i';


        $this->_log('Begining to divide the word "' . $word . '".', 6);


		//Begining the division of a letter
		while($i < $len and $switch === false) {
			$c1 = $c2 = $c3 = $c4 = '';
			$c1  = mb_substr($word, $i, 1);
			if($i+1 <= $len) $c2  = mb_substr($word, $i+1, 1);
			if($i+2 <= $len) $c3  = mb_substr($word, $i+2, 1);
			if($i+3 <= $len)$c4  = mb_substr($word, $i+3, 1);


			$this->_log("Cycle $i with letters $c1 $c2 $c3 $c4", 7);


			//we going to advance to first vowel to begin
			//because I would like to escape first consonants of the words like "gnomo"
			if( $this->_is_consonant($word[0]) and count($divisions) < 2 and
			    $i < 2 and $this->_is_consonant($c1) ) {
			    
			    $this->_log( "The word start with one or more consonants.", 7); 
				++$i;
				continue;
			}

			//Now we do all checks here without levels of if's
			//Consonant + Consonant + Consonant + Consonant
			if( $this->_is_consonant($c1) and $this->_is_consonant($c2) and
			    $this->_is_consonant($c3) and $this->_is_consonant($c4)) {
			
			    $this->_log( "Appending division between c2 ($c2) and c3 " .
			                 "($c3) because there are 3 consonants.", 7);
			                 
				$this->_addToArray(($i+=2), $divisions);
				continue;

			//Consonant + Consonant + Consonant + Vowel
			} else if( $this->_is_consonant($c1) and $this->_is_consonant($c2) and
			           $this->_is_consonant($c3) and $this->_is_vowel($c4) ) {
			           
				if($c3 === 'l' or $c3 === 'r') {
				
				    $this->_log("Appending a divisions between c1 ($c1) and c2 " .
				                "($c2) because there are 3 consonants and 3rd is $c3.", 7);
				                
					$this->_addToArray(($i+1), $divisions);
					$i += 3;
					continue;
				} else {
				    $this->_log( "Appending a division between c2 ($c2) and c3($c3) " .
				                 "because there are 3 consonants and last it is not " .
				                 "an l or r.", 7);
					
					$this->_addToArray(($i+=2), $divisions);
					continue;
				}

			//Consonant + Consonant + Vowel
			} else if( $this->_is_consonant($c1) and $this->_is_consonant($c2) and
			           $this->_is_vowel($c3)) {

				if($this->_is_double_consonant_exception($c1.$c2, $spain, $tl)) {
				
					$this->_log( "Adding a division between c2 ($c2) and c3 ($c3) " .
					             "because c1 ($c1) and ($c2) is a double consonant " .
					             "exception.", 7);

		  			$this->_addToArray($i, $divisions);
		  			$i += 2;
			  		continue;
			  	} else {
			  	
			  	    $this->_log( "Adding the division between 2 consonants c1 ($c1) " .
			  	                 "and c2 ($c2).", 7);

			  		$this->_addToArray(($i+1), $divisions);
		  			$i += 2; //If we don't do this we will have a problems with
		  					 //the coincidence of consonant + vowel
			  		continue;
			  	}
	
			//Consonant + Vowel
			} else if($this->_is_consonant($c1) and $this->_is_vowel($c2)) {

    			$this->_log("Consonant c1 ($c1) and vowel c2 ($c2).", 7);
				//First check if it is qu cause it's indivisible
		  		//Secondly check if it is gue or gui
		  		if(($c1 === 'q' or $c1 === 'g') and $c2 === 'u') {
		  		    $this->_log("They are undivisible c1 and c2: $c1$c2 = (gu or qu).", 7);
		  			//Then continue without 2 first letters of this syllable
		  			$i += 2;
		  			continue;
		  		} else { //Consonant + Vowel
		  				 //We must to count syllables because if the first letter it is an consonant
		  				 //we continue with the loop then only divide this if it isn't the first

		            $this->_log("There is a division previous to c1 ($c1).", 7);
		            
		  			$this->_addToArray($i, $divisions);
			  		++$i;
			  		continue;
			  	}//*/
			//Vowel + Vowel
			} else if($this->_is_vowel($c1) and $this->_is_vowel($c2)) {
				if($this->_is_hiatus($c1.$c2)) {
				    $this->_log("There is an hiatus between c1 ($c1) and c2($c2).", 7);
		  			$this->_addToArray((++$i), $divisions);
		  			continue;
			  	} else if($this->_is_vowel($c3) and $this->_is_hiatus($c2.$c3)) {
			  	    $this->_log("There is an hiatus between c2 ($c2) and c3 ($c3)", 7);
			  		$this->_addToArray(($i+=2), $divisions);
			  		continue;
		  		} else if($this->_is_vowel($c3) and $this->_is_triphthong($c1.$c2.$c3)) { //Then it is a triphthong
		  		    $this->_log("There are a triphthong between c1 ($c1), c2 ($c2) and c3 ($c3).", 7);
		  			//Continue
			  		$i += 3;
		  			continue;
			  	} elseif($this->_is_diphthong($c1.$c2)) { //Then it is a diphthong with 1 consonant after so we must check the consonants
			  	    $this->_log( "There are a diphthong and consonant c1 ($c1), c2 ($c2), " .
			  	                 "c3 ($c3) and c4 ($c4).", 7);
			  		++$i;
			  		continue;
		  		}

			//Vowel + Consonant + Vowel
			} else if( $this->_is_vowel($c1) and
					   $this->_is_consonant($c2) and $this->_is_vowel($c3)) {
				
				$this->_log("Vowel, consonant and vowel the division is between c1 ($c1) " .
				            "and c2 ($c2)", 7);
				$this->_addToArray((++$i), $divisions);
				continue;
			}

			$this->_log("No divisions found! Continue!", 7);
			//Vowel + Consonant + Consonant will be checked in next cycle
			++$i;
		} //End Loop

		//Now we add the end division for help us doing it later with substr function
		$this->_addToArray($len, $divisions);

		return $divisions;
	}


	/**
	 * Function to process the Stressed Syllable and Vowel
	 *
	 * Función para procesar la sílaba y letra tónica de una palabra
	 *
	 * @param string $word
	 * @param array $divisions
	 * @return array array(int|array Stressed Syllable, int|array stressed Letter)
	 */
	protected function _runStressed($word, array $divisions)
	{
		$stressedSyllable = 0; //Return value
		$stressedLetter   = -1; //Return Value; I use -1 because it could be the letter in pos = 0

		$i = 0; //Counter

		$total_syllables = count($divisions)-1;

		$len = mb_strlen($word);
		
		$original_word = $word;


		//First we going to check if it has a vowel with accent
		for($i = 0; $i < $len; $i++) {
		    $char = mb_substr($word, $i, 1);
    		if($this->_has_accent($char)) {
    		    $this->_log("Se encontró la letra acentuada \"{$char}\" en " .
    		                "la posición {$i}",
    		                7);
				$stressedLetter = $i;
			}
		}


		//Now we check the stressed syllable
		//First words with accent
		if($stressedLetter !== -1) {
		    for($i=0; $i<=$total_syllables; $i++) {
	    		if( $stressedLetter >= $divisions[$i] and
	    			$stressedLetter < $divisions[$i+1] ) {
	    			$stressedSyllable = $total_syllables - $i;  //Counting wich one is the
	    														//stressed syllable
	    			$this->_log("La sílaba tónica es la: {$stressedSyllable}", 7);
	    			break;
	    		}
	    	}

		//Secondly words without accent
	    } else {
			//Last letter of the word. I do this over the original word because if the last
			//letter is an "y" it doesn't has accent in spite of it works as a vowel for
			//diphthongs and that stuff
			$lastLetter = mb_substr($word, $len-1, 1);
			
			$this->_log('The word ends with the letter "' . $lastLetter . '"', 7);
			
			//Check if the last letter is an y, because if it is, it will run as a vowel
			//If it is we replace it because it don't take effect over this function
			if($lastLetter === 'y')
				$word = mb_substr($word, 0, $len-1) . 'i'; //Because of diphthongs and triphthongs we need to do this

			if(	$total_syllables > 1 and (in_array($lastLetter, array('n', 's'), true)
				or $this->_is_vowel($lastLetter))) {
				
				$this->_log('The word has not an accent and end in "n", "s" or vowel. ' .
				            'It is "Llana".', 7);
				$stressedSyllable = 2; //"Llana" because it hasn't
								       //accent and end in vowel
								       //"n" or "s"
			} else{
			    $this->_log('The word has not an accent and not end in "n", "s" ' .
			                'or vowel. It could also be a monosyllable word.');
			    $stressedSyllable = 1; //"Aguda" because it hasn't accent and don't end in "n",
								       //"s" or vowel
            }

        	//Now we going to get the stressed letter
	        //First we get the syllable
    	    $startDivision  = $divisions[$total_syllables - $stressedSyllable];
        	$finishDivision = $divisions[$total_syllables - $stressedSyllable + 1];
        	$syl            = mb_substr($word, $startDivision, $finishDivision);
        	$syl_len        = mb_strlen($syl);


        	$this->_log('Extracting the stressed vowel.', 6);
        	
        	//Now we going to check the syllable, We must know that there isn't any syllable
        	//with more than 4 vowels
        	for($i=0; $i<$syl_len; $i++) {
        		$c1 = $c2 = $c3 = $c4 = '';
    			$c1  = mb_substr($word, $i, 1);
    			if($i+1 <= $syl_len) $c2  = mb_substr($syl, $i+1, 1);
    			if($i+2 <= $syl_len) $c3  = mb_substr($syl, $i+2, 1);
    			if($i+3 <= $syl_len) $c4  = mb_substr($syl, $i+3, 1);
        		//Maximum syllables is 6 but if it has 6 syllables I will find a consonant before
        		//the vowels and the loop will continue, so 4 it's enough

        		//Only vowels have accent
        		if($this->_is_consonant($c1) and ($c1 !== 'g' or $c1 !== 'q')) continue;


        		//The exception of 'gu' and 'qu'
        		if( ($c1 === 'q' or $c1 === 'g') and $c2 === 'u' and ($c3 === 'e' or $c3 === 'i') ) {
        		    $this->_log('A inseparable "qu" or "gu" found.', 7);
        			++$i; //Because automatic increment in each cycle and we only want
        				  //to increment in 2 the counter
	        		continue;
        		
    	    	} else if($this->_is_triphthong($c1.$c2.$c3)) {
    	    	    $this->_log('There is a triphthong, middle vowel is stressed.', 7);
        			$stressedLetter = $startDivision + $i + 1;
        			break;
        	
        		} else if($this->_is_vowel($c1)) {
        		    $this->_log('Only one vowel, this is easy.', 7);
        			$stressedLetter = $startDivision + $i + 1;
        			break;
        		}
        	}
		}

		$this->_log("The stressed syllable is {$stressedSyllable} and stressed ' " .
		            "letter is {$stressedLetter}.",
		             7);

		return array($stressedSyllable, $stressedLetter);
	}
	
	
	/**
	 * Function to check if we have something to advertise about prefixes
	 */
	protected function _runPrefixes()
	{
		$number_prefixes = count(self::$_prefixes);
		
		$i = 0;
		
		//Comparing for fix or/and advert
		for($i = 0; $i < $number_prefixes; $i++) {
			$len = mb_strlen(self::$_prefixes[$i]);
			$prefix = mb_substr($this->_word, 0, $len);
			if($prefix === self::$_prefixes[$i]) {
			    $this->_log("A prefix \"{$prefix}\" found", 7);
				$this->_prefixAdvert = $prefix;
				
				//Now if we have not to ignore prefix we fix the word with prefix joined
				if(!$this->_ignorePrefix) $this->_divisions[1] = $len;
			}
		}
	}
	
	
	/**
	 * Function to run all
	 */
	protected function _run()
	{
		$this->_divisions = $this->_runDivision($this->_word, self::$_spain, self::$_tl);
		
		$stressedArray = $this->_runStressed(self::$_word, $this->_divisions);
		list($this->_stressedSyllable, $this->_stressedLetter) = $stressedArray;
		
		$this->_runPrefixes();
	}
}