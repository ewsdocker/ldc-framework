<?php
namespace Library\PrintU;
use Library\Error;
use Library\Utilities\FormatVar;

/*
 *		Output is copyright � 2012, 2015. EarthWalk Software.
 *		Licensed under the Academic Free License version 3.0.
 * 		Refer to the file named License.txt provided with the source, 
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 * Output.
 *
 * Unified printing class.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage PrintU.
 */
class Output
{
	/**
	 * printRequests
	 *
	 * Number of print requests handled by this class instance
	 * @var integer $printRequests
	 */
	private 	$printRequests;

	/**
	 * outputDevice
	 *
	 * Currently selected output device
	 * @var device $outputDevice
	 */
	private		$outputDevice;

	/**
	 * outputDeviceName
	 *
	 * Currently selected output device name
	 * @var string $outputDeviceName
	 */
	private		$outputDeviceName;

	/**
	 * outputFileName
	 *
	 * Name of the output file name, if appropriated for the selected device
	 * @var string $outputFileName
	 */
	private		$outputFileName;

	/**
	 * initialized
	 *
	 * Initialization flag = true if initialized
	 * @var boolean $initialized
	 */
	private		$initialized;

	/**
	 * printBuffer
	 *
	 * output to the internal buffer, for latter use via PrintU::getBuffer()
	 * @var string $printBuffer
	 */
	private		$printBuffer;

	/**
	 * maxRetries
	 *
	 * Maximum of retries for fwrite operations before aborting
	 * @var integer $maxRetries
	 */
	private		$maxRetries;

	/**
	 * webInterface
	 * 
	 * The interface type
	 * @var integer $webInterface
	 */
	private		$webInterface;

