<?php declare(strict_types = 1);

namespace WebProxy\Support\Wrappers;

use WebProxy\Support\Enumerations\Method;
use WebProxy\Support\Enumerations\RequestBodyType;
use WebProxy\Support\Exceptions\RequestBodyTypeMismatchException;
use WebProxy\Support\Exceptions\UnsupportedMethodException;

/**
 * Class Request
 *
 * Request options wrapper.
 */
class Request
{

	/** @var string $method Request method. See Method enumeration. */
	protected $method;

	/**
	 * @var array $body Array representation of request body.
	 *
	 * <pre>
	 * [
	 *    'foo' => 'bar',
	 *    'baz' => ['hi', 'there!']
	 * ]
	 * </pre>
	 *
	 * (FormData, JSON)
	 *
	 * OR
	 *
	 * <pre>
	 * [
	 *    [
	 *        'name'     => 'filename',
	 *        'contents' => fopen('/path/to/file', 'r'),
	 *        'filename' => 'custom_filename.txt'
	 *    ],
	 * ]
	 * </pre>
	 *
	 * (Multipart).
	 */
	protected $body;

	/**
	 * @var array $files Array of files supplied with request.
	 *
	 * <pre>
	 * [
	 *    [
	 *        'name'     => 'filename',
	 *        'contents' => fopen('/path/to/file', 'r'),
	 *        'filename' => 'custom_filename.txt'
	 *    ],
	 * ]
	 * </pre>.
	 */
	protected $files;

	/**
	 * @var array $headers Array of headers supplied with request.
	 *
	 * <pre>
	 * [
	 *    'Header-Name' => 'Header-Value'
	 * ]
	 * </pre>.
	 */
	protected $headers;

	/**
	 * @var array $options Array of optional WebProxy options.
	 *
	 * <pre>
	 * [
	 *    'request_body_type' => 'form-data'
	 * ]
	 * </pre>.
	 */
	protected $options;

	/**
	 * Private request constructor.
	 *
	 * @param string $method See Method enumeration.
	 *
	 * @throws UnsupportedMethodException When $method is not valid according to Method enumeration.
	 */
	private function __construct(string $method)
	{
		if (! Method::isValidValue($method)) {
			throw new UnsupportedMethodException();
		}

		$this->method = $method;
	}

	/**
	 * Public static request constructor.
	 *
	 * @param string $method See Method enumeration.
	 *
	 * @return Request
	 *
	 * @throws UnsupportedMethodException From self::__construct().
	 */
	public static function create(string $method): Request
	{
		return new self($method);
	}

	/**
	 * @return string See self::$method;
	 */
	public function getMethod(): string
	{
		return $this->method;
	}

	/**
	 * @return array See self::$body.
	 */
	public function getBody(): array
	{
		return $this->body ?? [];
	}

	/**
	 * Shorthand for self::withFormParamsBody() or self::withMultipartBody()
	 *
	 * @param array $body Request body parameters.
	 *
	 * <pre>
	 * [
	 *    'foo' => 'bar',
	 *    'baz' => ['hi', 'there!']
	 * ]
	 * </pre>.
	 *
	 * @return Request Self.
	 */
	public function withBody(array $body): Request
	{
		if (empty($this->files)) {
			return $this->withFormParamsBody($body);
		}

		return $this->withMultipartBody($body);
	}

	/**
	 * @param array $body Request body parameters.
	 *
	 * <pre>
	 * [
	 *    'foo' => 'bar',
	 *    'baz' => ['hi', 'there!']
	 * ]
	 * </pre>.
	 *
	 * @return Request Self.
	 */
	public function withFormParamsBody(array $body): Request
	{
		$this->body = $body;

		$this->options['request_body_type'] = RequestBodyType::FORM_PARAMS;

		return $this;
	}

	/**
	 * @param array $body Request body parameters.
	 *
	 * <pre>
	 * [
	 *    'foo' => 'bar',
	 *    'baz' => ['hi', 'there!']
	 * ]
	 * </pre>.
	 *
	 * @return Request Self.
	 */
	public function withJsonBody(array $body): Request
	{
		$this->body = $body;

		$this->options['request_body_type'] = RequestBodyType::JSON;

		return $this;
	}

