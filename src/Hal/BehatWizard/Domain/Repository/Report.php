<?php

namespace Hal\BehatWizard\Domain\Repository;

use Symfony\Component\Finder\Finder,
    Hal\BehatWizard\Domain\Entity\GherkinInterface,
    Hal\BehatWizard\Domain\Model\Report as ModelReport,
    Hal\BehatWizard\Domain\Repository\ReportInterface as Repo_ReportInterface;

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
class Report implements Repo_ReportInterface
{

    /**
     * Folder of reports
     *
     * @var string
     */
    private $reportFolder;

    /**
     * Reports
     *
     * @var array
     */
    private $reports;

    /**
     * Constructor
     *
     * @param string $reportFolder
     */
    public function __construct($reportFolder)
    {
        $this->reportFolder = (string) rtrim($reportFolder, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        $finder = new Finder();
        $finder->files()->in($this->reportFolder)->name('*.xml');
        $reports = array();

        foreach ($finder as $file) {
            $filename = $file->getRelativePathname();
            $report = new ModelReport(file_get_contents($file->getRealpath()));
            array_push($reports, $report);
        }

        $this->reports = $reports;
    }

    /**
     * Get the report of the given feature
     *
     * @param GherkinInterface $feature
     * @return ModelReport
     */
    public function getReportByFeature(GherkinInterface $feature)
    {
        $filename = $feature->getFile();
        foreach ($this->reports as $report) {
            if ($report->getFile() === $feature->getFile()) {
                return $report;
            }
        }

        return new ModelReport(null);
    }

    /**
     * Get all reports
     *
     * @return array
     */
    public function getReports() {
        return $this->reports;
    }

}