<?php

class MergeHelper_Core_RepoCommandExecutorTest extends PHPUnit_Framework_TestCase {

	protected function _assertThrowsException($sExpectedExceptionClassName, $function) {
		$bExceptionThrown = FALSE;
		try {
			$function();
		} catch (Exception $e) {
			if (is_a($e, $sExpectedExceptionClassName)) $bExceptionThrown = TRUE;
		}
		$this->assertTrue($bExceptionThrown);
	}

	public function setUp() {
		`rm -rf /var/tmp/MergeHelperExecutorTest`;
	}

	public function test_execution() {
		$sCommand = "svn log -r 6 -v --xml file://".realpath(MergeHelper_Helper_Bootstrap::sGetPackageRoot()."/../../tests/_testrepo")." | grep -v '<paths>' | grep -v '</paths>' | grep '<path' -A 2 | grep 'action'";

		$this->assertSame('   action="M">/branches/my-hammer2/_production/2010-01-02/b.php</path>'."\n",
		                  MergeHelper_Core_CommandLineExecutor::oGetInstance()->sGetCommandResult($sCommand));
	}

	public function test_singletonReturnsSameInstance() {
		$this->assertTrue(MergeHelper_Core_CommandLineExecutor::oGetInstance() === MergeHelper_Core_CommandLineExecutor::oGetInstance(),
                         'Singleton is not working!'
		                );

	}

	public function test_singletonIsNotDirectlyInstantiable() {
		$oReflection = new ReflectionClass('MergeHelper_Core_CommandLineExecutor');

		$this->assertFalse($oReflection->isInstantiable(),
		                  'Singleton instantiable. Please declare the construct as private or protected'
		                 );
	}

	public function test_cloningImpossible() {
		$this->_assertThrowsException('MergeHelper_Core_Exception', function() {
			$o = MergeHelper_Core_CommandLineExecutor::oGetInstance();
			clone($o);
		});
	}

}
