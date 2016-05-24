<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\FixtureDataReader;

use DOMDocument;
use DOMXPath;
use Nnx\DoctrineFixtureModule\FixtureDataReader\DataContainer\Entity;
use Nnx\DoctrineFixtureModule\FixtureDataReader\SimpleXmlFormat\ParserContext;
use Nnx\DoctrineFixtureModule\FixtureDataReader\DataContainer\Association;
use Nnx\DoctrineFixtureModule\FixtureDataReader\DataContainer\Property;
use Nnx\DoctrineFixtureModule\FixtureDataReader\DataContainer\Index;

/**
 * Class SimpleXmlFormat
 *
 * @package Nnx\DoctrineFixtureModule\FixtureDataReader
 */
class SimpleXmlFormat implements FixtureDataReaderInterface
{

    /**
     * Имя тега, который описывает данные для сущности
     *
     * @var string
     */
    const ITEM = 'item';

    /**
     * Загружает данные для фикстуры, на основе заданного ресурса
     *
     * @param $resource
     *
     * @return DataContainerInterface
     * @throws \Nnx\DoctrineFixtureModule\FixtureDataReader\Exception\InvalidResourceException
     * @throws \Nnx\DoctrineFixtureModule\FixtureDataReader\SimpleXmlFormat\Exception\InvalidParserContextException
     * @throws \Nnx\DoctrineFixtureModule\FixtureDataReader\Exception\RuntimeException
     * @throws \Nnx\DoctrineFixtureModule\FixtureDataReader\DataContainer\Exception\InvalidArgumentException
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
        $itemNodes = $xpath->query('/items/item');

        $index = new Index();
        $dataContainer = new DataContainer($index);

        $context = new ParserContext();
        $context
            ->setXpath($xpath)
            ->setIndex($index)
            ->setItemNodes($itemNodes)
            ->setDataContainer($dataContainer);

        $this->parseItem($context);

        return $dataContainer;
    }


    /**
     * Обработка набора узлов xml документа, в которых описываются данные для сущности
     *
     * @param ParserContext $context
     *
     * @return DataContainerInterface
     *
     * @throws \Nnx\DoctrineFixtureModule\FixtureDataReader\Exception\RuntimeException
     * @throws \Nnx\DoctrineFixtureModule\FixtureDataReader\SimpleXmlFormat\Exception\InvalidParserContextException
     * @throws \Nnx\DoctrineFixtureModule\FixtureDataReader\DataContainer\Exception\InvalidArgumentException
     */
    protected function parseItem(ParserContext $context)
    {
        $context->validate();

        $itemNodes = $context->getItemNodes();
        $level = $context->getLevel();
        $parentEntity = $context->getParentEntity();
        $xpath = $context->getXpath();
        $parentAssociationName = $context->getParentAssociation();
        $dataContainer = $context->getDataContainer();
        $index = $context->getIndex();


        for ($itemIndex = 0; $itemIndex < $itemNodes->length; $itemIndex++) {
            $itemNode = $itemNodes->item($itemIndex);

            if (static::ITEM !== $itemNode->nodeName) {
                $errMsg = sprintf('Invalid tag %s in fixture xml', $itemNode->nodeName);
                throw new Exception\RuntimeException($errMsg);
            }

            $entity = new Entity();
            $entity->setLevel($level);
            if (null !== $parentEntity) {
                $entity->setParentEntity($parentEntity);

                if (!$parentEntity->hasAssociation($parentAssociationName)) {
                    $association = new Association($index);
                    $association->setName($parentAssociationName);
                    $parentEntity->addAssociation($association);
                } else {
                    $association = $parentEntity->getAssociation($parentAssociationName);
                }
                $association->addEntity($entity);
            } else {
                $dataContainer->addEntity($entity);
            }

            $properties = $xpath->query('./*', $itemNode);

            $existingProperties = [];
            for ($propertyIndex = 0; $propertyIndex < $properties->length; $propertyIndex++) {
                $property = $properties->item($propertyIndex);
                $childItems = $xpath->query('./item', $property);
                $propertyName = $property->nodeName;

                if (array_key_exists($propertyName, $existingProperties)) {
                    $errMsg = sprintf('Property %s already exists', $propertyName);
                    throw new Exception\RuntimeException($errMsg);
                }
                $existingProperties[$propertyName] = $propertyName;


                if ($childItems->length > 0) {
                    $childLevel = $level + 1;
                    $newContext = new ParserContext();
                    $newContext
                        ->setXpath($context->getXpath())
                        ->setItemNodes($childItems)
                        ->setParentEntity($entity)
                        ->setLevel($childLevel)
                        ->setParentAssociation($propertyName)
                        ->setDataContainer($context->getDataContainer())
                        ->setIndex($context->getIndex());

                    $this->parseItem($newContext);
                } else {
                    $propertyValue = $property->nodeValue;
                    $property = new Property();
                    $property
                        ->setName($propertyName)
                        ->setValue($propertyValue)
                    ;
                    $entity->addProperty($property);
                }
            }
        }
    }
}
