<?php
namespace Library\Exception;

/*
 * 		Exception\Descriptor is copyright � 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
 */

/**
 * Exception\Descriptor.
 *
 * Exception descriptor class.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0
 * @package Library
 * @subpackage Exception
 */

class Descriptor extends \Library\Properties
{
	/**
	 * __construct
	 *
	 * Create an exception object instance
	 * @param Exception $exception = exception to record
	 * @param mixed $properties = (optional) array or Library\Properties object containing additional information to record
	 */
	public function __construct($exception, $properties=null)
	{
		parent::__construct($properties);

		$this->className	= get_class($exception);
		$this->exception	= $exception;

		$this->fileName		= $exception->getFile();
		$this->lineNumber	= $exception->getLine();
		$this->message		= $exception->getMessage();
		$this->code			= $exception->getCode();
		$this->trace		= $exception->getTrace();
		$this->previous		= $exception->getPrevious();

		if (property_exists($exception, 'errorinfo'))
		{
			$this->errorinfo = $exception->errorinfo;
		}

		$this->traceString	= sprintf('%s', $exception);
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

	/**
	 * __toString
	 *
	 * Return a printable string of the exception information.
	 * @return string $buffer
	 */
	public function __toString()
	{
		return sprintf('%s (%u) %s @ %u in %s. Trace: %s', 
						$this->className,
						$this->code,
						$this->message,
						$this->lineNumber,
						$this->fileName,
						$this->traceString);
	}

}
