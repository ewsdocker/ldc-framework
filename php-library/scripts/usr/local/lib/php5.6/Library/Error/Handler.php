<?php
namespace Library\Error;
use Library\Error\Descriptor as ErrorDescriptor;
use Library\PrintU;

/*
 *		Library\Error\Handler is copyright � 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
 */
/**
 * Library\Error\Handler.
 *
 * User error handler.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Error
 */
class Handler
{
	/**
	 *
	 * LOGGING DESTINATIONS
	 *
	 */
	const				LOG_SYSTEM 		=  0;
	const				LOG_EMAIL  		=  1;
	const				LOG_FILE 		=  3;
	const				LOG_SAPI 		=  4;
	const				LOG_HANDLER		= 10;

	/**
	 * errorType
	 *
	 * Array containing error types and definitions
	 * @var array $errorType
	 */
	protected static	$errorType = array (E_ERROR              => 'Fatal Error',
                					        E_WARNING            => 'Warning',
                    					    E_PARSE              => 'Parsing Error',
                    					    E_NOTICE             => 'Notice',
                    					    E_CORE_ERROR         => 'Core Error',
                    					    E_CORE_WARNING       => 'Core Warning',
                    					    E_COMPILE_ERROR      => 'Compile Error',
                    					    E_COMPILE_WARNING    => 'Compile Warning',
                    					    E_USER_ERROR         => 'User Error',
                    					    E_USER_WARNING       => 'User Warning',
                    					    E_USER_NOTICE        => 'User Notice',
                    					    E_STRICT             => 'Runtime Notice',
                    					    E_RECOVERABLE_ERROR  => 'Catchable Fatal Error',
                    					    E_DEPRECATED		 => 'Deprecated PHP Code or Function',
                    					    E_USER_DEPRECATED	 => 'Deprecated User Code or Function',
                   					   	    E_ALL				 => 'All Errors and Warnings.');

    /**
	 * ReportErrors
	 *
	 * Whether (true) or not (false) to report errors
	 * @var boolean $reportErrors
	 */
    protected		$reportErrors;

    /**
     * phpErrorLevel
     *
     * Error types handled by this handler
     * @var array $phpErrorLevel
     */
    protected		$phpErrorLevel;

    /**
     * logFileName
     *
     * The log file name, if required
     * @var string $logFileName
     */
    protected		$logFileName;

    /**
     * LogErrors
     *
     * Whether (true) or not (false) to report errors to the log file
     * @var boolean $logErrors
     */
    protected		$logErrors;

    /**
     * logDevice
     *
     * The device to do logging to
     * @var integer $logDevice
     */
    protected		$logDevice;

    /**
     * logLevel
     *
     * The log level to use for logging errors
     * @var integer $logLevel
     */
    protected		$logLevel;

    /**
     * displayErrors
     *
     * Whether (true) or not (false) to send errors to the console device
     * @var boolean $displayErrors
     */
    protected		$displayErrors;

    /**
     * queueErrors
     * 
     * Whether (true) or not (false) to add errors to the error queue MEMORY device
     * @var boolean $queueErrors
     */
    protected		$queueErrors;

    /**
     * queueDevice
     * 
     * A Stack\Queue to log errors to
     * @var Stack\Queue $queueDevice
     */
    protected		$queueDevice;

    /**
     * formatXml
     *
     * Whether (true) or not (false) to format the display as XML
     * @var boolean $formatXML
     */
    protected		$formatXML;

    /**
     * exceptionsEnabled
     *
     * Set true to throw exception with the error message
     * @var boolean $exceptionsEnabled
     */
    protected		$exceptionsEnabled;

    /**
     * initialized
     *
     * Initialization flag
     * @var boolean $initialized
     */
    protected		$initialized;

    /**
     * errorHandlerSet
     *
     * True if the error handler has already been set, false if not
     * @var boolean $errorHandlerSet
     */
    protected		$errorHandlerSet;

    /**
     * errorBuffer
     *
     * A string buffer to build the output error message in
     * @var string $errorBuffer
     */
    protected		$errorBuffer;

    /**
     * additionalLogs
     * 
     * An array containing additonal loggers to send message to
     * @var array $additionalLogs
     */
    protected		$additionalLogs;

    /**
     * descriptor
     * 
     * A copy of the last error message in an ErrorDescriptor containier
     * @var ErrorDescriptor
     */
    protected		$descriptor;

