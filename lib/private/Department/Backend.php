<?php
/**
 * @author Aldo "xoen" Giambelluca <xoen@xoen.org>
 * @author Bart Visscher <bartv@thisnet.nl>
 * @author Björn Schießle <schiessle@owncloud.com>
 * @author Dominik Schmidt <dev@dominik-schmidt.de>
 * @author Georg Ehrke <georg@owncloud.com>
 * @author Jakob Sack <mail@jakobsack.de>
 * @author Joas Schilling <nickvergessen@owncloud.com>
 * @author Jörn Friedrich Dreyer <jfd@butonic.de>
 * @author Lukas Reschke <lukas@owncloud.com>
 * @author Morris Jobke <hey@morrisjobke.de>
 * @author Robin Appelman <icewind@owncloud.com>
 * @author Sam Tuke <mail@samtuke.com>
 * @author Thomas Müller <thomas.mueller@tmit.eu>
 * @author Tigran Mkrtchyan <tigran.mkrtchyan@desy.de>
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
namespace OC\Department;

use \OCP\DepartmentInterface;

/**
 * Abstract base class for user management. Provides methods for querying backend
 * capabilities.
 */
abstract class Backend implements DepartmentInterface {

    /**
     * 根据父部门ID查询所有的子部门
     *
     * @param string $pid 父部门ID,顶级节点ID=“”
     * @return array[]
     */
    public function getDepartmentsByPid($pid){
        return array();
    }

    /**--------------------------------2016-07-11 jiangzhe 增加部门共享 start--------------------------------*/
    /**
     * 根据父部门ID查询所有的子部门
     *
     * @param string $pid 父部门ID,顶级节点ID=“”
     * @return array[]
     */
    public function getNodeByid($pid){
        return array();
    }
    /**--------------------------------2016-07-11 jiangzhe 增加部门共享 end--------------------------------*/
    /**
     * is user in Department?
     * @param string $uid $employeeID of the user
     * @param string $departmentID id of the department
     * @return bool
     *
     * Checks whether the user is member of a group or not.
     */
    public function inDepartment($employeeID, $departmentID){
        return false;
    }

    /**
     * 查询部门下的所有人员
     *
     * @param string $did 部门ID
     * @param string $search
     * @param int $limit
     * @param int $offset
     * @return array an array of user ids
     */
    public function usersInDepartment($did, $search = '', $limit = -1, $offset = 0){
        return array();
    }

    /**--------------------------------2016-07-11 jiangzhe 增加部门共享 start--------------------------------*/
    /**
     * 查询部门名称
     *
     * @param string $search
     * @param int $limit
     * @param int $offset
     * @return array an array of department ids
     */
    public function getDepartmentFullNames($search = '', $limit = null, $offset = null) {
        return array();
    }

    /**
     * Get all departments a user belongs to
     * @param string $uid Name of the user
     * @return array an array of group names
     *
     * This function fetches all groups a user belongs to. It does not check
     * if the user exists at all.
     */
    public function getUserDeparts($uid) {
        return array();
    }
    /**--------------------------------2016-07-11 jiangzhe 增加部门共享 end--------------------------------*/
}
