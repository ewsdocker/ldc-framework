<?php
namespace Library\Log;
use Library\Error;

/*
 * 	 	  Log\Base is copyright © 2012, 2013. EarthWalk Software.
 * 		  Licensed under the Academic Free License version 3.0.
 *        Refer to the file named License provided with the source, 
 *         or from http://opensource.org/licenses/academic.php.
 */
/**
 * Library\Log\Base.
 *
 * Provides a base class for routines shared by multiple classes.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright © 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Log.
 */
class Base
{
	/**
	 * logProperties
	 *
	 * A Library\Properties for log-relative properties
	 * @var object $logProperties
	 */
	protected	$logProperties;

	/**
	 * $properties
	 *
	 * A Library\Properties object containing required properties and values
	 * @var object $properties
	 */
	protected 	$properties;

	/**
	 * __construct
	 *
	 * Create a new instance of the Log\Base class
	 * @param object $properties = Properties object instance containing required properties and their values
	 */
	public function __construct($properties)
	{
		if (is_object($properties))
		{
			if (! $properties instanceof \Library\Properties)
			{
				throw new Exception(Error::code('InvalidClassObject'));
			}
		}
		elseif (is_array($properties))
		{
			if (count($properties) == 0)
			{
				throw new Exception(Error::code('MissingPropertiesArray'));
			}

			$properties = new \Library\Properties($properties);
		}
		else
		{
			throw new Exception(Error::code('MissingRequiredProperties'));
		}

		$this->properties = $properties;

		if (! $this->properties->exists('Log_SkipLevels'))
		{
			$this->properties->Log_SkipLevels = 2;
		}
		
		if (! $this->properties->exists('Log_Format'))
		{
			$this->properties->Log_Format = 'none';
		}

		if (! $this->properties->Log_Level)
		{
			$this->properties->Log_Level = \Library\Log::LOG_BSD_ERR;
		}

		if (is_string($this->properties->Log_Level))
		{
			$this->properties->Log_Level = \Library\Log::getInstance()->lookupLogType($this->properties->Log_Level);
		}
	}

	/**
	 * __destruct
	 *
	 * class destructor
	 * @return null
	 */
	public function __destruct()
	{
	}

	/**
	 * properties
	 *
	 * Set/get the properties array
	 * @param array $properties = (optional) properties array to set, null to query only
	 * @return array $properties
	 */
	public function properties($properties=null)
	{
		if ($properties !== null)
		{
			$this->properties = $properties;
		}

		return $this->properties;
	}

	/**
	 * adjustLogProperties
	 *
	 * Check the type of parameter received and return as a Properties object instance
	 * @param mixed $logProperties = the parameter to check/modify
	 * @return object $logProperties = Properties object, properly initialized.
	 * @throws Library\Log\Exception
	 */
	protected function adjustLogProperties($logProperties)
	{
		switch(gettype($logProperties))
		{
			case 'object':
				if (! $logProperties instanceof \Library\Properties)
				{
					throw new Exception(Error::code('InvalidClassObject'));
				}

				break;

			case 'array':
				if (count($logProperties) == 0)
				{
					$logProperties = array('Log_Level' => $logProperties);
				}

				$logProperties = new \Library\Properties($logProperties);

				break;

			case 'string':

				$logProperties = new \Library\Properties(array('Log_Level' => $logProperties));
				break;

			case 'integer':

				if (($key = array_search($logProperties, $this->logTypes)) === false)
				{
					$key = 'error';
				}

				$logProperties = new \Library\Properties(array('Log_Level' => $key));

				break;

			default:
				$logProperties = new \Library\Properties(array('Log_Level' => 'error'));
				break;
		}

		if ($logProperties->exists('Log_Level'))
		{
			if (is_string($logProperties->Log_Level))
			{
				$logProperties->Log_Level = \Library\Log::getInstance()->lookupLogType($logProperties->Log_Level);
			}

			if (! $logProperties->Log_Level)
			{
				$logProperties->Log_Level = $this->properties->Log_Level;
			}
		}
			
		if (! $logProperties->exists('Log_Level'))
		{
			$logProperties->Log_Level = \Library\Log::LOG_BSD_ERR;
		}

		return $logProperties;
	}

	/**
	 * logProperties
	 *
	 * Set/get the logProperties object
	 * @param object $logProperties = (optional) properties object or array to set, null to query only
	 * @return object $properties
	 */
	public function logProperties($logProperties=null)
	{
		if ($logProperties !== null)
		{
			$this->logProperties = $this->adjustLogProperties($logProperties);
		}

		return $this->logProperties;
	}

}
