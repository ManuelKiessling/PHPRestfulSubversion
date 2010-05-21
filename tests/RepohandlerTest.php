<?php

class MergeHelper_RepohandlerTest extends PHPUnit_Framework_TestCase {

	public function setUp() {

		$oRepo = new MergeHelper_Repo();
		$oRepo->setType(MergeHelper_Repo::TYPE_SVN);
		$oRepo->setLocation('file://'.realpath(MergeHelper_Bootstrap::sGetPackageRoot().'/../tests/_testrepo'));
		$oRepo->setCacheDirectory(realpath(MergeHelper_Bootstrap::sGetPackageRoot().'/../tests/_testrepocache'));
		$oRepo->setAuthinfo('user.name', 'secret');
		$oRepo->addSourcePath(new MergeHelper_RepoPath('/branches/my-hammer2/_production'));
		$oRepo->addSourcePath(new MergeHelper_RepoPath('/branches/my-hammer2/_project'));
		$oRepo->setTargetPath(new MergeHelper_RepoPath('/branches/my-hammer2/_approval'));
		$this->oRepo = $oRepo;

	}

	public function test_getRevisionsForString() {
		
		$aoRevisions = MergeHelper_Repohandler::aoGetRevisionsForString($this->oRepo, 'TF-4001');
		$this->assertSame(2, sizeof($aoRevisions));
		$this->assertSame('3', $aoRevisions[0]->sGetNumber());
		$this->assertSame('5', $aoRevisions[1]->sGetNumber());
		
	}
	
	public function test_getRevisionsForStringNoStringGiven() {
		
		$aoRevisions = MergeHelper_Repohandler::aoGetRevisionsForString($this->oRepo, '');
		$this->assertSame(0, sizeof($aoRevisions));
		
	}

	public function test_checkIfRevisionsAreInSameSourcePath() {
	
		$aoRevisions = array(new MergeHelper_Revision('3'), new MergeHelper_Revision('5'));
		$this->assertTrue(MergeHelper_Repohandler::bRevisionsAreInSameSourcePath($this->oRepo, $aoRevisions));
		
		$aoRevisions = array(new MergeHelper_Revision('5'), new MergeHelper_Revision('6'));
		$this->assertFalse(MergeHelper_Repohandler::bRevisionsAreInSameSourcePath($this->oRepo, $aoRevisions));
	
	}
	
	public function test_checkIfRevisionsAreInSameSourcePathNoRevisionsGiven() {
	
		$aoRevisions = array();
		$this->assertFalse(MergeHelper_Repohandler::bRevisionsAreInSameSourcePath($this->oRepo, $aoRevisions));
	
	}
	
	public function test_getCommonSourcePathForFullPath() {
	
		$this->assertEquals(new MergeHelper_RepoPath('/branches/my-hammer2/_production/2010-01-01'), MergeHelper_Repohandler::oGetCommonSourcePathForFullPath($this->oRepo, new MergeHelper_RepoPath('/branches/my-hammer2/_production/2010-01-01/lala/lulu/blah.txt')));
	
	}
	
	public function test_getCommonSourcePathForFullPathNoSourcePaths() {
	
		$oRepo = new MergeHelper_Repo();
		$oRepo->setType(MergeHelper_Repo::TYPE_SVN);
		$oRepo->setLocation('file://'.realpath(MergeHelper_Bootstrap::sGetPackageRoot().'/../tests/_testrepo'));
		$oRepo->setTargetPath(new MergeHelper_RepoPath('/branches/my-hammer2/_approval'));
		$this->assertEquals(NULL, MergeHelper_Repohandler::oGetCommonSourcePathForFullPath($oRepo, new MergeHelper_RepoPath('/branches/my-hammer2/_production/2010-01-01/lala/lulu/blah.txt')));
	
	}

	public function test_getCommonBasePathForFullPathNoSourcePaths() {

		$oRepo = new MergeHelper_Repo();
		$oRepo->setType(MergeHelper_Repo::TYPE_SVN);
		$oRepo->setLocation('file://'.realpath(MergeHelper_Bootstrap::sGetPackageRoot().'/../tests/_testrepo'));
		$oRepo->setTargetPath(new MergeHelper_RepoPath('/branches/my-hammer2/_approval'));
		$this->assertEquals(NULL, MergeHelper_Repohandler::oGetCommonBasePathForFullPath($oRepo, new MergeHelper_RepoPath('/branches/my-hammer2/_production/2010-01-01/lala/lulu/blah.txt')));

	}
	
	public function test_getPathsForRevisions() {
	
		$aoRevisions = array(new MergeHelper_Revision('3'), new MergeHelper_Revision('4'));
		$aoPaths = MergeHelper_Repohandler::aoGetPathsForRevisions($this->oRepo, $aoRevisions);
		$this->assertSame(3, sizeof($aoPaths));
		$this->assertEquals(new MergeHelper_RepoPath('/branches/my-hammer2/_production/2010-01-01/c/d.php'), $aoPaths[0]);
		$this->assertEquals(new MergeHelper_RepoPath('/branches/my-hammer2/_production/2010-01-01/a.php'), $aoPaths[1]);
		$this->assertEquals(new MergeHelper_RepoPath('/branches/my-hammer2/_project/TF-0001/a.php'), $aoPaths[2]);
	}
	
	public function test_getMergeCommandlineForRevisionAndPath() {
	
		$asCommandlines = MergeHelper_Repohandler::asGetMergeCommandlinesForRevisionsAndPaths($this->oRepo,
		                                                                                      array(
		                                                                                       array(
		                                                                                        new MergeHelper_Revision('4'),
		                                                                                        new MergeHelper_RepoPath('/branches/my-hammer2/_production/2010-01-01/c/d.php'),
		                                                                                        '.',
		                                                                                        FALSE
		                                                                                       ),
		                                                                                       array(
		                                                                                        new MergeHelper_Revision('3'),
		                                                                                        new MergeHelper_RepoPath('/branches/my-hammer2/_production/2010-01-01/c/d.php'),
		                                                                                        '.',
		                                                                                        TRUE
		                                                                                       )
		                                                                                      ));
		$this->assertSame('svn merge -c -3 file://'.realpath(MergeHelper_Bootstrap::sGetPackageRoot().'/../tests/_testrepo').'/branches/my-hammer2/_production/2010-01-01/c/d.php ./c/d.php',
		                  $asCommandlines[0]);
		$this->assertSame('svn merge -c 4 file://'.realpath(MergeHelper_Bootstrap::sGetPackageRoot().'/../tests/_testrepo').'/branches/my-hammer2/_production/2010-01-01/c/d.php ./c/d.php',
		                  $asCommandlines[1]);
	
	}
	
	public function test_getCommonSourcePathForRevision() {
	
			$this->assertSame('/branches/my-hammer2/_project/TF-0001', (string)MergeHelper_Repohandler::oGetCommonSourcePathForRevision($this->oRepo, new MergeHelper_Revision('4')));
			$this->assertNull(MergeHelper_Repohandler::oGetCommonSourcePathForRevision($this->oRepo, new MergeHelper_Revision('8')));
	
	}

	public function test_getCommonBasePathForRevision() {

			$this->assertSame('/branches/my-hammer2/_approval/2010-01-03_TF-3000', (string)MergeHelper_Repohandler::oGetCommonBasePathForRevision($this->oRepo, new MergeHelper_Revision('8')));

	}

}
