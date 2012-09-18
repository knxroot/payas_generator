<?php

// Declaring the codification of file like PEAR standards
declare(encoding = 'utf-8');

/**
 * This file is a abstract class for provide the basic functionality to work
 * with words and split it in syllables.
 *
 * This file is a abstract class for provide the basic functionality to work
 * with words and split it in syllables.
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
 * @since      File available since first version
 */

/*
 * Dependencies of other classes
 */
require_once 'Fwok/Word/Syllabler/Exception.php';

/**
 * This class provide a basic functionality to work with words and do it compatible
 * between various languages.
 *
 * This class provide a basic functionality to work with words and do it compatible
 * between various languages like get the number of syllables and provide the basic
 * functionality to track errors using, for example Zend_Log or another class with
 * log method.
 *
 * @category   Fwok
 * @package    Fwok_Word_Syllabler
 * @subpackage Fwok_Word_Syllabler
 * @author     Gabriel Trabanco <gtrabanco@fwok.org>
 * @copyright  {@link http://www.fwok.org/copyright Copyriht advisor}
 * @license    {@link {@link http://www.fwok.org/license}
 * @version    SVN: $Id$
 * @link       http://php.fwok.org/package/Fwok_Word_Syllabler
 * @since      File available since first version
 * @method     self setLogger(Object $logger)
 * @method     int    getNumberOfSyllables()
 * @method     int    getStressedLetter() Get the position of stressed Letter.
 * @method     int    getStressedType() Get the inverse position of stressed syllable.
 * @method     string getStressedSyllable()
 * @method     Array  getDivisions() You can get between wich word you have a division
 *                        with a start in 0 position and end in the len of characters.
 * @method     Array  getSyllables([string $word]) Get all syllables with default word
 *                        formated or with the format in $word. For example, if you
 *                        won't pass any argument and you set by default your word as
 *                        "hElLo" you get "he-llo" and not "hE-lLo" but if you put
 *                        as argument hElLo another time you will get "hE-lLo".
 */
abstract class Fwok_Word_Syllabler_Abstract
{


	/**
	 * A var for save the log handler. It must have a log method like Zend_Log
	 */
	protected static $_logger = null;


	/**
	 * Protected var for add exceptions
	 * @todo
	 */
	protected static $_exceptions = true;


	/**
	 * Protected var for add the word
	 */
	protected static $_word = '';


	/**
	 * Protected var with the information of the divisions in the word
	 */
	protected $_divisions = array();


	/**
	 * Stressed Vowel
	 */
	protected $_stressedLetter = 0;


	/**
	 * Stressed Syllable
	 */
	protected $_stressedSyllable = 0;


	/**
	 * Construct to Set a word
	 *
	 * @param string $word
	 */
	abstract public function __construct($word);

	/**
	 * Function to run all
	 */
	abstract protected function _run();


	/**
	 * Public method to set the logger for testing proposals. You have to provide a
	 * object with log method with at least one string parameter.
	 *
	 * Spanish:
	 * Método para escribir en el logger sirve para beta-testing
	 *
	 * @param Logger Object $logger
	 * @return $this
	 * @throws Fwok_Word_Syllabler_Exception if the method $log not exists in $logger
	 *        Object.
	 */
    public static function setLogger($logger) {
        if(!method_exists($logger, 'log')) {
            throw new Fwok_Word_Syllabler_Exception(
                'Important problem! The method "log" does not exists in the logger object.'
            );
        }


        self::$_logger = $logger;
        self::$_logger->log("Logger Set", 7);


        return self;
    }


	/**
	 * Function to know how many syllables has the word.
	 *
	 * Spanish:
	 * Función que devuelve el número de sílabas
	 *
	 * @return int Syllables
	 */
	public function getNumberOfSyllables()
	{
		if(empty($this->_divisions))
		    $this->_run();

		return (count($this->_divisions)-1);
	}


