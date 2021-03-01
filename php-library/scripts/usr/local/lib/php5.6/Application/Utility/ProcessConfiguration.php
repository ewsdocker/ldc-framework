<?php
namespace Application\Utility;

use Application\Utility\Exception;
use Application\Utility\Support;

use Library\Properties;
use Library\CliParameters;
use Library\Config;
use Library\Error;
use Library\Error\Handler as ErrorHandler;
use Library\Exception\Descriptor as ExceptionDescriptor;
use Library\Directory\SetPaths;
use Library\Stack\Queue as StackQueue;

/*
 *		Application\Utility\ProcessConfiguration is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source,
 *         or from http://opensource.org/licenses/academic.php
*/

/**
 * Application\Utility\ProcessConfiguration
 *
 * Set up the process runtime environment.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Application
 * @subpackage Utility
 */
class ProcessConfiguration
{
	/**
	 * phase
	 *
	 * The setup phase (0 = start setup, 1 = error handling installed, 2 = completed)
	 * @var integer $phase
	 */
	public	$phase;

	/**
	 * errorHandler
	 *
	 * Instance of the Error\Handler object
	 * @var object $errorHandler
	 */
	public	$errorHandler;

	/**
	 * configTree
	 *
	 * The selected configuration tree
	 * @var object $configTree
	 */
	public	$configTree;

	/**
	 * defaultProperties
	 *
	 * Default properties settings
	 * @var Properties $defaultProperties
	 */
	public $defaultProperties;

	/**
	 * properties
	 *
	 * Properties object containing default settings + cli option settings
	 * @var object $properties
	 */
	public $properties;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param array $properties = default properties array
	 * @param array $cliOptions = option to property mapping array
	 * @throws Exception
	 */
	public function __construct($defaults, $cliOptions)
	{
		$root = $defaults['Install_Root'];
		$this->loadClass($root . 'Library/Properties.php');
		$this->loadClass($root . 'Library/CliParameters.php');
		$this->loadClass($root . 'Library/Config.php');
		$this->loadClass($root . 'Library/Directory/SetPaths.php');
		$this->loadClass($root . 'Library/Exception/Descriptor.php');
		$this->loadClass($root . 'Application/Utility/Support.php');

		$this->error = false;
		$this->errorHandler = null;
		$this->configTree = null;

		$this->phase = 0;

		CliParameters::initialize();

		$this->defaultProperties = new Properties($defaults);

		$this->properties = $this->mapOptionsToProperties($this->defaultProperties, $cliOptions);
		Support::properties($this->properties);

		$this->properties->Exception_Descriptor = array();

		$this->initialize();
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
	 * initialize
	 *
	 * initialize the program
	 * @return integer $initialized
	 */
	protected function initialize()
	{
		if ($this->properties->Execute_Type !== 'production')
		{
			error_reporting(E_ALL);
			ini_set('display_errors', '1');
		}

		$this->properties->Config_Mode	= 'r';

		$this->initializeProcessProperties();
		$result = $this->initializeConfiguration();
	}

	/**
	 * initializeProcessProperties
	 *
	 * Initialize the supervising process properties
	 * @return object $properties
	 */
	protected function initializeProcessProperties()
	{
		$this->properties->Process_Name = $_SERVER['argv'][0];
		$this->properties->Process_Namespace = __NAMESPACE__;

		$nsDir = explode('\\', $this->properties->Process_Namespace);

		$appDirs = explode(DIRECTORY_SEPARATOR, __FILE__);

		$index = count($appDirs) - 1;

		$this->properties->Process_Program = substr($appDirs[$index], 0, stripos($appDirs[$index], '.'));
		$this->properties->Process_Name = $this->properties->Process_Namespace . '\\' . $this->properties->Process_Program;

		$index = 0;
		for($index = 0; $index < count($appDirs); $index++)
		{
			if ($appDirs[$index] == $nsDir[0])
			{
				break;
			}
		}

		$this->properties->Root_Path = implode(DIRECTORY_SEPARATOR, array_slice($appDirs, 0, $index));
		$this->properties->Install_Path	= $this->properties->Root_Path . DIRECTORY_SEPARATOR . $nsDir[0];
		$this->properties->NS_Path = $this->properties->Root_Path . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $nsDir);

		$this->properties->Config_Source = '';

		if (strpos($this->properties->Config_Folder, '/', 0) !== 0)
		{
			$this->properties->Config_Source = $this->properties->Root_Path;
		}

		$this->properties->Config_Source = sprintf('%s/%s/%s.%s',
												   $this->properties->Config_Source,
												   $this->properties->Config_Folder,
												   $this->properties->Config_FileName,
												   $this->properties->Config_Adapter);

		$this->properties->FileIO_Source = $this->properties->Config_Source;

		$index = count($appDirs) - 1;

		$this->properties->Process_Program = substr($appDirs[$index], 0, stripos($appDirs[$index], '.'));
		$this->properties->Init_ErrorHandler = true;
	}

