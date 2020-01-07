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
    ['GET', $base_url.'/fhir-test', 'REDCap\FhirDataTool\App\Controllers\FhirDataToolController/fhirTest'],
    ['GET', $base_url.'/resource', 'REDCap\FhirDataTool\App\Controllers\FhirDataToolController/fetchFhirPatientResource'],
    ['GET', $base_url.'/tokens', 'REDCap\FhirDataTool\App\Controllers\FhirDataToolController/getTokens'],
    ['GET', $base_url.'/index', 'REDCap\FhirDataTool\App\Controllers\FhirDataToolController/index'],
    ['GET', $base_url.'/fhir_metadata', 'REDCap\FhirDataTool\App\Controllers\FhirDataToolController/getFhirMetadata'],
    ['GET', $base_url.'/project_info', 'REDCap\FhirDataTool\App\Controllers\FhirDataToolController/getProjectInfo'],
    ['POST', $base_url.'/notify_admin', 'REDCap\FhirDataTool\App\Controllers\FhirDataToolController/notifyAdmin'],
];

// create a BaseController to manage common routes or errors
$baseController = new BaseController();

$router = new Router($routes, $baseController);

$route = Router::extractRoute();
    
$router->dispatch($route);