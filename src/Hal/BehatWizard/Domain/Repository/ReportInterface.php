<?php

namespace Hal\BehatWizard\Domain\Repository;

use Hal\BehatWizard\Domain\Entity\GherkinInterface;

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
interface ReportInterface
{

    /**
     * Get the report of the given feature
     *
     * @param GherkinInterface $feature
     * @return Report
     */
    public function getReportByFeature(GherkinInterface $feature);

    /**
     * Get all reports
     *
     * @return array
     */
    public function getReports();
}