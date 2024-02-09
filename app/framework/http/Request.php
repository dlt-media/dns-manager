<?php

namespace App\Framework\Http;

use App\Framework\Base\Validator;
use Exception;

/**
 * The Request class represents an HTTP request entity and provides methods
 * to work with query parameters. It is designed to simplify the extraction
 * of query parameters from a URL.
 *
 * @package App\Framework\Http
 */
class Request
{
    /**
     * Retrieve the value of a query parameter.
     *
     * @param string $parameter The name of the query parameter.
     * @param string|null $default The default value if the parameter is not set.
     * @return string|null The value of the query parameter or the default value.
     */
    final public function get(string $parameter, ?string $default = null): ?string
    {
        return $_GET[$parameter] ?? $default;
    }

    /**
     * Retrieve the value of a form post data parameter.
     *
     * @param string $parameter The name of the form post data parameter.
     * @param string|null $default The default value if the parameter is not set.
     * @return string|null The value of the form post data parameter or the default value.
     */
    final public function input(string $parameter, ?string $default = null): ?string
    {
        return $_POST[$parameter] ?? $default;
    }

    /**
     * Retrieve all form post data as an associative array.
     *
     * @return array The associative array of form post data.
     */
    final public function all(): array
    {
        return $_POST;
    }

    /**
     * Validate multiple parameters based on the given validation patterns.
     *
     * @param array $rules An associative array where keys are parameter names and values are validation patterns (e.g. ['name' => 'required|string|max:255']).
     * @return Validator
     *
     * @throws Exception
     */
    public function validate(array $rules): Validator
    {
        $validator = new Validator($this->all(), $rules);
        $validator->validate();

        return $validator;
    }
}
