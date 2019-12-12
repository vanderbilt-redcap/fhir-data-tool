<?php
namespace REDCap\FhirDataTool\App\Controllers
{
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