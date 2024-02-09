<?php

namespace App\Framework\Routing;

/**
 * Represents a route in the application's routing system.
 *
 * @package App\Framework\Routing
 */
final class Route
{
    /**
     * The URI pattern for the route.
     *
     * @var string $uri
     */
    protected string $uri;

    /**
     * The HTTP method associated with the route (e.g., GET, POST).
     *
     * @var string $method
     */
    protected string $method;

    /**
     * The action to be taken when the route is matched.
     *
     * @var array $action
     */
    protected array $action;

    /**
     * Router constructor.
     *
     * @param string $uri The URI pattern for the route.
     * @param string $method The HTTP method associated with the route.
     * @param array $action The action to be taken when the route is matched.
     */
    public function __construct(string $uri, string $method, array $action)
    {
        $this->uri = $uri;
        $this->method = $method;
        $this->action = $action;
    }

    /**
     * Get the URI pattern for the route.
     *
     * @return string The URI pattern.
     */
    public function uri(): string
    {
        return $this->uri;
    }

    /**
     * Get the HTTP method associated with the route.
     *
     * @return string The HTTP method.
     */
    public function method(): string
    {
        return $this->method;
    }

    /**
     * Get the action associated with the route.
     *
     * @return array The action.
     */
    public function get_action(): array
    {
        return $this->action;
    }
}
