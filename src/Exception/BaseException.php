<?php
declare(strict_types = 1);
namespace App\Exception;

use Exception;
use phpDocumentor\Reflection\Types\Void_;
use Throwable;

abstract class BaseException extends Exception
{
    protected $data = [];

    public function __construct($message = "", array $data = [], $code = 0, Throwable $previous = null)
    {
        $this->data = $data;
        parent::__construct($message, $code, $previous);
    }

    public function setData(string $key, $value): void
    {
        $this->data[$key] = $value;
    }

    public function getExtraData(): array
    {
        if(count($this->data) === 0)
        {
            return $this->data;
        }
        return json_decode(json_encode($this->data), true);
    }
}