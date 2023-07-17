<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Exception;

class OzonSellerException extends \Exception
{
    /** @var array|null */
    protected $details;

    public function __construct(string $messages, int $code = 0, array $details = [])
    {
        parent::__construct($messages, $code);
        $this->details = $details;
    }

    public function __toString(): string
    {
        return parent::__toString().PHP_EOL.'Data: '.json_encode($this->details);
    }

    /**
     * @deprecated use getDetails() method
     */
    public function getData(): ?array
    {
        return $this->details;
    }

    public function getDetails(): ?array
    {
        return $this->details;
    }
}
