<?php
namespace ApplicationTests\TVDB;

use Library\CliParameters;
use Library\Config;
use Library\Config\SectionTree;

/*
 * 		ApplicationTests\TVDB\ApplicationNodeTest is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source,
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 *
 * TVDB\ApplicationNodeTest.
 *
 * TVDB\ApplicationNode class tests.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package TVDB
 * @subpackage ApplicationNodeTest.
 */

class ApplicationNodeTest extends ApplicationConfigTest
{
	/**
	 * __construct
	 *
	 * Class constructor - pass parameters on to parent class
	 */
	public function __construct()
	{
		parent::__construct();
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

    	$this->labelBlock('BEGIN NodeTest.', 60, '=');

		$configSection = CliParameters::parameterValue('section', 'TVDB');

		$this->a_nodeTests($configSection);

   		$this->a_deleteNode();
    }

    /**
     * a_nodeTests
     *
     * run the node tests
     */
    public function a_nodeTests($configSection)
    {
    	$this->labelBlock('NodeTests.', 60, '=');

    	$this->a_getNodes($configSection);

    	$this->a_rewindNodes();

    	$this->a_newNode($this->a_configSection, 'Application\TVDB\Node');

    	$this->a_elementName();

    	$this->a_hostName();
    	$this->a_dbUser();
    }

    /**
     * a_dbUser
     *
     * get the database user name
     */
    public function a_dbUser()
    {
    	$this->labelBlock('DbUser.', 40, '*');

    	$assertion = '$this->a_data = $this->a_currentNode->DbUser;';
		if (! $this->assertTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_showData($this->a_data, 'DbUser');
    }

    /**
     * a_hostName
     *
     * get the name/ip of the host name
     */
    public function a_hostName()
    {
    	$this->labelBlock('Host Name.', 40, '*');

    	$assertion = '$this->a_data = $this->a_currentNode->DbHost;';
		if (! $this->assertTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_showData($this->a_data, 'Host Name');
    }

    /**
     * a_elementName
     *
     * get the name of the element
     */
    public function a_elementName()
    {
    	$this->labelBlock('Element Name.', 40, '*');

    	$assertion = '$this->a_data = $this->a_currentNode->elementName();';
		if (! $this->assertTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_showData($this->a_data, 'Element Name');
    }

    /**
     * a_deleteNode
     *
     * Delete the current Node class instance
     */
    public function a_deleteNode()
    {
    	$this->labelBlock('Delete Node.', 40, '*');

    	$assertion = '(($this->a_currentNode = null) !== false);';
		if (! $this->assertTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
    }

    /**
     * a_rewindNodes
     *
     * Reset the nodes pointer to the tree root
     */
    public function a_rewindNodes()
    {
    	$this->labelBlock('rewindNodes', 40, '*');

    	$this->a_node = null;
       	$assertion = '$this->a_node = $this->a_nodes->firstNode();';
		if (! $this->assertTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_showData($this->a_node, 'node');
		$this->a_showData((string)$this->a_node, 'node Tree');
    }

    /**
     * a_getNodes
     *
     * Point a_nodes to the configGroup tree root
     * @param string $group = configuration group name (default = 'TVDB')
     */
    public function a_getNodes($configSection='TVDB')
    {
    	$this->labelBlock('Get Node Tree', 40, '*');

    	$this->a_configSection = $configSection;
    	$this->a_showData($this->a_configSection, 'ConfigSection');

    	$assertion = '$this->a_nodes = $this->a_treeConfig->{$this->a_configSection};';
		if (! $this->assertTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpectedType(true, "Library\Config\SectionTree", get_class($this->a_nodes));
		$this->a_displayConfig($this->a_nodes);
    }

    /**
     * a_newNode
     *
     * Create a new Node class instance
     * @param string $nodeName = the name of the node to open
     * @param string $expected = expected class name
     */
    public function a_newNode($nodeName, $expected)
    {
    	$this->labelBlock('New Node.', 40, '*');

		$this->a_expected = $expected;
		$this->a_nodeName = $nodeName;

	  	$this->a_showData($this->a_expected, 'Expected');
		$this->a_showData($this->a_nodeName, 'nodeName');

		$assertion = '$this->a_currentNode = new \Application\TVDB\Node($this->a_nodeName, $this->a_nodes);';
		if (! $this->assertTrue($assertion, sprintf("Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpectedType(true, $expected, get_class($this->a_currentNode));

		$this->a_showData(sprintf("\n%s", $this->a_currentNode), $nodeName);
    }

}
