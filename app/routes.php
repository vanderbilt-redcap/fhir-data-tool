<?php
namespace REDCap\FhirDataTool\App;

use REDCap\FhirDataTool\App\Controllers\BaseController;
use REDCap\FhirDataTool\App\Helpers\Router;

require_once __DIR__."/bootstrap.php";

// get the base url (to use it in routes)
$base_url = '/'.basename(dirname(__DIR__)).'/api';
// httpMethod, route, handler
$routes = [
    [['GET','POST'], $base_url."/test[/{id:\d+}]", 'REDCap\FhirDataTool\App\Controllers\BaseController/test'],
    ['GET', $base_url.'/fhir-test', '\FhirMappingHelperController/fhirTest'],
    ['GET', $base_url.'/resource', '\FhirMappingHelperController/fetchFhirResource'],
    ['GET', $base_url.'/resource_by_mrn', '\FhirMappingHelperController/fetchFhirResourceByMrn'],
    ['GET', $base_url.'/tokens', '\FhirMappingHelperController/getTokens'],
    ['GET', $base_url.'/index', '\FhirMappingHelperController/index'],
    ['GET', $base_url.'/fhir_metadata', '\FhirMappingHelperController/getFhirMetadata'],
    ['GET', $base_url.'/project_info', '\FhirMappingHelperController/getProjectInfo'],
    ['POST', $base_url.'/notify_admin', '\FhirMappingHelperController/notifyAdmin'],
];

// create a BaseController to manage common routes or errors
$baseController = new BaseController();

$router = new Router($routes, $baseController);

$route = Router::extractRoute();
    
$router->dispatch($route);