<?php
namespace Library\DBO;

use Library\Error;
use Library\Error\AddMessages;

/*
 *		DBO\Messages is copyright � 2015, 2016. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, or from http://opensource.org/licenses/academic.php
*/
/**
 * DBO\Messages.
 *
 * The DBO\Messages class initializes the custom error codes and associated messages for the DBO class
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015, 2016. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage DBO
 */
class Messages extends AddMessages
{
	/**
	 * errorList
	 *
	 * Error name to error message array
	 * @var array $errorList
	 */
	protected	$errorList =
			  array('DbUnknownStorageEngine'	=> 'Unknown storage engine specified',
					'DbExceptionCaught'			=> 'Db exception caught',
					'DbMissingHost'				=> 'Database host must be specified',
					'DbNotConnected'			=> 'Not connected to a database server',
					'DbAlreadyConnected'		=> 'Already connected to database server',
					'DbConnectionFailed'		=> 'Database connection failed',
					'DbInvalidOption'			=> 'Invalid database connection option',
					'DbOpenFailed'				=> 'Database open failed',
					'DbInitFailed'				=> 'Database object initialize failed',

					'DbInvalidClassMethod'		=> 'Database class and/or method is not valid',
					'DbClassMethodFailed'		=> 'Database class method failed',

					'DbMissingDsn'				=> 'DBO missing dsn',
					'DbInvalidDriver'			=> 'An invalid or unsupported driver was specified',
					'DbMissingUserName'			=> 'DBO missing user name',
					'DbInvalidDsn'				=> 'Invalid DSN format',
					'DbInvalidAttribute'		=> 'Unknown or invalid attribute/option',
					'DbError'					=> 'DBO statement or result error',

					'DbMissingName'				=> 'Database name not specified',
					'DbNameExists'				=> 'Database name already exists',
					'DbNotCreated'				=> 'Database creation failed',
					'DbNotFound'				=> 'Database was not found',
					'DbCurrentNameFailed'		=> 'Get current database name failed',

					'DbNotOpen'					=> 'Database not open',
					'DbCloseError'				=> 'Database close error',
					'DbDriverException'			=> 'Database driver exception was thrown',
					'DbDropInvalidName'			=> 'Database drop - the open database is not the database specified',
					'DbDropError'				=> 'Database drop error',
					'DbQueryError'				=> 'Database query failed',
			  		'DbSqlError'				=> 'Invalid sql statement',
					'DbPrepareStmtError'		=> 'Database prepare statement failed',

					'DbAutocommitError'			=> 'Unable to change autocommit setting',
					'DbNoTransaction'			=> 'No transaction to commit',
					'DbNotTransaction'			=> 'No transaction to roll-back',
					'DbTransactionInProgress'	=> 'Transaction already in progress',
					'DbCommitFailed'			=> 'Database commit operation failed',
					'DbRollbackFailed'			=> 'Database rollback failed',

					'DbTableExists'				=> 'The requested table already exists',
					'DbMissingTableName'		=> 'The table name was not supplied',
					'DbMissingTableProperties'	=> 'The table properties were not supplied',
					'DbTableColumnsMissing'		=> 'The table columns properties were not supplied',
					'DbTableNotCreated'			=> 'Unable to create the requested table',
					'DbTableDescriptorExpected'	=> 'Table Descriptor was expected.',

					'DBUnknownCollection'		=> 'Requested unknown collection or table',
					'DbMissingCollection'		=> 'Requested collection or table is not present',

					'DbResultNotReturned'		=> 'A result set was not returned from the query',
			  		'DbExecuteReturnedResult'	=> 'Invalid statement type - Execute returned a result',
					'DbDropTableFailed'			=> 'Drop table failed',

					'DbUnknownIndex'			=> 'An unknown index or name was specified',
					'DbMissingStatementVerb'	=> 'No statement verb (action) was provided',

					'DbWrongScheme'				=> 'Wrong or unknown database connection scheme',
					'DbListFailed'				=> 'Database list request failed',

					'DbStatementNotPrepared'	=> 'The statement has not been prepared',
					'DbPrepareStatementFailed'	=> 'Unable to prepare the requested SQL statement',
					'DbBindParamFailed'			=> 'Statement bindParam failed',
					'DbBindValueFailed'			=> 'Statement bindValue failed',
					'DbParameterCountInvalid'	=> 'Count of supplied parameters does not match requirements',

					'DbBindColumnArray'			=> 'Statement bindColumn array is empty',
					'DbBindColumnFailed'		=> 'Unable to bind column(s)',
					'DbUnknownColumn'			=> 'An unknown column index or name was specified',
					'DbUnknownColumnProperty'	=> 'An unknown column property was specified',

					'DbStatementInvalid'		=> 'The statement property is invalid',

					'DbFetchObjectError'		=> 'Fetch object error',
					'DbFetchResultFailed'		=> 'Statement fetch result failed',

					'DbMetaDataNotSet'			=> 'Meta-data is not available for the requested field',
					'DbFetchResultMetaFailed'	=> 'Unable to fetch result meta-data',

					'DbQueryResultInvalid'		=> 'The result handle property is invalid',
					'DbQueryExecuteFailed'		=> 'The query execution failed',

					'DbDataSeekError'			=> 'Data seek failed',
					'DbSeekRangeError'			=> 'Data seek destination is unattainable',

			  		'DbDeleteRecordError'		=> 'Delete record(s) error',

					'DbAttributeError'			=> 'Unable to fetch requested attribute',
					'DbAttributeSetError'		=> 'Unable to set requested attribute',
					'DbOptionError'				=> 'Unknown database option',
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

		parent::__construct('DBO', $this->errorList);
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
