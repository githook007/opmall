<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2020 hook007
 * author: opmall
 */
namespace app\bootstrap\kafka;

use Enqueue\RdKafka\RdKafkaConnectionFactory;
use Enqueue\RdKafka\RdKafkaConsumer;
use Enqueue\RdKafka\RdKafkaContext;
use Enqueue\RdKafka\RdKafkaMessage;
use yii\base\Application as BaseApp;
use yii\base\Event;
use yii\base\NotSupportedException;
use yii\queue\cli\Queue as CliQueue;

/**
 * kafka Queue.
 * 教程地址：https://kafka.apachecn.org/documentation.html     https://arnaud.le-blanc.net/php-rdkafka-doc/phpdoc/book.rdkafka.html
 * @author chenzs
 * @since 2.0.2
 */
class Queue extends CliQueue
{
    const ATTEMPT = 'yii-attempt';
    const TTR = 'yii-ttr';
    const DELAY = 'yii-delay';
    const PRIORITY = 'yii-priority';

    const PUSH_REDIS = 1;
    const CONSUMER_REDIS = 2;

    /**
     * The connection to the borker could be configured as an array of options
     * or as a DSN string like kafka:, kafka://user:pass@localhost:1000.
     *
     * @var string
     */
    public $dsn;
    /**
     *
     * @var array
     */
    public $global;
    /**
     *
     * @var array
     */
    public $topic;
    /**
     * The queue used to consume messages from.
     *
     * @var string
     */
    public $channel = 'kafka_queue';
    /**
     * kafka context.
     *
     * @var RdKafkaContext
     */
    protected $context;
    /**
     * The property contains a command class which used in cli.
     *
     * @var string command class name
     */
    public $commandClass = Command::class;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        Event::on(BaseApp::class, BaseApp::EVENT_AFTER_REQUEST, function () {
            $this->close();
        });
    }

    /**
     * @param int $timeout number of seconds to wait for next message.
     * Listens kafka-queue and runs new jobs.
     */
    public function listen($timeout = 0)
    {
        $this->open();

        $queue = $this->context->createQueue($this->channel);
        $consumer = $this->context->createConsumer($queue);

        $callback = function (RdKafkaMessage $message, RdKafkaConsumer $consumer) {
            if ($message->isRedelivered()) {
                $consumer->acknowledge($message);

                $this->redeliver($message);

                return true;
            }

            $ttr = $message->getProperty(self::TTR);
            $attempt = $message->getProperty(self::ATTEMPT, 1);

            if ($this->handleMessage($message->getMessageId(), $message->getBody(), $ttr, $attempt)) {
                $this->store($message->getMessageId(), self::CONSUMER_REDIS);
                $consumer->acknowledge($message);
            } else {
                $consumer->acknowledge($message);

                $this->redeliver($message);
            }

            return true;
        };

        while (true) {
            $message = $consumer->receive($timeout);

            if($message){
                $callback($message, $consumer);
            }
        }
    }

    /**
     * @inheritdoc
     */
    protected function pushMessage($payload, $ttr, $delay, $priority)
    {
        $this->open();

        $topic = $this->context->createTopic($this->channel);

        $message = $this->context->createMessage($payload);

        $message->setMessageId(uniqid());
        $message->setTimestamp(time());
        $message->setProperty(self::ATTEMPT, 1);
        $message->setProperty(self::TTR, $ttr);

        $producer = $this->context->createProducer();

        if ($delay) {
            $message->setProperty(self::DELAY, $delay);
        }

        if ($priority) {
            $message->setProperty(self::PRIORITY, $priority);
        }

        $producer->send($topic, $message);

        $this->store($message->getMessageId(), self::PUSH_REDIS);

        return $message->getMessageId();
    }

    /**
     * Opens connection and channel.
     */
    protected function open()
    {
        if ($this->context) {
            return;
        }

        $connectionFactoryClass = new RdKafkaConnectionFactory();

        $config = [
            'dsn' => $this->dsn,
            'global' => $this->global,
            'topic' => $this->topic,
        ];

        $config = array_filter($config, function ($value) {
            return null !== $value;
        });

        $factory = new $connectionFactoryClass($config);

        $this->context = $factory->createContext();
    }

    /**
     * Closes connection and channel.
     */
    protected function close()
    {
        if (!$this->context) {
            return;
        }

        $this->context->close();
        $this->context = null;
    }

    protected function redeliver(RdKafkaMessage $message)
    {
        $attempt = $message->getProperty(self::ATTEMPT, 1);

        $newMessage = $this->context->createMessage($message->getBody(), $message->getProperties(), $message->getHeaders());
        $newMessage->setProperty(self::ATTEMPT, ++$attempt);

        $this->context->createProducer()->send(
            $this->context->createQueue($this->channel),
            $newMessage
        );
    }

    /**
     * @inheritdoc
     */
    public function status($id)
    {
        if (\Yii::$app->redis->exists("{$this->channel}_{$id}")) {
            $value = \Yii::$app->redis->get("{$this->channel}_{$id}");
            if($value == self::PUSH_REDIS){
                return self::STATUS_WAITING;
            }
            \Yii::$app->redis->del("{$this->channel}_{$id}");
            return self::STATUS_DONE;
        }

        return self::STATUS_DONE;
    }

    protected function store($messageId, $value){
        \Yii::$app->redis->setex("{$this->channel}_{$messageId}", 43200, $value);
    }

    /**
     * Sets delay for later execute.
     *
     * @param int|mixed $value
     */
    public function delay($value)
    {
        if($value > 0){
            throw new NotSupportedException('kafka暂时不支持延迟加载');
        }
        return parent::delay($value);
    }
}
