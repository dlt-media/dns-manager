<?php

namespace Framework\Foundation;

use Framework\Http\HeaderBag;
use Throwable;

/**
 * The View class is responsible for rendering the content of view files.
 * It provides a simple method to render views with optional data.
 * This class is designed to facilitate the separation of concerns
 * in a web application by handling the rendering of HTML views.
 *
 * @package Framework\Foundation
 */
class View
{
    /**
     * The path to the view file.
     *
     * @var string
     */
    protected string $path;

    /**
     * Data to be passed to the view.
     *
     * @var array
     */
    protected array $data;

    /**
     * Headers to be included in the response.
     *
     * @var HeaderBag
     */
    protected HeaderBag $headers;

    /**
     * View constructor.
     *
     * @param string $path The path to the view file.
     * @param array $data Data to be passed to the view.
     */
    public function __construct(string $path, array $data = [])
    {
        $this->data = $data;
        $this->path = $path;

        $this->headers = new HeaderBag();
    }

    /**
     * Create a new instance of the View class.
     *
     * @param string $path The path to the view file.
     * @param array $data Data to be passed to the view.
     * @return View
     */
    public static function make(string $path, array $data = []): View
    {
        return new self($path, $data);
    }

    /**
     * Add data to be passed to the view.
     *
     * @param string $key The key for the data.
     * @param mixed $value The value to be passed to the view.
     * @return View The current View instance.
     */
    public function with(string $key, $value): View
    {
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * Set a header for the response.
     *
     * @param string $name The name of the header.
     * @param string $value The value of the header.
     * @return View The current View instance.
     */
    public function with_header(string $name, string $value): View
    {
        $this->headers->set($name, $value);

        return $this;
    }

    /**
     * Set headers for the response.
     *
     * @param HeaderBag $headers The headers for the response.
     * @return View The current View instance.
     */
    public function with_headers(HeaderBag $headers): View
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Get the headers for the response.
     *
     * @return HeaderBag
     */
    public function get_headers(): HeaderBag
    {
        return $this->headers;
    }

    /**
     * Render the view and return the content as a string.
     *
     * @return string|null The rendered view content, or null on failure.
     */
    public function render(): ?string
    {
        $path = realpath($this->path) ?: realpath(base_path() . '/resources/views/' . str_replace('.', DIRECTORY_SEPARATOR, $this->path) . '.php');

        try {
            extract($this->data);
            ob_start();

            include $path;

            return ob_get_clean() ?: null;
        } catch (Throwable $exception) {
            return $exception->getMessage();
        }
    }
}
