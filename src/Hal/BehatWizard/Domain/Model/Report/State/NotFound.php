<?php

namespace Hal\BehatWizard\Domain\Model\Report\State;

/*
 * This file is part of the Behat Tools
 * (c) 2012 Jean-François Lépine <jeanfrancois@lepine.pro>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * State of a reports : found
 *
 * @author Jean-François Lépine <jeanfrancois@lepine.pro>
 */
class NotFound implements StateInterface
{

    /**
     * Get content of the report file
     *
     * @return string
     */
    public function getFile()
    {
        return null;
    }

    /**
     * Get content of the report file
     *
     * @return string
     */
    public function getReportContent()
    {
        return null;
    }

    /**
     * Count errors
     *
     * @return integer
     */
    public function countErrors()
    {
        return 0;
    }

    /**
     * Count pending
     *
     * @return integer
     */
    public function countPending() {
        return 0;
    }

    /**
     * Count failures
     *
     * @return integer
     */
    public function countFailures()
    {
        return 0;
    }

    /**
     * Count tests
     *
     * @return integer
     */
    public function countTests()
    {
        return 0;
    }

    /**
     * Counts success
     * 
     * @return integer
     */
    public function countSuccess()
    {
        return 0;
    }

    /**
     * Get duration
     *
     * @return float
     */
    public function getDuration()
    {
        return null;
    }

    /**
     * List all tests cases
     *
     * @return Traversable
     */
    public function listTestCases()
    {
        return array();
    }

}