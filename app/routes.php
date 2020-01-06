<?php
namespace REDCap\FhirDataTool\App;

use REDCap\FhirDataTool\App\Controllers\BaseController;
use REDCap\FhirDataTool\App\Helpers\Router;

require_once __DIR__."/bootstrap.php";

function removeBaseUrlFromRoute($route)
{
    $paths = explode('/', $route);
    $paths_no_base = array_slice($paths, 2);
    return implode('/', $paths_no_base);
}

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
    ['POST', $base_url.'/add_mapping', 'REDCap\FhirDataTool\App\Controllers\FhirDataToolController/addMapping'],
    ['POST', $base_url.'/remove_mapping', 'REDCap\FhirDataTool\App\Controllers\FhirDataToolController/removeMapping'],
];

// create a BaseController to manage common routes or errors
$baseController = new BaseController();

$router = new Router($routes, $baseController);

$route = Router::extractRoute();

function randomDelay() {
    $random_delta = rand(1,10);
    $sleep_time = 1*$random_delta;
    sleep($sleep_time);
}
    
$router->dispatch($route);