	/**
	 * Function to get the stressed letter
	 *
	 * Note: if you want to write a librarie for any language which
	 * has multiple stressed letter in one word consider to
	 * rewrite this function.
	 *
	 * Spanish:
	 * Función para saber cual es la letra tónica
	 *
	 * @return int Stressed letter
	 */
	public function getStressedLetter()
	{
		if(empty($this->_divisions))
		    $this->_run();

		return $this->_stressedLetter;
	}


	/**
	 * Function to get the type of word or inverse number of syllable
	 *                     1 => last syllable is the stressed
	 *                     2 => previous to last syllable
	 *                     ....
	 *
	 * Note: if you want to write a librarie for any language which
	 * has multiple stressed syllables in one word consider to
	 * rewrite this function.
	 *
	 * Spanish:
	 * Función para saber cual es la sílaba tónica
	 *                     1 => La última
	 *                     2 => La penúltima
	 *                     ....
	 *
	 * @return int Stressed Syllable
	 */
	public function getStressedType()
	{
		if(empty($this->_divisions))
		    $this->_run();

		return $this->_stressedSyllable;
	}


	/**
	 * Function to get the stressed Syllable
	 *
	 * Note: if you want to write a librarie for any language which
	 * has multiple stressed syllables in one word consider to
	 * rewrite this function.
	 *
	 * Spanish:
	 * Función para saber cual es la sílaba tónica
	 *
	 * @return int Stressed Syllable
	 */
	public function getStressedSyllable()
	{
		if(empty($this->_divisions))
		    $this->_run();

		//Start key of array
		$startKey = $this->getNumberOfSyllables() - $this->_stressedSyllable ;

		//Start and end of the syllable
		$start = $this->_divisions[$startKey];
		$len   = $this->_divisions[$startKey + 1] - $start; //How many letters?

		return mb_substr($this->_word, $start, $len);
	}


	/**
	 * Function to get the divisions of the word
	 *
	 * Spanish:
	 * Función que devuelve las divisiones de palabra
	 *
	 * @return arrray divisions
	 */
	public function getDivisions()
	{
		if(empty($this->_divisions))
		    $this->_run();

		return $this->_divisions;
	}


	/**
	 * Function to get an array of Syllables. You can set one argument with the word
	 * if you don't want watched the word in lower characters for example. Because the class
	 * set the word in lower character and utf8.
	 * The unique requirement is that the argument must be the same word, if not the function will
	 * return an array of saved word.
	 *
	 * Spanish:
	 * Función que devuelve un array con clave numerica y como valores las sílabas. Puedes poner un
	 * argumento que podría ser la palabra original si quieres, sino lo hará soble la palabra
	 * almacenada. Se permite esto por que se almacenan las palabras en minúsculas y podrías querer
	 * tener la palabra en mayúsculas o con la primera letra capital o algún formato diferente.
	 * El único requisito es que coincida la palabra con la original, si no es así devuelve
	 * las sílabas de la palabra guardada
	 *
	 * @param string $this->_word (optional)
	 * @return array
	 */
	public function getSyllables($word = null)
	{
		if(empty($this->_divisions))
		    $this->_run();

		$i = 0; //Counter
		$syllables = array();


		$total_syllables = $this->getNumberOfSyllables();


		if($word !== null and $this->_word !== $this->_formatString($word))
			$word = $this->_word;
		

		//Now we get the syllables from original word
		for($i=0; $i < $total_syllables; $i++)
			$syllables [] = mb_substr(	$this->_word,
									$this->_divisions[$i],
									$this->_divisions[$i+1]-$this->_divisions[$i]);


		return $syllables;
	}
	
	/**
	 * Protected method to log messages
	 *
	 * Spanish:
	 * Método privado para crear una bitácora de mensajes
	 *
	 * @param string $msg
	 * @param int $intType (Watch: http://framework.zend.com/manual/en/zend.log.overview.html)
	 */
    protected function _log($msg, $intType = 7)
    {
        if(is_object(self::$_logger)) {
            self::$_logger->log($msg, $intType);
        }
    }


	/**
	 * Function to add a value into $array if not exists
	 *
	 * Spanish:
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
}