    /**
     * __construct
     *
     * Class constructor
     */
    public function __construct($queueDevice=null)
    {
		$this->phpErrorLevel 		= E_ALL;

		$this->reportErrors 		= true;
		$this->displayErrors 		= true;
		$this->formatXML			= true;

		$this->logErrors 			= false;

		$this->logFileName 			= '';
		$this->logDevice 			= self::LOG_HANDLER;

		$this->queueErrors 			= ($this->queueDevice($queueDevice) !== null);

		$this->additionalLogs		= array();

		$this->exceptionsEnabled	= false;

		$this->initialized			= false;
		$this->errorHandlerSet		= false;

		$this->logLevel				= 'errors';

		$this->errorBuffer			= '';
		
		$this->descriptor			= null;
    }

    /**
     * __destruct
     *
     * Class destructor
     */
    public function __destruct()
    {
    	$this->deleteHandler();
    }

    /**
     * errorHandler
     *
     * PHP User error handler
     * @param integer  $errorNumber = error type (from PHP)
     * @param string   $message     = the error message (from PHP)
     * @param string   $script      = error script producing the error (from PHP)
     * @param integer  $lineNumber  = line in the script producing the error (from PHP)
     * @param array    $context   	= variables being traced (from PHP)
     * @return boolean $result      = true => handled, false => not handled
     */
    public function errorHandler($errorNumber, $message, $script, $lineNumber, $context=null)
    {
		$timeStamp = date("Y-m-d H:i:s (T)");

		$this->descriptor = new ErrorDescriptor(array('timeStamp' 			=> $timeStamp,
												      'errorNumber'			=> $errorNumber,
													  'errorType'			=> self::$errorType[$errorNumber],
													  'message'				=> $message,
													  'script'				=> $script,
													  'scriptLineNumber'	=> $lineNumber,
		));
/*
		if (isset($context))
		{
			$this->descriptor->context = wddx_serialize_value($context);
		}
*/
    	if ((! $this->initialized) || (! $this->errorHandlerSet))
    	{
    		return false;
    	}

  	  	//
  	  	//	check if okay to ignore errors
  	  	//
  	  	//	This can be achieved by setting $reportErrors false
  	  	//
	  	if ((! $this->reportErrors)									||
	  	    ($this->phpErrorLevel == 0)			             		||
	  	    ((! $this->logErrors) && (! $this->displayErrors))		||
	  	    (! array_key_exists($errorNumber, self::$errorType))	||
	  	    (! ($this->phpErrorLevel & $errorNumber)))
	  	{
	   		return true;
	  	}

    	if ($this->queueErrors)
	  	{
	  		$this->queueDeviceHandler($this->descriptor);
	  	}

	  	$this->errorBuffer = '';

	  	if ($this->formatXML)
	  	{
        	$this->bufferLine("<errorentry>");
        	$this->bufferLine("\t<datetime>" . $this->descriptor->timeStamp . "</datetime>");
        	$this->bufferLine("\t<errornum>" . $this->descriptor->errorNumber . "</errornum>");
       	 	$this->bufferLine("\t<errortype>" . $this->descriptor->errorType . "</errortype>");
       	 	$this->bufferLine("\t<errormsg>" . $message . "</errormsg>");
        	$this->bufferLine("\t<scriptname>" . $this->descriptor->script . "</scriptname>");
        	$this->bufferLine("\t<scriptlinenum>" . $this->descriptor->scriptLineNumber . "</scriptlinenum>");

			if ($this->descriptor->exists('context'))
	    	{
          		$this->bufferLine("\t<vartrace>" . $this->descriptor->context .	"</vartrace>");
	    	}

        	$this->bufferLine("</errorentry>");
	  	}
	  	elseif ($this->displayErrors)
	    {
	      		$this->bufferLine(sprintf("Error %s(%u) = %s (Line #%u of %s)",
	                             	      $this->descriptor->errorType,
							     	      $this->descriptor->errorNumber,
							     	      $this->descriptor->message,
							     	      $this->descriptor->scriptLineNumber,
							     	      $this->descriptor->script));
	  	}

	  	if ($this->logErrors)
	  	{
	  		if ($this->logDevice == self::LOG_HANDLER)
	  		{
	  			$this->logMessage($this->errorBuffer);
	  		}
	  		else
	  		{
        		error_log($this->errorBuffer, $this->logDevice, $this->logFileName);
	  		}
	  	}

	  	if ($this->displayErrors)
	  	{
        	print $this->errorBuffer;
	  	}

	  	if ($this->exceptionsEnabled)
	  	{
	  		throw new Exception($this->errorBuffer, Library\Error::code('PhpRuntimeError'));
	  	}

	  	return true;
    }

