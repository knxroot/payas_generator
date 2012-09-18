<?php

/**
 NOTA: PRIMERO EXCEPCIONES, LUEGO GRUPOS Y POR ÚLTIMO PALABRAS SUELTAS
 */

/**
 * Fwok Libraries
 *
 * LICENSE
 *
 * Check: http://code.fwok.org/license
 *
 * @category   Fwok
 * @package    Fwok
 * @copyright  You must see the url: http://code.fwok.org
 * @license    http://code.fwok.org/license
 * @version    You must see http://code.fwok.org/fwok_word_spanish_syllabler
 */

/**
 * @category   Fwok
 * @package    Fwok
 * @subpackage Fwok_Word
 * @copyright  See http://code.fwok.org/license
 * @license    http://code.fwok.org/license
*/

abstract class Fwok_Word_Phonetic_Abstract
{
    /**
     * Var to add the translation of groups of words
     * It will be considered a bad practice use this
     * var directly in your extension class
     */
    private $_tableGroups = array();


    /**
     * Var to add the translation of single letters
     * It will be considered a bad practice use this
     * var directly in your extension class
     */
    private $_tableLetter = array();


    /**
     * Var to add the exceptions and wich method will be called
     * For example imagine Consonant + consonant with any
     * special pronunciation, when the script detects
     * that special pronunciation that method will be called
     * if not the translation will be searched in the
     * translation table.
     *
     * It will be considered a bad practice use this
     * var directly in your extension class
     */
    private $_exceptions = array();


    abstract public function __construct();
    abstract private function _init(); //For example for append the exceptions
    abstract public function _run();


    /**
     * Function to add exception
     *
     * @param string $letters
     * @param string $method The method would be defined inside the class
     * @return $this
     */
    private function _addException($letters, $method) {}


    /**
     * To add more than one exception in one time
     * @param array $exceptions with arrays with values like _addException method
     * @return $this
     */
    private function _addExceptions(Array $exceptions) {}


    /**
     * Function to add a translation to one group of letters or single letter
     *
     * @param string $letters
     * @param string $phoneme
     * @return $this
     */
}