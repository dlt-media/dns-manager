<?php

namespace Framework\Support;

use Framework\Foundation\Config;
use Framework\Http\Request;

/**
 * The URL class represents a utility for generating and manipulating URLs.
 *
 * This class provides methods for generating absolute URLs, retrieving the base URL for the application,
 * and getting the current URL. It supports handling of query parameters and provides options to exclude
 * the host from the generated URLs if needed.
 *
 * @package Framework\Support
 */
class URL
{
    /**
     * Get the base URL for the application.
     *
     * @return string|null The base URL for the application. Returns relative path of document root to project directory if 'app_url' is not set.
     */
    public static function app_url(): ?string
    {
        $url = config('app.app_url');

        return !empty($url) ? $url : request()->base_url() . ltrim(dirname(server()->get('SCRIPT_NAME')), '/') . '/';
    }

    /**
     * Generate an absolute URL for the given path and parameters, optionally excluding the host.
     *
     * @param string $path The path to the resource.
     * @param array $parameters [optional] Parameters to append to the URL as query parameters.
     * @param bool $exclude_host [optional] Whether to exclude the host from the generated URL.
     * @return string The generated absolute URL.
     */
    public static function to(string $path, array $parameters = [], bool $exclude_host = false): string
    {
        $url = self::app_url() . ltrim($path, '/');

        if (!empty($parameters)) {
            $url .= '?' . http_build_query($parameters);
        }

        if ($exclude_host) {
            $parts = parse_url($url);
            $url = $parts['path'];

            if (isset($parts['query'])) {
                $url .= '?' . $parts['query'];
            }
        }

        return $url;
    }

    /**
     * Get the current URL.
     *
     * @return string The current URL.
     */
    public static function current(): string
    {
        return request()->base_url() . ltrim(request()->path(), '/');
    }
}

