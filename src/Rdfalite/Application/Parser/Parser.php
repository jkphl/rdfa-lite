<?php

/**
 * rdfa-lite
 *
 * @category Jkphl
 * @package Jkphl\Rdfalite
 * @subpackage Jkphl\Rdfalite\Application
 * @author Joschi Kuphal <joschi@tollwerk.de> / @jkphl
 * @copyright Copyright © 2017 Joschi Kuphal <joschi@tollwerk.de> / @jkphl
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */

/***********************************************************************************
 *  The MIT License (MIT)
 *
 *  Copyright © 2017 Joschi Kuphal <joschi@kuphal.net> / @jkphl
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy of
 *  this software and associated documentation files (the "Software"), to deal in
 *  the Software without restriction, including without limitation the rights to
 *  use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 *  the Software, and to permit persons to whom the Software is furnished to do so,
 *  subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in all
 *  copies or substantial portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 *  FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 *  COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 *  IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 *  CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 ***********************************************************************************/

namespace Jkphl\Rdfalite\Application\Parser;

use Jkphl\Rdfalite\Application\Contract\DocumentFactoryInterface;
use Jkphl\Rdfalite\Application\Contract\ElementProcessorInterface;
use Jkphl\Rdfalite\Domain\Thing\ThingInterface;


/**
 * Parser
 *
 * @package Jkphl\Rdfalite
 * @subpackage Jkphl\Rdfalite\Application
 */
class Parser implements ParserInterface
{
    /**
     * Document factory
     *
     * @var DocumentFactoryInterface
     */
    protected $documentFactory;
    /**
     * Element processor
     *
     * @var ElementProcessorInterface
     */
    protected $elementProcessor;

    /**
     * Parser constructor
     *
     * @param DocumentFactoryInterface $documentFactory Document factory
     * @param ElementProcessorInterface $elementProcessor Element processor
     */
    public function __construct(DocumentFactoryInterface $documentFactory, ElementProcessorInterface $elementProcessor)
    {
        $this->documentFactory = $documentFactory;
        $this->elementProcessor = $elementProcessor;
    }

    /**
     * Parse a string
     *
     * @param string $string Parseable string
     * @return ThingInterface[] Parsed things
     */
    public function parse($string)
    {
        $document = $this->documentFactory->createDocumentFromString($string);
        $context = new Context();
        $iterator = new DOMIterator($document->childNodes, $context, $this->elementProcessor);

        // Iterate through all $node
        foreach ($iterator->getRecursiveIterator() as $node) {
            $node || true;
        }

        return $context->getChildren();
    }
}

