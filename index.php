<?php


namespace REDCap\FhirDataTool;

require_once( __DIR__.'/app/bootstrap.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <!--Load Vue-->
    <!-- <script src="https://unpkg.com/vue"></script> -->
    <!--Load the web component polyfill-->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/webcomponentsjs/1.2.0/webcomponents-loader.js"></script> -->
    <!--Load your custom element-->
    <!-- <script src="./assets/js/fhir-data-tool/dist/fhir-data-tool.min.js"></script> -->
    <!--Use your custom element-->
    <!-- <fhir-data-tool msg="Hello..."></fhir-data-tool> -->

    <script src="https://unpkg.com/vue"></script>

</body>
</html>

<?php
print_r($_SERVER['REQUEST_URI']);
print_r($_SERVER);
?>