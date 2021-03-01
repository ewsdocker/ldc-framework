<?php
namespace Application\jackAudioControl\AudioConfig;

use Library\Config\SectionTree as AudioNode;

/*
 *		jackAudioControl\AudioConfig\Node is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source,
 *         or from http://opensource.org/licenses/academic.php
*/
/**
 * jackAudioControl\AudioConfig\Node
 *
 * Wraps an AudioNode object to provide direct access to the node's children
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package jackAudioControl
 * @subpackage AudioConfig
 */
class Node
{
	/**
	 * treeNode
	 * 
	 * the audio element node
	 * @var AudioNode $treeNode
	 */
	private $treeNode;

	/**
	 * processName
	 * 
	 * The name of this process element
	 * @var string $processName
	 */
	private $processName;

	/**
	 * __construct
	 * 
	 * Class constructor
	 * @param string $processName = AudioElement process name
	 * @param AudioNode $treeNode = the audio element node
	 */
	public function __construct($processName, AudioNode $treeNode)
	{
		$this->processName = $processName;
		$this->treeNode = $treeNode;
	}

	/**
	 * __destruct
	 * 
	 * Class destructor
	 */
	public function __destruct()
	{
	}

	/**
	 * treeNode
	 * 
	 * Get the treeNode property
	 * @return AudioNode $treeNode = the current $treeNode at exit
	 */
	public function treeNode()
	{
		return $this->treeNode;
	}

	/**
	 * processName
	 * 
	 * Return the name of the audio process
	 * @return string $processName = processName
	 */
	public function processName()
	{
		return $this->processName;
	}

	/**
	 * startSequence
	 * 
	 * Return the start sequence number
	 * @return integer $sequence = start sequence number
	 */
	public function startSequence()
	{
		return $this->treeNode->Start->Sequence;
	}

	/**
	 * startCommand
	 * 
	 * Return the start command
	 * @return string $command = start command
	 */
	public function startCommand()
	{
		return $this->treeNode->Start->Command;
	}

	/**
	 * stopSequence
	 * 
	 * Return the stop sequence number
	 * @return integer $sequence = stop sequence number
	 */
	public function stopSequence()
	{
		return $this->treeNode->Stop->Sequence;
	}

	/**
	 * stopCommand
	 * 
	 * Return the stop commane
	 * @return string $command = stop command
	 */
	public function stopCommand()
	{
		return $this->treeNode->Stop->Command;
	}

}

