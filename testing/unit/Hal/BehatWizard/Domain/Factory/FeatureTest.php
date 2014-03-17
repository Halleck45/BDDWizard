<?php

namespace Hal\BehatWizard\Tests;

use Hal\BehatWizard\Domain\Factory\Feature as FeatureFactory;

class FactoryFeatureTest extends \PHPUnit_Framework_TestCase
{

    private $factory;

    public function setUp()
    {


        $keywords = new \Behat\Gherkin\Keywords\ArrayKeywords(
                array(
                    'en' => array(
                        'feature' => 'Feature',
                        'background' => 'Background',
                        'scenario' => 'Scenario',
                        'scenario_outline' => 'Scenario Outline|Scenario Template',
                        'examples' => 'Examples|Scenarios',
                        'given' => 'Given',
                        'when' => 'When',
                        'then' => 'Then',
                        'and' => 'And',
                        'but' => 'But'
                    )
                )
        );
        $keywords->setLanguage('en');
        $lexer = $this->getMock('Behat\Gherkin\Lexer', null, array($keywords));
        $parser = $this->getMock('Behat\Gherkin\Parser', null, array($lexer));

        $report = $this->getMock('Hal\BehatWizard\Domain\Entity\ReportInterface');
        $repository = $this->getMock('Hal\BehatWizard\Domain\Repository\ReportInterface');
        $container = $this->getMock('\Symfony\Component\DependencyInjection\ContainerInterface');
        $repository
            ->expects($this->any())
            ->method('getReportByFeature')
            ->will($this->returnValue($report));

        $container = $this->getMock('Silex\Application');
        $this->factory = new FeatureFactory($parser, $repository, $container);
    }

    public function testWeCanFactoryAFeatureProvidingItsFilename()
    {

        $filename = __DIR__ . '/../../../../resources/fixtures/features/all-correct.feature';
        $feature = $this->factory->factory($filename);
        $this->assertInstanceOf('Hal\BehatWizard\Domain\Entity\FeatureInterface', $feature, 'We can factory a feature');
    }

    /**
     * @expectedException Hal\BehatWizard\Domain\Factory\NotBuildableException
     */
    public function testWeCannotFactoryAnUnexistentFeature()
    {
        $filename = __DIR__ . 'unexistent.feature';
        $feature = $this->factory->factory($filename);
    }

}