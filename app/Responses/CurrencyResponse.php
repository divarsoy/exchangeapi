<?php declare(strict_types=1);

namespace App\Responses;

class CurrencyResponse implements iResponse
{
    private $error;
    private $amount;
    private $fromCache;

    public function __construct(int $error, float $amount, int $fromCache)
    {
        $this->error = $error;
        $this->amount = $amount;
        $this->fromCache = $fromCache;
    }

    public function generateResponse():array
    {
        return [
            "error" => $this->error,
            "amount" => number_format($this->amount,2,'.',''),
            "fromCache" => $this->fromCache
        ];
    }
}