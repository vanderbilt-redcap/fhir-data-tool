<?php
namespace REDCap\FhirDataTool\App\Controllers
{

    use REDCap\FhirDataTool\App\Models\FhirDataTool;
    use REDCap\FhirDataTool\App\Models\FhirEndpoint;

    /**
     * @method void index()
     * @method string fhirTest()
     * @method string fetchFhirResource()
     * @method string getTokens()
     */
    class FhirDataToolController extends BaseController
    {

        /**
         * instance of the model
         *
         * @var FhirDataTool
         */
        private $model;

        public function __construct()
        {
            parent::__construct();
        }

        /**
         * route, get a list of revisions
         *
         * @return string json response
         */
        public function fhirTest()
        {
            $response = array('test' => 123);
            $this->printJSON($response, $status_code=200);
        }

        /**
         * get info about a project
         *
         * @return void
         */
        public function getProjectInfo()
        {
            $model = new FhirDataTool();
            $project_id = $_GET['pid'];
            $project_info = $model->getProjectInfo($project_id);
            $datamart_active_revision = $model->getDatamartRevision($project_id);
            $cdp_mapping = $model->getClinicalDataPullMapping($project_id);
            $response = array(
                'info' => $project_info,
                'datamart_revision' => $datamart_active_revision,
                'cdp_mapping' => $cdp_mapping,
            );
            $this->printJSON($response, $status_code=200);
        }

        /**
         * get data from a FHIR endpoint
         *
         * @return string json response
         */
        public function fetchFhirResourceByMrn()
        {
            $model = new FhirDataTool();
            $mrn = $_GET['mrn'];
            $interaction = $_GET['interaction_name'];
            $resource_type = $_GET['resource_type'];
            $params = json_decode($_GET['params']);

            try {
                $resource = $model->getResourceByMrn($mrn, $resource_type, $interaction, $params);
                $response = array();
                if(is_a($resource, \FhirResource::class))
                {
                    $response['data'] = $resource;
                    $response['metadata'] = $resource->getMetaData();
                }
                $this->printJSON($response, $status_code=200);
            } catch (\FhirException $e) {
                $this->printJSON($e, $status_code=$e->getCode());
            }
        }

        /**
         * get data from a FHIR endpoint
         *
         * @return string json response
         */
        public function fetchFhirResource()
        {
            $model = new FhirDataTool();
            $endpoint = $_GET['endpoint'];
            $interaction = $_GET['interaction'];
            $resource_type = $_GET['resource_type'];
            $id = $_GET['id'];
            $params = $_GET['params'];
            try {
                $access_token = FhirDataTool::getAccessToken();
                if(in_array($interaction, array(FhirEndpoint::INTERACTION_READ, FhirEndpoint::INTERACTION_UPDATE, FhirEndpoint::INTERACTION_DELETE)))
                {

                }else
                {

                }
                $resource = $model->getResource($endpoint, $access_token, $params);
                $response = array();
                if(is_a($resource, \FhirResource::class))
                {
                    $response['data'] = $resource;
                    $response['metadata'] = $resource->getMetaData();
                }
                $this->printJSON($response, $status_code=200);
            } catch (\FhirException $e) {
                $this->printJSON($e, $status_code=$e->getCode());
            }
        }

        /**
         * get a list of token for a user_id
         *
         * @return void
         */
        public function getTokens()
        {
            $model = new FhirDataTool();
            $user = $_GET['user'];
            if(!$user)
            {
                $e = new \FhirException('No user has been specified', $code=400);
                $this->printJSON($e, $status_code=$e->getCode());
            }
            $tokens = $model->getTokens($user);
            $response = $tokens;
            $this->printJSON($response, $status_code=200);
        }

        /**
         * get fields and codes
         *
         * @return void
         */
        public function getFhirMetadata()
        {
            $model = new FhirDataTool();
            $ddp = new \DynamicDataPull(0, 'FHIR');
            $source_fields = $ddp->getExternalSourceFields();
            $fields = $model->getGroupedSourceFields($source_fields);
            $data = array(
                'fields' => $fields,
                'codes' => array_keys($source_fields),
            );
            $this->printJSON($data, $status_code=200);
        }

        /**
         * send a notification to an admin
         * when a user wants to add a code
         * to those available in REDCap
         * admin will add the codes in Resources/misc/loinc_labs_additions.csv
         * @return void
         */
        public function notifyAdmin()
        {
            global $userid, $ui_id, $project_id, $project;

            $code = $_POST['code'];

            $response = array(
                'code' => $code,
                'userid' => $userid,
                'ui_id' => $ui_id,
                'project_id' => $project_id,
                'project' => $project,
            );
            // $ui_id, $request_to, $todo_type, $action_url, $project_id=null, $comment=null
            /* $request_id = \ToDoList::insertAction(
                $ui_id,
                $request_to='$projectInfo->project_contact_email',
                $todo_type='FHIR code addition request',
                $action_url='', // request_id is automatically appended to end of action URL after insert to keep as a reference during admin processing
                $project_id
            ); */
            $this->printJSON($response, $status_code=200);
        }

        public function index()
        {
            extract($GLOBALS);
            include APP_PATH_DOCROOT . 'ProjectGeneral/header.php';
            if ($isIE && vIE() <= 10) : ?>
                <h3>
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>This feature is not available for your browser.</span>
                </h3>
            <?php endif;
            include APP_PATH_DOCROOT . 'ProjectGeneral/footer.php';
        }

    }
}