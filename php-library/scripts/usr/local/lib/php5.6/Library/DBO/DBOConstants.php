<?php
namespace Library\DBO;

/*
 * 		DBOConstants is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, 
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 * 
 * DBOConstants.
 * 
 * DBOConstants interface.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2015. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library.
 * @subpackage DBO.
 */
interface DBOConstants
{
	const 	FETCH_ASSOC 				=    1;
	const	FETCH_NUM					=    2;
	const	FETCH_BOTH					=    4;
	const	FETCH_BOUND					=    8;
	const	FETCH_CLASS					=   16;
	const	FETCH_CLASSTYPE				=   32;
	const	FETCH_INTO					=   64;
	const	FETCH_LAZY					=  128;
	const	FETCH_NAMED					=  256;
	const	FETCH_OBJ					=  512;

	const	FETCH_ORI_NEXT				= 1024;
	const	FETCH_ORI_ABS				= 2048;
	const	FETCH_ORI_REL				= 4096;

	const	TYPE_INTEGER				= 'i';
	const	TYPE_DOUBLE					= 'd';
	const	TYPE_STRING					= 's';
	const	TYPE_BLOB					= 'b';
	
	const	ATTR_AUTOCOMMIT				= 'DBO_ATTR_01';
	const	ATTR_CHARSET_DEFAULT		= 'DBO_ATTR_02';
	const	ATTR_CHARSET_INFO			= 'DBO_ATTR_03';
	const	ATTR_CLIENT_INFO			= 'DBO_ATTR_04';
	const	ATTR_CLIENT_VERSION			= 'DBO_ATTR_05';
	const	ATTR_CONNECTION_STATS		= 'DBO_ATTR_06';
	const	ATTR_ERROR_INFO				= 'DBO_ATTR_07';
	const	ATTR_ERROR_LIST				= 'DBO_ATTR_08';
	const	ATTR_FIELD_COUNT			= 'DBO_ATTR_09';
	const	ATTR_HOST_INFO				= 'DBO_ATTR_10';
	const	ATTR_QUERY_INFO				= 'DBO_ATTR_11';
	const	ATTR_SERVER_INFO			= 'DBO_ATTR_12';
	const	ATTR_SERVER_VERSION			= 'DBO_ATTR_13';
	const	ATTR_STAT					= 'DBO_ATTR_14';
	const	ATTR_THREAD_SAFE			= 'DBO_ATTR_15';
	const	ATTR_WARNING_COUNT			= 'DBO_ATTR_16';
	const	ATTR_REPORT_MODE			= 'DBO_ATTR_17';
	const	ATTR_FETCH_STYLE			= 'DBO_ATTR_18';

	const	ATTR_DRIVER_HANDLE			= 'DBO_ATTR_19';
	const	ATTR_RESULT_HANDLE			= 'DBO_ATTR_20';
	const	ATTR_STATEMENT_HANDLE		= 'DBO_ATTR_21';
	const	ATTR_GET_RESULT				= 'DBO_ATTR_22';
	const	ATTR_RESULT_COLUMNS			= 'DBO_ATTR_23';

	const	STMT_ATTR_UPDATE_MAX_LENGTH	= 'DBO_STMT_ATTR_01';
	const	STMT_ATTR_CURSOR_TYPE		= 'DBO_STMT_ATTR_02';
	const	STMT_ATTR_PREFETCH_ROWS		= 'DBO_STMT_ATTR_03';

	const	CURSOR_TYPE_READ_ONLY		= 'DBO_CURSOR_TYPE_01';
	const	CURSOR_TYPE_NO_CURSOR		= 'DBO_CURSOR_TYPE_02';
}
