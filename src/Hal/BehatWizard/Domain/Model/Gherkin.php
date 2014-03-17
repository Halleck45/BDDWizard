<?php

namespace Hal\BehatWizard\Domain\Model;

use Behat\Gherkin\Node\FeatureNode as FeatureNode,
    Hal\BehatWizard\Domain\Entity\FeatureInterface,
    Hal\BehatWizard\Domain\Entity\GherkinInterface,
    Hal\BehatWizard\Domain\Entity\ReportInterface;
use Behat\Gherkin\Node\ScenarioNode,
    Behat\Gherkin\Node\BackgroundNode;

/*
 * This file is part of the Behat Tools
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
class Gherkin implements GherkinInterface
{

    /**
     * Feature node
     *
     * @var FeatureNode
     */
    private $featureNode;

    /**
     * Constructor
     *
     * @param FeatureNode $gherkin
     */
    public function __construct(FeatureNode $gherkin)
    {
        $this->featureNode = $gherkin;
    }

    /**
     * Get the feature node
     * 
     * @return FeatureNode
     */
    public function getFeatureNode()
    {
        return $this->featureNode;
    }

    public function addScenario(ScenarioNode $scenario)
    {
        $this->featureNode->addScenario($scenario);
    }

    public function addTag($tag)
    {
        $this->featureNode->addTag($tag);
    }

    public function freeze()
    {
        $this->featureNode->freeze();
    }

    public function getBackground()
    {
        return $this->featureNode->getBackground();
    }

    public function getDescription()
    {
        return $this->featureNode->getDescription();
    }

    public function getFile()
    {
        return $this->featureNode->getFile();
    }

    public function getLanguage()
    {
        return $this->featureNode->getLanguage();
    }

    public function getOwnTags()
    {
        return $this->featureNode->getOwnTags();
    }

    public function getScenarios()
    {
        return $this->featureNode->getScenarios();
    }

    public function getTags()
    {
        return $this->featureNode->getTags();
    }

    public function getTitle()
    {
        return $this->featureNode->getTitle();
    }

    public function hasBackground()
    {
        return $this->featureNode->hasBackground();
    }

    public function hasScenarios()
    {
        return $this->featureNode->hasScenarios();
    }

    public function hasTag($tag)
    {
        return $this->featureNode->hasTag($tag);
    }

    public function hasTags()
    {
        return $this->featureNode->hasTags();
    }

    public function isFrozen()
    {
        return $this->featureNode->isFrozen();
    }

    public function setBackground(BackgroundNode $background)
    {
        return $this->featureNode->setBackground($background);
    }

    public function setDescription($description)
    {
        return $this->featureNode->setDescription($description);
    }

    public function setFile($path)
    {
        return $this->featureNode->setFile($path);
    }

    public function setLanguage($language)
    {
        return $this->featureNode->setLanguage($language);
    }

    public function setScenarios(array $scenarios)
    {
        return $this->featureNode->setScenarios($scenarios);
    }

    public function setTags(array $tags)
    {
        return $this->featureNode->setTags($tags);
    }

    public function setTitle($title)
    {
        return $this->featureNode->setTitle($title);
    }

}