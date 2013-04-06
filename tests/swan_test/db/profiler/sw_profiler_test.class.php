<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker: */
// +---------------------------------------------------------------------------
// | SWAN [ $_SWANBR_SLOGAN_$ ]
// +---------------------------------------------------------------------------
// | Copyright $_SWANBR_COPYRIGHT_$
// +---------------------------------------------------------------------------
// | Version  $_SWANBR_VERSION_$
// +---------------------------------------------------------------------------
// | Licensed ( $_SWANBR_LICENSED_URL_$ )
// +---------------------------------------------------------------------------
// | $_SWANBR_WEB_DOMAIN_$
// +---------------------------------------------------------------------------
 
namespace swan_test\db\profiler;
use lib\db\profiler\sw_profiler;
use lib\db\profiler\exception\sw_exception;

/**
+------------------------------------------------------------------------------
* sw_db_profiler_test 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @group sw_db
+------------------------------------------------------------------------------
*/
class sw_profiler_test extends \PHPunit_FrameWork_TestCase
{
	// {{{ members

	/**
	 * __profiler 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $__profiler = null;

	// }}}
	// {{{ functions
	// {{{ public function setUp()

	/**
	 * setUp 
	 * 
	 * @access public
	 * @return void
	 */
	public function setUp()
	{
		$this->__profiler = new sw_profiler();	
	}

	// }}}
	// {{{ public function test_enabled()
	
	/**
	 * test_enabled 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_enabled()
	{
		$this->assertFalse($this->__profiler->get_enabled());
		$rev = $this->__profiler->set_enabled(true);
		$this->assertTrue($this->__profiler->get_enabled());
		$this->assertSame($rev, $this->__profiler);
	}

	// }}}	
	// {{{ public function test_filter_elapsed_secs()

	/**
	 * test_filter_elapsed_secs 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_filter_elapsed_secs()
	{
		$this->assertNull($this->__profiler->get_filter_elapsed_secs());
		$rev = $this->__profiler->set_filter_elapsed_secs(2);
		$this->assertSame(2, $this->__profiler->get_filter_elapsed_secs());
		$this->assertSame($rev, $this->__profiler);
	}

	// }}}
	// {{{ public function test_filter_query_type()

	/**
	 * test_filter_query_type 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_filter_query_type()
	{
		$this->assertNull($this->__profiler->get_filter_query_type());
		$rev = $this->__profiler->set_filter_query_type(sw_profiler::INSERT);
		$this->assertEquals(sw_profiler::INSERT, $this->__profiler->get_filter_query_type());
		$this->assertSame($rev, $this->__profiler);
	}

	// }}}
	// {{{ public function test_query_start_return_null()

	/**
	 * test_query_start_return_null 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_query_start_return_null()
	{
		$rev = $this->__profiler->query_start('select * from a;');
		$this->assertNull($rev);
	}

	// }}}
	// {{{ public function test_query_start_type()

	/**
	 * test_query_start_type
	 * 
	 * @access public
	 * @return void
	 */
	public function test_query_start_type()
	{
		$this->__profiler->set_enabled(true);
		$rev = $this->__profiler->query_start('select * from a;');
		$profiler_query = $this->__profiler->get_query_profile($rev);
		$this->assertEquals(sw_profiler::SELECT, $profiler_query->get_query_type());
		$rev = $this->__profiler->query_start('11select * from a;');
		$profiler_query = $this->__profiler->get_query_profile($rev);
		$this->assertEquals(sw_profiler::QUERY, $profiler_query->get_query_type());
	}

	// }}}
	// {{{ public function test_get_query_profile()

