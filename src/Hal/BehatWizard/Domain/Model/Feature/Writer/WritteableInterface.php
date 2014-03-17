<?php

namespace Hal\BehatWizard\Domain\Model\Feature\Writer;

/*
 * This file is part of the Behat Tools
 * (c) 2012 Jean-François Lépine <jeanfrancois@lepine.pro>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * writteable feature
 *
 * @author Jean-François Lépine <jeanfrancois@lepine.pro>
 */
interface WritteableInterface
{

    /**
     * Get the content of the feature
     *
     * @return string
     */
    public function getContent();

    /**
     * Set the content of the feature
     *
     * @param string
     */
    public function setContent($content);
}