<?php

namespace yii2bundle\account\domain\v3\helpers;

use yii2bundle\account\domain\v3\entities\SocketConnectEntity;

class SocketIOConnection {

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

    public function getByConnection($connection) {
        /** @var SocketConnectEntity $socketConnectEntity */
        foreach ($this->connections as $key => $socketConnectEntity) {
            if($socketConnectEntity->connection->id == $connection->id) {
                return $socketConnectEntity;
            }
        }
    }

    public function hasByConnection($connection) {
        /** @var SocketConnectEntity $socketConnectEntity */
        foreach ($this->connections as $key => $socketConnectEntity) {
            if($socketConnectEntity->connection->id == $connection->id) {
                return true;
            }
        }
        return false;
    }

    public function removeByConnection($connection) {
        /** @var SocketConnectEntity $socketConnectEntity */
        foreach ($this->connections as $key => $socketConnectEntity) {
            if($socketConnectEntity->connection->id == $connection->id) {
                $this->remove($key);
            }
        }
    }

}
