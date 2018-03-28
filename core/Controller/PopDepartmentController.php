<?php
/**
 * @author duyanjun
 * @since  department manage 2016/05/16 Create
 */

namespace OC\Core\Controller;

use OCP\AppFramework\Controller;
use OCP\IDepartmentManager;
use OCP\IRequest;
use OCP\AppFramework\Http\DataResponse;

/**
 *  Class PopDepartmentController
 *
 * @package OC\Core\Controller
 */
class PopDepartmentController extends Controller {

    /** @var IDepartmentManager */
    private $departmentManager;

    /**
     * @param string $appName
     * @param IRequest $request
     * @param IDepartmentManager $groupManager
     * @param  \OCP\DepartmentInterface $backends
     */
    public function __construct($appName,
                                IRequest $request,
                                IDepartmentManager $departmentManager){
        parent::__construct($appName, $request);
        $this->departmentManager = $departmentManager;
    }


    /**
     * @NoAdminRequired
     *
     * @param string $pdepartId id to filter for department
     * @return DataResponse
     */
    public function getDepart($id = '') {
        $nodes = [];
        if (!empty($this->departmentManager)) {
            $nodes = $this->departmentManager->getNodeByid($id);
        }

        $res = $this->formatterNodeForIndex($nodes);
        return new DataResponse($res);
    }

    /**
     * 格式化部门返回画面的数据
     *
     * @param array $departments
     * @return array
     */
    private function formatterNodeForIndex(array $nodes){
        $res = array();
        if (!empty($nodes)) {
            foreach ($nodes as $item) {
                if ($item instanceof \OC\Department\PopDepartMetaData) {
                    if($item->getNodeFlg() === "1"){
                        $isParent = false;
                    }else{
                        $isParent = true;
                    }
                    $res[] = [
                        'id'  => $item->getNodeID(),
                        'name' => $item->getNodeName(),
                        'isParent' => $isParent,
                        'nodeFlg' => $item->getNodeFlg(),
                        'PNodeID' => $item->getPNodeID(),
                        'nodeUserId' => $item->getNodeUserID()
                    ];
                }
            }
        }
        return $res;
    }
}