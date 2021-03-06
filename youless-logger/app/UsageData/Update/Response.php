<?php declare(strict_types=1);

namespace Casa\YouLess\UsageData\Update;

use Stellar\Curl\Contracts\RequestInterface;
use Stellar\Curl\Response\JsonResponse;

class Response extends JsonResponse
{
    /** @var string */
    protected $_unit;

    /** @var int */
    protected $_startTime;

    /** @var int */
    protected $_deltaTime;

    /** @var array<int, int> */
    protected $_values;

    protected function _processValues(int $timestamp, array $values)
    {
        $result = [];

        foreach ($values as $value) {
            if ('*' === $value) {
                continue;
            }

            $result[ $timestamp ] = (int) $value;
            $timestamp += $this->_deltaTime;
        }

        \array_pop($result);

        return $result;
    }

    public function __construct(RequestInterface $request, string $response)
    {
        parent::__construct($request, $response);

        $this->_unit = \trim($this->_data['un']);
        $this->_startTime = \strtotime($this->_data['tm']);
        $this->_deltaTime = (int) $this->_data['dt'];
        $this->_values = $this->_processValues($this->_startTime, $this->_data['val']);
    }

    public function getUnit() : string
    {
        return $this->_unit;
    }

    public function getStartTime() : int
    {
        return $this->_startTime;
    }

    public function getDeltaTime() : int
    {
        return $this->_deltaTime;
    }

    public function getValues() : array
    {
        return $this->_values;
    }
}