	/**
	 * initializeConfiguration
	 *
	 * Initialize the configuration data from the configuration file.
	 * @return boolean true = successful, false = some kind of an error occurred
	 */
	protected function initializeConfiguration()
	{
		$this->error = false;

		$this->properties->Config_Section = strtolower($this->properties->Execute_Type);
		$this->properties->Config_Instance = new Config($this->properties->Config_Source, $this->properties->Config_Section, $this->properties);

		$this->properties->Config_Instance->section($this->properties->Config_Section);

		$this->setPaths = new SetPaths($this->properties->NS_Path, $this->properties->Install_Path, $this->properties->Root_Path);

		if (! $this->setPaths->searchPaths())
		{
			return false;
		}

		$autoloaders = $this->setAutoloaders();

		$this->phase = 1;
		$descriptor = null;

		try
		{
			$this->properties->Config_Tree = $this->properties->Config_Instance->load();
		}
		catch(\Library\Config\Exception $exception)
		{
			$descriptor = new ExceptionDescriptor($exception);
		}
		catch(\Library\DOM\Exception $exception)
		{
			$descriptor = new ExceptionDescriptor($exception);
		}
		catch(\Library\FileIO\Exception $exception)
		{
			$descriptor = new ExceptionDescriptor($exception);
		}
		catch(\Library\Exception $exception)
		{
			$descriptor = new ExceptionDescriptor($exception);
		}
		catch(\Exception $exception)
		{
			$descriptor = new ExceptionDescriptor($exception);
		}

		if ($descriptor)
		{
			array_push($this->properties->Exception_Descriptor, $descriptor);

			print "Exception: " . (string)$descriptor . "\n";
			return false;
		}

		date_default_timezone_set($this->properties->Config_Tree->TimeZone);

		if ($this->properties->Verbose === '')
		{
			$this->properties->Verbose = $this->properties->Config_Tree->Verbose;
		}

		$skipPaths = array('.', $this->properties->NS_Path, $this->properties->Install_Path, $this->properties->Root_Path);
		$this->properties->Roots = $this->setPaths->addRoots($this->properties->Config_Tree->Roots, $skipPaths);

		$this->setErrorHandling();
		$this->phase = 2;

		if ($this->properties->Config_Tree->InstallErrors->Process)
		{
			$classes = $this->properties->Config_Tree->InstallErrors->Classes;
			if (($classes = trim($classes)) !== '')
			{
				$classList = explode(',', $classes);
				if (count($classList) > 0)
				{
					foreach($classList as $className)
					{
						$class = $this->properties->Config_Tree->InstallErrors->{$className};
						new $class();
					}
				}
			}
		}

		if ($this->properties->Config_Tree->ErrorHandler->MySqliErrors)
		{
			mysqli_report(MYSQLI_REPORT_ALL);
		}

		return true;
	}

	/**
	 * loadClass
	 *
	 * Load the specified class into memory
	 * @param string $className = the name of the class to load, including relative/absolute path
	 */
	public function loadClass($className)
	{
		if (! (include $className))
    	{
    		print "Unable to load " . $className . PHP_EOL;
    		exit(1);
    	}
	}

