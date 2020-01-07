<?php

namespace REDCap\FhirDataTool\App;

define('NOAUTH', true);
// Set constant for distinguishing this file from plugins or modules that call redcap_connect.php

/**
 * expose global parameters for testing javascript calls when not authenticated
 *
 * @return array
 */
function setupTestingEnvironmentVariables()
{
    $userid = $_GET['userid'] ?: 'delacqf';
    // \Authentication::autoLogin($userid);
    
    $project_id = $_GET['pid'] ?: '';

    return array(
        'pid' => $project_id,
        'userid' => $userid,
    );
}

require_once( __DIR__.'/../../redcap_connect.php');

require_once( __DIR__.'/../vendor/autoload.php');

$variables = setupTestingEnvironmentVariables();
extract($variables);