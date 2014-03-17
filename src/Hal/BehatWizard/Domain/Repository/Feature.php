<?php

namespace Hal\BehatWizard\Domain\Repository;

use Symfony\Component\Finder\Finder,
    Hal\BehatWizard\Domain\Repository\FeatureInterface as Repo_FeatureInterface,
    Hal\BehatWizard\Domain\Factory\FeatureInterface as FeatureFactoryInterface,
    Hal\BehatWizard\Domain\Entity\FeatureInterface as EntityFeatureInterface,
    Hal\BehatWizard\Domain\Model\Feature\Writer\WritteableInterface;

/*
 * This file is part of the Behat Tools
 * (c) 2012 Jean-François Lépine <jeanfrancois@lepine.pro>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Report Manager
 *
 * @author Jean-François Lépine <jeanfrancois@lepine.pro>
 */
class Feature implements Repo_FeatureInterface {

    /**
     * features path
     *
     * @var string
     */
    private $folder;

    /**
     * Feature factory
     *
     * @var FeatureFactoryInterface
     */
    private $featureFactory;

    /**
     * Constructor
     *
     * @param string $testsFolder
     */
    public function __construct($testsFolder, FeatureFactoryInterface $featureFactory) {
        $this->folder = (string) rtrim($testsFolder, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $this->featureFactory = $featureFactory;
    }

    /**
     * Get features
     *
     * @return array
     */
    public function getFeatures() {
        $finder = new Finder();
        $finder->files()->in($this->folder)->name('*.feature');
        $features = array();

        foreach ($finder as $file) {
            $filename = $file->getRelativePathname();
            $node = $this->factoryGherkinFeature($file->getRealpath());
            array_push($features, $node);
        }

        return $features;
    }

    /**
     * get feature by its path
     *
     * @param string $filename
     * @return null|Behat\Gherkin\Node\FeatureNode
     */
    public function getFeatureByPath($filename) {
        $finder = new Finder();
        $finder->files()->in($this->folder . dirname($filename))->name(basename($filename));
        foreach ($finder as $file) {
            return $this->factoryGherkinFeature($file->getRealpath());
        }
        return null;
    }

    /**
     * Get pending features
     *
     * @return array
     */
    public function getPendingFeatures() {
        $features = $this->getFeatures();
        $stack = array();
        foreach ($features as $feature) {
            if ($feature->getReport()->countTests() == 0) {
                array_push($stack, $feature);
            }
        }
        return $stack;
    }

    /**
     * Get valid features
     *
     * @return array
     */
    public function getValidFeatures() {
        $features = $this->getFeatures();
        $stack = array();
        foreach ($features as $feature) {
            if ($feature->getReport()->countErrors() == 0
                    && $feature->getReport()->countFailures() == 0) {
                array_push($stack, $feature);
            }
        }
        return $stack;
    }

    /**
     * Get fails features
     * 
     * @return array
     */
    public function getFailingFeatures() {
        $features = $this->getFeatures();
        $stack = array();
        foreach ($features as $feature) {
            if ($feature->getReport()->countErrors() >= 0
                    || $feature->getReport()->countFailures() > 0) {
                array_push($stack, $feature);
            }
        }
        return $stack;
    }

    /**
     * Factory a gherkin node
     *
     * @param string $filename
     * @return EntityFeatureInterface
     */
    public function factoryGherkinFeature($filename) {
        return $this->featureFactory->factory($filename);
    }

    /**
     * Get the relative path of the given feature
     *
     * @param FeatureNode $node
     * @return type
     */
    public function getRelativePath(FeatureInterface $node) {
        return str_replace($this->folder, '', $node->getFile());
    }

    /**
     * Get features
     *
     * @return null|EntityFeatureInterface
     */
    public function loadFeatureByHash($hash) {
        $features = $this->getFeatures();
        foreach ($features as $feature) {
            if ($feature->getHash() === $hash) {
                return $feature;
            }
        }
        return null;
    }

    /**
     * Save the given feature
     *
     * @todo use Symfony component
     * 
     * @param EntityFeatureInterface $feature
     */
    public function saveFeature(WritteableInterface $feature) {
        $this->removeFeature($feature);
        $gherkin = $feature->getGherkin();
        $name = strtolower($gherkin->getTitle());
        $name = preg_replace('!([^A-Za-z0-9])!', '-', $name);
        $name = preg_replace('!--*!', '-', $name);
        $filename = $this->folder . $name . '.feature';
        file_put_contents($filename, $feature->getContent());
    }

    /**
     * Create a new feature
     *
     * @return EntityFeatureInterface $feature
     */
    public function createFeature() {
        $content = "Feature: My Feature\n\n Scenario: My scenario\n    Given anything";
        $name = 'tmp-' . uniqid() . '.feature';
        $filename = $this->folder . $name;
        file_put_contents($filename, $content);
        $feature = $this->getFeatureByPath($name);
        unlink($filename);
        return $feature;
    }

    /**
     * Remove the feature
     *
     * @param EntityFeatureInterface $feature
     */
    public function removeFeature(EntityFeatureInterface $feature) {
        $gherkin = $feature->getGherkin();
        $filename = $gherkin->getFile();
        if (file_exists($filename)) {
            unlink($filename);
        }
    }

}