	/**
	 * setHandler
	 *
	 * set the error_reporting handler
	 * @param integer $phpErrorLevel = (optional) error level, null to use current
	 * @return null
	 */
	public function setHandler($phpErrorLevel = null)
	{
		error_reporting(0);

		if ($phpErrorLevel !== null)
		{
			set_error_handler(array($this, "errorHandler"), 
					     $this->phpErrorLevel($phpErrorLevel));
		}
		else
		{
			set_error_handler(array($this, "errorHandler"));
		}

		$this->errorHandlerSet = true;
	}

    /**
     * isInitialized
     *
     * get the state of the initialized flag
     * @param boolean $oktoinit = (optional) ok to initialize if true and not init'ed, false = not okay if not init'ed
     * @return boolean true = initialized, false = not initialized
     */
    public function isInitialized($oktoinit=true)
    {
    	if (! $this->initialized)
    	{
    		if (! $oktoinit)
    		{
    			return false;
    		}

    		$this->initialize();
    	}

    	return true;
    }

    /**
     * initialize
     *
     * initialize (or re-initialize) the error settings
     * @return null
     */
    public function initialize()
    {
	  	if ($this->phpErrorLevel == 0)
	  	{
	  		error_reporting($this->phpErrorLevel(0));
	  	}

		if (! $this->errorHandlerSet)
		{
	  		$this->setHandler();
		}

    	$this->initialized = true;
    }

    /**
     * reportErrors
     *
     *get/set reportErrors flag
     * @param boolean $report = (optional) true = set, false = reset, null = query
     * @return boolean $reportErrors
     */
    public function reportErrors($report=null)
    {
      	if ($report !== null)
      	{
    		$this->reportErrors = $report;
      	}

      	return $this->reportErrors;
    }

    /**
     * phpErrorLevel
     *
     * get/set the phpErrorLevel
     * @param integer $phpErrorLevel = (optional) minimum error level to report, null to query
     * @return integer $phpErrorLevel
     */
    public function phpErrorLevel($phpErrorLevel=null)
    {
      	if ($phpErrorLevel !== null)
      	{
      		error_reporting($this->phpErrorLevel = $phpErrorLevel);
      	}

      	return $this->phpErrorLevel;
    }

    /**
     * errorBits
     *
     * set/reset phpErrorLevel bits
     * @param boolean $setReset = (optional) set = true (default), reset = false
     * @return integer $phpErrorLevel
     */
    public function errorBits($setReset=true)
    {
		if (func_num_args() > 1)
		{
			$arguements = array_slice(func_get_args(),1);
			foreach($arguements as $index => $value)
			{
				switch($setReset)
				{
					default:
					case true:
						$this->phpErrorLevel = $this->phpErrorLevel | $value;
						break;

					case false:
						$this->phpErrorLevel = $this->phpErrorLevel & ~$value;
						break;
				}
			}
		}

		return $this->phpErrorLevel;
    }

    /**
     * logMessage
     *
     * Write the error message to the Library\Log class
     * @param string $message = message to log
     * @throws Library\Log\Exception
     */
    protected function logMessage($message)
    {
    	\Library\Log::message($message, $this->logLevel);

    	if (count($this->additionalLogs) > 0)
    	{
    		foreach($this->additionalLogs as $logNumber => $logger)
    		{
    			$logger->logMessage($message, 'error');
    		}
    	}
    }

    /**
     * logDevice
     *
     * set/get the logDevice
     * @param integer $logDevice = (optional) log device, null to query
     * @return integer $logDevice
     */
    public function logDevice($logDevice=null)
    {
    	if ($logDevice !== null)
    	{
    		$this->logDevice = $logDevice;
    	}

    	return $this->logDevice;
    }

    /**
     * queueDevice
     *
     * set/get/reset the queueDevice
     * @param integer $queueDevice = (optional) queue device to set, null to query
     * @param bool $queueReset = (optional) queue device reset ($queueDevice = null), default = false
     * @return integer $queueDevice
     */
    public function queueDevice($queueDevice=null, $queueReset=false)
    {
    	if (($queueDevice !== null) || (($queueDevice === null) && $queueReset))
    	{
    		$this->queueDevice = $queueDevice;
    	}

    	return $this->queueDevice;
    }

