<?php
namespace Library;
use Library\ProgramExec\Proc as Proc;
use Library\Queue;
use Library\Error\Messages as ErrorMessages;

/*
 *		Library\Accept is copyright � 2015. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 *      Refer to the file named License.txt provided with the source,
 *         or from http://opensource.org/licenses/academic.php
*/
/**
 * Library\Accept
 *
 * Accept input from the console with controlling options
 * @author Jay Wheeler
 * @version 1.0
 * @copyright � 2015 EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Library
 * @subpackage Accept
 */
class Accept
{
	private	static $_accept=null;	// instance variable
    private static $_console = 1;  // set to 1 for console i/o, 0 for network

  	private 	$_acceptBuffer;         // console input buffer

  	private 	$_inputcase = 'both';   // case option: 'upper', 'lower', 'both'
  	private 	$_trim = 'both';        // trim option: 'left',  'right', 'both'
  	private 	$_promptString = ' < '; // the prompt sequence

    private 	$_name = 'console';     // name of this object class
    protected 	$_debug = 0;            // set non-zero to debug

    private 	$_parameters = array(); // console parameters parsed into this

    // *************************************************************************
    //
    //    __construct
    //
    //    Enter:
    //      $name    = object name
    //      $icase   = input string case mapping (upper, lower, both)
    //      $trim    = input string trim setting (left, right, both, none)
    //      $prompt  = prompt string to terminate prompt inputs
    //      $debug   = 1 to debug output
    //      $console = 1 to direct to console, 0 to direct to html
    //    Exit:
    //      new object created
    //
    // *************************************************************************
  	public function __construct($name='console', $icase='both', $trim='both', $prompt=' < ', $debug=0, $console=null)
  	{
  	  $this->_debug = $debug;
  	  $this->_name = $name;
  	  $this->_inputcase = $icase;
  	  $this->_trim = $trim;
  	  $this->_promptString = $prompt;
  	  consoleAccept::directConsole($console);

  	  if ($this->_debug)
  	    $this->displayLine(sprintf('consoleAccept::__construct %s, icase: %s, trim: %s, prompt: %s',
		                           $name,
							       $icase,
							       $trim,
							       $prompt));
  	}

    // *************************************************************************
    //
    //    __destruct
    //
    //    Enter:
    //      none
    //    Exit:
    //      object destroyed
    //
    // *************************************************************************
    public function __destruct()
    {
  	  if ($this->_debug)
  	    $this->displayLine(sprintf('consoleAccept::__destruct %s', 
		                           $this->_name));
    }

    // *************************************************************************
    //
    //    displayLine
    //
    //      display a message followed by a newline character
    //        Note: a <br> sequence is added first if not in console mode
    //
    //    Enter:
    //      $message = message to display
    //    Exit:
    //      none
    //
    // *************************************************************************
    public function displayLine($message='')
    {
	  if (! consoleAccept::directConsole())
	    $message .= '<br>';
	  $this->display($message . "\n");
    }
    
    // *************************************************************************
    //
    //    display
    //
    //      display a message on STDOUT for console, otherwise on a web page
    //
    //    Enter:
    //      $message = message to display
    //    Exit:
    //      none
    //
    // *************************************************************************
    public function display($message='')
    {
      if (consoleAccept::directConsole())
	    fwrite(STDOUT, $message);
	  else
	    print $message;
    }

    // *************************************************************************
    //
    //    displayPrompt
    //
    //      display a prompt message followed by the default prompt sequence
    //
    //    Enter:
    //      $prompt = message to display followed by prompt sequence
    //    Exit:
    //      none
    //
    // *************************************************************************
    public function displayPrompt($prompt='')
    {
	  $this->display(sprintf('%s%s ',
	                         $prompt,
	                         $this->_promptString));
    }

