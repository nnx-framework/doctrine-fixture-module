<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\FixtureDataReader\SimpleXmlFormat;

use DOMNodeList;
use DOMXPath;
use Nnx\DoctrineFixtureModule\FixtureDataReader\DataContainer\Entity;
use Nnx\DoctrineFixtureModule\FixtureDataReader\DataContainerInterface;
use Nnx\DoctrineFixtureModule\FixtureDataReader\DataContainer\Index;

/**
 * Class ParserContext
 *
 * @package Nnx\DoctrineFixtureModule\FixtureDataReader\SimpleXmlFormat
 */
class ParserContext
{
    /**
     * Объект позволящий делать выборки в xml
     *
     * @var DOMXPath
     */
    protected $xpath;

    /**
     * @var DOMNodeList
     */
    protected $itemNodes;

    /**
     * Устанавливает связь, с контейнером в котором содержаться данные для родительской сущности
     *
     * @var Entity
     */
    protected $parentEntity;

    /**
     * Уровень вложенности сущности в xml дереве, на котором находится обрабатываемый узел
     *
     * @var int
     */
    protected $level = 0;

    /**
     * Имя связи, которая связывает контейнер с  данные для сущности, с контейнером данных, родительской сущности
     *
     * @var string|null
     */
    protected $parentAssociation;

    /**
     * Контейнер с результирующими данными
     *
     * @var DataContainerInterface
     */
    protected $dataContainer;

    /**
     * Устанавливает индекс
     *
     * @var Index
     */
    protected $index;

    /**
     * Набор нод для парсинга
     *
     * @return DOMNodeList
     */
    public function getItemNodes()
    {
        return $this->itemNodes;
    }

    /**
     * Устанавливает набор нод для парсинга
     *
     * @param DOMNodeList $itemNodes
     *
     * @return $this
     */
    public function setItemNodes(DOMNodeList $itemNodes)
    {
        $this->itemNodes = $itemNodes;

        return $this;
    }

    /**
     * Возвращает связь, с контейнером в котором содержаться данные для родительской сущности
     *
     * @return Entity
     */
    public function getParentEntity()
    {
        return $this->parentEntity;
    }

    /**
     * Устанавливает связь, с контейнером в котором содержаться данные для родительской сущности
     *
     * @param Entity $parentEntity
     *
     * @return $this
     */
    public function setParentEntity(Entity $parentEntity)
    {
        $this->parentEntity = $parentEntity;

        return $this;
    }

    /**
     * Возвращает объект позволящий делать выборки в xml
     *
     * @return DOMXPath
     */
    public function getXpath()
    {
        return $this->xpath;
    }

    /**
     * Устанавливает объект позволящий делать выборки в xml
     *
     * @param DOMXPath $xpath
     *
     * @return $this
     */
    public function setXpath(DOMXPath $xpath)
    {
        $this->xpath = $xpath;

        return $this;
    }

    /**
     * Возвращает уровень вложенности сущности в xml дереве, на котором находится обрабатываемый узел
     *
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Устанавливает уровень вложенности сущности в xml дереве, на котором находится обрабатываемый узел
     *
     * @param int $level
     *
     * @return $this
     */
    public function setLevel($level)
    {
        $this->level = (integer)$level;

        return $this;
    }

    /**
     * Возвращает имя связи, которая связывает контейнер с  данные для сущности, с контейнером данных, родительской сущности
     *
     * @return null|string
     */
    public function getParentAssociation()
    {
        return $this->parentAssociation;
    }

    /**
     * Устанавливает имя связи, которая связывает контейнер с  данные для сущности, с контейнером данных, родительской сущности
     *
     * @param null|string $parentAssociation
     *
     * @return $this
     */
    public function setParentAssociation($parentAssociation)
    {
        $this->parentAssociation = $parentAssociation;

        return $this;
    }

    /**
     * Проверка корректного состояния контекста
     *
     * @throws \Nnx\DoctrineFixtureModule\FixtureDataReader\SimpleXmlFormat\Exception\InvalidParserContextException
     */
    public function validate()
    {
        $parentAssociation = $this->getParentAssociation();
        $parentEntity = $this->getParentEntity();

        if (null !== $parentAssociation && null === $parentEntity) {
            $errMsg = 'Parent entity not specified';
            throw new Exception\InvalidParserContextException($errMsg);
        }

        if (null === $parentAssociation && null !== $parentEntity) {
            $errMsg = 'Parent association not specified';
            throw new Exception\InvalidParserContextException($errMsg);
        }

        if (null === $this->getXpath()) {
            $errMsg = 'Xpath object not specified';
            throw new Exception\InvalidParserContextException($errMsg);
        }

        if (null === $this->getDataContainer()) {
            $errMsg = 'Data container not specified';
            throw new Exception\InvalidParserContextException($errMsg);
        }

        if (null === $this->getIndex()) {
            $errMsg = 'Index storage not specified';
            throw new Exception\InvalidParserContextException($errMsg);
        }
    }

    /**
     * Возвращает контейнер с результирующими данными
     *
     * @return DataContainerInterface
     */
    public function getDataContainer()
    {
        return $this->dataContainer;
    }

    /**
     * Устанавливает контейнер с результирующими данными
     *
     * @param DataContainerInterface $dataContainer
     *
     * @return $this
     */
    public function setDataContainer(DataContainerInterface $dataContainer)
    {
        $this->dataContainer = $dataContainer;

        return $this;
    }

    /**
     * Возвращает хранилище индексов
     *
     * @return Index
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * Устанавливает хранилище индексов
     *
     * @param Index $index
     *
     * @return $this
     */
    public function setIndex(Index $index)
    {
        $this->index = $index;

        return $this;
    }
}