    /**
     * queueDeviceHandler
     * 
     * create errorDescriptor and add to the queueDevice queue
     * @param ErrorDescriptor $descriptor = object to record
     */
    public function queueDeviceHandler(&$descriptor)
    {
    	if ($this->queueErrors && ($this->queueDevice !== null))
    	{

	    	$this->queueDevice->add($descriptor);
    	}
    }

    /**
     * queueErrors
     *
     * get/set the queueErrors flag
     * @param bolean $queueErrors = (optional) true to set, false to reset, null to query
     * @return boolean $queueErrors
     */
    public function queueErrors($queueErrors=null)
    {
      	if ($queueErrors !== null)
      	{
      		$this->queueErrors = $queueErrors;
      	}

      	return $this->queueErrors;
    }

    /**
     * additionalLog
     *
     * Add an additional log stream to log to
     * @param object $logger = additional log stream
     */
    public function additionalLog($logger)
    {
    	array_push($this->additionalLogs, $logger);
    }

    /**
     * deleteAdditionalLog
     *
     * Delete a logger from the additional log streams
     * @param object $logger = log stream to delete
     */
    public function deleteAdditionalLog($logger)
    {
    	if ($key = array_search($logger, $this->additionalLogs))
    	{
    		unset($this->additionalLogs[$key]);
    	}
    }

    /**
     * logFileName
     *
     * get/set the logfile name
     * @param string $logFileName = (optional) log file name, null to query
     * @return string $logFileName
     */
    public function logFileName($logFileName=null)
    {
      	if ($logFileName !== null)
      	{
      		$this->logFileName = $logFileName;
      	}

      	return $this->logFileName;
    }

    /**
     * logLevel
     *
     * Set/get the log level for logging
     * @param integer $level = (optional) log level to set, null to get only
     * @return integer $level
     */
    public function logLevel($level=null)
    {
    	if ($level !== null)
    	{
    		$this->logLevel = $level;
    	}

    	return $this->logLevel;
    }

    /**
     * logErrors
     *
     * get/set the logErrors flag
     * @param bolean $logErrors = (optional) true to set, false to reset, null to query
     * @return boolean $logErrors
     */
    public function logErrors($logErrors=null)
    {
      	if ($logErrors !== null)
      	{
      		$this->logErrors = $logErrors;
      	}

      	return $this->logErrors;
    }

    /**
     * displayErrors
     *
     * set/get displayErrors flag
     * @param boolean $displayErrors = (optional) true = set, false = reset, null = query
     * @return boolean $displayErrors
     */
    public function displayErrors($displayErrors=null)
    {
      	if ($displayErrors !== null)
      	{
      		if ($this->displayErrors = $displayErrors)
      		{
      			ini_set('display_errors', '1');
      		}
      		else
      		{
      			ini_set('display_errors', '0');
      		}
      	}

      	return $this->displayErrors;
    }

    /**
     * formatXML
     *
     * set/get the formatXML flag
     * @param boolean $formatXML = (optional) true = set, false = reset, null = query
     * @return boolean $formatXML
     */
    public function formatXML($formatXML=null)
    {
      	if ($formatXML !== null)
      	{
      		$this->formatXML = $formatXML;
      	}

      	return $this->formatXML;
    }

    /**
     * enableExceptions
     *
     * get/set enableExceptions flag
     * @param boolean $enableExceptions = (optional) true = set, false = reset, null = query
     * @return boolean $enableExceptions
     */
    public function enableExceptions($enableExceptions=null)
    {
      	if ($enableExceptions !== null)
      	{
    		$this->exceptionsEnabled = $enableExceptions;
      	}

      	return $this->exceptionsEnabled;
	}

	/**
	 * deleteHandler
	 *
	 * Delete this handler by restoring previous handler
	 */
	public function deleteHandler()
	{
		if ($this->errorHandlerSet)
		{
			restore_error_handler();
			$this->errorHandlerSet = false;
		}
	}

    /**
     * bufferLine
     *
     * Add a message + eol to the error buffer
     * @param string $message = (optional) message to add, empty to add EOL only
     */
    private function bufferLine($message='')
    {
    	$this->bufferMessage($message . PHP_EOL);
    }

    /**
     * bufferMessage
     *
     * Add a message to the error buffer
     * @param string $message = message to add to the buffer
     */
    private function bufferMessage($message)
    {
    	$this->errorBuffer .= $message;
    }

}
