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

	/**
	 * Shorthand for getting attribute of a single element.
	 *
	 * @param string $selector
	 * @param string $attr
	 *
	 * @return string
	 */
	public function getAttr($selector, $attr): string
	{
		$node = $this->crawler
			->filter($selector)
			->first();

		return (count($node) > 0) ? $node->attr($attr) : '';
	}

	/**
	 * Shorthand for getting text contents of a single element.
	 *
	 * @param string $selector
	 *
	 * @return string
	 */
	public function getText($selector): string
	{
		$node = $this->crawler
			->filter($selector)
			->first();

		return (count($node) > 0) ? trim($node->text()) : '';
	}

	/**
	 * @param string $selector
	 *
	 * @return integer
	 */
	public function countElements($selector): int
	{
		return count($this->crawler->filter($selector));
	}
}
