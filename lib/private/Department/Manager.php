<?php
/**
 * @author duyanjun
 */

namespace OC\Department;

use OC\Hooks\PublicEmitter;
use OCP\IConfig;
use OCP\IDepartmentManager;

/**
 * Class Manager
 *
 * @package OC\Department
 */
class Manager extends PublicEmitter implements IDepartmentManager{
	/**
	 * @var \OCP\DepartmentInterface[] $backends
	 */
	private $backends = array();

	/**--------------------------------2016-07-11 jiangzhe 增加部门共享 start--------------------------------*/
	/**
	 * @var String $departmentId
	 */
	private $superDepartmentId = array();
	/**--------------------------------2016-07-11 jiangzhe 增加部门共享 end--------------------------------*/

    /**
     * @var \OCP\IConfig $config
     */
    private $config;
	/**
	 * @param \OCP\IConfig $config
	 */
	public function __construct(IConfig $config = null) {
        $this->config = $config;
	}

	/**
	 * Get the active backends
	 * @return \OCP\DepartmentInterface[]
	 */
	public function getBackends() {
		return $this->backends;
	}

	/**
	 * register a user backend
	 *
	 * @param \OCP\DepartmentInterface $backend
	 */
	public function registerBackend($backend) {
		$this->backends[] = $backend;
	}

	/**
	 * remove a user backend
	 *
	 * @param \OCP\DepartmentInterface $backend
	 */
	public function removeBackend($backend) {
		if (($i = array_search($backend, $this->backends)) !== false) {
			unset($this->backends[$i]);
		}
	}

    /**
     * @param \OCP\DepartmentInterface $backend
     */
    public function addBackend($backend) {
        $this->backends[] = $backend;
    }

	/**
	 * remove all user backends
	 */
	public function clearBackends() {
		$this->backends = array();
	}

	/**
	 * 添加部门
	 *
     * @param int $pDepartmentId
	 * @param string $departName
	 * @param int $sort
	 * @return boolean
	 */
	public function addDepartment($pDepartmentId,$departName,$sort){
        $l = \OC::$server->getL10N('lib');
        // Check the name for bad characters
        // Allowed are: "a-z", "A-Z", "0-9" and "_.@-'"
        if (preg_match('/[^a-zA-Z0-9 _\.@\-\']/', $pDepartmentId)) {
            throw new \Exception($l->t('Only the following characters are allowed in a username:'
                . ' "a-z", "A-Z", "0-9", and "_.@-\'"'));
        }

        // No whitespace at the beginning or at the end
        if (strlen(trim($pDepartmentId, "\t\n\r\0\x0B\xe2\x80\x8b")) !== strlen(trim($pDepartmentId))) {
            throw new \Exception($l->t('Username contains whitespace at the beginning or at the end'));
        }
        // No empty departName
        if (trim($departName) == '') {
            throw new \Exception($l->t('A valid password must be provided'));
        }

        unset($this->backends[1]);

        foreach ($this->backends as $backend){
            $departments = $backend->addDepartment($pDepartmentId,$departName,$sort);

        }

        return $departments;
	}

    /**
     * 修改部门
     *
     * @param int $id
     * @param string $departName
     * @param int $sort
     * @return boolean
     */
    public function setDepartment($id,$departName,$sort){
        $l = \OC::$server->getL10N('lib');
        // Check the name for bad characters
        // Allowed are: "a-z", "A-Z", "0-9" and "_.@-'"
        if (preg_match('/[^a-zA-Z0-9 _\.@\-\']/', $id)) {
            throw new \Exception($l->t('Only the following characters are allowed in a username:'
                . ' "a-z", "A-Z", "0-9", and "_.@-\'"'));
        }
        // No empty pDepartmentId
        if (trim($id) == '') {
            throw new \Exception($l->t('A valid username must be provided'));
        }
        // No whitespace at the beginning or at the end
        if (strlen(trim($id, "\t\n\r\0\x0B\xe2\x80\x8b")) !== strlen(trim($id))) {
            throw new \Exception($l->t('Username contains whitespace at the beginning or at the end'));
        }
        // No empty departName
        if (trim($departName) == '') {
            throw new \Exception($l->t('A valid password must be provided'));
        }

        unset($this->backends[1]);

        foreach ($this->backends as $backend){
            $departments = $backend->setDepartment($id,$departName,$sort);

        }

        return $departments;
    }

