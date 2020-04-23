<?php declare(strict_types=1);

namespace App\Responses;

interface iResponse
{
    public function generateResponse():array;
}