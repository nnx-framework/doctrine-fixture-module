<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\FixtureDataReader;

use DOMDocument;
use DOMXPath;

/**
 * Class SimpleXmlFormat
 *
 * @package Nnx\DoctrineFixtureModule\FixtureDataReader
 */
class SimpleXmlFormat implements FixtureDataReaderInterface
{


    /**
     * Загружает данные для фикстуры, на основе заданного ресурса
     *
     * @param $resource
     *
     * @return DataContainerInterface
     * @throws \Nnx\DoctrineFixtureModule\FixtureDataReader\Exception\RuntimeException
     * @throws \Nnx\DoctrineFixtureModule\FixtureDataReader\Exception\InvalidResourceException
     */
    public function loadDataFromResource($resource)
    {
        if (!(is_string($resource) && file_exists($resource) && is_readable($resource))) {
            $errMsg = sprintf(
                'Invalid resource for fixture data: %s',
                is_string($resource) ? $resource : ''
            );
            throw new Exception\InvalidResourceException($errMsg);
        }
        libxml_use_internal_errors(true);
        $xmlDoc = new DOMDocument();
        $filename = realpath($resource);
        $xmlDoc->load($filename, LIBXML_COMPACT | LIBXML_NOBLANKS);

        if ($error = libxml_get_last_error()) {
            throw new Exception\InvalidResourceException($error->message);
        }

        $xpath = new DOMXPath($xmlDoc);
        $itemEntries = $xpath->query('/fixtureSimpleDataContainer/item');

        $results = [];
        for ($itemIndex = 0; $itemIndex < $itemEntries->length; $itemIndex++) {
            $itemNode = $itemEntries->item($itemIndex);
            $itemElements = $xpath->query('./*', $itemNode);
            for ($elementIndex = 0; $elementIndex < $itemElements->length; $elementIndex++) {
                $element = $itemElements->item($itemIndex);

                $elementName = $element->nodeName;
                $elementValue = $element->nodeValue;


                if (!array_key_exists($elementName, $results)) {
                    $results[$elementName] = [];
                }
            }
        }
    }
}