    /**
     * 删除部门
     *
     * @param int $id
     * @return boolean
     */
    public function delete($id){
        $l = \OC::$server->getL10N('lib');
        // Check the name for bad characters
        // Allowed are: "a-z", "A-Z", "0-9" and "_.@-'"
        if (preg_match('/[^a-zA-Z0-9 _\.@\-\']/', $id)) {
            throw new \Exception($l->t('Only the following characters are allowed in a username:'
                . ' "a-z", "A-Z", "0-9", and "_.@-\'"'));
        }

        unset($this->backends[1]);

        foreach ($this->backends as $backend){
            $departments = $backend->delete($id);

        }

        return $departments;

    }


    /**
	 * 根据父部门ID查询所有的子部门
	 *
	 * @param string $pid 父部门ID,顶级节点ID=“”
	 * @return array[]
	 */
	public function getDepartmentsByPid($pid){
		$departments = array();
		foreach ($this->backends as $backend) {
			$departments = $backend->getDepartmentsByPid($pid);
		}
		return $departments;
	}

	/**--------------------------------2016-07-11 jiangzhe 增加部门共享 start--------------------------------*/
	/**
	 * 根据父部门ID查询所有的子部门和人
	 *
	 * @param string $pid 父部门ID,顶级节点ID=“”
	 * @return array[]
	 */
	public function getNodeByid($pid){
		$departments = array();
		foreach ($this->backends as $backend) {
			$departments = $backend->getNodeByid($pid);
		}
		return $departments;
	}
	/**
	 * 查询部门名称
	 *
	 * @param string $search
	 * @param int $limit
	 * @param int $offset
	 * @return array[]
	 */
	public function getDepartmentFullNames($search = '',$flg = null, $limit = null, $offset = null){
		$departmentNames = array();
		foreach ($this->backends as $backend) {
			$departmentNames = $backend->getDepartmentFullNames($search, $flg, $limit, $offset);
		}
		return $departmentNames;
	}

	/**
	 * @param string $did
	 * @return bool
	 */
	public function departExists($did) {
		return !is_null($this->getDepartmentsByPid($did));
	}
	/**
	 * check if a user is in the department
	 *
	 * @param \OCP\IUser $user
	 * @return bool
	 * @since 8.0.0
	 */
	public function inDepartment($user){
		if (isset($this->users[$user->getUID()])) {
			return true;
		}
		foreach ($this->backends as $backend) {
			if ($backend->inDepartment($user->getUID(), $this->did)) {
				$this->users[$user->getUID()] = $user;
				return true;
			}
		}
		return false;
	}
	/**
	 * @param \OC\User\User|null $user
	 * @return \OC\Group\Group[]
	 */
	public function getUserDeparts($user) {
		if (is_null($user)) {
			return [];
		}
		return $this->getUserIdDeparts($user->getUID());
	}

	/**
	 * @param string $uid the user id
	 * @return \OC\Group\Group[]
	 */
	public function getUserIdDeparts($uid) {
		if (isset($this->cachedUserDeparts[$uid])) {
			return $this->cachedUserDeparts[$uid];
		}
		$departs = array();
		foreach ($this->backends as $backend) {
			$departIds = $backend->getUserDeparts($uid);
			if (is_array($departIds)) {
				foreach ($departIds as $groupId) {
					$departs[$groupId] = $this->getDepartmentsByPid($departIds);
				}
			}
		}
		$this->cachedUserDeparts[$uid] = $departs;
		return $this->cachedUserDeparts[$uid];
	}
	/**
	 * @param \OC\User\User|null $user
	 * @return \OC\Department\Department[]
	 */
	public function getUserFullDeparts($empid) {
		if (is_null($empid)) {
			return [];
		}
		return $this->getUserIdFullDeparts($empid);
	}

