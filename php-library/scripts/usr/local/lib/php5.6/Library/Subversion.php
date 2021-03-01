<?php
namespace Library;

use Library\Error;
use Library\Subversion\Exception as SubversionException;
use Library\Url\Parse as UrlParse;

/*
 *		Library\Subversion is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *	  Refer to the file named License.txt provided with the source,
 *		 or from http://opensource.org/licenses/academic.php
*/
/**
 * Library\Subversion
 *
 * Subversion command interface
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Subversion
 */
class Subversion
{
	/**
	 * svn
	 * 
	 * SVN class instance
	 * @var $svn
	 */
	public $svn;

	/**
	 * repositoryUrl
	 * 
	 * Url of the repository for this instance
	 * @var string $repositoryUrl
	 */
	public $repositoryUrl;

	/**
	 * parsedUrl
	 * 
	 * The parsed url instance
	 * @var UrlParse $parsedUrl
	 */
	public $parsedUrl;

	/**
	 * flags
	 * 
	 * Flags
	 * @var integer $flags
	 */
	public $flags;

	/**
	 * __construct
	 * 
	 * Class constructor
	 * @param string $repositoryUrl = Repository URL
	 */
	public function __construct($repositoryUrl)
	{
		$this->repositoryUrl = $repositoryUrl;
		$this->flags = 0;

		$this->parsedUrl = new UrlParse($this->repositoryUrl);

		$this->svn = new \Svn();
	}

	/**
	 * __destruct
	 * 
	 * Class destructor
	 */
	public function __destruct()
	{
		$this->svn = null;
	}

	/**
	 * update
	 * 
	 * Update local source from repository to revision number
	 * @param string $working = Working (check-out) directory
	 * @param string $revision = (optional) Software revision (default = HEAD)
	 * @param bool $recurse = (optional) recursion flag (true = default)
	 * @return integer $revNumber = new revision number, false if error
	 * @throws Exception
	 */
	public function update($destination, $revision=\Svn::HEAD, $recurse=true)
	{
		if (! ($revNumber = $this->svn->update($destination, $revision, $recurse)))
		{
			throw new SubversionException(Error::code('SvnUpdateFailed'));
		}

		return $revNumber;
	}

	/**
	 * checkOut
	 * 
	 * Check out source from repository
	 * @param string $destination = Destination directory
	 * @param string $revision = (optional) Software revision (default = HEAD)
	 * @throws Exception
	 */
	public function checkout($destination, $revision=\Svn::HEAD)
	{
		if ($this->flags !== 0)
		{
			if (! $this->svn->checkout($this->repositoryUrl, $destination, $revision, $this->flags))
			{
				throw new SubversionException(Error::code('SvnCheckoutFailed'));
			}
		}

		if (! $this->svn->checkout($this->repositoryUrl, $destination, $revision))
		{
			throw new SubversionException(Error::code('SvnCheckoutFailed'));
		}
	}

	/**
	 * ls
	 * 
	 * Returns a directory tree of the repository contents
	 * @param string $revision = (optional) Software revision (default = HEAD)
	 * @param string $recurse = (optional) true (default) to recurse throught the directory tree, false to not
	 */
	public function ls($revision=\Svn::HEAD, $recurse=true)
	{
		return @svn_ls($this->repositoryUrl, $revision, $recurse);
	}

	/**
	 * cat
	 * 
	 * Return the contents of a file in the repository
	 * @param string $relativePath = path to the file relative to $repositoryUrl
	 * @return string $contents = file contents
	 * @throws Exception
	 */
	public function cat($relativePath)
	{
		if (! ($contents = @svn_cat(sprintf("%s/%s", $this->repositoryUrl, $relativePath))))
		{
			throw new SubversionException(Error::code('SvnInvalidUrl'));
		}
		
		return $contents;
	}

}
