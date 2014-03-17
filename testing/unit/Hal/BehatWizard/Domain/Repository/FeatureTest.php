<?php

namespace Hal\BehatWizard\Tests;

use \Hal\BehatWizard\Domain\Repository\Feature as Repo_Feature;

class RespositoryFeatureTest extends \PHPUnit_Framework_TestCase
{

    private $repo;

    public function setUp()
    {
        $factory = $this->getMock('Hal\BehatWizard\Domain\Factory\FeatureInterface');
        $feature = $this->getMock('Hal\BehatWizard\Domain\Entity\FeatureInterface');
        $factory
            ->expects($this->any())
            ->method('factory')
            ->will($this->returnValue($feature));
        $folder = __DIR__ . '/../../../../resources/fixtures/features/';
        $this->repo = new Repo_Feature($folder, $factory);
    }

    public function testRepoFindsAllFeatures()
    {
        $features = $this->repo->getFeatures();
        $this->assertCount(5, $features, 'All features are found');
    }

    public function testRepoFindFeatureByItsPath()
    {
        $feature = $this->repo->getFeatureByPath('all-correct.feature');
        $this->assertNotNull($feature, 'Feature can be found by its path');
        $this->assertInstanceOf('Hal\BehatWizard\Domain\Entity\FeatureInterface', $feature, 'Feature can be found by its path');
    }

    public function testUnexistentFeatureIsNotFound()
    {
        $feature = $this->repo->getFeatureByPath('toto');
        $this->assertNull($feature, 'Unexistent feature is not found');
    }

}