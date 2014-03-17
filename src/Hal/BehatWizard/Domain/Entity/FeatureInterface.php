<?php

namespace Hal\BehatWizard\Domain\Entity;

/*
 * This file is part of the BehatWizard
 * (c) 2012 Jean-François Lépine <jeanfrancois@lepine.pro>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Represents a feature
 *
 * @author Jean-François Lépine <jeanfrancois@lepine.pro>
 */
interface FeatureInterface
{

    /**
     * Get the state of a feature
     *
     * @return ReportInterface
     */
    public function getReport();

    /**
     * Get the current gherkin object
     *
     * @return GherkinInterface
     */
    public function getGherkin();

    /**
     * Get a hash to identify the feature
     *
     * @return string
     */
    public function getHash();
}