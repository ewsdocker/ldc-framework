<?php
namespace Library\SoapExt;

/*
 *		SoapExt\MapFault is copyright � 2016. EarthWalk Software.
 *		Licensed under the Academic Free License version 3.0.
 *		Refer to the file named License.txt provided with the source,
 *			  or from http://opensource.org/licenses/academic.php
 */
/**
 * Library\SoapExt\MapFault.
 *
 * SoapFault mapping class.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2016. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage SoapExt
 */
class MapFault
{
	protected static $faultMap = array('VersionMismatch'		=> 'Soap_VersionMismatch',
									   'MustUnderstand'			=> 'Soap_MustUnderstand',
									   'DataEncodingUnknown'	=> 'Soap_DataEncodingUnknown',
									   'Sender'					=> 'Soap_Sender',
									   'Receiver'				=> 'Soap_Receiver',
									   );

	protected static $mapFault = null;

	/**
	 * __construct
	 *
	 * Class constructor
	 * @param array $additionalErrors = (optional) additonal error messages array
	 */
	private function __construct($additionalErrors=null)
	{
		new Messages($additionalErrors);
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
	 * toErrorCode
	 *
	 * STATIC Function
	 * Maps faultCode to the proper error message code
	 * @param string $faultCode = fault code to be mapped
	 * @return string $mapCode = fault code mapped to error code
	 */
	public static function toErrorCode($faultCode)
	{
		if (! self::$mapFault)
		{
			self::$mapFault = new MapFault();
		}

		if (! array_key_exists(self::$faultMap, $faultCode))
		{
			return ('Soap_Unknown');
		}

		return self::$faultMap[$faultCode];
	}

}
