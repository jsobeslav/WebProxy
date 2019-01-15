# Overview
- WebProxy is PHP library that offers consistent interface for communicating with standard third-party web services, be it API or an ordinary website.
- It offers parsing JSON response from REST API, scrapping and crawling HTML code from website, or calling methods on SOAP services.
- To clarify certain potential misconceptions, please see terminology chapter below.

## Included libraries / dependencies
- For HTTP: `guzzlehttp/guzzle` | http://docs.guzzlephp.org/en/stable/
- For HTML crawling: `symfony/dom-crawler` | https://symfony.com/doc/current/components/dom_crawler.html
- For SOAP: `econea/nusoap` | https://econea.github.io/nusoap/



# Usage

## Examples
- For implemented examples, see `demo` folder.

## General guide
- Create Service class that extends suitable child
    - Options are:
        - `RestApi` (for REST APIs)
        - `Website` (for crawling trough Websites)
        - `SoapService` (for SOAP APIs)
    - Fill in the missing interface methods
        - `getUri()`: return base URI where service is located.
            - For `HttpService` it's base URI. E.q. `return 'https://jsonplaceholder.typicode.com';`
            - For `SoapService` it's WSDL URI. E.q. `return 'https://soapexample.com?wsdl';`
- Create Endpoint classes that extends children appropriate to selected Service type
    - Options are:
        - `RestResource` (for `RestApi`)
        - `Webpage` (for crawling trough `Website`)
        - `SoapEndpoint` (for `SoapService`)
    - Fill in the missing interface methods, and optional class variables
        - `getServiceClass()`: Return class of appropriate service. E.q. `return JsonPlaceholderRestApi::class;`
        - `getRequestName()`: Return specification of request that the endpoint performs.
            - For `HttpService`, it's URI appendend after base Service URI. E.q. `return '/posts';`
            - For `SoapService`, it's method name. E.q. `return 'getPosts';`
        - `$supportedMethods`: Optional whitelist for HttpServices. E.q. `return [Method::POST];`
    - Write methods to filter the results
        - For `RestResource`, utilize parsed data object: `return $this->getResponseData()['id'];`
        - For `Websites`, utilize Symfony DOM Crawler: `return $this->getCrawler()->filter('.address h3')->text();`
        - For `SoapEndpoints`, utilize parsed data: `return $this->getResponseData()['result'];`
- Initialize `WebProxy` object (`new WebProxy()`) and desired endpoints (`new ExampleResource()`)
- Pass new instance of the custom endpoint to `WebProxy` trough one of its methods
    - `get`, `post`, `put`, `delete` as shorthands for HTTP Requests
    - `httpRequest` for customized HTTP Requests
    - `call` as shorthand for SOAP request
    - `soapRequest` for customized SOAP Requests
- for specific purposes, you might want to use `httpRequest` and `soapRequest` methods with custom `Request` object
    - For example, default POST body type is Form-data, or Multipart (if files are sent with it). To send JSON, use `Request::create(Method::POST)->withJsonBody(['array','content])`
    - pass instance of the `Request` as second parameter to mentioned methods
- Use returned modified instance of endpoint to return parsed data



# Terminology used
- `Client`: A class that is responsible for performing request on `Endpoint` located on `Service`, and fetching the response body
    - `HttpClient`: Contains single Guzzle instance to perform all requests
    - `SoapClient`: Passes requests to every independent `SoapService`'s own instance of `nusoap_client`
- `Service`: A collection of endpoints located on single domain.
    - `HttpService`: Service acessible trough HTTP requests
        - `RestApi`: REST API containing multiple `RestResource`s
        - `Website`: Site containg multiple `Webpage`s
    - `SoapService`: Service accessible trough SOAP calls
- `Endpoint`: One specific function of `Service`
    - `HttpEndpoint`: Specific URI on `HttpService`
        - `RestResource`: RESTful `HttpEndpoint`
        - `Webpage`: `HttpEndpoint` that returns HTML
    - `SoapEndpoint`: A method of `SoapService`
