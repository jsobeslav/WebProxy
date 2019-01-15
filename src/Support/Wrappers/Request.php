<?php declare(strict_types = 1);

namespace WebProxy\Support\Wrappers;

use WebProxy\Support\Enumerations\Method;
use WebProxy\Support\Enumerations\RequestBodyType;
use WebProxy\Support\Exceptions\RequestBodyTypeMismatchException;

/**
 * Class Request
 *
 * Request wrapper
 *
 * @package WebProxy\Support\Wrappers
 */
class Request
{

	/** @var string $method */
	protected $method;

	/** @var array $body */
	protected $body;

	/** @var array $files */
	protected $files;

	/** @var array $headers */
	protected $headers;

	/** @var array $options */
	protected $options;

	/**
	 * Private request constructor.
	 *
	 * @param string $method
	 */
	private function __construct(string $method)
	{
		$this->method = $method;
	}

	/**
	 * Public static request constructor.
	 *
	 * @param string $method
	 *
	 * @return Request
	 */
	public static function create(string $method)
	{
		return new self($method);
	}

	public function getMethod(): string
	{
		return $this->method;
	}

	public function getBody(): array
	{
		return $this->body ?? [];
	}

	/**
	 * Shorthand for self::withFormParamsBody()
	 *
	 * @param array $body
	 *
	 * @return Request
	 */
	public function withBody(array $body): Request
	{
		if (empty($this->files)) {
			return $this->withFormParamsBody($body);
		}

		return $this->withMultipartBody($body);
	}

	/**
	 * @param array $body
	 *
	 * <pre>
	 * [
	 *    'foo' => 'bar',
	 *    'baz' => ['hi', 'there!']
	 * ]
	 * </pre>
	 *
	 * @return Request
	 */
	public function withFormParamsBody(array $body): Request
	{
		$this->body = $body;

		$this->options['request_body_type'] = RequestBodyType::FORM_PARAMS;

		return $this;
	}

	/**
	 * @param array $body
	 *
	 * <pre>
	 * [
	 *    'foo' => 'bar',
	 *    'baz' => ['hi', 'there!']
	 * ]
	 * </pre>
	 *
	 * @return Request
	 */
	public function withJsonBody(array $body): Request
	{
		$this->body = $body;

		$this->options['request_body_type'] = RequestBodyType::JSON;

		return $this;
	}

	/**
	 * @param array $body
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
	 * </pre>
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

	public function getFiles(): array
	{
		return $this->files ?? [];
	}

	/**
	 * @param array $files
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
	 * </pre>
	 *
	 * @return Request
	 *
	 * @throws RequestBodyTypeMismatchException When return body type is not multipart
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

	public function getHeaders(): array
	{
		return $this->headers ?? [];
	}

	public function withHeaders(array $headers): Request
	{
		$this->headers = $headers;

		return $this;
	}

	public function getOptions(): array
	{
		return $this->options ?? [];
	}

	public function getOption(string $key)
	{
		return $this->options[$key] ?? null;
	}

	public function withOptions(array $options): Request
	{
		$this->options = $options;

		return $this;
	}

	/**
	 * Transform the object for use with Guzzle HTTP.
	 *
	 * @return array
	 */
	public function getGuzzleOptions()
	{
		$options = [
			'headers' => array_merge(
				$this->getHeaders()
			),
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