	/**
	 * __construct
	 * 
	 * Class constructor
     * @param string $outDevice = device to use for output.
	 */
	public function __construct($outDevice=null)
	{
		$this->outputDevice = null;
		$this->outputDeviceName = null;
		$this->outputFileName = null;

		$this->printBuffer = '';
		$this->printRequests = 0;

		$this->webInterface = \Library\PrintU::INTERFACE_CONSOLE;

		$this->maxRetries = 10;

		$this->initialized = false;
		
		if ($outDevice !== null)
		{
			$this->setOutputDevice($outDevice);
		}
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
     * printLine
     *
     * Output message to the print device followed by an end-of-line sequence.
     * @param string $message = (optional) message to print
     * @throws PrintU\Exception
     */
    public function printLine($message='')
    {
    	if ($this->webInterface === \Library\PrintU::INTERFACE_WEB)
    	{
    		$message  .= '<br>';
    	}

      	$this->printMessage($message . PHP_EOL);
    }

    /**
     * printMessage.
     *
     * Output the message to the current print device.
     * @param string $message = (optional) message to print
     * @throws PrintU\Exception
     */
	public function printMessage($message='')
	{
		if (! $this->initialized)
		{
			$this->resetOutputDevice();
		}

		$this->printRequests++;

		if ($this->outputDevice == \Library\PrintU::BUFFER)
		{
			$this->printBuffer .= $message;
			return;
		}

		$retries = 0;
		$written = 0;
		$messageLength = strlen($message);

		$bytesWritten = 0;
		do
		{
			if ((($written = @fwrite($this->outputDevice, $message)) === false) ||
			    (($written == 0) && ($this->maxRetries != 0) && (++$retries > $this->maxRetries)))
			{
				throw new Exception(Error::code('FileWriteError'));
			}

			if ($written !== 0)
			{
				$retries = 0;
			}

			$bytesWritten += $written;
		}
		while ($bytesWritten < $messageLength);

	}

	/**
     * printArray.
     *
     * Print the array on the current output device.
     * @param array $array          = array to print
     * @param string $arrayName     = (optional) name of the array (if formatOutput)
     * @param boolean $sort         = (optional) true to sort (default = don't sort)
     * @param boolean $sortValues    = (optional) if $sort, sort by value if true, sort by key if false
     * @throws PrintU\Exception
     */
   	public function printArray($array, $arrayName=null, $sort=false, $sortValues=false)
    {
		FormatVar::sort($sort);
		FormatVar::sortValues($sortValues);
		FormatVar::recurse(true);

    	$this->printLine(FormatVar::format($array, $arrayName));
    }

	/**
     * selectInterface.
     *
     * Set the interface for output messages.
     * @param integer $interfaceType = (optional) interface type (0 = undefined/invalid, 1 = console, 2 = web, 3 = buffer)
     * @return integer $interfaceType
     */
	public function selectInterface($interfaceType=1)
	{
		return $this->webInterface = $interfaceType;
	}

    /**
     * resetOutputDevice.
     *
     * Set the output device.  Defaults to 'php://output'.
     * @param string $outDevice = device to use for output
     * @throws PrintU\Exception
     */
	public function resetOutputDevice($outDevice='php://output')
	{
		$this->outputDeviceName = $outDevice;

		$this->initialized = false;

		try
		{
			$this->setOutputDevice($this->outputDeviceName);
		}
		catch(Exception $exception)
		{
			if (($this->outputDeviceName != 'STDOUT') && ($this->outputDeviceName != 'php://output') && ($this->outputDeviceName != \Library\PrintU::BUFFER))
			{
				throw new Exception($exception->getMessage(), $exception->getCode(), $exception->getPrevious());
			}
		}

		$this->initialized = true;
	}

	/**
	 * getOutputDevice
	 *
	 * get the current outputDevice
	 * @return mixed|resource $outputDevice
	 */
	public function getOutputDevice()
	{
		return $this->outputDevice;
	}

	/**
	 * getOutputDeviceName
	 *
	 * get the current outputDevice name ($outDevice)
	 * @return string $outDevice
	 */
	public function getOutputDeviceName()
	{
		return $this->outputDeviceName;
	}

	/**
     * setOutputDevice.
     *
     * Set the output device.  Defaults to STDOUT.
     * @param string $outDevice = device to use for output.
     * @return boolean true
     * @throws \Library\PrintU\Exception
     */
	public function setOutputDevice($outDevice)
	{
		if ($this->outputDevice)
		{
			if ($this->outputDevice !== \Library\PrintU::BUFFER)
			{
				@fclose($this->outputDevice);
			}

			$this->outputDevice = null;
		}

		switch ($outDevice)
		{
		case STDIN:
		case 'STDIN':
			throw new Exception(Error::code('InvalidFileMode'));

		case STDOUT:
		case 'STDOUT':
			$this->outputFileName = 'php://output';
			break;

		case STDERR:
		case 'STDERR':
			$this->outputFileName = 'php://stderr';
			break;

		case \Library\PrintU::BUFFER:
			$this->printBuffer = '';
			$this->outputDevice = \Library\PrintU::BUFFER;
			return;

		default:
			if (! is_string($outDevice))
			{
				if ($outDevice)
				{
					$this->outputDevice = $outDevice;
					return;
				}

				$outDevice = 'php://output';
			}

			$this->outputFileName = $outDevice;
			break;
		}

		if (($this->outputDevice  = @fopen($this->outputFileName, 'w')) === false)
		{
			$this->outputFileName = 'php://output';
			if (($this->outputDevice = @fopen($this->outputFileName, 'w')) === false)
			{
				throw new Exception(Error::code('FileOpenError'));
			}
		}
	}

    /**
     * printRequests
     *
     * get the number of print requests received
     * @return integer $printrequests
     */
	public function printRequests()
	{
		return $this->printRequests;
	}

	/**
	 * getBuffer
	 *
	 * get a copy of the print buffer
	 * @return string $printBuffer
	 */
	public function getBuffer()
	{
		return $this->printBuffer;
	}

	/**
	 * maxRetries
	 *
	 * set/get the max retries allowed for a fwrite operation to complete
	 * @param integer $maxRetries = (optional) maximum retries, null to query
	 * @return integer $maxRetries
	 */
	public function maxRetries($maxRetries=null)
	{
		if ($maxRetries !== null)
		{
			$this->maxRetries = $maxRetries;
		}

		return $this->maxRetries;
	}

}
