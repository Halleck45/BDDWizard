<?php

namespace Hal\BehatWizard\Domain\Entity;

/*
 * This file is part of the Behat Tools
 * (c) 2012 Jean-François Lépine <jeanfrancois@lepine.pro>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Represents a report (state of the feature)
 *
 * @author Jean-François Lépine <jeanfrancois@lepine.pro>
 */
interface ReportInterface
{

    /**
     * Get content of the report file
     *
     * @return string
     */
    public function getReportContent();

    /**
     * Count errors
     *
     * @return integer
     */
    public function countErrors();

    /**
     * Count failures
     * 
     * @return integer
     */
    public function countFailures();

    /**
     * Count success
     *
     * @return integer
     */
    public function countSuccess();

    /**
     * Count pending
     *
     * @return integer
     */
    public function countPending();

    /**
     * Count tests
     *
     * @return integer
     */
    public function countTests();

    /**
     * Get filename
     *
     * @return string
     */
    public function getFile();

    /**
     * Get duration
     * 
     * @return float
     */
    public function getDuration();

    /**
     * List all tests cases
     *
     * @return array|SimpleXMLElement
     */
    public function listTestCases();
}