<?php
namespace Library\Error;

use Library\Error;

/*
 *		Error\Messages is copyright � 2012, 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
*/
/**
 * Messages.
 *
 * The Error\Messages class initializes the custom error codes and associated messages.
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2012, 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Error
 */
class Messages
{
	/**
	 * errorList
	 *
	 * Error name to error message array
	 * @var array $errorList
	 *
	 * NOTE: keep the order of the first 5 elements unless the Tests\Error\CodeTest.php is modified!
	 */
	private	$errorList =
			  array('NoError'	 				=> 'No errors detected',
			  		'UnknownError'				=> 'An Unknown Error has been detected',

					'AutoloadFailed'            => 'Autoload failed',
					'AutoloadRegisterFailed'	=> 'Autoload registration failed.',

					'PhpRuntimeError' 			=> 'PHP runtime error',
					'PhpFeatureNotAvailable'	=> 'The requested PHP feature is not available',
					'PhpExtensionNotAvailable'	=> 'The requested PHP extension is not available',
					'PhpTestingFailure'			=> 'PHP testing failure',

					'MethodNotImplemented'		=> 'The requested method has not been implemented',
					'PendingImplementation'		=> 'The function requested has not yet been implemented',

			  		'SetupFailed'				=> 'Unable to initialize setup properties',
					'MissingClassName'			=> 'Missing class name',
					'NoClassConstructor'		=> 'No class constructor',
					'MissingHostName'			=> 'Missing host name',

					'MissingParametersArray'	=> 'Missing parameters array or properties object',
			  		'ArrayVariableExpected'		=> 'An array variable was expected',
			  		'InvalidArraySize'			=> 'Array is not of the proper size',

					'AdapterError'				=> 'Adapter error ocurred',
					'DriverError'				=> 'Unknown driver name',
					'MissingAdapter'			=> 'Missing or Unknown Adapter or Driver',

					'LinkTargetError'			=> 'Link target error',

					'MissingSourceFileName'		=> 'Missing source file name',
					'EmptyBuffer'				=> 'Empty buffer',
					'ReadOnly'					=> 'Read only violation',
			  		'NotInitialized'			=> 'Object not initialized',
					'MissingFileName'			=> 'Missing file name',

					'StringOrObjectExpected'	=> 'A string or object variable was expected',
					'ArrayOrObjectExpected'		=> 'An array or object variable was expected',
					'ArrayKeysMissing'			=> 'One or more required array keys are missing',
			  		'ArrayKeyInvalid'			=> 'The provided array key is invalid',
					'ArraySortFailed'			=> 'Array sort was unsuccessful',
			  		'StringVariableExpected'	=> 'A string variable was expected',
					'NumericVariableExpected'	=> 'A numeric variable was expected',
					'NumericOptionExpected'		=> 'A numeric option value was expected',
			  		'StringOrListExpected'		=> 'A string list was expected',

					'UnknownExecuteType'		=> 'An unknown execute type was specified',
					'UnknownClass'				=> 'An unknown class was specified',
					'UnknownClassMethod'		=> 'Unknown class method',
					'MissingClassObject'		=> 'Missing class object',
					'MissingMethodName'			=> 'Missing class method name',
			  		'MissingClassOrMethod'		=> 'Missing and/or invalid class name and/or method name',
					'MissingFunctionName'		=> 'Missing function name',
					'FactoryMissingClassName'	=> 'Factory missing class name',
					'MissingPropertiesArray'	=> 'Missing properties array',
					'MissingPropertiesObject'	=> 'Missing properties object instance',
			  		'MissingRequiredProperties'	=> 'A required property is missing or invalid',

					'InvalidClassObject'		=> 'Invalid class object',
					'ObjectExpected'			=> 'An object was expected',
					'stdClassObjectExpected'	=> 'An stdClass object was expected',

			  		'CallbackFunctionError'		=> 'Callback function failed',
			  		'CallbackIsNotCallable'		=> 'The supplied callback function is not callable',

					'InvalidStringSize'			=> 'Invalid string size',
					'RestrictedUseOfNew'		=> 'Restricted use of the NEW keyword',
					'FactoryInvalidInstance'	=> 'Factory created invalid instance',
					'InvalidParameterValue'		=> 'The supplied parameter is invalid',

			  		/****      Log Errors      ****/

			  		'LogNotStarted'				=> 'Log not started.',
					'InvalidLoggingLevel'		=> 'Invalid logging level',

			  		/****      Serialize Errors      ****/

					'UnableToUnserialize'		=> 'Unable to unserialize object',
					'UnableToSerialize'			=> 'Unable to serialize object',

			  		/****      Stack Errors      ****/

			  		'StackEmpty'				=> 'Empty stack (stack underflow)',
					'UnknownStackElement'		=> 'Unknown stack element',
					'StackError'				=> 'Stack error',
					'StackInvalidMode'			=> 'Invalid LIFO stack mode',

					'NoRecordsSelected'			=> 'No records were selected',
					'NoRecordsRemaining'		=> 'No records remain',
					'NoValidRecords'			=> 'Only Blank records and/or Comment records remain',
					'InvalidResult'				=> 'An invalid result was received',

					/****      Queue Errors      ****/

			  		'QueueEmpty'				=> 'Queue contains no elements',
					'QueueInvalidMode'			=> 'Invalid queue mode',

			  		/****      URL Errors      ****/

					'UrlMalformed'				=> 'Malformed or invalid url',
					'MissingUri'				=> 'Missing client URI',
					'ParseError'				=> 'Parse error',

					/****      Resource Errors      ****/

					'ResourceAlreadyAssigned'	=> 'The resource is already assigned',
					'ResourceNotIntialized'		=> 'Resource was not initialized',
					'ResourceNotResource'		=> 'The specified resource is not a resource',

					'InvalidPipeName'			=> 'The specified pipe resource name is invalid',
					'InvalidPipeNumber'			=> 'The specified pipe number is invalid',

			  		/****      Process Errors      ****/

					'ProcessNotOpen'			=> 'The process is not open.',
					'ProcessOpenFailed'			=> 'The OPEN process failed.',
					'ProcessAlreadyOpen'		=> 'The process is already open.',
					'ProcessPropertyReadOnly'	=> 'Unable to set read-only property.',
					'ProcessCommandMissing'		=> 'Missing command string',
					'ProcessStatusError'		=> 'Status invalid or process is not running',
					'ProcessEmptyRequest'		=> 'Process request list is empty',

					/****      Directory Errors      ****/

			  		'DirectoryOpenError'		=> 'Directory open failed',
					'DirectoryInvalidParams'	=> 'Directory open with wrong parameters',
					'DirectoryMissingName'		=> 'Directory name was not specified',
					'DirectoryScanError'		=> 'Directory scan error occurred',
					'DirectoryNotInitialized'	=> 'Directory scan not performed',
			  		'DirectoryDuplicateEntry'	=> 'Directory entry is a duplicate',
			  		'DirectoryTreeError'		=> 'Directory tree could not be built',
			  		'DirectoryNotPresent'		=> 'Directory does not exist',
					'DirectoryNotCreated'		=> 'Directory was not created',

					/****      File Errors      ****/

			  		'FileNotOpen'				=> 'File is not open',
			  		'FileCloseError'			=> 'File close error',
					'FileEOF'					=> 'File is at end of file',
					'FileOpenError'				=> 'File open error',
					'FileWriteError'			=> 'File write error',
					'InvalidFileMode'			=> 'Invalid file mode selected',
					'FileRewindError'			=> 'File rewind error',
					'FileDoesNotExist'			=> 'The requested file does not exist',
			  		'FileCopyError'				=> 'Unable to copy the requested file',
					'FileExtractError'			=> 'Unable to extract requested file(s)',

					'FileGroupError'			=> 'File group error',
					'FileInodeError'			=> 'File inode error',
					'FileOwnerError'			=> 'File owner error',
					'FileRealPathFailed'		=> 'realpath failed',
					'FileSizeFailed'			=> 'filesize error',
					'FileTypeFailed'			=> 'filetype error',
					'MaxLineLenLessThanZero'	=> 'Max line length must not be less than zero',
					'FileFlushFailed'			=> 'File flush error',
					'FileSeekError'				=> 'File seek error',
					'FileNoRecords'				=> 'File contains no records',

					/****      Stream Errors      ****/

			  		'StreamNotLocal'			=> 'The stream is not a local disk stream',
					'StreamReadFailed'			=> 'Stream read failed',
					'StreamNotAssigned'			=> 'The stream adapter class is not assigned',
					'StreamEof'					=> 'Stream is at end-of-file',
					'StreamWriteFailed'			=> 'Stream write failes',

					/****      Select Errors      ****/

			  		'StreamSelectRegistration'	=> 'Unknown stream registration type',
					'StreamSelectUnknown'		=> 'Unregistered process stream',
					'StreamSelectEmpty'			=> 'No selected streams requesting service',

					/****      Compress Errors      ****/

			  		'FileCompressError'			=> 'Compression Library error',
					'FileDecompressError'		=> 'Decompression Library error',
					'EncodeError'				=> 'Encode Library error',
					'DecodeError'				=> 'Decode Library error',
			  		'DeflateError'				=> 'Deflate Library error',
			  		'InflateError'				=> 'Inflate Library error',

					/****      DOM Errors      ****/

			  		'DomNoChildren'				=> 'DOM document contains not children',
					'DomLoadFailed'				=> 'DOM load failed',
					'DomObjectExpected'			=> 'DOM object was expected',
					'DomSaveFailed'				=> 'DOM save failed',
					'DomXPathQueryError'		=> 'DOM XPath query error',
					'DomUknownAdapter'			=> 'DOM unknown adapter',

					/****      XML Errors      ****/

			  		'XmlToString'				=> 'XML-to-string conversion failure',
			  		'StringToXml'				=> 'String-to-XML conversion failure',

			  		/****      Config Errors      ****/

			  		'SectionNotElementNode'		=> 'Section is not an element node',
					'SectionNoChildren'			=> 'Section has no children',
					'SectionExtendsFailed'		=> 'Configuration section extension failed to load',

					'ConfigBadFormat'			=> 'Configuration file badly formatted',

					/****      HTTP Client Errors      ****/

			  		'ClientRequestFailed'		=> 'Client request failed',
					'ClientConfigError'			=> 'Client configuration error',
					'ClientInvalidContenttype'	=> 'Client invalid content-type',
			  		'ClientInvalidResponse'		=> 'Client returned an invalid or unexpected response',

					/****      cUrl Errors      ****/

			  		'CurlSetOption'				=> 'cUrl set option failed',
					'CurlSelectError'			=> 'cUrl select failed',
					'CurlRemoveHandle'			=> 'cUrl remove handle failed',
					'CurlReadInfoError'			=> 'cUrl read info failed',
					'CurlAddHandle'				=> 'cUrl add handle failed',

					'HttpHeaderError'			=> 'Http header error in the response message',

					/****      SVN Errors      ****/

			  		'SvnCheckoutFailed'			=> 'SVN Checkout Error',
					'SvnInvalidUrl'				=> 'SVN Repository URL is invalid',
					'SvnUpdateFailed'			=> 'SVN Update failed',
			  		'SvnRepoInvalidSearch'		=> 'An invalid search type was specified',

					/****      LLRBTree Errors      ****/

			  		'NodeAllocationFailed'		=> 'LLRBTree Node allocation failure',
			  );

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param array $additionalErrors = (optional) additonal error messages array
	 */
	public function __construct($additionalErrors=null)
	{
		$this->registerErrors($this->errorList);
		if (($additionalErrors !== null) && is_array($additionalErrors))
		{
			$this->registerErrors($additionalErrors);
		}
	}

	/**
	 * __destruct
	 *
	 * class destructor
	 */
	public function __destruct()
	{
	}

	/**
	 * registerErrors
	 *
	 * Register the error Messages in the list
	 * @param array $errorList = array containing the error name as the key and message as the value.
	 */
	protected function registerErrors($errorList)
	{
		foreach($errorList as $name => $message)
		{
			Error::register($name, $message, true);
		}
	}

}
