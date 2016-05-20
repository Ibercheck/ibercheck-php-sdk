<?php

namespace Ibercheck\Api\ApiProblem;

use Psr\Http\Message\ResponseInterface;

/**
 * @method string getMessage()
 * @method int getCode()
 */
trait ApiProblemExceptionTrait
{
    /**
     * @param ResponseInterface $response
     *
     * @return self
     */
    public static function fromResponse(ResponseInterface $response)
    {
        $responseBody = (string) $response->getBody();
        if (empty($responseBody)) {
            return new self($response->getStatusCode(), $response->getReasonPhrase());
        }

        $payload = json_decode($responseBody, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return new self($response->getStatusCode(), $response->getReasonPhrase(), '', $responseBody);
        }

        return self::fromPayload($payload);
    }

    /**
     * @param array $payload
     *
     * @return self
     */
    public static function fromPayload(array $payload)
    {
        return new self($payload['status'], $payload['title'], $payload['type'], $payload['detail']);
    }

    /**
     * @var string
     */
    private $type;

    /**
     * @var string|array
     */
    private $detail;

    /**
     * @param int $status
     * @param string $title
     * @param string $type
     * @param string|array $detail
     */
    public function __construct($status, $title, $type = '', $detail = '')
    {
        parent::__construct($title, $status);

        $this->type = $type;
        $this->detail = $detail;
    }

    /**
     * @see ApiProblemExceptionInterface::getTitle
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getMessage();
    }

    /**
     * @see ApiProblemExceptionInterface::getStatus
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->getCode();
    }

    /**
     * @see ApiProblemExceptionInterface::getType
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @see ApiProblemExceptionInterface::getDetail
     *
     * @return string|array
     */
    public function getDetail()
    {
        return $this->detail;
    }
}
