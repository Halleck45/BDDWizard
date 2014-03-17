<?php

namespace Hal\BehatWizard\Domain\Model;

use Behat\Gherkin\Node\FeatureNode as FeatureNode,
    Behat\Gherkin\Dumper\GherkinDumper as Dumper,
    Behat\Gherkin\Keywords\KeywordsInterface,
    Hal\BehatWizard\Domain\Entity\FeatureInterface,
    Hal\BehatWizard\Domain\Entity\GherkinInterface,
    Hal\BehatWizard\Domain\Entity\ReportInterface,
    Hal\BehatWizard\Domain\Model\Feature\Writer\WritteableInterface,
    Hal\BehatWizard\Domain\Repository\FeatureInterface as Repo_FeatureInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/*
 * This file is part of the BehatWizard
 * (c) 2012 Jean-François Lépine <jeanfrancois@lepine.pro>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Represents a feature, with its state
 *
 * @author Jean-François Lépine <jeanfrancois@lepine.pro>
 */
class Feature implements FeatureInterface, WritteableInterface
{

    /**
     * Contains the feature node
     *
     * @var FeatureNode
     */
    protected $gherkinObject;

    /**
     * Infos about the state of the feature
     * @var ReportInterface
     */
    protected $report;

    /**
     * Keywords used by behat
     *
     * @var KeywordsInterface
     */
    protected $keywords;

    /**
     * Feature manager
     *
     * @var Repo_FeatureInterface
     */
    protected $featureRepository;

    /**
     * Folder
     *
     * @var string
     */
    protected $folder;

    /**
     * Constructor
     *
     * @param FeatureNode $feature
     * @param Report $report
     */
    public function __construct(GherkinInterface $feature, ReportInterface $report,  $container)
    {
        $this->gherkinObject = $feature;
        $this->report = $report;
        $this->keywords = $container['gherkin.keywords'];
        $this->featureRepository = $container['hbt.feature.repository'];
        $this->folder = rtrim($container['behat.paths.features'], DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }

    /**
     * Get the state of a feature
     *
     * @return ReportInterface
     */
    public function getReport()
    {
        return $this->report;
    }

    /**
     * Get the current feature
     *
     * @return GherkinInterface
     */
    public function getGherkin()
    {
        return $this->gherkinObject;
    }

    /**
     * Get a hash to identify the feature
     *
     * @return string
     */
    public function getHash()
    {
        return md5($this->getGherkin()->getFile());
    }

    /**
     * Get the content of the feature
     *
     * @return string
     */
    public function getContent()
    {
        $dumper = new Dumper($this->keywords);
        return $dumper->dump($this->getGherkin()->getFeatureNode());
    }

    /**
     * Set the content of the feature
     *
     * @param string
     */
    public function setContent($content)
    {

        // @tmp
        // @todo
        // don't use directly the DIC
        $filename = 'tmp-' . uniqid() . '.feature';
        $path = $this->folder . $filename;
        file_put_contents($path, $content);
        $feature = $this->featureRepository->getFeatureByPath($filename);
        unlink($path);
        if ($feature === null) {
            throw new \Exception('Cannot set the content of the feature');
        }
        $this->gherkinObject = $feature->getGherkin();
    }

}