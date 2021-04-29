<?php

namespace Codememory\Components\JsonParser\Exceptions;

use ErrorException;
use JetBrains\PhpStorm\Pure;

/**
 * Class ArrayIsMissing
 * @package System\Support\Exceptions\JsonParser
 *
 * @author  Codememory
 */
class InvalidDataType extends ErrorException
{

    /**
     * InvalidDataType constructor.
     *
     * @param string $message
     */
    #[Pure] public function __construct(string $message)
    {

        parent::__construct(sprintf('JsonParser: %s', $message));

    }

}