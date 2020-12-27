<?php

namespace Ibercheck\Api\ApiProblem;

use Psr\Http\Message\ResponseInterface;

/**
 * @method string getMessage()
 * @method int getCode()
 */
trait ApiProblemExceptionTrait
{
    public static function fromResponse(ResponseInterface $response): self
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

    public static function fromPayload(array $payload): self
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
     * @param string|array $detail
     */
    public function __construct(int $status, string $title, string $type = '', $detail = '')
    {
        parent::__construct($title, $status);

        $this->type = $type;
        $this->detail = $detail;
    }

    public function getTitle(): string
    {
        return $this->getMessage();
    }

    public function getStatus(): int
    {
        return $this->getCode();
    }

    public function getType(): string
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
