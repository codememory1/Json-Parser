<?php

namespace Codememory\Components\JsonParser\Exceptions;

use ErrorException;
use JetBrains\PhpStorm\Pure;

/**
 * Class JsonErrorException
 * @package System\Http\Response\Headers\Exceptions
 *
 * @author  Codememory
 */
class JsonErrorException extends ErrorException
{

    /**
     * JsonErrorException constructor.
     *
     * @param string $message
     */
    #[Pure] public function __construct(string $message)
    {

        parent::__construct(sprintf('JsonParser: %s', $message));

    }

}