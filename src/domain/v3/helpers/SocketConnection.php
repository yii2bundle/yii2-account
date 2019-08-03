<?php

namespace yii2bundle\account\domain\v3\helpers;

use Workerman\Connection\ConnectionInterface;
use yii2bundle\account\domain\v3\entities\SocketConnectEntity;

class SocketConnection {

    private $connections = [];

    public function get($key) : SocketConnectEntity {
        return $this->connections[$key];
    }

    public function set(SocketConnectEntity $socketConnectEntity) {
        $socketConnectEntity->validate();
        $this->connections[$socketConnectEntity->user_id] = $socketConnectEntity;
    }

    public function has($key) : bool {
        return array_key_exists($key, $this->connections);
    }

    public function remove($key) {
        unset($this->connections[$key]);
    }

    public function getByConnection(ConnectionInterface $connection) {
        /** @var SocketConnectEntity $socketConnectEntity */
        foreach ($this->connections as $key => $socketConnectEntity) {
            if($socketConnectEntity->connection == $connection) {
                return $socketConnectEntity;
            }
        }
    }

    public function hasByConnection(ConnectionInterface $connection) {
        /** @var SocketConnectEntity $socketConnectEntity */
        foreach ($this->connections as $key => $socketConnectEntity) {
            if($socketConnectEntity->connection == $connection) {
                return true;
            }
        }
        return false;
    }

    public function removeByConnection(ConnectionInterface $connection) {
        /** @var SocketConnectEntity $socketConnectEntity */
        foreach ($this->connections as $key => $socketConnectEntity) {
            if($socketConnectEntity->connection == $connection) {
                $this->remove($key);
            }
        }
    }

}
