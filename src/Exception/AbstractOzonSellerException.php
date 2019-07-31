<?php
namespace Gam6itko\OzonSeller\Exception;

class AbstractOzonSellerException extends \Exception
{
    /** @var array|null */
    protected $data;

    /**
     * ValidationException constructor.
     * @param string $messages
     * @param array $data
     */
    public function __construct(string $messages, array $data)
    {
        parent::__construct($messages);
        $this->data = $data;
    }

    public function __toString()
    {
        return parent::__toString() . PHP_EOL . 'Data: ' . json_encode($this->data);
    }

    /**
     * @return array
     */
    public function getData(): ?array
    {
        return $this->data;
    }
}