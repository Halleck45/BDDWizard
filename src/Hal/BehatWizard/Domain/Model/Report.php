<?php

namespace Hal\BehatWizard\Domain\Model;

use Hal\BehatWizard\Domain\Model\Report\State\NotFound,
    Hal\BehatWizard\Domain\Model\Report\State\Found,
    Hal\BehatWizard\Domain\Model\Report\State\StateInterface,
    Hal\BehatWizard\Domain\Entity\ReportInterface;

/*
 * This file is part of the Behat Tools
 * (c) 2012 Jean-François Lépine <jeanfrancois@lepine.pro>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Contains the state of a feature, according to a given report content
 *
 * @author Jean-François Lépine <jeanfrancois@lepine.pro>
 */
class Report implements ReportInterface
{

    /**
     * State
     *
     * @var StateInterface
     */
    private $state;

    public function __construct($content)
    {
        $content = trim($content);
        switch ($content) {
            case null:
                $this->state = new NotFound;
                break;
            case '<?xml version="1.0" encoding="UTF-8"?>':
                $this->state = new NotFound;
                break;
            default:
                $this->state = new Found($content);
                break;
        }
    }

    /**
     * Get content of the report file
     *
     * @return string
     */
    public function getReportContent()
    {
        return $this->state->getReportContent();
    }

    /**
     * Count success
     *
     * @return integer
     */
    public function countSuccess()
    {
        return $this->state->countSuccess();
    }

    /**
     * Count errors
     *
     * @return integer
     */
    public function countErrors()
    {
        return $this->state->countErrors();
    }

    /**
     * Count failures
     *
     * @return integer
     */
    public function countFailures()
    {
        return $this->state->countFailures();
    }

    /**
     * Count tests
     *
     * @return integer
     */
    public function countTests()
    {
        return $this->state->countTests();
    }

    /**
     * Count pending
     *
     * @return integer
     */
    public function countPending() {
        return $this->state->countPending();
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getFile()
    {
        return $this->state->getFile();
    }

    /**
     * Get duration
     *
     * @return float
     */
    public function getDuration()
    {
        return $this->state->getDuration();
    }

    /**
     * List all tests cases
     *
     * @return Traversable
     */
    public function listTestCases()
    {
        return $this->state->listTestCases();
    }

}
