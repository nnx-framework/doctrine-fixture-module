<?php
/**
 * @link    https://github.com/nnx-framework/doctrine-fixture-module
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace Nnx\DoctrineFixtureModule\Executor;

use Doctrine\Fixture\Executor as FixtureExecutor;
use Doctrine\Fixture\Configuration;
use Nnx\DoctrineFixtureModule\Listener\ExecuteEventSubscriberInterface;
use Nnx\DoctrineFixtureModule\Listener\PostExecuteEventSubscriber;
use Nnx\DoctrineFixtureModule\Listener\PreExecuteEventSubscriber;
use Nnx\DoctrineFixtureModule\Listener\RunFixtureEventSubscriber;

/**
 * Class FixtureExecutorBuilder
 *
 * @package Nnx\DoctrineFixtureModule\Executor
 */
class FixtureExecutorBuilder implements FixtureExecutorBuilderInterface
{
    /**
     *  Прототип для обработчика событий возникающий после начала выполнения циккла диспетчиризации фикстур
     *
     * @var ExecuteEventSubscriberInterface
     */
    protected $postExecuteEventSubscriberPrototype;

    /**
     * Прототип для обработчика событий возникающий перед началом выполнения циккла диспетчиризации фикстур
     *
     * @var ExecuteEventSubscriberInterface
     */
    protected $preExecuteEventSubscriberPrototype;

    /**
     * Прототип для обарботчика события возникающего перед запуском фикстуры
     *
     * @var ExecuteEventSubscriberInterface
     */
    protected $runFixtureEventSubscriber;

    /**
     * @inheritdoc
     *
     * @param Configuration $configuration
     *
     * @return FixtureExecutor
     */
    public function build(Configuration $configuration, ExecutorInterface $executor)
    {
        $eventManager = $configuration->getEventManager();

        $preExecuteEventSubscriberPrototype = clone $this->getPreExecuteEventSubscriberPrototype();
        $preExecuteEventSubscriberPrototype->setExecutor($executor);
        $eventManager->addEventSubscriber($preExecuteEventSubscriberPrototype);

        $runFixtureEventSubscriber = clone $this->getRunFixtureEventSubscriber();
        $runFixtureEventSubscriber->setExecutor($executor);
        $eventManager->addEventSubscriber($runFixtureEventSubscriber);

        $doctrineFixtureExecutor = new FixtureExecutor($configuration);

        $postExecuteEventSubscriberPrototype = clone $this->getPostExecuteEventSubscriberPrototype();
        $postExecuteEventSubscriberPrototype->setExecutor($executor);
        $eventManager->addEventSubscriber($postExecuteEventSubscriberPrototype);

        return $doctrineFixtureExecutor;
    }

    /**
     * Возвращает прототип для обработчика событий возникающий после начала выполнения циккла диспетчиризации фикстур
     *
     * @return ExecuteEventSubscriberInterface
     */
    public function getPostExecuteEventSubscriberPrototype()
    {
        if (null === $this->postExecuteEventSubscriberPrototype) {
            $postExecuteEventSubscriberPrototype = new PostExecuteEventSubscriber();
            $this->setPostExecuteEventSubscriberPrototype($postExecuteEventSubscriberPrototype);
        }
        return $this->postExecuteEventSubscriberPrototype;
    }

    /**
     * Устанавливает прототип для обработчика событий возникающий после начала выполнения циккла диспетчиризации фикстур
     *
     * @param ExecuteEventSubscriberInterface $postExecuteEventSubscriberPrototype
     *
     * @return $this
     */
    public function setPostExecuteEventSubscriberPrototype(ExecuteEventSubscriberInterface $postExecuteEventSubscriberPrototype)
    {
        $this->postExecuteEventSubscriberPrototype = $postExecuteEventSubscriberPrototype;

        return $this;
    }

    /**
     * Возвращает прототип для обработчика событий возникающий перед началом выполнения циккла диспетчиризации фикстур
     *
     * @return ExecuteEventSubscriberInterface
     */
    public function getPreExecuteEventSubscriberPrototype()
    {
        if (null === $this->preExecuteEventSubscriberPrototype) {
            $preExecuteEventSubscriberPrototype = new PreExecuteEventSubscriber();
            $this->setPreExecuteEventSubscriberPrototype($preExecuteEventSubscriberPrototype);
        }
        return $this->preExecuteEventSubscriberPrototype;
    }

    /**
     * Устанавливает прототип для обработчика событий возникающий перед началом выполнения циккла диспетчиризации фикстур
     *
     * @param ExecuteEventSubscriberInterface $preExecuteEventSubscriberPrototype
     *
     * @return $this
     */
    public function setPreExecuteEventSubscriberPrototype(ExecuteEventSubscriberInterface $preExecuteEventSubscriberPrototype)
    {
        $this->preExecuteEventSubscriberPrototype = $preExecuteEventSubscriberPrototype;

        return $this;
    }

    /**
     * Возвращает прототип для обарботчика события возникающего перед запуском фикстуры
     *
     * @return ExecuteEventSubscriberInterface
     */
    public function getRunFixtureEventSubscriber()
    {
        if (null === $this->runFixtureEventSubscriber) {
            $runFixtureEventSubscriber = new RunFixtureEventSubscriber();
            $this->setRunFixtureEventSubscriber($runFixtureEventSubscriber);
        }
        return $this->runFixtureEventSubscriber;
    }

    /**
     * Устанавливает прототип для обарботчика события возникающего перед запуском фикстуры
     *
     * @param ExecuteEventSubscriberInterface $runFixtureEventSubscriber
     *
     * @return $this
     */
    public function setRunFixtureEventSubscriber(ExecuteEventSubscriberInterface $runFixtureEventSubscriber)
    {
        $this->runFixtureEventSubscriber = $runFixtureEventSubscriber;

        return $this;
    }
}