    // *************************************************************************
    //
    //    accept
    //
    //      get the next message in the input stream
    //
    //    Enter:
    //      $icase = case option: 'upper', 'lower', 'both'
    //      $trim  = trim option: 'left',  'right', 'both'
    //    Exit:
    //      $_acceptBuffer = input message
    //
    // *************************************************************************
    public function accept($icase=null,$trim=null)
    {
  	  if ($this->_debug)
  	    $this->displayLine(sprintf('consoleAccept::accept %s, icase=%s, trim:%s', 
		                           $this->_name,
								   $icase,
								   $trim));

      $this->_acceptBuffer = fgets(STDIN);

      if ($trim === null)
        $trim = $this->_trim;

      if ($icase === null)
        $icase = $this->_inputcase;

      switch($trim)
      {
      	case 'left':
      	  $this->_acceptBuffer = ltrim($this->_acceptBuffer);
      	  break;
      	  
      	case 'right':
      	  $this->_acceptBuffer = rtrim($this->_acceptBuffer);
      	  break;
      	  
      	case 'both':
      	  $this->_acceptBuffer = trim($this->_acceptBuffer);
      	  break;
      	  
      	default:
      	case 'none':
      	  break;
      }

      switch($icase)
      {
      	case 'upper':
      	  $this->_acceptBuffer = strtoupper($this->_acceptBuffer);
      	  break;

      	case 'lower':
      	  $this->_acceptBuffer = strtolower($this->_acceptBuffer);
      	  break;

        default:
      	case 'mixed':
      	case 'both':
      	  break;
      }

      return $this->_acceptBuffer;
    }

    // *************************************************************************
    //
    //    promptAndAccept
    //
    //      prompt for the next message in the input stream
    //
    //    Enter:
    //      $prompt = message to display
    //      $icase = case option: 'upper', 'lower', 'both'
    //      $trim  = trim option: 'left',  'right', 'both'
    //    Exit:
    //      $_acceptBuffer = input message
    //
    // *************************************************************************
    public function promptAndAccept($prompt='',$icase=null,$trim=null)
    {
  	  if ($this->_debug)
  	    $this->displayLine(sprintf('consoleAccept::promptAndAccept %s, prompt: %s', 
		                           $this->_name,
						           $prompt));
      $this->displayPrompt($prompt);
      $this->accept($icase,$trim);
      if (empty($this->_acceptBuffer) ||
          (strtolower(trim($this->_acceptBuffer)) == 'quit'))
        return false;
      return $this->_acceptBuffer;
    }

    // *************************************************************************
    //
    //    newLineAndAccept
    //
    //      prompt for the next message in the input stream after outputing a
    //        new line sequence
    //
    //    Enter:
    //      $prompt = message to display
    //      $icase = case option: 'upper', 'lower', 'both'
    //      $trim  = trim option: 'left',  'right', 'both'
    //    Exit:
    //      $_acceptBuffer = input message
    //
    // *************************************************************************
    public function newLineAndAccept($prompt=null,$icase=null,$trim=null)
    {
  	  if ($this->_debug)
  	    $this->displayLine(sprintf('consoleAccept::newLineAndAccept %s, prompt: %s', 
		                           $this->_name,
						           $prompt));
	  $this->displayLine('');
	  if ($prompt !== null)
        $this->displayPrompt($prompt);
      $this->accept($icase,$trim);
      if (empty($this->_acceptBuffer) ||
          (strtolower(trim($this->_acceptBuffer)) == 'quit'))
        return false;
      return $this->_acceptBuffer;
    }

    // *************************************************************************
    //
    //    consoleParameters
    //
    //    Enter:
    //      $argcount = number of arguements
    //	    $arguements = arguement array
    //    Exit:
    //      $parameters = current parameters array
    //
    // *************************************************************************
    public function consoleParameters($argcount, $arguements)
    {
      $this->_parameters = array();
      $index = 1;
	  while ($index <= ($argcount-1))
	  {
	    $paramstring = $arguements[$index];
	    if (strpos($paramstring, '='))
	    {
	      list($param,$arg) = explode('=', strtolower(trim($paramstring)));
	      $this->_parameters[$param] = $arg;
	    }
	    else
	      $this->_parameters[$paramstring] = $arguements[++$index];
	    $index++;
	  }

	  if ($this->_debug)
	    foreach($this->_parameters as $key => $value)
	      $this->displayLine(sprintf('parameters: %s = %s', $key, $value));

	  return $this->_parameters;
    }

    // *************************************************************************
    // *************************************************************************
    //
    //    class attributes
    //
    // *************************************************************************
    // *************************************************************************

    // *************************************************************************
    //
    //    parameters
    //
    //    Enter:
    //      $parameters = (optional) parameters array contents to set
    //    Exit:
    //      $parameters = current parameters array
    //
    // *************************************************************************
    public function parameters($parameters=null)
    {
      if ($parameters !== null)
        $this->_parameters = $parameters;
      return $this->_parameters;
    }

