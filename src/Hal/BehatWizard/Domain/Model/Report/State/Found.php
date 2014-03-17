<?php

namespace Hal\BehatWizard\Domain\Model\Report\State;

/*
 * This file is part of the BehatWizard
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
class Found implements StateInterface
{

    /**
     * Content of the report
     *
     * @var string
     */
    private $reportContent;

    /**
     * XML Element
     *
     * @var \SimpleXMLElement
     */
    private $xml;

    /**
     * Cache
     *
     * @var array
     */
    private $testsCases;

    /**
     * Constructor
     *
     * @param string $reportContent
     */
    public function __construct($reportContent)
    {
        $this->reportContent = (string) $reportContent;
        $this->xml = new \SimpleXMLElement($this->reportContent);
    }

    /**
     * Get content of the report file
     *
     * @return return string
     */
    public function getFile()
    {
        return (string) $this->xml['file'];
    }

    /**
     * Get content of the report file
     *
     * @return return string
     */
    public function getReportContent()
    {
        return $this->reportContent;
    }

    /**
     * Count errors
     *
     * @return return integer
     */
    public function countErrors()
    {
        return (int) $this->xml['errors'];
    }

    /**
     * Count pending
     *
     * @return integer
     */
    public function countPending()
    {
        return sizeof($this->listTestCasesByType('undefined'));
    }

    /**
     * Count failures
     *
     * @return return integer
     */
    public function countFailures()
    {

        return sizeof($this->listTestCasesByType('failed'));
    }

    /**
     * Count success
     *
     * @return return integer
     */
    public function countSuccess()
    {
        return sizeof($this->listTestCasesByType('success'));
    }

    /**
     * Count tests
     *
     * @return return integer
     */
    public function countTests()
    {
        return (int) sizeof($this->listTestCases());
    }

    /**
     * Get duration
     *
     * @return return float
     */
    public function getDuration()
    {
        return (int) $this->xml['durations'];
    }

    /**
     * List all tests cases
     *
     * @return array|SimpleXMLElement
     */
    public function listTestCases()
    {
        if (!is_null($this->testsCases)) {
            return $this->testsCases;
        }
        $testscases = array();
        foreach ($this->xml->testcase as $case) {
            $testcase = (object) array(
                    'name' => $case['name']
                    , 'classname' => $case['classname']
                    , 'type' => 'success'
                    , 'time' => $case->time
                    , 'nbFails' => sizeof($case->failure)
                    , 'failure' => $case->failure
            );

            if (sizeof($case->failure) > 0) {
                $testcase->type = $case->failure[0]['type'];
            }

            array_push($testscases, $testcase);
        }
        $this->testsCases = $testscases;
        return $testscases;
    }

    /**
     * List all tests cases
     *
     * @param string $type
     * @return array|SimpleXMLElement
     */
    private function listTestCasesByType($type)
    {
        $result = array();
        $testscases = $this->listTestCases();
        if ($type == 'all') {
            return $testscases;
        }

        foreach ($testscases as $case) {
            if ($case->type == $type) {
                array_push($result, $case);
            }
        }
        return $result;
    }

}