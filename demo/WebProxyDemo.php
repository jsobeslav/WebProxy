<?php declare(strict_types = 1);

namespace WebProxyDemo;

use WebProxy\Support\Enumerations\Method;
use WebProxy\Support\Wrappers\Request;
use WebProxy\WebProxy;
use WebProxyDemo\Client\FakeNameGenerator\HomeWebpage;
use WebProxyDemo\Client\JsonPlaceholder\DeleteResource;
use WebProxyDemo\Client\JsonPlaceholder\PostResource;
use WebProxyDemo\Client\JsonPlaceholder\PutResource;
use WebProxyDemo\Client\LocalServer\HttpService\PostFormWebpage;
use WebProxyDemo\Client\LocalServer\SoapService\GetProductSoapEndpoint;
use WebProxyDemo\Client\LocalServer\SoapService\ListProductsSoapEndpoint;
use WebProxyDemo\Client\Nameday\TodayResource;
use WebProxyDemo\Client\Nameday\TomorrowResource;

class WebProxyDemo
{

	/** @var WebProxy $proxy */
	protected $proxy;

	/**
	 * WebProxyDemo constructor.
	 */
	public function __construct()
	{
		$this->proxy = new WebProxy();
	}

	/**
	 * Showcast all abilities
	 *
	 * @throws \WebProxy\Support\Exceptions\UnknownClient
	 * @throws \WebProxy\Support\Exceptions\UnknownService
	 * @throws \WebProxy\Support\Exceptions\UnsupportedMethodException
	 * @throws \WebProxy\Support\Exceptions\RequestBodyTypeMismatchException
	 */
	public function run()
	{

		// Fetch JSON from REST API.
		$this->restGet();

		// Send JSON to REST API.
		$this->restPost();

		// Put resource to REST API.
		$this->restPut();

		// Delete resource from REST API.
		$this->restDelete();

		// Scrape data from website.
		$this->websiteGet();

		// Submit form with file to website.
		$this->websitePost();

		// Perform SOAP call.
		$this->soapCall();
	}

	/**
	 * Perform HTTP GET request on REST API and parse name from JSON response.
	 *
	 * @throws \WebProxy\Support\Exceptions\UnknownClient
	 * @throws \WebProxy\Support\Exceptions\UnknownService
	 * @throws \WebProxy\Support\Exceptions\UnsupportedMethodException
	 */
	protected function restGet()
	{
		/** @var TodayResource $nameday */
		$nameday = $this->proxy->get(
			new TodayResource()
		);

		echo 'REST GET: Current nameday: ' . $nameday->getName() . '<br/>';

		/** @var TomorrowResource $nameday */
		$nameday = $this->proxy->get(
			new TomorrowResource()
		);

		echo 'REST GET: Tomorrow nameday: ' . $nameday->getName() . '<br/>';
	}

	/**
	 * Perform HTTP POST request on REST API and parse data from JSON response.
	 *
	 * @throws \WebProxy\Support\Exceptions\UnknownClient
	 * @throws \WebProxy\Support\Exceptions\UnknownService
	 * @throws \WebProxy\Support\Exceptions\UnsupportedMethodException
	 */
	protected function restPost()
	{
		/** @var PostResource $newPost */
		$newPost = $this->proxy->httpRequest(
			new PostResource(),
			Request::create(Method::POST)
				   ->withJsonBody(
					   [
						   'title'  => 'foo',
						   'body'   => 'bar',
						   'userId' => 1,
					   ]
				   )
		);

		echo 'REST POST: New post: ' . $newPost->getTitle() . ' (ID: ' . $newPost->getId() . ')</br>';
	}

	/**
	 * Perform HTTP PUT request on REST API
	 *
	 * @throws \WebProxy\Support\Exceptions\UnknownClient
	 * @throws \WebProxy\Support\Exceptions\UnknownService
	 * @throws \WebProxy\Support\Exceptions\UnsupportedMethodException
	 */
	protected function restPut()
	{
		/** @var PutResource $post */
		$post = $this->proxy->httpRequest(
			new PutResource('5'),
			Request::create(Method::PUT)
				   ->withJsonBody(
					   [
						   'title' => 'bar',
					   ]
				   )
		);

		echo 'REST PUT: New post title: ' . $post->getNewTitle() . '</br>';
	}

	/**
	 * Perform HTTP DELETE request on REST API
	 *
	 * @throws \WebProxy\Support\Exceptions\UnknownClient
	 * @throws \WebProxy\Support\Exceptions\UnknownService
	 * @throws \WebProxy\Support\Exceptions\UnsupportedMethodException
	 */
	protected function restDelete()
	{
		$response = $this->proxy->delete(
			new DeleteResource('20')
		);

		echo 'REST DELETE: Resource with ID 20 deleted' . '<br/>';
	}

	/**
	 * Perform HTTP GET request on website and scrape name by CSS selector.
	 *
	 * @throws \WebProxy\Support\Exceptions\UnknownClient
	 * @throws \WebProxy\Support\Exceptions\UnknownService
	 * @throws \WebProxy\Support\Exceptions\UnsupportedMethodException
	 */
	protected function websiteGet()
	{
		/** @var HomeWebpage $fakeName */
		$fakeName = $this->proxy->get(
			new HomeWebpage('gen-male-cs-cz.php')
		);

		echo 'Website GET: Random name: ' . $fakeName->getName() . '<br/>';
	}

	/**
	 * Perform HTTP POST with file on website and scrape response message.
	 *
	 * @throws \WebProxy\Support\Exceptions\RequestBodyTypeMismatchException
	 * @throws \WebProxy\Support\Exceptions\UnknownClient
	 * @throws \WebProxy\Support\Exceptions\UnknownService
	 * @throws \WebProxy\Support\Exceptions\UnsupportedMethodException
	 */
	protected function websitePost()
	{
		/** @var PostFormWebpage $localForm */
		$localForm = $this->proxy->post(
			new PostFormWebpage(),
			[
				'title' => 'example',
			],
			[
				'image' => 'image.png',
			]
		);

		echo 'Website POST: Response message:' . $localForm->getResponseMessage() . '</br>';
	}

	/**
	 * Perform SOAP calls and parse XML response.
	 *
	 * @throws \WebProxy\Support\Exceptions\UnknownClient
	 * @throws \WebProxy\Support\Exceptions\UnknownService
	 * @throws \WebProxy\Support\Exceptions\UnsupportedMethodException
	 */
	protected function soapCall()
	{
		/** @var ListProductsSoapEndpoint $listProducts */
		$listProducts = $this->proxy->call(
			new ListProductsSoapEndpoint(),
			[
				'category' => 'books',
			]
		);

		echo 'SOAP call: List of products:' . print_r($listProducts->getList(), true) . '</br>';

		/** @var GetProductSoapEndpoint $getProduct */
		$getProduct = $this->proxy->call(
			new GetProductSoapEndpoint(),
			[
				'id' => 1,
			]
		);

		echo 'SOAP call: Detail of product with ID 1:' . $getProduct->getTitle() . '</br>';
	}
}