	/**
	 * test_get_query_profile 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_query_profile()
	{
		try {
			$this->__profiler->get_query_profile(5);
			$this->fail('Query handle 5 not exists in profiler log. should rasies exception.');	
		} catch (sw_exception $e) {
			$this->assertContains('handle `5` not', $e->getMessage());	
		}
	}

	// }}}
	// {{{ public function test_query_end_return_ignore()

	/**
	 * test_query_end_return_ignore 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_query_end_return_ignore()
	{
		$rev = $this->__profiler->query_end(1);
		$this->assertEquals(sw_profiler::IGNORED, $rev);

		$this->__profiler->set_enabled(true);
		$this->__profiler->set_filter_elapsed_secs(3);
		$qid = $this->__profiler->query_start('select * from a;');
		$rev = $this->__profiler->query_end($qid);
		$this->assertEquals(sw_profiler::IGNORED, $rev);
		$this->__profiler->set_filter_elapsed_secs(null);

		$this->__profiler->set_filter_query_type(sw_profiler::SELECT);
		$qid = $this->__profiler->query_start('select * from a;');
		$rev = $this->__profiler->query_end($qid);
		$this->assertEquals(sw_profiler::IGNORED, $rev);
		$this->__profiler->set_filter_query_type(null);

		$this->__profiler->set_enabled(true);
		$this->__profiler->set_filter_elapsed_secs(1);
		$qid = $this->__profiler->query_start('select * from a;');
		sleep(1);
		$rev = $this->__profiler->query_end($qid);
		$this->assertEquals(sw_profiler::STORED, $rev);
		$this->__profiler->set_filter_elapsed_secs(null);

		$this->__profiler->set_filter_query_type(sw_profiler::SELECT);
		$qid = $this->__profiler->query_start('insert * from a;');
		$rev = $this->__profiler->query_end($qid);
		$this->assertEquals(sw_profiler::STORED, $rev);
	}

	// }}}
	// {{{ public function test_query_end_exception()

	/**
	 * test_query_end_exception 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_query_end_exception()
	{
		$this->__profiler->set_enabled(true);
		try {
			$this->__profiler->query_end(1);
			$this->fail('Profiler has no query with handle 1. should throw exception.');	
		} catch (sw_exception $e) {
			$this->assertContains('has no query with handle `1`', $e->getMessage());	
		}

		$qid = $this->__profiler->query_start('select * from a;');
		$this->__profiler->query_end($qid);
		try {
			$this->__profiler->query_end($qid);
			$this->fail("Query with profiler handle `$qid` has already ended. should throw exception.");	
		} catch (sw_exception $e) {
			$this->assertContains('has already ended', $e->getMessage());	
		}
	}

	// }}}
	// {{{ public function test_get_query_profiles_query_type()
	
	/**
	 * test_get_query_profiles_query_type 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_query_profiles_query_type()
	{
		$this->__profiler->set_enabled(true);
		$qid  = $this->__profiler->query_start('insert into a values(1, 2);');
		$qid1 = $this->__profiler->query_start('insert into a values(1, 2);');
		$qid2 = $this->__profiler->query_start('select * from a;');
		$profiles = $this->__profiler->get_query_profiles();
		$this->assertFalse($profiles);
			
		$this->__profiler->query_end($qid);
		$this->__profiler->query_end($qid1);
		$this->__profiler->query_end($qid2);
		$profiles = $this->__profiler->get_query_profiles();
		$this->assertCount(3, $profiles);
		$profiles = $this->__profiler->get_query_profiles(sw_profiler::INSERT);
		$this->assertCount(2, $profiles);
	}

	// }}}
	// {{{ public function test_get_query_profiles_finished()
	
	/**
	 * test_get_query_profiles_finished
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_query_profiles_finished()
	{
		$this->__profiler->set_enabled(true);
		$qid  = $this->__profiler->query_start('insert into a values(1, 2);');
		$qid1 = $this->__profiler->query_start('insert into a values(1, 2);');
		$qid2 = $this->__profiler->query_start('select * from a;');
		$profiles = $this->__profiler->get_query_profiles();
		$this->assertFalse($profiles);

		$profiles = $this->__profiler->get_query_profiles(null, true);
		$this->assertCount(3, $profiles);
			
		$this->__profiler->query_end($qid);
		$this->__profiler->query_end($qid1);
		$this->__profiler->query_end($qid2);
		$profiles = $this->__profiler->get_query_profiles();
		$this->assertCount(3, $profiles);
	}

	// }}}
	// {{{ public function test_get_total_elapsed_secs()

	/**
	 * test_get_total_elapsed_secs 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_total_elapsed_secs()
	{
		$this->__profiler->set_enabled(true);
		$qid = $this->__profiler->query_start('select * from a;');
		$this->assertEquals(0, $this->__profiler->get_total_elapsed_secs());

		$this->__profiler->query_end($qid);
		$this->assertGreaterThan(0, $this->__profiler->get_total_elapsed_secs());
	}

	// }}}
	// {{{ public function test_get_total_num_queries()

	/**
	 * test_get_total_num_queries
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_total_num_queries()
	{
		$this->__profiler->set_enabled(true);
		$qid = $this->__profiler->query_start('select * from a;');
		$this->assertEquals(1, $this->__profiler->get_total_num_queries());

		$this->assertEquals(0, $this->__profiler->get_total_num_queries(sw_profiler::SELECT));
		$this->__profiler->query_end($qid);
		$this->assertEquals(1, $this->__profiler->get_total_num_queries(sw_profiler::SELECT));
	}

	// }}}
	// {{{ public function test_get_last_query_profile()

	/**
	 * test_get_last_query_profile 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_last_query_profile()
	{
		$this->__profiler->set_enabled(true);
		$this->assertFalse($this->__profiler->get_last_query_profile());

		$this->__profiler->query_start('select * from a;');
		$this->assertContains('select * from a;', $this->__profiler->get_last_query_profile()->get_query());
	}

	// }}}
	// }}}
}
