<?php
namespace Tests\LLRBTree;
use Library\LLRBTree\LLRBNode;

/*
 * 		LLRBTree\LLRBNodeTest is copyright � 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, 
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 *
 * LLRBNodeTest.
 *
 * Sample application to test the LLRBNode class.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests.
 * @subpackage LLRBTree.
 */

class LLRBNodeTest extends \Application\Launcher\Testing\UtilityMethods
{
	/**
	 * __construct
	 *
	 * Class constructor - pass parameters on to parent class
	 */
	public function __construct()
	{
		parent::__construct();
		$this->assertSetup(1, 0, 0, array('Application\Launcher\Testing\Base', 'assertCallback'));
	}

	/**
	 * __destruct
	 *
	 * Class destructor.
	 */
	public function __destruct()
	{
		parent::__destruct();
	}

	/**
     * assertionTests
     *
     * run the current assertion test steps
     * @parm string $logger = (optional) name of the logger to use, null for none 
     */
    public function assertionTests($logger=null, $format=null)
    {
    	parent::assertionTests($logger, $format);

    	$this->a_newLLRBNode('node1', 1);
    	$this->a_node1 = $this->a_node;

    	$this->a_printNode($this->a_node1);
    	$this->a_deleteLLRBNode($this->a_node1);

    	$this->a_newLLRBNode('california', 1);
    	$this->a_node1 = $this->a_node;

    	$this->a_newLLRBNode('arizona', 2);
    	$this->a_node2 = $this->a_node;

    	$this->a_newLLRBNode('virginia', 3);
    	$this->a_node3 = $this->a_node;

    	$this->a_set($this->a_node1, 'left', $this->a_node2);
    	$this->a_set($this->a_node1, 'right', $this->a_node3);
		$this->a_set($this->a_node1, 'flag', LLRBNode::RED);

    	$this->a_get($this->a_node1, 'key', 'california');
    	$this->a_get($this->a_node1, 'data', 1);
		$this->a_get($this->a_node1, 'left', $this->a_node2);
		$this->a_get($this->a_node1, 'right', $this->a_node3);

		$this->a_printNode($this->a_node1);

    	$this->a_compareNodes($this->a_node1, $this->a_node2, -1);
    	$this->a_compareNodes($this->a_node1, $this->a_node3, 1);
    	$this->a_compareNodes($this->a_node1, $this->a_node1, 0);

    	$this->a_deleteLLRBNode($this->a_node3);
    	$this->a_deleteLLRBNode($this->a_node2);
    	$this->a_deleteLLRBNode($this->a_node1);
    }

    public function a_compareNodes($node1, $node2, $expected)
    {
    	$this->labelBlock('Compare Nodes.', 40, '*');

    	$this->a_node1 = $node1;
    	$this->a_node2 = $node2;
    	$assertion = '(($this->a_data = $this->a_node1->compare($this->a_node2)) !== null);';
		if (! $this->assertTrue($assertion, sprintf("Compare Nodes - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->assertLogMessage('$this->a_data = ' . (string)$this->a_data);
		$this->a_compareExpected($expected);
    }

    /**
     * a_get
     *
     * get a node's property value
     * @param LLRBNode $node = node to get property from
     * @param string $property = property name to get
     * @param mixed $expected = expected value to be returned
     */
    public function a_get($node, $property, $expected)
    {
    	$this->labelBlock('Get.', 40, '*');

    	$this->a_node = $node;
//$this->a_data = $this->a_node->$property;

    	$assertion = sprintf('$this->a_data = $this->a_node->%s;', $property);
		if (! $this->assertTrue($assertion, sprintf("Get - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->assertLogMessage('$this->a_data = ' . (string)$this->a_data);
		$this->a_compareExpected($expected);
    }

    /**
     * a_set
     *
     * Set a node's property value
     * @param LLRBNode $node = node to set property in
     * @param string $property = property name to set
     * @param mixed $value = value to set property to.
     */
    public function a_set($node, $property, $value)
    {
    	$this->labelBlock('Set.', 40, '*');

    	$this->a_node = $node;
		$this->a_value = $value;

    	$assertion = sprintf('$this->a_node->%s = $this->a_value;', $property);
		if (! $this->assertTrue($assertion, sprintf("Set - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
    }

    /**
     * a_printNode
     *
     * Print the nodes contents
     * @param LLRBTree\LLRBNode $node = node to print
     */
    public function a_printNode($node)
    {
    	$this->labelBlock('Print Node.', 40, '*');

    	$this->a_node = $node;

    	$assertion = '$this->a_buffer = (string)$this->a_node';
		if (! $this->assertTrue($assertion, sprintf("Print Node - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->assertLogMessage(sprintf("%s\n", $this->a_buffer));
    }

	/**
     * a_newLLRBNode
     *
     * Create a new LLRBNode instance
     */
    public function a_newLLRBNode($key, $data)
    {
    	$this->labelBlock('Creating NEW LLRBNode object.', 40, '*');

    	$this->a_data = $data;
   		$assertion = sprintf('$this->a_node = new \Library\LLRBTree\LLRBNode("%s", $this->a_data);', $key);

		if (! $this->assertTrue($assertion, sprintf("NEW LLRBNode object - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
    }

    /**
     * a_deleteLLRBNode
     *
     * Delete the current LLRBNode object.
     */
    public function a_deleteLLRBNode(&$node)
    {
    	$this->labelBlock('Delete LLRBNode object.', 40, '*');

    	$node = null;
    }

}
    