<?php declare(strict_types=1);

namespace App;

class CacheResponse implements iResponse
{
    private $error;
    private $msg;

    public function __construct(int $error, string $msg)
    {
        $this->error = $error;
        $this->msg = $msg;
    }

    public function generateResponse():array
    {
        return [
            "error" => $this->error,
            "msg" => $this->msg
        ];
    }
}