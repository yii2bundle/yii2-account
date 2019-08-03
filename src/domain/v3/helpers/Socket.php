<?php

namespace yii2bundle\account\domain\v3\helpers;

use Workerman\Connection\ConnectionInterface;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;
use yii\web\UnauthorizedHttpException;
use yii2rails\domain\data\EntityCollection;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii2rails\extension\console\enums\TypeMessageEnum;
use yii2rails\extension\console\helpers\Output;
use yii2rails\extension\registry\helpers\Registry;
use yii2bundle\account\domain\v3\entities\LoginEntity;
use yii2bundle\account\domain\v3\entities\SocketConnectEntity;
use yii2bundle\account\domain\v3\entities\SocketEventEntity;
use yii2bundle\account\domain\v3\forms\LoginForm;
use yii2bundle\account\domain\v3\interfaces\services\SocketInterface;
use yii2rails\domain\services\base\BaseService;
use Workerman\Worker;
use App;

class Socket {

    private $tcpHost;
    private $wsHost;
    /**
     * @var SocketConnectEntity[]
     */
    private $socketConnection;

    public function __construct(array $config) {
        $this->socketConnection = new SocketConnection;
        $this->tcpHost = ArrayHelper::getValue($config, 'tcp.host');
        $this->wsHost = ArrayHelper::getValue($config, 'ws.host');
        $context = ArrayHelper::getValue($config, 'ws.context');
        $worker = new Worker($this->wsHost, $context);
        $worker->onConnect = [$this, 'onConnect'];
        $worker->onClose = [$this, 'onClose'];
        $worker->onWorkerStart = [$this, 'onWorkerStart'];
        if(isset($context['ssl'])) {
            $worker->transport = 'ssl';
        }
    }

    /**
     * @param SocketEventEntity $event
     * @throws ErrorException
     */
    public function sendMessage(SocketEventEntity $eventEntity) {
        // connect to a local tcp-server
        $instance = stream_socket_client($this->tcpHost);
        // send message
        fwrite($instance, json_encode($eventEntity->toArray())  . "\n");
    }

    private function log($message, $type = TypeMessageEnum::INFO) {
        $arg = TypeMessageEnum::toArgs($type);
        Output::line($message, 'after', $arg);
    }

    private function eventJsonToEntity(string $eventJsonString) : SocketEventEntity {
        // you have to use for $data json_decode because send.php uses json_encode
        $eventObject = json_decode($eventJsonString); // but you can use another protocol for send data send.php to local tcp-server
        // send a message to the user by userId
        $eventArray = ArrayHelper::toArray($eventObject);
        $eventEntity = new SocketEventEntity;
        $eventEntity->load($eventArray);
        return $eventEntity;
    }

    public function onMessage(ConnectionInterface $connection, string $eventJsonString) {
        $eventEntity = $this->eventJsonToEntity($eventJsonString);
        if ($this->socketConnection->has($eventEntity->user_id)) {
            $connectEntity = $this->socketConnection->get($eventEntity->user_id);
            if(in_array($eventEntity->name, $connectEntity->events)) {
            //if(true) {
                $connectEntity->connection->send($eventJsonString);
                $this->log("send message \"{$eventEntity->name}\" to user \"{$connectEntity->data->login}\"", TypeMessageEnum::SUCCESS);
            } else {
                $this->log("send message \"{$eventEntity->name}\" to user \"{$connectEntity->data->login}\". SKIP", TypeMessageEnum::WARNING);
            }
        } else {
            $this->log("user \"{$eventEntity->user_id}\" is offline. SKIP", TypeMessageEnum::WARNING);
        }
    }

    public function start() {
        Worker::runAll();
    }

    public function onConnect(ConnectionInterface $connection) {
        $connection->onWebSocketConnect = [$this, 'onWebSocketConnect'];
	}

    public function onWebSocketConnect(ConnectionInterface $connection) {
        // put get-parameter into $users collection when a new user is connected
        // you can set any parameter on site page. for example client.html: ws = new WebSocket("ws://127.0.0.1:8000/?user=tester01");

        try {
            $loginEntity = $this->login($_GET);
            $socketConnectEntity = new SocketConnectEntity;
            $socketConnectEntity->connection = $connection;
            $socketConnectEntity->user_id = $loginEntity->person_id;
            $socketConnectEntity->data = $loginEntity;
            $socketConnectEntity->events = $this->getEvents($_GET);

            $this->socketConnection->set($socketConnectEntity);
            $this->log("user \"{$loginEntity->login}\" is online");
        } catch (UnprocessableEntityHttpException|UnauthorizedHttpException $e) {
            $this->log("user fail attempt connection", TypeMessageEnum::DANGER);
        }

        // or you can use another parameter for user identification, for example $_COOKIE['PHPSESSID']
    }

    public function onClose(ConnectionInterface $connection) {
        // unset parameter when user is disconnected
        if ($this->socketConnection->hasByConnection($connection)) {
            $socketConnectEntity = $this->socketConnection->getByConnection($connection);
            $this->socketConnection->removeByConnection($connection);
            $this->log("user \"{$socketConnectEntity->data->login}\" is offline");
        }
    }

    // it starts once when you start server.php:
    public function onWorkerStart() {
        // create a local tcp-server. it will receive messages from your site code (for example from send.php)
        $inner_tcp_worker = new Worker($this->tcpHost);
        // create a handler that will be called when a local tcp-socket receives a message (for example from send.php)
        $inner_tcp_worker->onMessage = [$this, 'onMessage'];
        $inner_tcp_worker->listen();
    }

    private function getEvents(array $queryParams) {
        $events = [];
        if(isset($queryParams['events'])) {
            $events = $queryParams['events'];
            if(is_string($events)) {
                $events = explode(',', $events);
            }
        }
        return $events;
    }

    private function login(array $queryParams) : LoginEntity {
        if(!empty($queryParams['authorization'])) {
            $loginEntity = \App::$domain->account->auth->authenticationByToken($queryParams['authorization']);
        } elseif (!empty($queryParams['login'])) {
            $loginForm = new LoginForm;
            $loginForm->login = $queryParams['login'];
            $loginForm->password = $queryParams['password'];
            $loginEntity = App::$domain->account->auth->authenticationFromApi($loginForm);
        } else {
            throw new UnauthorizedHttpException;
        }
        return $loginEntity;
    }
}
