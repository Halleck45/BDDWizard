<?php

namespace Hal\BehatWizard\Tests;

use \Hal\BehatWizard\Domain\Repository\Report as Repo_Report;

class RespositoryReportTest extends \PHPUnit_Framework_TestCase
{

    private $repo;

    public function setUp()
    {
        $folder = __DIR__ . '/../../../../resources/fixtures/reports/';
        $this->repo = new Repo_Report($folder);
    }


    public function testRepoFindReportByItsFeature()
    {

        $feature = $this->getMock('Hal\BehatWizard\Domain\Entity\GherkinInterface');
        $feature
            ->expects($this->any())
            ->method('getFile')
            ->will($this->returnValue(__DIR__.'../../Fixtures/features/all-correct.feature'));

        $report = $this->repo->getReportByFeature($feature);

        $this->assertInstanceOf('Hal\BehatWizard\Domain\Entity\ReportInterface', $report, 'Report can be found by its path');
    }

    public function testUnexistentReportIsCreatedButIsEmpty()
    {

        $feature = $this->getMock('Hal\BehatWizard\Domain\Entity\GherkinInterface');
        $feature
            ->expects($this->any())
            ->method('getFile')
            ->will($this->returnValue(__DIR__.'/../../../../resources/fixtures/features/unexistent.feature'));

        $report = $this->repo->getReportByFeature($feature);
        $this->assertInstanceOf('Hal\BehatWizard\Domain\Entity\ReportInterface', $report, 'Unexistent report is created but is empty');
    }

}