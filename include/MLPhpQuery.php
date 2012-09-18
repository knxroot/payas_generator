<?php
/* 
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This library is distributed in the hope that it will be useful,
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
 * Description of MLPhpQuery
 *
 * @author arch
 */
class MLPhpQuery extends phpQuery {


	/**
	 * Creates new document from markup.
	 * Chainable.
	 *
	 * @param unknown_type $markup
	 * @return mixed
	 */
	public static function getDIdNewDocumentFileHTML($file, $charset = null) {
		$contentType = $charset
			? ";charset=$charset"
			: '';
		return self::getDIdNewDocumentFile($file, "text/html{$contentType}");
	}
	/**
	 * Creates new document from markup.
	 * Chainable.
	 *
	 * @param unknown_type $markup
	 * @return mixed
	 */
	public static function getDIdNewDocumentFileXML($file, $charset = null) {
		$contentType = $charset
			? ";charset=$charset"
			: '';
		return self::getDIdNewDocumentFile($file, "text/xml{$contentType}");
	}

    /**
	 * Creates new document from markup.
	 * Chainable.
	 *
	 * @param unknown_type $markup
	 * @return phpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
	 */
	public static function getDIdNewDocumentHTML($markup = null, $charset = null) {
		$contentType = $charset
			? ";charset=$charset"
			: '';
		return self::createDocWrapper($markup, "text/html{$contentType}");
	}
    /**
	 * Creates new document from markup.
	 * Chainable.
	 *
	 * @param unknown_type $markup
	 * @return mixed
	 */
	public static function getDIdNewDocumentXML($markup = null, $charset = null) {
		$contentType = $charset
			? ";charset=$charset"
			: '';
		return self::createDocWrapper($markup, "text/xml{$contentType}");
	}

    public static function  createDocWrapper($html, $contentType = null, $documentID = null) {
        return parent::createDocumentWrapper($html, $contentType, $documentID);
    }
    /**
	 * Creates new document from file $file.
	 * Chainable.
	 *
	 * @param string $file URLs allowed. See File wrapper page at php.net for more supported sources.
	 * @return phpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
	 */
	public static function newDocumentFile($file, $contentType = null) {
		$documentID = self::createDocumentWrapper(
			file_get_contents($file), $contentType
		);
		return new CmsTemplate($documentID);
	}


    public static function getDIdNewDocumentFile($file = null, $contentType = null) {
        $documentID = self::createDocumentWrapper(
			file_get_contents($file), $contentType
		);
		return $documentID;
    }
    /**
	 * Creates new document from markup.
	 * Chainable.
	 *
	 * @param unknown_type $markup
	 * @return phpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
	 */
	public static function newDocument($markup = null, $contentType = null) {
		if (! $markup)
			$markup = '';
		$documentID = phpQuery::createDocumentWrapper($markup, $contentType);
		return new CmsTemplate($documentID);
	}

    /**
	 * Returns document with id $id or last used as phpQueryObject.
	 * $id can be retrived via getDocumentID() or getDocumentIDRef().
	 * Chainable.
	 *
	 * @see phpQuery::selectDocument()
	 * @param unknown_type $id
	 * @return phpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery
	 */
	public static function getDocument($id = null) {
		if ($id)
			phpQuery::selectDocument($id);
		else
			$id = phpQuery::$defaultDocumentID;
		return new CmsTemplate($id);
	}


