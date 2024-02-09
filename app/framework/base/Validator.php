<?php

namespace App\Framework\Base;

use Exception;

/**
 * The Validator class provides a simple and extensible way to validate data based on specified rules.
 *
 * @package App\Framework\Base
 */
final class Validator
{
    /**
     * The data to be validated.
     *
     * @var array
     */
    protected array $data;

    /**
     * The validation rules for each field.
     *
     * @var array
     */
    protected array $rules;

    /**
     * The array to store validation errors.
     *
     * @var array
     */
    protected array $errors = [];

    /**
     * Validator constructor.
     *
     * @param array $data The data to be validated.
     * @param array $rules An associative array where keys are parameter names and values are validation patterns (e.g. ['name' => 'required|string|max:255']).
     */
    public function __construct(array $data, array $rules)
    {
        $this->data = $data;
        $this->rules = $rules;
    }

    /**
     * Validates the data based on the specified rules.
     *
     * @return bool True if validation passes, false otherwise.
     *
     * @throws Exception If an unsupported validation rule is encountered.
     */
    public function validate(): bool
    {
        foreach ($this->rules as $field => $rules) {
            array_map(fn($rule) => $this->apply_rule($field, $rule), explode('|', $rules));
        }

        return empty($this->errors);
    }

    /**
     * Applies a single validation rule to a field.
     *
     * @param string $field The field to validate.
     * @param string $rule The validation rule to apply.
     * @return bool
     *
     * @throws Exception If an unsupported validation rule is encountered.
     */
    protected function apply_rule(string $field, string $rule): bool
    {
        if ($rule === 'required') {
            return $this->is_required($field);
        }

        if ($rule === 'string') {
            return $this->is_string($field);
        }

        if ($rule === 'numeric') {
            return $this->is_numeric($field);
        }

        if ($rule === 'email') {
            return $this->is_email($field);
        }

        throw new Exception($rule);
    }

    /**
     * Adds a validation error for a specific field and rule.
     *
     * @param string $field The field for which the validation error occurred.
     * @param string $rule The rule that was not satisfied.
     */
    protected function add_error(string $field, string $rule)
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }

        $this->errors[$field][] = $rule;
    }

    /**
     * Retrieves the array of validation errors.
     *
     * @return array|null The array of validation errors, or null if no errors exist.
     */
    public function errors(): ?array
    {
        return empty($this->errors) ? null : $this->errors;
    }

    /**
     * Validates that the specified field is required and not empty.
     *
     * @param string $field The field to validate.
     * @return bool True if the validation passes, false otherwise.
     */
    protected function is_required(string $field): bool
    {
        $value = $this->data[$field] ?? null;
        $is_valid = !empty($value);

        if (!$is_valid) {
            $this->add_error($field, 'required');
        }

        return $is_valid;
    }

    /**
     * Validates that the specified field is a string.
     *
     * @param string $field The field to validate.
     * @return bool True if the validation passes, false otherwise.
     */
    protected function is_string(string $field): bool
    {
        $value = $this->data[$field] ?? null;
        $is_valid = isset($value) && is_string($value);

        if (!$is_valid) {
            $this->add_error($field, 'string');
        }

        return $is_valid;
    }

    /**
     * Validates that the specified field is numeric.
     *
     * @param string $field The field to validate.
     * @return bool True if the validation passes, false otherwise.
     */
    protected function is_numeric(string $field): bool
    {
        $value = $this->data[$field] ?? null;
        $is_valid = isset($value) && is_numeric($value);

        if (!$is_valid) {
            $this->add_error($field, 'numeric');
        }

        return $is_valid;
    }

    /**
     * Validates that the specified field is a valid email address.
     *
     * @param string $field The field to validate.
     * @return bool True if the validation passes, false otherwise.
     */
    protected function is_email(string $field): bool
    {
        $value = $this->data[$field] ?? null;
        $is_valid = isset($value) && filter_var($value, FILTER_VALIDATE_EMAIL) !== false;

        if (!$is_valid) {
            $this->add_error($field, 'email');
        }

        return $is_valid;
    }
}
