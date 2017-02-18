<?php

namespace Snake;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class App implements MessageComponentInterface
{

    /**
     * @var \SplObjectStorage
     */
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        foreach ($this->clients as $client) {
            $conn->send($this->clients[$client]);
        }

        $this->clients->attach($conn);
    }

    public function onMessage(ConnectionInterface $conn, $msg)
    {
        $this->clients[$conn] = $msg;

        foreach ($this->clients as $client) {
            if ($conn !== $client) {
                $client->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->close();
    }

}
