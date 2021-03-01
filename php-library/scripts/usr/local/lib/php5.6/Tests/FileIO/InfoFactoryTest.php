<?php
namespace Tests\FileIO;
use Library\FileIO\FileInfo;

/*
 *		InfoFactoryTest is copyright � 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source, 
 *         or from http://opensource.org/licenses/academic.php
*/
/**
 * InfoFactoryTest
 *
 * FileIO FileInfo tests.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012, 2013 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests
 * @subpackage FileIO
 */
class InfoFactoryTest extends \Application\Launcher\Testing\UtilityMethods
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

    	$this->a_fileInfo = null;

    	$this->labelBlockFlag(true);

    	$this->a_fileName_CSV	= 'Tests/FileIO/Files/TestCSV.txt';
    	$this->a_fileName_TXT	= 'Tests/FileIO/Files/TestText.txt';
    	$this->a_fileName_XML	= 'Tests/FileIO/Files/TestXML.txt';
		$this->a_fileName_HTML	= 'Tests/FileIO/Files/TestHTML.txt';
		$this->a_fileName_LOCK	= 'Tests/FileIO/Files/TestLock.txt';
		$this->a_fileName_PHP	= 'Tests/FileIO/InfoFactoryTest.php';

		if (! $this->a_infoName = \Library\CliParameters::parameterValue('info', false))
		{
			$this->assertLogMessage("No info class specified for class factory");
			$this->a_outputAndDie();
		}

		$this->a_adapterName = \Library\CliParameters::parameterValue('adapter', null);

		$this->a_absoluteFileName($this->a_fileName_XML);
		$this->a_newFactoryInfo();

		if (substr($this->a_infoName, 0, 3) == 'spl')
		{
			$this->a_objectName = 'splobject';
		}
		else
		{
			$this->a_objectName = 'fileobject';
		}

		$this->a_infoClass     = \Library\FileIO\Factory::getInstance()->className($this->a_infoName);
		$this->a_objectClass   = \Library\FileIO\Factory::getInstance()->className($this->a_objectName);

		if ($this->a_infoName == 'info')
		{
			$this->a_derivedInfoClass = \Library\FileIO\Factory::getInstance()->className($this->a_adapterName);
			if (substr($this->a_adapterName, 0, 3) == 'spl')
			{
				$this->a_derivedObjectClass = \Library\FileIO\Factory::getInstance()->className('splobject');
			}
			else
			{
				$this->a_derivedObjectClass = \Library\FileIO\Factory::getInstance()->className('fileobject');
			}
		}
		else
		{
			$this->a_derivedInfoClass = $this->a_infoClass;
    		$this->a_derivedObjectClass = $this->a_objectClass;
		}

    	unset($this->a_fileInfo);
    	$this->a_fileInfo = null;

   		$files = array($this->a_fileName_PHP,
   					   $this->a_fileName_XML);

   		if (substr($this->utility_os, 0, 3) != 'win')
   		{
   			$files = array_merge($files, array('/home/jay/public_html/',
   					  			 		       '/home/jay/public_html',
   					  						   '/home/jay/Logs/phptesting'));
   		}

   		foreach($files as $fileName)
   		{
   			$this->a_absoluteFileName($fileName);
   			$this->a_fileTests();
   		}
    }

    /**
     * a_fileTests
     *
     * Run tests for the current fileName
     */
    public function a_fileTests()
    {
   		$this->labelBlock(sprintf('Testing file: %s.', $this->a_fileName), 40, '*');
    	
   		$this->a_newFactoryInfo();

    	$this->a_getATime(false);

    	$this->a_getBasename(basename($this->a_fileName));
    	$this->a_getBasename(basename($this->a_fileName, 'php'), 'php');

    	$this->a_getCTime(false);

    	$this->a_getExtension(pathinfo($this->a_fileName, PATHINFO_EXTENSION));

    	$this->a_getFileName(basename($this->a_fileName));

    	$this->a_getGroup(@filegroup($this->a_fileName));

    	$this->a_getInode(@fileinode($this->a_fileName));

		if (function_exists('readlink'))
		{
    		$this->a_readlinkResult = readlink($this->a_fileName);
    		$this->a_getLinkTarget($this->a_readlinkResult);
		}
		else
		{
			$this->labelBlock('Skipping missing php-function: readlink', 60, '*');
		}

    	$this->a_getMTime(@filemtime($this->a_fileName));

    	$this->a_getOwner(@fileowner($this->a_fileName));

    	$this->a_getPath(pathinfo($this->a_fileName, PATHINFO_DIRNAME));

    	$this->a_setInfoClass($this->a_derivedInfoClass);

    	$this->a_getPathInfo($this->a_derivedInfoClass);

    	$this->a_fileInfoParent = null;

    	$this->a_getPathname($this->a_fileInfo->__toString());

    	$this->a_getPerms(@fileperms($this->a_fileInfo));

    	$this->a_getRealPath(@realpath($this->a_fileName), false);

    	$this->a_getSize(@filesize($this->a_fileName), false);

    	$this->a_getType(@filetype($this->a_fileName), false);

    	$this->a_isDir(@is_dir($this->a_fileName));
    	$this->a_isExecutable(@is_executable($this->a_fileName));
    	$this->a_isFile(@is_file($this->a_fileName));
    	$this->a_isLink(@is_link($this->a_fileName));
		$this->a_isReadable(@is_readable($this->a_fileName));
		$this->a_isWritable(@is_writable($this->a_fileName));

    	$this->a_setFileClass($this->a_derivedObjectClass);

    	$this->a_openFile($this->a_derivedObjectClass);

    	$this->a_fileObject = null;

		$this->a_fileInfo = null;
    }

    /**
     * a_openFile
     *
     * Open the File Object class
     * @param string   $mode        = (optional) open mode, default = 'r'
     */
    public function a_openFile($expected, $mode='r')
    {
    	$this->labelBlock('Open File.', 40, '*');

    	$assertion = sprintf('(get_class($this->a_fileObject = $this->a_fileInfo->openFile("%s")) == "%s");', $mode, $expected);
		$this->assertExceptionTrue($assertion, sprintf("Open File - Asserting: %s", $assertion));

		$this->a_exceptionCaughtFalse();
    }

    /**
     * a_setInfoClass
     *
     * set the class name used by getFileInfo and getPathInfo
     */
    public function a_setInfoClass($infoClass)
    {
    	$this->labelBlock('Set Info Class.', 40, '*');

    	$assertion = sprintf('$this->a_fileInfo->setInfoClass("%s");', $infoClass);
		$this->assertFalse($assertion, sprintf("setInfoClass - Asserting: %s", $assertion));
    }

    /**
     * a_setFileClass
     *
     * set the class name used by openFile
     */
    public function a_setFileClass($fileClass)
    {
    	$this->labelBlock('Set File Class.', 40, '*');

    	$assertion = sprintf('$this->a_fileInfo->setFileClass("%s");', $fileClass);
		$this->assertFalse($assertion, sprintf("setFileClass - Asserting: %s", $assertion));
    }

    /**
     * a_isWritable
     *
     * Check if file is writable
     */
    public function a_isWritable($expected)
    {
    	$this->labelBlock('isWritable.', 40, '*');

    	$assertion = '$this->a_data = $this->a_fileInfo->isWritable();';
		$this->assertTrue($assertion, sprintf("isWritable - Asserting: %s", $assertion));

		$this->a_compareExpected($expected);
    }

    /**
     * a_isReadable
     *
     * Check if file is readable
     */
    public function a_isReadable($expected)
    {
    	$this->labelBlock('isReadable.', 40, '*');

    	$assertion = '$this->a_data = $this->a_fileInfo->isReadable();';
		$this->assertTrue($assertion, sprintf("isReadable - Asserting: %s", $assertion));

		$this->a_compareExpected($expected);
    }

    /**
     * a_isLink
     *
     * Check if file is a symbolic link
     */
    public function a_isLink($expected)
    {
    	$this->labelBlock('isLink.', 40, '*');

    	$assertion = '$this->a_data = $this->a_fileInfo->isLink();';
		$this->assertTrue($assertion, sprintf("isLink - Asserting: %s", $assertion));

		$this->a_compareExpected($expected);
    }

    /**
     * a_isFile
     *
     * Check if file is a regular file
     */
    public function a_isFile($expected)
    {
    	$this->labelBlock('isFile.', 40, '*');

    	$assertion = '$this->a_data = $this->a_fileInfo->isFile();';
		$this->assertTrue($assertion, sprintf("isFile - Asserting: %s", $assertion));

		$this->a_compareExpected($expected);
    }

    /**
     * a_isExecutable
     *
     * Check if file is executable
     */
    public function a_isExecutable($expected)
    {
    	$this->labelBlock('isExecutable.', 40, '*');

    	$assertion = '$this->a_data = $this->a_fileInfo->isExecutable();';
		$this->assertTrue($assertion, sprintf("isExecutable - Asserting: %s", $assertion));

		$this->a_compareExpected($expected);
    }

    /**
     * a_isDir
     *
     * Check if file is directory
     */
    public function a_isDir($expected)
    {
    	$this->labelBlock('isDir.', 40, '*');

    	$assertion = '$this->a_data = $this->a_fileInfo->isDir();';
		$this->assertTrue($assertion, sprintf("isDir - Asserting: %s", $assertion));

		$this->a_compareExpected($expected);
    }

    /**
     * a_getType
     *
     * Get file type
     */
    public function a_getType($expected, $exit=true)
    {
    	$this->labelBlock('Get File Type.', 40, '*');

    	$assertion = '$this->a_data = $this->a_fileInfo->getType();';
		$this->assertExceptionTrue($assertion, sprintf("Type - Asserting: %s", $assertion));

		$this->a_exceptionCaughtFalse($exit);

		if (! $this->exceptionCaught())
		{
			$this->a_compareExpected($expected);
		}
    }

    /**
     * a_getSize
     *
     * Get file size
     */
    public function a_getSize($expected, $exit=true)
    {
    	$this->labelBlock('Get File Size.', 40, '*');

    	$assertion = '$this->a_data = $this->a_fileInfo->getSize();';
		$this->assertExceptionTrue($assertion, sprintf("Size - Asserting: %s", $assertion));

		$this->a_exceptionCaughtFalse($exit);

		if (! $this->exceptionCaught())
		{
			$this->a_compareExpected($expected);
		}
    }

    /**
     * a_getRealPath
     *
     * Get absolute path to file
     */
    public function a_getRealPath($expected, $exit=true)
    {
    	$this->labelBlock('Get Real Path.', 40, '*');

    	$assertion = '$this->a_data = $this->a_fileInfo->getRealPath();';
		$this->assertExceptionTrue($assertion, sprintf("Real path - Asserting: %s", $assertion));

		$this->a_exceptionCaughtFalse($exit);

		if (! $this->exceptionCaught())
		{
			$this->a_compareExpected($expected);
		}
    }

    /**
     * a_getPerms
     *
     * Get file permissions
     */
    public function a_getPerms($expected)
    {
    	$this->labelBlock('Get Permissions.', 40, '*');

    	$assertion = '$this->a_data = $this->a_fileInfo->getPerms();';
		$this->assertTrue($assertion, sprintf("Permissions - Asserting: %s", $assertion));

		$this->a_compareExpected($expected);
    }

    /**
     * a_getPathname
     *
     * Get path to file
     */
    public function a_getPathname($expected)
    {
    	$this->labelBlock('Get Path Name.', 40, '*');

    	$assertion = '$this->a_data = $this->a_fileInfo->getPathname();';
		$this->assertTrue($assertion, sprintf("Path name - Asserting: %s", $assertion));

		$this->a_compareExpected($expected);
    }

    /**
     * a_getPathInfo
     *
     * Get pathInfo object
     */
    public function a_getPathInfo($expected)
    {
    	$this->labelBlock('Get PathInfo object.', 40, '*');
    	
    	$this->a_className = $expected;

    	$assertion = '($this->a_data = get_class($this->a_fileInfoParent = $this->a_fileInfo->getPathInfo($this->a_derivedInfoClass)));';
		$this->assertTrue($assertion, sprintf("getPathInfo class object - Asserting: %s", $assertion));

		$this->a_compareExpected($expected);
    }

    /**
     * a_getPath
     *
     * Get file path
     */
    public function a_getPath($expected)
    {
    	$this->labelBlock('File Path.', 40, '*');

    	$assertion = '$this->a_data = $this->a_fileInfo->getPath();';
		$this->assertTrue($assertion, sprintf("File path - Asserting: %s", $assertion));

		$this->a_compareExpected($expected);
    }

    /**
     * a_getOwner
     *
     * Get file owner
     */
    public function a_getOwner($expected)
    {
    	$this->labelBlock('File Owner.', 40, '*');

    	$assertion = '$this->a_data = $this->a_fileInfo->getOwner();';
		$this->assertExceptionTrue($assertion, sprintf("File owner - Asserting: %s", $assertion));

		$this->a_exceptionCaughtFalse(false);

		if (! $this->exceptionCaught())
		{
			$this->a_compareExpected($expected);
		}
    }

    /**
     * a_getMTime
     *
     * Get file modified time
     */
    public function a_getMTime($exit=true)
    {
    	$this->labelBlock('File Modified Time.', 40, '*');

    	$assertion = '$this->a_data = $this->a_fileInfo->getMTime();';
		if (! $this->assertExceptionTrue($assertion, sprintf("File modified - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse($exit);

		if (! $this->exceptionCaught())
		{
			$this->assertLogMessage(date("F d Y H:i:s.", $this->a_data));
		}
    }

    /**
     * a_getLinkTarget
     *
     * Get file link target
     */
    public function a_getLinkTarget($expected)
    {
    	$this->labelBlock('Link Target.', 40, '*');

    	$assertion = '$this->a_data = $this->a_fileInfo->getLinkTarget();';
		$this->assertExceptionTrue($assertion, sprintf("Link target - Asserting: %s", $assertion));

		$this->a_exceptionCaughtFalse(false);

		if (! $this->exceptionCaught())
		{
			$this->a_compareExpected($expected);
		}
    }

    /**
     * a_getGroup
     *
     * Get file group id
     */
    public function a_getInode($expected)
    {
    	$this->labelBlock('Getting Inode.', 40, '*');

    	$assertion = '$this->a_data = $this->a_fileInfo->getInode();';
		$this->assertExceptionTrue($assertion, sprintf("Getting Inode - Asserting: %s", $assertion));

		$this->a_exceptionCaughtFalse(false);

		if (! $this->exceptionCaught())
		{
			$this->a_compareExpected($expected);
		}
    }

    /**
     * a_getGroup
     *
     * Get file group id
     */
    public function a_getGroup($expected)
    {
    	$this->labelBlock('Getting Group ID.', 40, '*');

    	$assertion = '$this->a_data = $this->a_fileInfo->getGroup();';
		$this->assertExceptionTrue($assertion, sprintf("Getting Group ID - Asserting: %s", $assertion));

		$this->a_exceptionCaughtFalse(false);

		if (! $this->exceptionCaught())
		{
			$this->a_compareExpected($expected);
		}
    }

    /**
     * a_getFileName
     *
     * Get file name
     */
    public function a_getFileName($expected)
    {
    	$this->labelBlock('Getting Filename.', 40, '*');

    	$assertion = '$this->a_data = $this->a_fileInfo->getFileName();';
		$this->assertExceptionTrue($assertion, sprintf("Getting file name - Asserting: %s", $assertion));

		$this->a_exceptionCaughtFalse();

		$this->a_compareExpected($expected);
    }

    /**
     * a_getExtension
     *
     * Get file extension
     */
    public function a_getExtension($expected)
    {
    	$this->labelBlock('Getting Extension.', 40, '*');

    	$assertion = '$this->a_data = $this->a_fileInfo->getExtension();';
		$this->assertExceptionTrue($assertion, sprintf("Getting extension - Asserting: %s", $assertion));

		$this->a_exceptionCaughtFalse();

		$this->a_compareExpected($expected);
    }

    /**
     * a_getCTime
     *
     * Get inode change time
     */
    public function a_getCTime($exit=true)
    {
    	$this->labelBlock('Getting Changed Time.', 40, '*');

    	$assertion = '$this->a_data = $this->a_fileInfo->getCTime();';
		if (! $this->assertExceptionTrue($assertion, sprintf("Getting changed time - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse($exit);

		if (! $this->exceptionCaught())
		{
			$this->assertLogMessage(date("F d Y H:i:s.", $this->a_data));
		}
    }

    /**
     * a_getBasename
     *
     * Get basename of the file
     * @param string $expected = expected basename
     * @param string $suffix = (optional) suffix to delete, null to not delete suffix
     */
    public function a_getBasename($expected, $suffix=null)
    {
    	$this->labelBlock('GetBasename.', 40, '*');

    	if ($suffix !== null)
    	{
	    	$assertion = sprintf('$this->a_data = $this->a_fileInfo->getBasename("%s");', $suffix);
    	}
    	else
    	{
	    	$assertion = '$this->a_data = $this->a_fileInfo->getBasename();';
    	}

		if (! $this->assertExceptionTrue($assertion, sprintf("Basename - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_compareExpected($expected);
    }

    /**
     * a_getATime
     *
     * Create Last accessed time
     */
    public function a_getATime($exit=true)
    {
    	$this->labelBlock('Getting Accessed Time.', 40, '*');
    	$assertion = '$this->a_data = $this->a_fileInfo->getATime();';
		if (! $this->assertExceptionTrue($assertion, sprintf("Getting accessed time - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse($exit);
		if (! $this->exceptionCaught())
		{
			$this->assertLogMessage(date("F d Y H:i:s.", $this->a_data));
		}
    }

    /**
     * a_newFactoryInfo
     *
     * Create a new FileInfo object
     */
    public function a_newFactoryInfo()
    {
    	$this->labelBlock('Creating NEW FileIO Factory Info object.', 40, '*');

    	if ($this->a_adapterName)
    	{
    		$assertion = sprintf('(($this->a_fileInfo = \Library\FileIO\Factory::instantiateClass("%s", "%s", "%s")) !== null);',
    							 $this->a_infoName,
    							 $this->a_adapterName,
    							 $this->a_fileName);
    	}
    	else
    	{
    		$assertion = sprintf('(($this->a_fileInfo = \Library\FileIO\Factory::instantiateClass("%s", "%s")) !== null);',
    							 $this->a_infoName,
    							 $this->a_fileName);
    	}

		if (! $this->assertExceptionTrue($assertion, sprintf("NEW FileIO Factory Info object - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}

		$this->a_exceptionCaughtFalse();

		$this->a_compareExpectedType(true, \Library\FileIO\Factory::getInstance()->className($this->a_infoName), get_class($this->a_fileInfo));
    }

}
