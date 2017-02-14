<?php

/**
 * rdfa-lite
 *
 * @category Jkphl
 * @package Jkphl\Rdfalite
 * @subpackage Jkphl\Rdfalite\Tests
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

namespace Jkphl\Rdfalite\Tests\Application;

use Jkphl\Rdfalite\Application\Contract\ElementProcessorInterface;
use Jkphl\Rdfalite\Application\Parser\Context;
use Jkphl\Rdfalite\Application\Parser\DOMIterator;
use Jkphl\Rdfalite\Infrastructure\Parser\RdfaliteElementProcessor;

/**
 * DOM node iterator tests
 *
 * @package Jkphl\Rdfalite
 * @subpackage Jkphl\Rdfalite\Tests
 */
class DOMNodeIteratorTest extends ParserIteratorTestBase
{
    /**
     * Test recursive DOM node iteration
     */
    public function testDomNodeIteration()
    {
        $dom = new \DOMDocument();
        $dom->loadHTML(self::$html);
        $context = new Context();

        $elementProcessor = $this->getMock(ElementProcessorInterface::class);
        $elementProcessor->method('processElement')->willReturn($context);
        $elementProcessor->method('processElementChildren')->willReturn($context);
        /** @var ElementProcessorInterface $elementProcessor */
        $domNodeIterator = new DOMIterator($dom->childNodes, $context, $elementProcessor);
        $this->assertInstanceOf(DOMIterator::class, $domNodeIterator);

        $elements = ['html', 'head', 'title', 'body', 'h1', 'p', 'span', 'span', 'img', 'span'];

        /**
         * Recursively run through all child elements
         *
         * @var int $nodeIndex Element index
         * @var \DOMElement $node Element
         */
        foreach ($domNodeIterator->getRecursiveIterator() as $element) {
            if ($element instanceof \DOMElement) {
                $this->assertEquals($element->localName, array_shift($elements));
            }
        }
        $this->assertEquals(0, count($elements));
    }

    /**
     * Test the RDFa Lite element processor
     */
    public function testRdfaLiteProcessor()
    {
        $dom = new \DOMDocument();
        $dom->loadHTML(self::$html);
        $context = new Context();
        $domNodeIterator = new DOMIterator($dom->childNodes, $context, new RdfaliteElementProcessor());
        $this->assertInstanceOf(DOMIterator::class, $domNodeIterator);

        $elements = ['html', 'head', 'title', 'body', 'h1', 'p', 'span', 'span', 'img', 'span'];

        /**
         * Recursively run through all child elements
         *
         * @var int $nodeIndex Element index
         * @var \DOMElement $node Element
         */
        foreach ($domNodeIterator->getRecursiveIterator() as $element) {
            if ($element instanceof \DOMElement) {
                $this->assertEquals($element->localName, array_shift($elements));
            }
        }
        $this->assertEquals(0, count($elements));
        $this->validateIteratorResult($context->getChildren());
    }
}
