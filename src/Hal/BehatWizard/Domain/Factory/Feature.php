<?php

namespace Hal\BehatWizard\Domain\Factory;

use Symfony\Component\Finder\Finder,
    Behat\Gherkin\Lexer,
    Behat\Gherkin\Parser,
    Behat\Gherkin\Keywords\KeywordsInterface;
use Hal\BehatWizard\Domain\Repository\ReportInterface as Repo_ReportInterface,
    Hal\BehatWizard\Domain\Repository\FeatureInterface as Repo_FeatureInterface,
    Hal\BehatWizard\Domain\Factory\FeatureInterface as Factory_FeatureInterface,
    Hal\BehatWizard\Domain\Entity\FeatureInterface,
    Hal\BehatWizard\Domain\Model\Feature as ModelFeature,
    Hal\BehatWizard\Domain\Model\Gherkin as ModelGherkin;

use Symfony\Component\DependencyInjection\ContainerInterface;

/*
 * This file is part of the BehatWizard
 * (c) 2012 Jean-François Lépine <jeanfrancois@lepine.pro>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Allows to factory a featureElement
 *
 * @author Jean-François Lépine <jeanfrancois@lepine.pro>
 */
class Feature implements Factory_FeatureInterface
{

    /**
     * Report manager
     *
     * @var Repo_FeatureInterface
     */
    private $reportRepository;

    /**
     * Feature manager
     *
     * @var Repo_FeatureInterface
     */
    private $featureRepository;

    /**
     * Keywords used by behat
     *
     * @var ContainerInterface
     */
    private $container;

    /**
     * Constructor
     *
     * @param string $testsFolder
     */
    public function __construct(Parser $parser, Repo_ReportInterface $reportManager,  $container)
    {
        $this->parser = $parser;
        $this->reportRepository = $reportManager;
        $this->container = $container;
    }

    /**
     * Factory a featureElement
     * 
     * @return FeatureInterface
     */
    public function factory($filename)
    {
        if (!file_exists($filename)) {
            throw new NotBuildableException(sprintf('File "%s" not found', $filename));
        }
        $feature = $this->parser->parse(file_get_contents($filename), $filename);
        $proxy = new ModelGherkin($feature);
        $report = $this->reportRepository->getReportByFeature($proxy);
        return new ModelFeature($proxy, $report, $this->container);
    }

}