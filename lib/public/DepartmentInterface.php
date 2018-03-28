<?php
/**
 * @author duyanjun
 *
 */

/**
 * Public interface of ownCloud for apps to use.
 * User Interface
 */

// use OCP namespace for all classes that are considered public.
// This means that they should be used by apps instead of the internal ownCloud classes
namespace OCP;

/**
 * TODO actually this is a IUserBackend
 *
 * @package OCP
 * @since 4.5.0
 */
interface DepartmentInterface {

	/**
	 * 根据父部门ID查询所有的子部门
	 *
	 * @param string $pid 父部门ID,顶级节点ID=“”
	 * @return array[]
	 */
	public function getDepartmentsByPid($pid);

	/**
	 * is user in Department?
	 * @param string $uid $employeeID of the user
	 * @param string $departmentID id of the department
	 * @return bool
	 *
	 * Checks whether the user is member of a group or not.
	 */
	public function inDepartment($employeeID, $departmentID);

	/**
	 * 查询部门下的所有人员
	 *
	 * @param string $did 部门ID
	 * @param string $search
	 * @param int $limit
	 * @param int $offset
	 * @return array an array of user ids
	 */
	public function usersInDepartment($did, $search = '', $limit = -1, $offset = 0);
	/**--------------------------------2016-07-11 jiangzhe 增加部门共享 start--------------------------------*/
	/**
	 * 查询部门名称
	 *
	 * @param string $search
	 * @param int $limit
	 * @param int $offset
	 * @return array an array of department ids
	 */
	public function getDepartmentFullNames($search = '', $limit = null, $offset = null);
	/**
	 * 根据父部门ID查询所有的子部门
	 *
	 * @param string $pid 父部门ID,顶级节点ID=“”
	 * @return array[]
	 */
	public function getNodeByid($pid);
	/**--------------------------------2016-07-11 jiangzhe 增加部门共享 end--------------------------------*/

    /**
     * 添加部门
     *
     * @param int $pDepartmentId
     * @param string $departName
     * @param int $sort
     * @return boolean
     */
	public function addDepartment($pDepartmentId,$departName,$sort);

    /**
     * 修改部门
     *
     * @param int $id
     * @param string $departName
     * @param int $sort
     * @return boolean
     */
    public function setDepartment($id,$departName,$sort);

    /**
     * 删除部门
     *
     * @param int $id
     * @return boolean
     */
    public function delete($id);
}
