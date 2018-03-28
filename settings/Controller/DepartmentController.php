<?php
/**
 * @author tianquanjun
 * @since  department manage 2018/3/14 Create

 */

namespace OC\Settings\Controller;

use OC\AppFramework\Http;
use OCP\AppFramework\Controller;
use OCP\IDepartmentManager;
use OCP\IRequest;
use OCP\IConfig;
use OCP\ILogger;
use OCP\IL10N;
use OCP\App\IAppManager;
use OCP\AppFramework\Http\DataResponse;


/**
 * @package OC\Settings\Controller
 */

class DepartmentController extends Controller {

    /** @var IL10N */
    private $l10n;
    /** @var IDepartmentManager */
    private $departmentManager;
    /** @var IConfig */
    private $config;
    /** @var IAppManager */
    private $appManager;
    /** @var ILogger */
    private $log;


    /**
     * @param string $appName
     * @param IRequest $request
     * @param IDepartmentManager $departmentManager
     * @param IConfig $config
     * @param bool $isAdmin
     * @param IL10N $l10n
     * @param ILogger $log
     */


    public function __construct($appName,
                                IRequest $request,
                                IConfig $config,
                                IAppManager $appManager,
                                ILogger $log,
                                IL10N $l10n,
                                IDepartmentManager $departmentManager){
        parent::__construct($appName, $request);

        $this->config = $config;
        $this->log = $log;
        $this->l10n = $l10n;
        $this->appManager = $appManager;
        $this->departmentManager = $departmentManager;

    }

    /**
     * @NoAdminRequired
     *
     * @param string $pdepartId id to filter for department
     * @return DataResponse
     */
    public function index($id = ''){
        $departments = [];
        if (!empty($this->departmentManager)){
            $departments = $this->departmentManager->getDepartmentsByPid($id);
        }

        $res = $this->formatterDepartmentsForIndex($departments);
        return new DataResponse($res);
    }

    /**
     * @NoAdminRequired
     *
     * @param int $pDepartmentId
     * @param string $departname
     * @param int $sort
     * @return DataResponse
     */
    public function create($pDepartmentId,$departname,$sort) {

        if (!empty($this->departmentManager)) {

            $departments = $this->departmentManager->addDepartment($pDepartmentId,$departname,$sort);
        }

        if($departments){

            return new DataResponse(
                array(
                    'status' => 'success',
                    'data' => array(
                        'id' => $departments,
                        'message' => $departname
                    )
                ),
                Http::STATUS_OK
            );

        }

        return new DataResponse(
            array(
                'status' => 'error',
                'data' => array(
                    'message' => $departname
                )
            ),
            Http::STATUS_FORBIDDEN
        );


    }
    /**
     * @NoAdminRequired
     *
     * @param int $id
     * @param string $departname
     * @param int $sort
     * @return DataResponse
     */
    public function setDepartName($id,$departname,$sort){

        if (!empty($this->departmentManager)) {
            $departments = $this->departmentManager->setDepartment($id,$departname,$sort);
        }

        if($departments){

            return new DataResponse(
                array(
                    'status' => 'success',
                    'data' => array(
                        'message' => $departname
                    )
                ),
                Http::STATUS_OK
            );

        }

        return new DataResponse(
            array(
                'status' => 'error',
                'data' => array(
                    'message' => $departname
                )
            ),
            Http::STATUS_FORBIDDEN
        );
    }

    /**
     * @NoAdminRequired
     *
     * @param int $id
     * @return DataResponse
     */
    public function destroy($id){

        if (!empty($this->departmentManager)) {
            $departments = $this->departmentManager->delete($id);
        }

        if($departments){

            return new DataResponse(
                array(
                    'status' => 'success',
                    'data' => array(
                        'message' => $id
                    )
                ),
                Http::STATUS_OK
            );

        }

        return new DataResponse(
            array(
                'status' => 'error',
                'data' => array(
                    'message' => $id
                )
            ),
            Http::STATUS_FORBIDDEN
        );
    }

    /**
     * 格式化部门返回画面的数据
     *
     * @param array $departments
     * @return array
     */
    private function formatterDepartmentsForIndex(array $departments){
        $res = array();
        if (!empty($departments)) {
            foreach ($departments as $item) {
                if ($item instanceof \OC\Department\MetaData) {
                    $res[] = [
                        'id'  => $item->getDepartmentID(),
                        'name' => $item->getDepartName(),
                        'isParent' => true
                    ];
                }
            }
        }
        return $res;
    }
}