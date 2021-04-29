<?php

namespace Codememory\Components\JsonParser;

use Codememory\Components\JsonParser\Exceptions\InvalidDataType;
use Codememory\Components\JsonParser\Exceptions\JsonErrorException;
use Codememory\Components\JsonParser\Traits\DecoderTrait;

/**
 * Class JsonParser
 * @package System\Support
 *
 * @author  Codememory
 */
class JsonParser
{

    use DecoderTrait;

    public const JSON_TO_ARRAY = 4;
    public const JSON_TO_OBJECT = 6;
    public const JSON_TO_SERIALIZE = 9;

    /**
     * @var mixed|null
     */
    private mixed $data = null;

    /**
     * @var int|string
     */
    private int|string $flags = 0;

    /**
     * @var int
     */
    private int $error = 0;

    /**
     * @var string|null
     */
    private ?string $errorMessage = null;

    /**
     * @var int
     */
    private int $theirFlags = self::JSON_TO_ARRAY;

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * & Add data with which jsonParser will work
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param mixed $data
     *
     * @return $this
     */
    public function setData(mixed $data): JsonParser
    {

        $this->data = $data;

        return $this;

    }

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * & Remove recursive references from array or object and replace. It defaults to null
     * & instead of recursion
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param mixed|null $replacement
     *
     * @return $this
     * @throws InvalidDataType
     */
    public function removeRecursions(mixed $replacement = null): JsonParser
    {

        if (false === is_array($this->data) && false === is_object($this->data)) {
            throw new InvalidDataType('In order to search for recursion in an array, the transmitted data must be of type array');
        }

        foreach ($this->data as $key => $data) {
            if (is_array($data) || is_object($data)) {
                $this->data[$key] = $replacement;
            }
        }

        return $this;

    }

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * & Find Nan or INF values and replace them with 0
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @return JsonParser
     */
    public function removeNanOrInf(): JsonParser
    {

        $this->flags += JSON_PARTIAL_OUTPUT_ON_ERROR;

        return $this;

    }

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * & Parsing from json to another type. By default json is parsed into an array
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param int $inType
     *
     * @return $this
     */
    public function ofJson(int $inType = self::JSON_TO_ARRAY): JsonParser
    {

        $this->theirFlags = $inType;

        return $this;

    }

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * & The main method that is called when encode and decode is checking json for errors.
     * & In case of error, an exception will be thrown
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @throws JsonErrorException
     */
    private function handlerDecodeOrEncode(): void
    {

        $this->error = json_last_error();
        $this->errorMessage = json_last_error_msg();

        if ($this->error > 0) {
            throw new JsonErrorException($this->errorMessage);
        }

        $this->flags = 0;

    }

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * & Data encoding in json with the ability to additionally specify registered flags
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param int $flags
     *
     * @return mixed
     * @throws JsonErrorException
     */
    public function encode(int $flags = 0): mixed
    {

        $this->flags += $flags;
        $this->data = json_encode($this->data, $this->flags);

        $this->handlerDecodeOrEncode();

        return $this->data;

    }

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * & Decode data into a specific type with the ability to additionally specify
     * & registered flags, by default it is decoded into an array
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param int  $flags
     * @param bool $validation
     *
     * @return mixed
     * @throws JsonErrorException
     */
    public function decode(int $flags = 0, bool $validation = true): mixed
    {

        $this->flags += $flags;
        $this->data = match ($this->theirFlags) {
            self::JSON_TO_OBJECT => json_decode($this->data, false, flags: $flags),
            self::JSON_TO_SERIALIZE => serialize(json_decode($this->data, flags: $flags)),

            default => json_decode($this->data, true, flags: $flags)
        };

        if ($validation) {
            $this->handlerDecodeOrEncode();
        }

        return $this->data;

    }

}