    /**
	 * Multi-purpose function.
	 * Use pq() as shortcut.
	 *
	 * In below examples, $pq is any result of pq(); function.
	 *
	 * 1. Import markup into existing document (without any attaching):
	 * - Import into selected document:
	 *   pq('<div/>')				// DOESNT accept text nodes at beginning of input string !
	 * - Import into document with ID from $pq->getDocumentID():
	 *   pq('<div/>', $pq->getDocumentID())
	 * - Import into same document as DOMNode belongs to:
	 *   pq('<div/>', DOMNode)
	 * - Import into document from phpQuery object:
	 *   pq('<div/>', $pq)
	 *
	 * 2. Run query:
	 * - Run query on last selected document:
	 *   pq('div.myClass')
	 * - Run query on document with ID from $pq->getDocumentID():
	 *   pq('div.myClass', $pq->getDocumentID())
	 * - Run query on same document as DOMNode belongs to and use node(s)as root for query:
	 *   pq('div.myClass', DOMNode)
	 * - Run query on document from phpQuery object
	 *   and use object's stack as root node(s) for query:
	 *   pq('div.myClass', $pq)
	 *
	 * @param string|DOMNode|DOMNodeList|array	$arg1	HTML markup, CSS Selector, DOMNode or array of DOMNodes
	 * @param string|phpQueryObject|DOMNode	$context	DOM ID from $pq->getDocumentID(), phpQuery object (determines also query root) or DOMNode (determines also query root)
	 *
	 * @return phpQueryObject|QueryTemplatesSource|QueryTemplatesParse|QueryTemplatesSourceQuery|QueryTemplatesPhpQuery|false
   * phpQuery object or false in case of error.
	 */
	public static function pq($arg1, $context = null) {
		if ($arg1 instanceof DOMNODE && ! isset($context)) {
			foreach(phpQuery::$documents as $documentWrapper) {
				$compare = $arg1 instanceof DOMDocument
					? $arg1 : $arg1->ownerDocument;
				if ($documentWrapper->document->isSameNode($compare))
					$context = $documentWrapper->id;
			}
		}
		if (! $context) {
			$domId = self::$defaultDocumentID;
			if (! $domId)
				throw new Exception("Can't use last created DOM, because there isn't any. Use phpQuery::newDocument() first.");
//		} else if (is_object($context) && ($context instanceof PHPQUERY || is_subclass_of($context, 'phpQueryObject')))
		} else if (is_object($context) && $context instanceof CmsTemplate)
			$domId = $context->getDocumentID();
		else if ($context instanceof DOMDOCUMENT) {
			$domId = self::getDocumentID($context);
			if (! $domId) {
				//throw new Exception('Orphaned DOMDocument');
				$domId = self::newDocument($context)->getDocumentID();
			}
		} else if ($context instanceof DOMNODE) {
			$domId = self::getDocumentID($context);
			if (! $domId) {
				throw new Exception('Orphaned DOMNode');
//				$domId = self::newDocument($context->ownerDocument);
			}
		} else
			$domId = $context;
		if ($arg1 instanceof CmsTemplate) {
//		if (is_object($arg1) && (get_class($arg1) == 'phpQueryObject' || $arg1 instanceof PHPQUERY || is_subclass_of($arg1, 'phpQueryObject'))) {
			/**
			 * Return $arg1 or import $arg1 stack if document differs:
			 * pq(pq('<div/>'))
			 */
			if ($arg1->getDocumentID() == $domId)
				return $arg1;
			$class = get_class($arg1);
			// support inheritance by passing old object to overloaded constructor
			$phpQuery = $class != 'phpQuery'
				? new $class($arg1, $domId)
				: new CmsTemplate($domId);
			$phpQuery->elements = array();
			foreach($arg1->elements as $node)
				$phpQuery->elements[] = $phpQuery->document->importNode($node, true);
			return $phpQuery;
		} else if ($arg1 instanceof DOMNODE || (is_array($arg1) && isset($arg1[0]) && $arg1[0] instanceof DOMNODE)) {
			/*
			 * Wrap DOM nodes with phpQuery object, import into document when needed:
			 * pq(array($domNode1, $domNode2))
			 */
			$phpQuery = new CmsTemplate($domId);
			if (!($arg1 instanceof DOMNODELIST) && ! is_array($arg1))
				$arg1 = array($arg1);
			$phpQuery->elements = array();
			foreach($arg1 as $node) {
				$sameDocument = $node->ownerDocument instanceof DOMDOCUMENT
					&& ! $node->ownerDocument->isSameNode($phpQuery->document);
				$phpQuery->elements[] = $sameDocument
					? $phpQuery->document->importNode($node, true)
					: $node;
			}
			return $phpQuery;
		} else if (self::isMarkup($arg1)) {
			/**
			 * Import HTML:
			 * pq('<div/>')
			 */
			$phpQuery = new CmsTemplate($domId);
			return $phpQuery->newInstance(
				$phpQuery->documentWrapper->import($arg1)
			);
		} else {
			/**
			 * Run CSS query:
			 * pq('div.myClass')
			 */
			$phpQuery = new CmsTemplate($domId);
//			if ($context && ($context instanceof PHPQUERY || is_subclass_of($context, 'phpQueryObject')))
			if ($context && $context instanceof CmsTemplate)
				$phpQuery->elements = $context->elements;
			else if ($context && $context instanceof DOMNODELIST) {
				$phpQuery->elements = array();
				foreach($context as $node)
					$phpQuery->elements[] = $node;
			} else if ($context && $context instanceof DOMNODE)
				$phpQuery->elements = array($context);
			return $phpQuery->find($arg1);
		}
	}

}
?>
