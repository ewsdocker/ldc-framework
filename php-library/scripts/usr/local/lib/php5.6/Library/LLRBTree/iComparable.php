<?php
namespace Library\LLRBTree;

/*
 * 		iComparable is copyright � 2008, 2016. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, 
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 * 
 * iComparable.
 * 
 * iComparable interface to compare two objects of the same type.
 * @author Jay Wheeler.
 * @version 2.0
 * @copyright � 2008, 2016. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library.
 * @subpackage LLRBTree.
 */
interface iComparable
{
	/**
	 * compare.
	 * 
	 * Compare compares 2 object of the same type.
	 * @param $object = object to be compared
	 * @return integer 0 = equal, 1 = >, -1 = <
	 */
	public function compare($object);
}
