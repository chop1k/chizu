<?php

namespace App\Exception;

use Exception;
use Throwable;

/**
 * Class BaseException represents base of custom exception
 *
 * @package App\Exception
 */
class BaseException extends Exception
{
    /**
     * Contains exception name
     *
     * @var string $name
     */
    protected string $name;

    /**
     * Returns exception name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->name = $this->getClassName();
    }

    /**
     * Returns class name
     *
     * @return string
     */
    protected function getClassName(): string
    {
        $name = explode('\\', static::class);

        return $name[array_key_last($name)];
    }

    /**
     * Converts exception data to json
     *
     * @return string
     */
    public function toJSON(): string
    {
        $json = [
            'name' => $this->getName(),
            'message' => $this->getMessage()
        ];

        if ((bool)$_SERVER['APP_DEBUG'] === true)
        {
            $json['trace'] = $this->getTrace();
        }

        return json_encode($json);
    }
}