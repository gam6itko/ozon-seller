<?php
namespace Gam6itko\OzonSeller\Exception;

class ValidationException extends AbstractOzonSellerException
{
    /** @var array */
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
}