	/**
	 * @param array $body Request body parameters.
	 *
	 * <pre>
	 * [
	 *    'name' => 'foo'
	 * ]
	 * </pre>
	 *
	 * OR
	 *
	 * <pre>
	 * [
	 *    [
	 *        'name'     => 'foo',
	 *        'contents' => 'data',
	 *        'headers'  => ['X-Baz' => 'bar']
	 *    ],
	 *    [
	 *        'name'     => 'baz',
	 *        'contents' => fopen('/path/to/file', 'r')
	 *    ],
	 *    [
	 *        'name'     => 'qux',
	 *        'contents' => fopen('/path/to/file', 'r'),
	 *        'filename' => 'custom_filename.txt'
	 *    ],
	 * ]
	 * </pre>.
	 *
	 * @return Request
	 */
	public function withMultipartBody(array $body): Request
	{
		foreach ($body as $parameterName => $parameter) {
			if (is_scalar($parameter)) {
				$parameter = [
					'name'     => $parameterName,
					'contents' => $parameter,
				];
			}

			$this->body[] = $parameter;
		}

		$this->options['request_body_type'] = RequestBodyType::MULTIPART;

		return $this;
	}

	/**
	 * @return array See self::$files.
	 */
	public function getFiles(): array
	{
		return $this->files ?? [];
	}

	/**
	 * @param array $files Files supplied with the request.
	 *
	 * <pre>
	 * [
	 *    'filename' => '/fullPath/to/file'
	 * ]
	 * </pre>
	 *
	 * OR
	 *
	 * <pre>
	 * [
	 *    [
	 *        'name'     => 'qux',
	 *        'contents' => fopen('/path/to/file', 'r'),
	 *        'filename' => 'custom_filename.txt'
	 *    ],
	 * ]
	 * </pre>.
	 *
	 * @return Request Self.
	 *
	 * @throws RequestBodyTypeMismatchException When return body type is not multipart.
	 */
	public function withFiles(array $files): Request
	{
		$bodyType = $this->getOption('request_body_type');
		if ($bodyType !== null && $bodyType !== RequestBodyType::MULTIPART) {
			throw new RequestBodyTypeMismatchException("Request body type must be multipart in order to add images");
		}

		foreach ($files as $filename => $file) {
			if (is_scalar($file)) {
				$file = [
					'name'     => $filename,
					'contents' => fopen($file, 'r'),
					'filename' => $filename,
				];
			}

			$this->files[] = $file;
		}

		// In case body type was null.
		$this->options['request_body_type'] = RequestBodyType::MULTIPART;

		return $this;
	}

	/**
	 * @return array See self::$headers;
	 */
	public function getHeaders(): array
	{
		return $this->headers ?? [];
	}

	/**
	 * @param array $headers Headers submitted with the request.
	 *
	 * <pre>
	 * [
	 *    'Header-Name' => 'Header-Value'
	 * ]
	 * </pre>.
	 *
	 *
	 * @return Request Self.
	 */
	public function withHeaders(array $headers): Request
	{
		$this->headers = $headers;

		return $this;
	}

	/**
	 * @return array See self::$options.
	 */
	public function getOptions(): array
	{
		return $this->options ?? [];
	}

	/**
	 * @param string $key Get one key from self::$options.
	 *
	 * @return mixed|null Null if option was not set.
	 *
	 */
	public function getOption(string $key)
	{
		return $this->options[$key] ?? null;
	}

	/**
	 * @param array $options Array of optional WebProxy options.
	 *
	 * <pre>
	 * [
	 *    'request_body_type' => 'form-data'
	 * ]
	 * </pre>.
	 *
	 * @return Request Self.
	 */
	public function withOptions(array $options): Request
	{
		$this->options = $options;

		return $this;
	}

	/**
	 * Transform the object for use with Guzzle HTTP.
	 *
	 * @return array Array suitable for Guzzle request method.
	 *
	 * <pre>
	 * [
	 * 'headers' => []
	 * 'json' => []
	 * 'form_params' => []
	 * ]
	 * </pre>.
	 */
	public function getGuzzleOptions(): array
	{
		$options = [
			'headers' => $this->getHeaders(),
		];

		if (in_array($this->getMethod(), [Method::GET, Method::DELETE])) {
			return $options;
		}

		// If method supports body, append.
		$postBodyType = $this->getOption('request_body_type') ?? 'body';

		$options[$postBodyType] = array_merge(
			$this->getBody(),
			$this->getFiles()
		);

		return $options;
	}
}
