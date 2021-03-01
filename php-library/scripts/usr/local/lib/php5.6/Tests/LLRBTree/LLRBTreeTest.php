<?php
namespace Tests\LLRBTree;
use Library\LLRBTree;

/*
 * 		LLRBTree\LLRBTreeTest is copyright � 2012, 2013. EarthWalk Software.
 * 		Licensed under the Academic Free License version 3.0
 * 		Refer to the file named License.txt provided with the source, 
 * 		   or from http://opensource.org/licenses/academic.php
 */
/**
 *
 * LLRBTreeTest.
 *
 * Sample application to test the LLRBTree classes.
 * @author Jay Wheeler.
 * @version 1.0
 * @copyright � 2012, 2013. EarthWalk Software.
 * @license Licensed under the Academic Free License version 3.0.
 * @package Tests.
 * @subpackage LLRBTree.
 */

class LLRBTreeTest extends \Application\Launcher\Testing\UtilityMethods
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

		$this->a_words = array('cat',      'animal',  'donkey',
							   'bear',     'bat',     'dog',
							   'elephant', 'gazelle', 'llama',
							   'zebra',    'horse',   'ferret',
							   'wombat');

		$this->a_orderedWords = array('animal',   'bat',    'bear',
									  'cat',      'dog',    'donkey',
									  'elephant', 'ferret', 'gazelle',
									  'horse',    'llama',  'wombat',
									  'zebra');

		$this->a_deleteWords =array('elephant', 'gazelle', 'animal',
									'horse',    'wombat',  'cat',
									'zebra',    'ferret',  'donkey',
									'bat',      'llama',   'dog',
									'bear');

		// ********************************************

		$this->a_newLLRBTree();
		
		$this->a_showDetail(true);
		$this->a_showRoot(true);

		$this->a_addTree($this->a_words);
		$this->a_deleteWordList($this->a_deleteWords);

		$this->a_deleteLLRBTree();

		// ********************************************

		$this->a_newLLRBTree();
		
		$this->a_addTree($this->a_orderedWords);
		$this->a_deleteWordList($this->a_deleteWords);

		$this->a_deleteLLRBTree();

		// ********************************************

		$this->a_newLLRBTree();
		
		$this->a_offsetAddWordList($this->a_words);
		$this->a_offsetUnsetWordList($this->a_deleteWords);

		$this->a_deleteLLRBTree();
    }

    /**
     * a_showDetail
     *
     * Set show detail flag
     * @param boolean $show = value to set
     */
    public function a_showDetail($show=false)
    {
    	$this->labelBlock('Show Detail.', 40, '*');

    	$this->a_show = $show;
    	$assertion = sprintf('$this->a_data = $this->a_tree->showDetail($this->a_show);', $show);
		$this->assertTrue($assertion, sprintf("Show Detail - Asserting: %s", $assertion));

		$this->a_compareExpected($show);
    }

    /**
     * a_showRoot
     *
     * Set show root flag
     * @param boolean $show = value to set
     */
    public function a_showRoot($show=false)
    {
    	$this->labelBlock('Show Root.', 40, '*');

    	$this->a_show = $show;
		$assertion = sprintf('$this->a_data = $this->a_tree->showRoot($this->a_show);', $show);
		$this->assertTrue($assertion, sprintf("Show Root - Asserting: %s", $assertion));

		$this->a_compareExpected($show);
    }

    /**
     * a_offsetAddWordList
     *
     * Add a word list to the tree using array access
     * @param array $wordList = array of words to add
     */
    public function a_offsetAddWordList($wordList)
    {
		$this->a_offsetSetWordList($wordList);

		$this->a_printTree();

		$this->a_treeTop();

		$this->a_offsetSet('milk', '3.50');

		$this->a_printTree();

		$this->a_offsetExists('milk');

		$this->a_offsetGet('milk', '3.50');

		$this->a_offsetUnset('milk');
		$this->a_printTree();
	}

	/**
	 * a_offsetExists
	 *
	 * Returns true if the offset exists
	 * @param string $word = word to test for
	 * @param boolean $expected = true to test if exists, false to test if doesn't exist
	 */
	public function a_offsetExists($word, $expected=true)
	{
    	$this->labelBlock('Offset Exists.', 40, '*');

		$assertion = sprintf('$this->a_tree->offsetExists("%s");', $word);
		if ($expected)
		{
			if (! $this->assertTrue($assertion, sprintf("Offset Set - Asserting: %s", $assertion)))
			{
				$this->a_outputAndDie();
			}
			
			$this->assertLogMessage(sprintf('"%s" was found.', $word));
		}
		else
		{
			if (! $this->assertFalse($assertion, sprintf("Offset Set - Asserting: %s", $assertion)))
			{
				$this->a_outputAndDie();
			}
			
			$this->assertLogMessage(sprintf('"%s" was not found.', $word));
		}
	}

	/**
	 * a_offsetSetWordList
	 *
	 * Add the word list using array syntax
	 * @param array $wordList = array of words to add
	 */
	public function a_offsetSetWordList($wordList)
	{
		$wordNumber = 0;
		foreach($wordList as $word)
		{
			$this->a_offsetSet($word, ++$wordNumber);
			$this->a_printTree();
		}
	}

	/**
	 * a_offsetSet
	 *
	 * Set the specified value at the specified word (property)
	 * @param string $word = the key to add to the tree
	 * @param mixed $value = value of the key
	 */
	public function a_offsetSet($word, $value)
	{
    	$this->labelBlock('Offset Set.', 40, '*');

		$assertion = sprintf('$this->a_tree["%s"] = "%s";', $word, $value);
		if (! $this->assertTrue($assertion, sprintf("Offset Set - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
	}

	/**
	 * a_offsetGet
	 *
	 * Returned the value from the specified word (property)
	 * @param string $word = the key to add to the tree
	 * @param mixed $expected = the expected value
	 */
	public function a_offsetGet($word, $expected)
	{
    	$this->labelBlock('Offset Get.', 40, '*');

		$assertion = sprintf('$this->a_data = $this->a_tree["%s"];', $word);
		$this->assertTrue($assertion, sprintf("Offset Get - Asserting: %s", $assertion));

		$this->a_compareExpected($expected);
	}

	/**
	 * a_offsetUnsetWordList
	 *
	 * Add the word list using array syntax
	 * @param array $wordList = array of words to add
	 */
	public function a_offsetUnsetWordList($wordList)
	{
		$wordNumber = 0;
		foreach($wordList as $word)
		{
			$this->a_offsetUnset($word);
			$this->a_printTree();
		}
	}

	/**
	 * a_offsetUnset
	 *
	 * Set the specified value at the specified word (property)
	 * @param string $word = the key to add to the tree
	 */
	public function a_offsetUnset($word)
	{
    	$this->labelBlock('Offset Unset.', 40, '*');

		$assertion = sprintf('$this->a_tree->offsetUnset("%s");', $word);
		if (! $this->assertFalse($assertion, sprintf("Offset Unset - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
	}

    /**
     * a_addTree
     *
     * Add a tree
     * @param array $wordList = array of words to add
     */
    public function a_addTree($wordList)
    {
		$this->a_insertWordList($wordList);
		
		$this->a_printTree();
		
		$this->a_treeTop();
		$this->a_leaf('cat', 'animal');
		$this->a_leaf('donkey', 'dog');

		$this->a_directAccess($wordList);
		
		$this->a_set('milk', '3.50');
		$this->a_printTree();

		$this->a_search('milk');
		$this->a_search('crystal', false);

		$this->a_get('milk', '3.50');
		
		$this->a_deleteWord('milk');
		$this->a_printTree();
	}

	/**
	 * a_createNode
	 *
	 * Create a node.
	 * @param string $word = key name
	 * @param mixed $data = key data
	 */
	public function a_createNode($word, $data)
	{
		$this->labelBlock('Create Node.', 40, '*');

		$this->a_data = $data;
		$assertion = sprintf('$this->a_node = new LLRBTree\LLRBNode("%s", $this->a_data);', $word);
		if (! $this->assertTrue($assertion, sprintf("Create Node - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
	}

	/**
	 * a_leaf
	 *
	 * Find the leaf of the supplied branch
	 * @param string $word = word to search for
	 */
	public function a_leaf($word, $expected)
	{
		$this->labelBlock('Leaf.', 40, '*');

		$this->a_search($word);

		$assertion = '$this->a_node = $this->a_tree->leaf($this->a_node);';
		if (! $this->assertTrue($assertion, sprintf("leaf - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->assertLogMessage((string)$this->a_node);
		$this->a_compareExpectedType(true, $expected, $this->a_node->key());
	}

	/**
	 * a_search
	 *
	 * Search for the word
	 * @param string $word = word to search for
	 * @param boolean $expected = true for successful search, false for unsuccessful search
	 */
	public function a_search($word, $expected=true)
	{
		$this->labelBlock('Search.', 40, '*');

		$assertion = sprintf('$this->a_node = $this->a_tree->search("%s");', $word);

		if ($expected)
		{
			if (! $this->assertTrue($assertion, sprintf("Search - Asserting: %s", $assertion)))
			{
				$this->a_outputAndDie();
			}
		}
		else
		{
			if (! $this->assertFalse($assertion, sprintf("Search Failure - Asserting: %s", $assertion)))
			{
				$this->a_outputAndDie();
			}
		}
		
	}

	/**
	 * a_deleteWordList
	 *
	 * Delete the words in the list from the tree
	 * @param array $wordList = list of words to remove from the tree
	 */
    public function a_deleteWordList($wordList)
    {
    	$this->labelBlock('Delete Word List.', 40, '*');

    	$this->a_printTree();

    	foreach($wordList as $word)
    	{
    		$this->a_deleteWord($word);
    		if (current($wordList))
    		{
    			$this->a_printTree();
    		}
    	}
    }

	/**
     * a_newLLRBTree
     *
     * Create a new LLRBTree instance
     */
    public function a_newLLRBTree()
    {
    	$this->labelBlock('Creating NEW LLRBTree object.', 40, '*');

   		$assertion = '$this->a_tree = new \Library\LLRBTree();';

		if (! $this->assertTrue($assertion, sprintf("NEW LLRBTree object - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
    }

    /**
     * a_deleteLLRBTree
     *
     * Delete the current LLRBTree object.
     */
    public function a_deleteLLRBTree()
    {
    	$this->labelBlock('Delete LLRBTree object.', 40, '*');

   		$assertion = '$this->a_tree = null;';
		if (! $this->assertFalse($assertion, sprintf("Delete LLRBTree object - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
    }

    /**
	 * a_insertWordList
	 *
	 * Insert a list of words into the tree.
	 * @param array $wordList = list of words to be inserted into the tree
	 */
	public function a_insertWordList($wordList)
	{
		$this->labelBlock('Insert Word List.', 40, '*');

		for($index=0; $index < count($wordList); $index++)
		{
			$this->a_wordNumber = $index;
			$this->a_insertWord($wordList[$index]);

			$this->a_root();
			$this->assertLogMessage(sprintf('Root: %u = "%s"', $this->a_data->data(), $this->a_data->key()));
			
			$this->a_nodes($index+1);
			$this->a_printTree();
		}
	}

	/**
	 * a_insertWord
	 *
	 * Insert a word into the tree.
	 * @param string $word = word to be inserted into the tree
	 */
	public function a_insertWord($word)
	{
		$this->labelBlock('Insert Word.', 40, '*');

		$assertion = sprintf('$this->a_node = $this->a_tree->insert("%s", %u);', $word, ++$this->a_wordNumber);

		if (! $this->assertTrue($assertion, sprintf("insert Word - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
	}

	/**
	 * a_root
	 *
	 * Get root node.
	 * @param integer $expected = expected root node number
	 */
	public function a_root()
	{
		$this->labelBlock('Root.', 40, '*');

		$assertion = '$this->a_data = $this->a_tree->root();';

		if (! $this->assertTrue($assertion, sprintf("Root - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
	}

	/**
	 * a_nodes
	 *
	 * Get node count.
	 * @param integer $expected = expected node count.
	 */
	public function a_nodes($expected)
	{
		$this->labelBlock('Nodes.', 40, '*');

		$assertion = '$this->a_data = $this->a_tree->nodes();';

		if (! $this->assertTrue($assertion, sprintf("Nodes - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->a_compareExpected($expected);
	}

	/**
	 * a_printTree
	 *
	 * Print the tree contents.
	 */
	public function a_printTree()
	{
		$this->labelBlock('Print Tree.', 40, '*');

		$assertion = '$this->a_buffer = (string)$this->a_tree;';

		if (! $this->assertTrue($assertion, sprintf("Print Tree - Asserting: %s", $assertion)))
		{
			$this->a_buffer = 'Empty Tree';
		}
		
		$this->assertLogMessage("\n" . $this->a_buffer);
	}

	/**
	 * a_treeTop
	 *
	 * Get the minimum node in the tree.
	 */
	public function a_treeTop()
	{
		$this->labelBlock('Tree Top.', 40, '*');

		$assertion = '$this->a_data = $this->a_tree->treeTop();';

		if (! $this->assertTrue($assertion, sprintf("Tree Top - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->assertLogMessage(sprintf('Tree top: %s = %s', $this->a_data->key(), $this->a_data->data()));
		
	}

	/**
	 * a_directAccess
	 *
	 * Test operation of the __get method
	 * @param array $wordList = list of words to test
	 */
	public function a_directAccess($wordList)
	{
		$this->labelBlock('Direct Access.', 40, '*');

		for($index=0; $index < count($wordList); $index++)
		{
			$this->a_wordNumber = $index;
			$this->a_get($wordList[$index], $index+1);
			$this->assertLogMessage(sprintf('Word: %s = "%s"', $wordList[$index], $this->a_data));
		}
		
		$this->a_getFailure('johnny');
		$this->a_getFailure('redwood');
	}

	/**
	 * a_get
	 *
	 * Get the specified tree value
	 * @param string $word = key of the tree value to get
	 * @param mixed $expected = expected tree value
	 */
	public function a_get($word, $expected)
	{
		$this->labelBlock('Get.', 40, '*');

		$assertion = sprintf('$this->a_data = $this->a_tree->%s;', $word);

		if (! $this->assertTrue($assertion, sprintf("Get - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->a_compareExpected($expected);
	}

	/**
	 * a_getFailure
	 *
	 * Get tree node, with expected failure
	 * @param string $word = word to get
	 */
	public function a_getFailure($word)
	{
		$this->labelBlock('Get Failure.', 40, '*');

		$assertion = sprintf('$this->a_data = $this->a_tree->%s;', $word);

		if (! $this->assertFalse($assertion, sprintf("Get Failure - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
		
		$this->assertLogMessage(sprintf('%s was not found in the tree.', $word));
	}

	/**
	 * a_set
	 *
	 * Set a key directly (direct add to the tree)
	 * @param string $word = key to add to the tree
	 * @param mixed $value = data value
	 */
	public function a_set($word, $value)
	{
		$this->labelBlock('Set.', 40, '*');

		$assertion = sprintf('$this->a_tree->%s = "%s";', $word, $value);

		if (! $this->assertTrue($assertion, sprintf("Set - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
	}

	/**
	 * a_deleteWord
	 *
	 * Delete a word from the tree.
	 * @param string $word = word to be deleted from the tree
	 */
	public function a_deleteWord($word)
	{
		$this->labelBlock('Delete Word.', 40, '*');

		$assertion = sprintf('$this->a_tree->delete("%s");', $word);

		if (! $this->assertFalse($assertion, sprintf("Delete Word - Asserting: %s", $assertion)))
		{
			$this->a_outputAndDie();
		}
	}

}
    