<?php

namespace Ibercheck\Api\ApiProblem;

interface ApiProblemInterface
{
    /**
     * A short, human-readable summary of the problem type.
     *
     * @return string
     */
    public function getTitle();

    /**
     * The HTTP status code generated by the origin server for this occurrence of the problem.
     *
     * @return int
     */
    public function getStatus();

    /**
     * An absolute URI that identifies the problem type.
     *
     * @return string
     */
    public function getType();

    /**
     * An human readable explanation specific to this occurrence of the problem.
     *
     * @return string|array
     */
    public function getDetail();
}
