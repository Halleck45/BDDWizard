<?php

namespace Hal\BehatWizard\Domain\Entity;

use Behat\Gherkin\Node\ScenarioNode,
    Behat\Gherkin\Node\BackgroundNode;

/*
 * This file is part of the BehatWizard
 * (c) 2012 Jean-François Lépine <jeanfrancois@lepine.pro>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Represents a gherkin definition, and contains a gherkin object
 *
 * @proxy
 * @author Jean-François Lépine <jeanfrancois@lepine.pro>
 */
interface GherkinInterface
{

    public function addScenario(ScenarioNode $scenario);

    public function addTag($tag);

    public function freeze();

    public function getBackground();

    public function getDescription();

    public function getFile();

    public function getLanguage();

    public function getOwnTags();

    public function getScenarios();

    public function getTags();

    public function getTitle();

    public function hasBackground();

    public function hasScenarios();

    public function hasTag($tag);

    public function hasTags();

    public function isFrozen();

    public function setBackground(BackgroundNode $background);

    public function setDescription($description);

    public function setFile($path);

    public function setLanguage($language);

    public function setScenarios(array $scenarios);

    public function setTags(array $tags);

    public function setTitle($title);
}