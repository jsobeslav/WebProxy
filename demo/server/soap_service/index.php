<?php
/**
 * Fakes a SOAP service that supplies list of products and detail of one specific product.
 */

// Include dependencies.
include 'vendor\autoload.php';

// Instantiate SOAP server.
$server = new soap_server();

$namespace = 'http://localhost/soap_service/?wdsl';
$server->configureWSDL('serviceName', $namespace);

// Implement and register listProducts method.
function listProducts($category = 'books')
{
	if ($category == "books") {
		return [
			"The WordPress Anthology",
			"PHP Master: Write Cutting Edge Code",
			"Build Your Own Website the Right Way",
		];
	} else {
		return ["No products listed under that category"];
	}
}

$server->wsdl->addComplexType(
	'ListArray',
	'complexType',
	'array',
	'',
	'SOAP-ENC:Array',
	[],
	[
		['ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'xsd:string[]'],
	],
	'xsd:string'
);

$server->register(
	"listProducts", // FUNCTION NAME
	[
		// INPUT
		'category' => 'xsd:string',
	],
	[
		//OUTPUT
		'return' => 'tns:ListArray',
	],
	$namespace, //NAMESPACE
	$namespace . '#listProducts', //SOAP ACTION
	'rpc', //STYLE
	'encoded', // USE
	'List available products' //DOCS
);

// Implement and register getProduct method.
function getProduct(int $id)
{
	if ($id == 1) {
		return 'The WordPress Anthology';
	} else {
		return "Product not found";
	}
}

$server->register(
	"getProduct", // FUNCTION NAME
	[
		// INPUT
		'id' => 'xsd:integer',
	],
	[
		//OUTPUT
		'return' => 'xsd:string',
	],
	$namespace, //NAMESPACE
	$namespace . '#getProduct', //SOAP ACTION
	'rpc', //STYLE
	'encoded', // USE
	'Show details of one product' //DOCS
);

// Implement and register getHeaders method.
function getHeaders()
{
	 $headers = $GLOBALS['server']->requestHeaders;

	if (!empty($headers)) {
		return 'The headers are present: '.$headers;
	} else {
		return 'The header is NOT present!';
	}
}

$server->register(
	"getHeaders", // FUNCTION NAME
	[], // INPUT
	[ //OUTPUT
	  'return' => 'xsd:string',
	],
	$namespace, //NAMESPACE
	$namespace . '#getHeaders', //SOAP ACTION
	'rpc', //STYLE
	'encoded', // USE
	'Verify that a header was received by the service' //DOCS
);

// SERVE THE REQUEST.
$request = file_get_contents('php://input');
$server->service($request);