<?php

namespace Hal\BehatWizard\Domain\Model\Feature\Dumper;

use Behat\Gherkin\Node\FeatureNode as FeatureNode,
    Hal\BehatWizard\Domain\Entity\FeatureInterface,
    Hal\BehatWizard\Domain\Entity\GherkinInterface,
    Hal\BehatWizard\Domain\Entity\ReportInterface;
use \Behat\Gherkin\Node\OutlineNode,
    \Behat\Gherkin\Node\AbstractScenarioNode,
    \Behat\Gherkin\Node\BackgroundNode
;
use Symfony\Component\Serializer\Serializer,
    Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer,
    Symfony\Component\Serializer\Encoder\JsonEncoder;

/*
 * This file is part of the BehatWizard
 * (c) 2012 Jean-François Lépine <jeanfrancois@lepine.pro>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Get a representation a feature
 *
 * @author Jean-François Lépine <jeanfrancois@lepine.pro>
 */
class Json extends DumperAbstract {

    /**
     * Dump
     *
     * @return string
     */
    public function dump() {
        $gherkin = $this->feature->getGherkin();
        $description = array_pad(preg_split('!\n!', $gherkin->getDescription(), 4), 4, '');

        $result = array(
            'title' => $gherkin->getTitle()
            , 'notes' => $description[3]
            , 'inorder' => $description[0]
            , 'as' => $description[1]
            , 'should' => $description[2]
            , 'scenarios' => array()
            , 'background' => array()
        );

        //
        // Scenarios
        foreach ($gherkin->getScenarios() as $scenario) {
            array_push($result['scenarios'], $this->_dumpSteppable($scenario));
        }

        //
        // Background
        if ($gherkin->getBackground()) {
            $result['background'] = $this->_dumpSteppable($gherkin->getBackground());
        }

        $jsonEncoder = new JsonEncoder();
        return $jsonEncoder->encode($result, 'json');
    }

    /**
     * Dump content for scenario and background nodes
     * 
     * @param AbstractScenarioNode $node
     * @return string
     */
    protected function _dumpSteppable(AbstractScenarioNode $node) {
        $newNode = array(
            'title' => ($node instanceof BackgroundNode ? '' : $node->getTitle())
            , 'steps' => array()
            , 'isOutline' => ($node instanceof OutlineNode)
            , 'examples' => array()
        );
        if ($newNode['isOutline']) {
            $newNode['examples'] = $node->getExamples()->getRows();
        }

        //
        // Steps
        foreach ($node->getSteps() as $step) {
            $tmpStep = array(
                'type' => $step->getType()
                , 'text' => $step->getText()
                , 'outline' => array()
            );

            $args = $step->getArguments();
            if (sizeof($args) > 0) {
                $rows = $args[0]->getRows();
                $tmpStep['outline'] = $rows;
            }

            array_push($newNode['steps'], $tmpStep);
        }
        return $newNode;
    }

}