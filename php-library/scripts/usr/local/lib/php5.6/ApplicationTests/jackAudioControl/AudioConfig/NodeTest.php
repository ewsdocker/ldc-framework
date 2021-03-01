<?php
namespace ApplicationTests\jackAudioControl\AudioConfig;

use ApplicationTests\jackAudioControl\ConfigTest;

use Application\jackAudioConfig\AudioConfig\Node;
use Application\jackAudioConfig\AudioConfig\Exception;

use \Application\Launcher\Testing\UtilityMethods;

use Library\Error;
use Library\Properties;
use Library\Config;
use Library\Config\SectionTree;
use Library\CliParameters;

/*
 * 		ApplicationTests\jackAudioControl\AudioConfig\NodeTest is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, 
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 *
 * AudioConfig\NodeTest.
 *
 * AudioConfig\Node class tests.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package jackAudioControl
 * @subpackage AudioConfig.
 */

class NodeTest extends ConfigTest
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

		$configGroup = CliParameters::parameterValue('group', 'Audio');
    	$audioSystem = CliParameters::parameterValue('system', 'Radio');

    	$this->a_getNodes($configGroup, $audioSystem);

    	$this->a_rewindNodes();
    	
    	while ($this->a_node !== null)
    	{
    		$this->a_newNode($this->a_node->key(), 'Application\jackAudioControl\AudioConfig\Node');
    		
    		$this->a_processName();

    		$this->a_startSequence();
    		$this->a_startCommand();

    		$this->a_stopSequence();
    		$this->a_stopCommand();

    		$this->a_deleteNode();

    		$this->a_nextNodes();
    	}
    }

    /**
     * a_startSequence
     *
     * get the process start sequence number
     */
    public function a_startSequence()
    {
    	$this->labelBlock('Start Sequence.', 40, '*');

    	$assertion = '$this->a_data = $this->a_currentNode->startSequence();';
		if (! $this->assertTrue($assertion, sprintf("Start Sequence - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->a_showData($this->a_data, 'Start Sequence');
    }

    /**
     * a_startCommand
     *
     * get the process start command string
     */
    public function a_startCommand()
    {
    	$this->labelBlock('Start Command.', 40, '*');

    	$assertion = '$this->a_data = $this->a_currentNode->startCommand();';
		if (! $this->assertTrue($assertion, sprintf("Start Command - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->a_showData($this->a_data, 'Start Command');
    }

    /**
     * a_stopSequence
     *
     * get the process stop sequence number
     */
    public function a_stopSequence()
    {
    	$this->labelBlock('Stop Sequence.', 40, '*');

    	$assertion = '$this->a_data = $this->a_currentNode->stopSequence();';
		if (! $this->assertTrue($assertion, sprintf("Stop Sequence - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->a_showData($this->a_data, 'Stop Sequence');
    }

    /**
     * a_stopCommand
     *
     * get the process stop command string
     */
    public function a_stopCommand()
    {
    	$this->labelBlock('Stop Command.', 40, '*');

    	$assertion = '$this->a_data = $this->a_currentNode->stopCommand();';
		if (! $this->assertTrue($assertion, sprintf("Stop Command - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->a_showData($this->a_data, 'Stop Command');
    }

    /**
     * a_processName
     *
     * get the name of the process
     */
    public function a_processName()
    {
    	$this->labelBlock('Process Name.', 40, '*');

    	$assertion = '$this->a_data = $this->a_currentNode->processName();';
		if (! $this->assertTrue($assertion, sprintf("Process Name - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->a_showData($this->a_data, 'Process Name');
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
		if (! $this->assertTrue($assertion, sprintf("Delete Node - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
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

    	$assertion = '$this->a_currentNode = new \Application\jackAudioControl\AudioConfig\Node($this->a_nodeName, $this->a_nodes->{$this->a_nodeName});';
		if (! $this->assertTrue($assertion, sprintf("New Node - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->a_compareExpectedType(true, $expected, get_class($this->a_currentNode));
    }

    /**
     * a_nextNodes
     * 
     * get the next nodes pointer 
     */
    public function a_nextNodes()
    {
    	$this->labelBlock('nextNodes', 40, '*');

       	$assertion = '$this->a_node = $this->a_nodes->nextNode();';
		if (! $this->assertTrue($assertion, sprintf("rewindNodes - Asserting: %s", $assertion)))
		{
			if ($this->a_node !== null)
			{
				$this->a_outputAndDie();
			}
		}

		$this->a_showData($this->a_node, 'node');
		if ($this->a_node !== null)
		{
			$this->a_showData((string)$this->a_node, 'node Tree');
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
		if (! $this->assertTrue($assertion, sprintf("rewindNodes - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_showData($this->a_node, 'node');
		$this->a_showData((string)$this->a_node, 'node Tree');
    }

    /**
     * a_getNodes
     *
     * Point a_nodes to the configGroup, subgroup audioSystem tree root
     * @param string $group = configuration group name (default = 'Audio')
     * @param string $System = audio system name (default = 'Radio')
     */
    public function a_getNodes($configGroup='Audio', $audioSystem='Radio')
    {
    	$this->labelBlock('Get Node Tree', 40, '*');

    	$this->a_configGroup = $configGroup;
    	$this->a_audioSystem = $audioSystem;

    	$this->a_showData($this->a_configGroup, 'ConfigGroup');
    	$this->a_showData($this->a_audioSystem, 'AudioSystem');

    	$assertion = '$this->a_nodes = $this->a_treeConfig->{$this->a_configGroup}->{$this->a_audioSystem};';
		if (! $this->assertTrue($assertion, sprintf("getNodes - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_compareExpectedType(true, "Library\Config\SectionTree", get_class($this->a_nodes));
		$this->a_displayConfig($this->a_nodes);
    }

}
