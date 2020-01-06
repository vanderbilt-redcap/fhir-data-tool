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
    class FhirDataToolController extends BaseController {

        /**
         * list of allowed routes with request types
         *
         * @var array
         */
        protected $routes = array(
            'index' => array('GET'), // main page
            'fhirTest' => array('GET'), // test
            'fetchFhirResource' => array('GET'), // fetchFhirResource
            'fetchFhirPatientResource' => array('GET'), // fetchFhirPatientResource
            'getTokens' => array('GET'), // fetchFhirPatientResource
        );

        
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
         * route, get a list of revisions
         *
         * @return string json response
         */
        public function fetchFhirPatientResource()
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
                $resource = $this->model->getResource($mrn, $endpoint, $params);
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

        private function groupFields($fields)
        {
            $groups = array();
            foreach ($fields as $field) {
                $category = $field['category'];
                if(empty($category)) continue; //category must be set
                // make sure category is an array
                if(!is_array($groups[$category])) $groups[$category] = array();
                // priority to sub categories
                if($sub_category = $field['subcategory'])
                {
                    // make sure sub_category is an array
                    if(!is_array($groups[$category][$sub_category])) $groups[$category][$sub_category] = array();
                    $groups[$category][$sub_category][] = $field;
                }else
                {
                    $groups[$category][] = $field;
                }
            }
            return $groups;
        }

        /**
         * get 
         *
         * @return void
         */
        public function getFhirMetadata()
        {
            $ddp = new \DynamicDataPull(0, 'FHIR');
            $source_fields = $ddp->getExternalSourceFields();
            $fields = $this->groupFields($source_fields);
            $data = array(
                'fields' => $fields,
                'codes' => array_keys($source_fields),
            );
            $this->printJSON($data, $status_code=200);
        }

        public function addMapping()
        {
            $code = $_POST['code'];
            $project_id = $_POST['project_id'];
            $response = array(
                'code' => $code,
                'project_id' => $project_id,
            );
            $this->printJSON($response, $status_code=200);
        }

        public function removeMapping()
        {
            $code = $_POST['code'];
            $project_id = $_POST['project_id'];
            $response = array(
                'code' => $code,
                'project_id' => $project_id,
            );
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