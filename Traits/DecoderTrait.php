<?php

namespace Codememory\Components\JsonParser\Traits;

/**
 * Trait DecoderTrait
 * @package System\Support\Traits\JsonParser
 *
 * @author  Codememory
 */
trait DecoderTrait
{

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * & Find all & characters and replace them with \u0026. Works only when encoding in json
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @return DecoderTrait
     */
    public function decodeAmp(): DecoderTrait
    {

        $this->flags += JSON_HEX_AMP;

        return $this;

    }

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * & Find all quotation marks (single and double) and replace them with
     * & \u0027 - single, \u0022 - double. Works only when encoding in json
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @return DecoderTrait
     */
    public function decodeQuotes(): DecoderTrait
    {

        $this->flags += JSON_HEX_APOS | JSON_HEX_QUOT;

        return $this;

    }

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * & Find all tags related or similar to html and replace with \u003C and \u003E
     * & Works only when encoding in json
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @return DecoderTrait
     */
    public function decodeTag(): DecoderTrait
    {

        $this->flags += JSON_HEX_TAG;

        return $this;

    }

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * & Do not escape slashes "/"
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @return $this
     */
    public function slashAdaptation(): DecoderTrait
    {

        $this->flags += JSON_UNESCAPED_SLASHES;

        return $this;

    }


}