    // *************************************************************************
    //
    //    parameterField
    //
    //    Enter:
    //      $field = field name to get
    //    Exit:
    //      $field = current field value
    //             = false if not present
    //
    // *************************************************************************
    public function parameterField($field)
    {
      if (array_key_exists($field, $this->_parameters))
        return $this->_parameters[$field];
      return false;
    }

    // *************************************************************************
    //
    //    acceptBuffer
    //
    //      return the current accept buffer
    //
    //    Enter:
    //      none
    //    Exit:
    //      $_acceptBuffer = input message
    //
    // *************************************************************************
    public function acceptBuffer()
    {
  	  return $this->_acceptBuffer;
    }

    // *************************************************************************
    //
    //    trim
    //
    //      set/get the current trim setting
    //
    //    Enter:
    //      $trim = (optional) trim setting: 'upper', 'lower', 'both'
    //    Exit:
    //      $trim = current trim setting
    //
    // *************************************************************************
    public function trim($trim=null)
    {
      if ($trim !== null)
        $this->_trim = $trim;
      return $this->_trim;
    }

    // *************************************************************************
    //
    //    inputcase
    //
    //      set/get the current case setting
    //
    //    Enter:
    //      $icase = (optional) case setting: 'left', 'right', 'both'
    //    Exit:
    //      $icase = current case setting
    //
    // *************************************************************************
    public function inputcase($icase=null)
    {
      if ($icase !== null)
        $this->_inputcase = $icase;
      return $this->_inputcase;
    }

    // *************************************************************************
    //
    //    debug
    //
    //      set/get the debug setting
    //
    //    Enter:
    //      $debug = (optional) debug setting: true = enable, false = disable, null = query
    //    Exit:
    //      $debug = current debug flag setting
    //
    // *************************************************************************
    public function debug($debug=null)
    {
      if ($debug !== null)
        $this->_debug = $debug;
      return $this->_debug;
    }

    // *************************************************************************
    //
    //    console
    //
    //      set/get the current console setting
    //
    //    Enter:
    //      $console = (optional) console setting (1 = console, 0 = online)
    //    Exit:
    //      $console = current console setting
    //
    // *************************************************************************
    public function console($console=null)
    {
      return consoleAccept::directConsole($console);
    }

    // *************************************************************************
    // *************************************************************************
    //
    //		STATIC member functions
    //
    // *************************************************************************
    // *************************************************************************

    // *************************************************************************
    //
    //    acceptConsole
    //
    //		STATIC entry point to the singleton class
    //
    //    Enter:
    //      $name    = object name
    //      $icase   = input string case mapping (upper, lower, both)
    //      $trim    = input string trim setting (left, right, both, none)
    //      $prompt  = prompt string to terminate prompt inputs
    //      $debug   = 1 to debug output
    //      $console = 1 to direct to console, 0 to direct to html
    //    Exit:
    //      new object created
    //
    // *************************************************************************
  	public static function acceptConsole($name='console', $icase='both', $trim='both', $prompt=' < ', $debug=0, $console=null)
  	{
  	  if (! self::$_accept)
  	    self::$_accept = new consoleAccept($name, $icase, $trim, $prompt, $debug, $console);

	  return self::$_accept;
  	}

	public static function cliParameters($argcount, $argvalues)
	{
	  return consoleAccept::acceptConsole()->consoleParameters($argcount, $argvalues);
	}

	public static function directConsole($console=null)
	{
	  if ($console !== null)
	    self::$_console = $console;
	  return self::$_console;
	}

    // *************************************************************************
    //
    //    stDisplay
    //
    //      display a message on STDOUT for console, otherwise on a web page
    //
    //    Enter:
    //      $message = message to display
    //    Exit:
    //      none
    //
    // *************************************************************************
	public static function stDisplay($message='')
	{
  	  if (! self::$_accept)
  	    consoleAccept::acceptConsole();
	  self::acceptConsole()->display($message);
	}
	
    // *************************************************************************
    //
    //    stDisplayLine
    //
    //      display a message followed by a newline character
    //        Note: a <br> sequence is added first if not in console mode
    //
    //    Enter:
    //      $message = message to display
    //    Exit:
    //      none
    //
    // *************************************************************************
	public static function stDisplayLine($message='')
	{
  	  if (! self::$_accept)
  	    consoleAccept::acceptConsole();
	  self::acceptConsole()->displayLine($message);
	}

}

