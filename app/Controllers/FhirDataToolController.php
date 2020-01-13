<?php
namespace REDCap\FhirDataTool\App\Controllers
{

    use DynamicDataPull;
    use REDCap\FhirDataTool\App\Models\FhirDataTool;

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
            $this->model = new FhirDataTool();
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
            $project_id = $_GET['pid'];
            $project_info = $this->model->getProjectInfo($project_id);
            $datamart_active_revision = $this->model->getDatamartRevision($project_id);
            $cdp_mapping = $this->model->getClinicalDataPullMapping($project_id);
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
            $mrn = $_GET['mrn'];
            $endpoint = $_GET['endpoint'];
            $params = $_GET['params'];
            try {
                // TODO: get rid of this after the tests are done!!
                // override
                // $json = file_get_contents(APP_PATH_TEMP.'observation-test.json');
                // $data = json_decode($json);
                // override
                $resource = $this->model->getResourceByMrn($mrn, $endpoint, $params);
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
            $user = $_GET['user'];
            if(!$user)
            {
                $e = new \FhirException('No user has been specified', $code=400);
                $this->printJSON($e, $status_code=$e->getCode());
            }
            $tokens = $this->model->getTokens($user);
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
            $ddp = new \DynamicDataPull(0, 'FHIR');
            $source_fields = $ddp->getExternalSourceFields();
            $fields = $this->model->getGroupedSourceFields($source_fields);
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