	/**
	 * @param string $uid the user id
	 * @return \OC\Department\Department[]
	 */
	public function getUserIdFullDeparts($empId) {
		if (isset($this->cachedUserDeparts[$empId])) {
			return $this->cachedUserDeparts[$empId];
		}
		$userDeparts = array();
		$departs = array();
		foreach ($this->backends as $backend) {
			$userDeparts = $backend->getUserDeparts($empId);
			if (is_array($userDeparts)) {
				foreach ($userDeparts as $departId) {
					$departs[$departId] = $this->getDepartmentsByPid($departId);
				}
			}
		}
		$this->cachedUserDeparts[$empId] = $departs;
		return $this->cachedUserDeparts[$empId];
	}
	/**
	 * Get a list of all Subordinate department info.
	 *
	 * @param string $search
	 * @param string|null $limit
	 * @param string|null $offset
	 * @return array an array of all departmentNames (value) and the corresponding uids (key)
	 */
	public function getSubDepartmentInfo($DepartId = null, $limit = null, $offset = null) {
		foreach ($this->backends as $backend) {
			$userSubDeparts = $backend->getSubDepartmentInfo($DepartId);
		}
		return $userSubDeparts;
	}

	/**
	 * Get a list of all Superior department info.
	 *
	 * @param string $search
	 * @param string|null $limit
	 * @param string|null $offset
	 * @return array an array of all departmentNames (value) and the corresponding uids (key)
	 */
	public function getSuperDepartmentInfo($DepartId = null, $limit = null, $offset = null) {
		foreach ($this->backends as $backend) {
			$userSuperDeparts = $backend->getSuperDepartmentInfo($DepartId);
		}
		return $userSuperDeparts;
	}

	/**
	 * Get a list of all Superior departmentId.
	 *
	 * @param string $search
	 * @param string|null $limit
	 * @param string|null $offset
	 * @return array an array of all departmentNames (value) and the corresponding uids (key)
	 */
	public function getSuperDepartmentId($DepartId = null, $limit = null, $offset = null) {
		foreach ($this->backends as $backend) {
			$superDeptId = $backend->getSuperDepartmentInfo($DepartId);
		}
		$this->superDepartmentId = array();
		$this->arr_foreach($superDeptId);

		return array_unique($this->superDepartmentId);
	}


	/**
	 * @param string $empid
	 * @param string|null $departid
	 * @return true|alse
	 */
	public function isDepartAdmin($empid,$departid = null) {
		if (is_null($empid)) {
			return false;
		}

		foreach ($this->backends as $backend) {
			$departAdmin = $backend->getDepartAdmin($empid,$departid);
		}
		if (count($departAdmin) === 0 ){
			return false;
		}

		return true;
	}

	/**
	 * @param string $empid
	 * @param string|null $departid
	 * @return true|alse
	 */
	public function setDepartAdmin($empid,$departid) {

		$result = current($this->backends)->addDepartAdmin($empid,$departid);

		return $result;
	}

	/**
	 * @param string $empid
	 * @param string|null $departid
	 * @return true|alse
	 */
	public function delDepartAdmin($empid,$departid) {

		$result = current($this->backends)->delDepartAdmin($empid,$departid);

		return $result;
	}

	public function arr_foreach ($arr)
	{
		if (!is_array ($arr)){
			return false;
		}
		foreach ($arr as $key => $val ){
			if (is_array ($val)){
				$this->arr_foreach ($val);
			}else{
				array_push($this->superDepartmentId,$arr["departmentID"]);
			}

		}
	}
	/**--------------------------------2016-07-11 jiangzhe 增加部门共享 end--------------------------------*/
}
