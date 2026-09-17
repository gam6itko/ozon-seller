<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Exception;

class ProductValidatorException extends OzonSellerException
{
    /** A required property is missing from the item. */
    public const CODE_REQUIRED_NOT_DEFINED = 1;
    /** A required property is present but empty. */
    public const CODE_EMPTY_VALUE = 2;
    /** The property value is not in the list of allowed options. */
    public const CODE_INCORRECT_VALUE = 3;

    protected $key;
    protected $value;

    public function __construct(string $message, int $code = 0, array $details = [], ?string $key = null, $value = null)
    {
        parent::__construct($message, $code, $details);
        $this->key = $key;
        $this->value = $value;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function getValue()
    {
        return $this->value;
    }
}
