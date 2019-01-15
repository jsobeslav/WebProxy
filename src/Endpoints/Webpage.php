<?php declare(strict_types = 1);

namespace WebProxy\Endpoints;

use Symfony\Component\DomCrawler\Crawler;
use WebProxy\Support\Wrappers\Response;

abstract class Webpage extends HttpEndpoint
{

	/** @var Crawler Symfony DomCrawler instance created upon response source */
	protected $crawler;

	/**
	 * Save response nad initialize DOM crawler on the returned HTML body.
	 *
	 * @param Response $response
	 *
	 * @return void
	 */
	public function setResponse(Response $response): void
	{
		parent::setResponse($response);

		$this->crawler = new Crawler($this->getResponse()->getBody());
	}

	/**
	 * @return Crawler
	 */
	public function getCrawler(): Crawler
	{
		return $this->crawler;
	}
}
