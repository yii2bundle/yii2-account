<?php

namespace yii2bundle\account\domain\v3\helpers;

use domain\mail\v1\enums\MailSocketEventEnum;
use PHPSocketIO\Socket;
use Workerman\Connection\ConnectionInterface;
use yii\helpers\ArrayHelper;
use yii\web\UnauthorizedHttpException;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii2rails\extension\console\enums\TypeMessageEnum;
use yii2rails\extension\console\helpers\Output;
use yii2bundle\account\domain\v3\entities\LoginEntity;
use yii2bundle\account\domain\v3\entities\SocketConnectEntity;
use yii2bundle\account\domain\v3\entities\SocketEventEntity;
use yii2bundle\account\domain\v3\forms\LoginForm;
use Workerman\Worker;
use App;
use PHPSocketIO\SocketIO as PHPSocketIO;
use yii2bundle\account\domain\v3\helpers\SocketIOConnection;

class SocketIO {

    private $tcpHost;
    private $wsHost;
    private $io;
    /**
     * @var SocketConnectEntity[]
     */
    private $socketConnection;

    public function __construct(array $config) {
        $this->socketConnection = new SocketIOConnection();
        $this->tcpHost = ArrayHelper::getValue($config, 'tcp.host');
        $this->wsHost = ArrayHelper::getValue($config, 'ws.host');
        $context = ArrayHelper::getValue($config, 'ws.context');
        $this->io = new PHPSocketIO(8000, $context);
        $this->io->on('workerStart', function() {
            // text is a simple protocol provide by workerman
            $inner_worker = new Worker($this->tcpHost);
            if(isset($context['ssl'])) {
                $inner_worker->transport = 'ssl';
            }
            $inner_worker->onMessage = function($connection, $data)
            {
                $this->onMessage($connection, $data);
            };
            $inner_worker->listen();
        });
        $this->io->on('connection', function($socket){
            $socket->addedUser = false;
            // when the client emits 'new message', this listens and executes
            $socket->on(MailSocketEventEnum::OUTPUT_MESSAGE, function ($data)use($socket){
                $this->onWebSocketConnect($socket,[MailSocketEventEnum::OUTPUT_MESSAGE]);
            });
            $socket->on('disconnect', function ($data) use($socket) {
                $this->onClose($socket);
            });
        });
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
                $this->io->to($connectEntity->connection->id)->emit($eventEntity->name, $eventJsonString);
                $this->log("send message \"{$eventEntity->name}\" to user \"{$connectEntity->data->login}\"", TypeMessageEnum::SUCCESS);
        } else {
            $this->log("user \"{$eventEntity->user_id}\" is offline. SKIP", TypeMessageEnum::WARNING);
        }
        $connection->send('recv success');
    }

    public function start() {
        Worker::runAll();
    }

    public function onWebSocketConnect(Socket $socket, $event) {
        // put get-parameter into $users collection when a new user is connected
        // you can set any parameter on site page. for example client.html: ws = new WebSocket("ws://127.0.0.1:8000/?user=tester01");
        try {
            $loginEntity = $this->login($socket->request->_query);
            $socketConnectEntity = new SocketConnectEntity;
            $socketConnectEntity->connection = $socket;
            $socketConnectEntity->user_id = $loginEntity->person_id;
            $socketConnectEntity->data = $loginEntity;
            $socketConnectEntity->events = $event;
            $this->socketConnection->set($socketConnectEntity);
            $this->log("user \"{$loginEntity->login}\" is online");
        } catch (UnprocessableEntityHttpException|UnauthorizedHttpException $e) {
            $this->log("user fail attempt connection", TypeMessageEnum::DANGER);
        }

        // or you can use another parameter for user identification, for example $_COOKIE['PHPSESSID']
    }

    public function onClose($connection) {
        // unset parameter when user is disconnected
        if ($this->socketConnection->hasByConnection($connection)) {
            $socketConnectEntity = $this->socketConnection->getByConnection($connection);
            $this->socketConnection->removeByConnection($connection);
            $this->log("user \"{$socketConnectEntity->data->login}\" is offline");
        }
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
