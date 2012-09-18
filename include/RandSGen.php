<?php
/*
 * This file is part of Gen-rsg.
 *
 * Gen-rsg is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * Gen-rsg is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @copyright  2010 Francisco Javier Amarilla Peralta <franciamarilla@gmail.com>
 * @license    http://www.gnu.org/copyleft/lesser.html
 */

/**
 * Random Sentence Generator.
 *
 * @author Francisco Javier Amarilla Peralta
 */
class RandSGen {

    /**
     * Constructor.
     *
     * 
     * @param phpQueryObject $xmlDoc  phpQueryObject generate with phpQuery::newDocumentFile()
     * @param string $cfgrammarName  context free grammar Name
     */
    public function __construct($xmlDoc, $cfgrammarName = "n1")
    {
        $this->xmlDoc = $xmlDoc;
        if ($cfgrammarName == null || empty($cfgrammarName))
            throw new Exception("param: cfgrammarName: null or empty");
        $this->cfgrammarName = $cfgrammarName;
        $this->cfgrammar = $xmlDoc->find($cfgrammarName);
        if ($this->cfgrammar === null || !($this->cfgrammar instanceof phpQueryObject))
            throw new Exception("No se encontro el node grammar");
    }


    /**
     *
     * @param string $startName
     */
    public function generateFromStart($startName='start')
    {
        if ((is_null($startName) || empty($startName))
                && ( is_null($this->startTagName) || empty($this->startTagName)))
            throw new Exception('Must provide a start tag name');
        if ('start' !== $startName && $startName !== $this->startTagName)
            $this->setStartTagName ($startName);
        
        if (!$this->cfgrammar || (!$this->xmlDoc && !$this->cfgrammarName))
            throw new Exception("No se puede generar las sentencias faltan datos");

        $this->generatedSentence =$this->getOneRandomValueByTag((($this->cfgrammar) ? $this->cfgrammar : $this->xmlDoc),$this->startTagName);
        $this->expandNonTerminal ($this->generatedSentence);

    }

    /**
     * Expande los nodos no terminales del nodo $nodo.
     * y luego se llama recursivamente con cada nodo expandido.
     * 
     * @param mixed $nodo
     * @param int $stop Este valor permite para la recursion
     * @return <type> unknown
     */
    public function expandNonTerminal($nodo, $stop = 5)
    {
        if ($stop <= 0) return;
        else $stop -= 1;
        foreach ($nodo->children() as $child)
        {
            
            if (trim(pq($child)->text()) == "" ||
                    trim(pq($child)->find('value')->text()) == "") {
                $replacemnt = $this->getOneRandomValueByTag($this->cfgrammar, pq($child)->get(0)->tagName);

                if ($replacemnt !== null && $replacemnt !== "") {
                    if (pq('value', $child) && pq($child)->children()->count() == 1)
                        pq('value', $child)->replaceWith($replacemnt->clone());
                    else {
                        pq($child)->append ($replacemnt->clone());
                    }

                    $this->expandNonTerminal(pq($child)->find('value'), $stop);
                }
            }
        }
    }

    /**
     * Selecciona un valor de la lista (en caso de que haya mas de uno) de valores
     * del nodo dado por $tagName.
     * 
     * @param phpQueryObject $grammar
     * @param string $tagName
     * @return phpQueryObject
     */
    public function getOneRandomValueByTag($grammar, $tagName)
    {
    
        $count = $grammar->find($tagName.' > value')->count();
        if ($count - 1 < 0)
                return "";
        $randomIndex = rand(0, $count - 1);
        return $grammar->find($tagName.' > value')->eq($randomIndex);
    }

    /**
     * Guarda la referencia a la sentencia generada.
     * 
     * @var phpQueryObject $generatedSentence
     */
    private $generatedSentence;

    /**
     * Devuelve la sentencia generada.
     * 
     * @return phpQueryObject
     */
    public function getGeneratedSentence()
    {
        return $this->generatedSentence;
    }

    /**
     * El documento xml q contiene la/las gramaticas
     *
     * @var phpQueryObject xmlDoc
     */
    private $xmlDoc;

    /**
     * El objecto q contiene la gramatica a utilizar
     *
     * @var phpQueryObject cfgrammar
     */
    private $cfgrammar;

    /**
     * El nombre de la gramatica q se utilizara.
     * 
     * @var string $cfgrammarName
     */
    private $cfgrammarName;
    
    /**
     * El nombre de la etiqueta que sirve de inicio para la gramatica
     * 
     * @var string $startTagName
     */
    private $startTagName = 'start';

    public function setStartTagName($name)
    {
        if ($name == null || empty($name))
            throw new Exception("param: name: null o empty");
        $this->startTagName = $name;
    }

    /**
     * Controla el numero de recursiones
     * @var int $numRecurtions
     */
    private $numRecurtions = 5;

    public function setNumRecurtions($num)
    {
        $this->numRecurtions = $num;
    }

}
?>
