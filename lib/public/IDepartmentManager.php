<?php
/**
 * @author Lukas Reschke <lukas@owncloud.com>
 * @author Morris Jobke <hey@morrisjobke.de>
 * @author Robin Appelman <icewind@owncloud.com>
 * @author Thomas Müller <thomas.mueller@tmit.eu>
 *
 * @copyright Copyright (c) 2016, ownCloud, Inc.
 * @license AGPL-3.0
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 */

namespace OCP;


/**
 * Class Manager
 *
 * Hooks available in scope \OC\User:
 * - preSetPassword(\OC\User\User $user, string $password, string $recoverPassword)
 * - postSetPassword(\OC\User\User $user, string $password, string $recoverPassword)
 * - preDelete(\OC\User\User $user)
 * - postDelete(\OC\User\User $user)
 * - preCreateUser(string $uid, string $password)
 * - postCreateUser(\OC\User\User $user, string $password)
 *
 * @package OC\Department
 * @since 8.0.0
 */
interface IDepartmentManager {
    /**
     * register a user backend
     *
     * @param \OCP\UserInterface $backend
     * @since 8.0.0
     */
    public function registerBackend($backend);

    /**
     * Get the active backends
     * @return \OCP\UserInterface[]
     * @since 8.0.0
     */
    public function getBackends();

    /**
     * remove a user backend
     *
     * @param \OCP\UserInterface $backend
     * @since 8.0.0
     */
    public function removeBackend($backend);

    /**
     * @param \OCP\DepartmentInterface $backend
     */
    public function addBackend($backend);

    /**
     * remove all user backends
     * @since 8.0.0
     */
    public function clearBackends() ;

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

    /**
     * 根据父部门ID查询所有的子部门
     *
     * @param string $pid 父部门ID,顶级节点ID=“”
     * @return array[]
     */
    public function getDepartmentsByPid($pid);
    /**--------------------------------2016-07-11 jiangzhe 增加部门共享 start--------------------------------*/
    /**
     * 根据父部门ID查询所有的子部门和人
     *
     * @param string $pid 父部门ID,顶级节点ID=“”
     * @return array[]
     */
    public function getNodeByid($pid);
    /**
     * 查询部门名称
     *
     * @param string $search
     * @param int $limit
     * @param int $offset
     * @return array[]
     */
    public function getDepartmentFullNames($search = '',$flg = null, $limit = null, $offset = null);
    /**
     * 判断部门是否存在
     *
     * @param $did
     * @return
     */
    public function departExists($did);
    /**
     * check if a user is in the department
     *
     * @param \OCP\IUser $user
     * @return bool
     * @since 8.0.0
     */
    public function inDepartment($user);
    /**
     * check if a user is in the department
     *
     * @param \OCP\IUser $user
     * @return bool
     * @since 8.0.0
     */
    public function getUserDeparts($user);
    /**
     * check if a user is in the department
     *
     * @param string $empid
     * @return bool
     * @since 8.0.0
     */
    public function getUserFullDeparts($empid);
    /**
     * Get a list of all Subordinate department info.
     *
     * @param string $departId
     * @return bool
     * @since 8.0.0
     */
    public function getSubDepartmentInfo($departId);
    /**
     * Get a list of all Superior department info.
     *
     * @param string $departId
     * @return bool
     * @since 8.0.0
     */
    public function getSuperDepartmentInfo($departId);
    /**
     * Get a list of all Superior departmentId.
     *
     * @param string $departId
     * @return bool
     * @since 8.0.0
     */
    public function getSuperDepartmentId($departId);

    /**
     * @param string $empid
     * @param string|null $departid
     * @return true|alse
     */
    public function isDepartAdmin($empid,$departid);

    /**
     * @param string $empid
     * @param string|null $departid
     * @return true|alse
     */
    public function setDepartAdmin($empid,$departid);

    /**
     * @param string $empid
     * @param string|null $departid
     * @return true|alse
     */
    public function delDepartAdmin($empid,$departid);
    /**--------------------------------2016-07-11 jiangzhe 增加部门共享 end--------------------------------*/
}
