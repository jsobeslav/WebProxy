<?php declare(strict_types = 1);

if (! function_exists('prepend_slash')) {
	/**
	 * If necessary, prepend slash before string.
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	function prepend_slash(string $string): string
	{
		return '/' . ltrim($string, "/");
	}
}

if (! function_exists('append_slash')) {
	/**
	 * If necessary, appends trailing slash to URI.
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	function append_slash(string $string): string
	{
		return rtrim($string, "/") . '/';
	}
}

if (! function_exists('trim_slashes')) {
	/**
	 * If necessary, trim slash from beginning and end of the string.
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	function trim_slashes(string $string): string
	{
		return trim($string, "/");
	}
}
