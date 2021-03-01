<?php
namespace Library\SoapExt;

use Library\Error;
use Library\Error\AddMessages;

/*
 *		SoapExt\Messages is copyright � 2015, 2016. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
*/
/**
 * SoapExt\Messages.
 *
 * The SoapExt\Messages class initializes the custom error codes and associated messages for the SoapExt class
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015, 2016. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage SoapExt
 */
class Messages extends AddMessages
{
	/**
	 * errorList
	 *
	 * Error name to error message array
	 * @var array $errorList
	 */
	protected $errorList =
			  array('Soap_Unknown'				=> 'Unknown SOAP fault',
			  		'Soap_VersionMismatch'		=> 'The faulting node found an invalid element information item',
			  		'Soap_MustUnderstand'		=> 'An immediate child element information item of the SOAP Header was not understood',
			  		'Soap_DataEncodingUnknown'	=> 'Unsupported data encoding',
			  		'Soap_Sender'				=> 'The message was incorrectly formed or did not contain the appropriate information in order to succeed',
			  		'Soap_Receiver'				=> 'The message could not be processed for reasons attributable to the processing of the message rather than to the contents of the message itself',
			  		);

	/**
	 * __construct
	 *
	 * Class constructor
	 * Register custom error messages with the error code/message system
	 * @param array $additionalErrors = (optional) additonal error messages array
	 */
	public function __construct($additionalErrors=null)
	{
		if (($additionalErrors !== null) && is_array($additionalErrors))
		{
			$this->errorList = array_merge($this->errorList, $additionalErrors);
		}

		parent::__construct('SOAP', $this->errorList);
	}

	/**
	 * __destruct
	 *
	 * class destructor
	 */
	public function __destruct()
	{
		parent::__destruct();
	}

}