	/**
	 * setAutoloaders
	 *
	 * register the autoLoader and libraryLoader classes
	 * @return boolean true = successful, false = unsuccessful
	 */
	protected function setAutoloaders()
	{
		if (! class_exists('\Library\Autoload\Simple', false))
		{
			if (! include $this->properties->Root_Path . '/Library/Autoload/Simple.php')
			{
				return false;
			}
		}

		\Library\Autoload\Simple::instantiate();
		$this->properties->SPL_AutoLoader = \Library\Autoload::instantiate();

		try
		{
			\Library\Autoload\Spl::unRegister(array(\Library\Autoload\Simple::instantiate(), "loader"));
		}
		catch(\Library\Autoload\Exception $exception)
		{
		}

		$loaderName = '\Library\Autoload\Libraries';
		$this->properties->SPL_Libraryloader = $loaderName::instantiate($this->properties->Roots);

		return true;
	}

	/**
	 * setErrorHandling
	 *
	 * Set the error handling level for development (leave unmodified to use production settings)
	 */
	protected function setErrorHandling()
	{
		if ($this->properties->Init_ErrorHandler && ($this->properties->Execute_Type != 'production') && (! $this->errorHandler))
		{
			$this->errorHandler = new ErrorHandler();

			$configTree = $this->properties->Config_Tree;

			$this->errorHandler->reportErrors($configTree->ErrorHandler->ReportErrors);
			$this->errorHandler->formatXML($configTree->ErrorHandler->FormatXML);
			$this->errorHandler->displayErrors($configTree->ErrorHandler->DisplayErrors);

			$this->errorHandler->enableExceptions($configTree->ErrorHandler->EnableExceptions);
			$this->errorHandler->logErrors($configTree->ErrorHandler->LogErrors);
			$this->errorHandler->logLevel($configTree->ErrorHandler->LogLevel);

			$this->errorHandler->setHandler(E_ALL | E_PARSE | E_COMPILE_ERROR | E_RECOVERABLE_ERROR);
			$this->errorHandler->logDevice(ErrorHandler::LOG_HANDLER);

			$this->errorHandler->initialize();

			$this->errorHandler->queueDevice(new StackQueue('ErrorQueue'));
			$this->errorHandler->queueErrors(true);

			$this->properties->Error_Handler = $this->errorHandler;

			Error::instance();
		}
	}

	/**
     * properties
     *
     * get/set properties object
     * @param object $properties = (optional) properties object, null to query
     * @return object $properties
     */
    public function properties($properties=null)
    {
    	if (($properties !== null) && (($properties != $this->properties) || ($properties != Support::properties())))
    	{
    		$this->properties = Support::properties($properties);
    	}

    	return $this->properties;
    }

	/**
	 * autoLoader
	 *
	 * get the setup autoLoader object
	 * @return object $autoLoader
	 */
	public function autoLoader()
	{
		return $this->properties->SPL_Autoloader;
	}

	/**
	 * libraryLoader
	 *
	 * get the setup libraryLoader object
	 * @return object $libraryLoader
	 */
	public function libraryLoader()
	{
		return $this->properties->SPL_Libraryloader;
	}

	/**
	 * errorHandler
	 *
	 * get the setup ErrorHandler object instance
	 * @return object Library\Error\Handler
	 */
	public function errorHandler()
	{
		return $this->errorHandler;
	}

	/**
	 * phase
	 *
	 * Get the setup phase completed.
	 * @return integer $phase
	 */
	public function phase()
	{
		return $this->phase;
	}

	/**
	 * mapOptionsToProperties
	 *
	 * Using the cliOptions array, map cli input into property values, with default settings from the $defaultProperties values
	 * @param object $defaultProperties = Properties object containing default settings for each option
	 * @param array $cliOptions = option name to property name mapping array
	 * @return object $properties = Properties object containing the result.
	 */
	protected function mapOptionsToProperties(Properties $defaultProperties, array $cliOptions)
	{
		$properties = new Properties($defaultProperties);

		foreach($cliOptions as $option => $property)
		{
			if (CliParameters::parameterExists($option))
			{
				$properties->{$property} = CliParameters::parameterValue($option, $properties->{$property});
			}
		}

		return $properties;
	}

}
