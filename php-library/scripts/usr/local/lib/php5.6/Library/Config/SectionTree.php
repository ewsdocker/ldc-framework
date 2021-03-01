<?php
namespace Library\Config;
use Library\Error;

/*
 * 		Config\SectionTree is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * Config\SectionTree
 *
 * Tree based solution for section configuration(s).
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage Config.
 */
class SectionTree extends \Library\LLRBTree
{
	/**
	 * name
	 *
	 * the section name
	 * @var string $name
	 */
	protected	$name;

	/**
	 * level
	 * 
	 * Current tree level
	 * @var integer $level
	 */
	private		$level;

	/**
	 * extendsList
	 *
	 * @var array $extendsList
	 */
	private		$extendsList;

    /**
     * __construct
     *
     * class constructor
     * @param XML_ELEMENT_NODE $node = node to build subtree from
     */
	public function __construct(&$node, $modifyName=true)
	{
		$this->level = 0;
		$this->extendsList = array();
		$this->parse($node, $modifyName);
	}

    /**
     * __destruct
     *
     * class destructor
     */
    public function __destruct()
    {
    	parent::__destruct();
    }

    /**
     * parse
     *
     * parse the section node into $extendsList
     * @param XML_ELEMENT_NODE $node = node to parse
     * @throws \Library\Config\Exception
     */
	public function parse(&$node, $modifyName=true)
	{
		$this->level = 0;

		if ($modifyName)
		{
			$this->name = $node->localName;
		}

		if ($this->name == 'log')
		{
			$log = 1;
		}

		array_push($this->extendsList, $node->localName);

		if ($node->nodeType != \Library\DOM\XML::XML_ELEMENT_NODE)
		{
			throw new \Library\Config\Exception(Error::code('SectionNotElementNode'));
		}

		if (! $node->hasChildNodes())
		{
			throw new \Library\Config\Exception(Error::code(SectionNoChildren));
		}

		$child = $node->firstChild;
		do
      	{
			if (($child->nodeType == \Library\DOM\XML::XML_ELEMENT_NODE) && ($child->hasChildNodes()))
			{
				$firstChild = $child->firstChild;

				$firstChildName = $firstChild->localName;
				$childName = $child->localName;

				if ($firstChild->nodeType == \Library\DOM\XML::XML_TEXT_NODE)
				{
					$treeNode = $this->search($child->localName, $firstChild->nodeValue, true);
					$treeNode->data($firstChild->nodeValue);
				}
				elseif ($firstChild->nodeType == \Library\DOM\XML::XML_ELEMENT_NODE)
				{
					if (! $treeNode = $this->search($child->localName, null, false))
					{
						$treeNode = $this->insert($child->localName, new \Library\Config\SectionTree($child, true));
					}
					else
					{
						$subTree = $treeNode->data();
						$subTreeName = $treeNode->key();

						$subTree->parse($child, false);
					}
				}
			}
      	}
		while ($child = $child->nextSibling);
	}

	/**
	 * get
	 *
	 * get the value of a named variable
	 * @param string $name = name of the value to fetch
	 * @return mixed $value = fetched value, null if not found
	 */
	public function get($name)
	{
		$treeNode = $this->search($name, null, false);
		if ($treeNode === null)
		{
			return $treeNode;
		}

		return $treeNode->data();
	}

	/**
	 * __get
	 *
	 * override parent __get to allow $section->name to return the requested value
	 * @param string $name = name of the value to fetch
	 * @return mixed $value = fetched value
	 * @throws \Library\Config\Exception
	 */
	public function __get($name)
	{
		return $this->get($name);
	}

}
