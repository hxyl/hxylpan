<?php
/**
 * @author adrien <adrien.waksberg@believedigital.com>
 * @author Aldo "xoen" Giambelluca <xoen@xoen.org>
 * @author Arthur Schiwon <blizzz@owncloud.com>
 * @author Bart Visscher <bartv@thisnet.nl>
 * @author Björn Schießle <schiessle@owncloud.com>
 * @author fabian <fabian@web2.0-apps.de>
 * @author Georg Ehrke <georg@owncloud.com>
 * @author Jakob Sack <mail@jakobsack.de>
 * @author Joas Schilling <nickvergessen@owncloud.com>
 * @author Jörn Friedrich Dreyer <jfd@butonic.de>
 * @author Lukas Reschke <lukas@owncloud.com>
 * @author Michael Gapczynski <GapczynskiM@gmail.com>
 * @author Morris Jobke <hey@morrisjobke.de>
 * @author nishiki <nishiki@yaegashi.fr>
 * @author Robin Appelman <icewind@owncloud.com>
 * @author Robin McCorkell <robin@mccorkell.me.uk>
 * @author Thomas Müller <thomas.mueller@tmit.eu>
 * @author Victor Dubiniuk <dubiniuk@owncloud.com>
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

/*
 *
 * The following SQL statement is just a help for developers and will not be
 * executed!
 *
 * CREATE TABLE `users` (
 *   `uid` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
 *   `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
 *   PRIMARY KEY (`uid`)
 * ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
 *
 */

/**
 * Class for user management in a SQL Database (e.g. MySQL, SQLite)
 */
class Database extends Backend {

    /** @var \OCP\IDBConnection */
    private $dbConn;


    /**
     * \OC\Department\Database constructor.
     *
     * @param \OCP\IDBConnection|null $dbConn
     */
    public function __construct(\OCP\IDBConnection $dbConn = null) {
        $this->dbConn = $dbConn;
        require_once ('PopDepartmetaData.php');
    }

    /**
     * 根据父部门ID查询所有的子部门
     *
     * @param string $pid 父部门ID,顶级节点ID=“”
     * @return array[]
     */
    public function getDepartmentsByPid($pid){
        $parameters = [];
        $searchLike = '';
        if ($pid !== '') {
            $parameters[] = $pid;
            $searchLike = ' AND pDepartmentID=?';
        } else {
            $searchLike = ' AND pDepartmentID is null';
        }

        $stmt = \OC_DB::prepare('SELECT `departmentID`,`pDepartmentID`,`departName`,`sort` FROM `*PREFIX*department` WHERE 1=1 ' . $searchLike . ' ORDER BY `sort` ASC');
        $result = $stmt->execute($parameters);
        $departments = array();
        while ($row = $result->fetchRow()) {
            $department = new \OC\Department\MetaData();
            $department->setDepartmentID($row['departmentID']);
            $department->setPDepartmentID($row['pDepartmentID']);
            $department->setDepartName($row['departName']);
            $department->setSort($row['sort']);
            $departments[] = $department;
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
        $nodes = [];
        $parameters = [];
        $searchLike = '';
        if ($pid !== '') {
            $parameters[] = $pid;
            $searchLike = ' AND pDepartmentID=?';
        } else {
            $searchLike = ' AND pDepartmentID is null';
        }

        $stmt = \OC_DB::prepare('SELECT `departmentID` nodeid,`pDepartmentID` pNodeid,`departName` nodeName,`sort`,0 nodeFlg,NULL nodeUserID FROM `*PREFIX*department` WHERE 1=1 ' . $searchLike . ' ORDER BY `sort` ASC');
        $result = $stmt->execute($parameters);
        $nodes = array();
        //include ('PopDepartmetaData.php');
        while ($row = $result->fetchRow()) {

            $node = new \OC\Department\PopDepartMetaData();
            $node->setNodeID($row['nodeid']);
            $node->setPNodeID($row['pNodeid']);
            $node->setNodeName($row['nodeName']);
            $node->setSort($row['sort']);
            $node->setNodeFlg($row['nodeFlg']);
            $node->setNodeUserID($row['nodeFlg']);
            $nodes[] = $node;
        }
        //
        $parameters = [];
        $parameters[] = $pid;
        $stmtEmp = \OC_DB::prepare('SELECT users.`employeeId` nodeid,NULL pNodeid,users.`uid` nodeUserID,users.`displayname` nodeName,map.`sort`,1 nodeFlg FROM `*PREFIX*users` as users  INNER JOIN `*PREFIX*map_dep_emp` as map 
on users.`employeeId` = map.`employeeId` WHERE map.`departmentID` = ? ORDER BY users.`uid` ASC');
        $resultEmp = $stmtEmp->execute($parameters);
        while ($rowEmp = $resultEmp->fetchRow()) {
            $node = new \OC\Department\PopDepartMetaData();
            $node->setNodeID($rowEmp['nodeid']);
            $node->setPNodeID($rowEmp['pNodeid']);
            $node->setNodeName($rowEmp['nodeName']);
            $node->setSort($rowEmp['sort']);
            $node->setNodeFlg($rowEmp['nodeFlg']);
            $node->setNodeUserID($rowEmp['nodeUserID']);
            $nodes[] = $node;
        }
        return $nodes;
    }
    /**
     * Get a list of all display names and department ids.
     *
     * @param string $search
     * @param string|null $limit
     * @param string|null $offset
     * @return array an array of all departmentNames (value) and the corresponding uids (key)
     */
    public function getDepartmentFullNames($search = '', $flg = null, $limit = null, $offset = null) {
        $parameters = [];
        $searchLike = '';
        if ($search !== '') {
            if ($flg === null) {
                $parameters[] = '%' . $search . '%';
                $searchLike = ' WHERE LOWER(`departName`) LIKE LOWER(?) ';
            } else {
                $parameters[] = $search;
                $searchLike = ' WHERE departmentID = ? ';
            }
        }

        $deptNames = array();
        $query = \OC_DB::prepare('SELECT `departmentID`, `departName`,`pDepartmentID` FROM `*PREFIX*department`'
            . $searchLike .' ORDER BY `sort` ASC');
        $result = $query->execute($parameters);

        $deptName = '';
        while ($row = $result->fetchRow()) {
            $departmentID = $row['departmentID'];
            $pDepartmentID = $row['pDepartmentID'];
            $deptName = $row['departName'].'|'.$deptName;

            $parameter = [];
            $parameter[] = $pDepartmentID;
            $query1 = \OC_DB::prepare('SELECT `departmentID`, `departName`,`pDepartmentID`  FROM `*PREFIX*department` WHERE `departmentID` = ?');
            $result1 = $query1->execute($parameter);

            while (isset($result1)) {
                while ($row1 = $result1->fetchRow()) {
                    $pDepartmentID1 = $row1['pDepartmentID'];
                    $deptName = $row1['departName'].'|'.$deptName;
                }

                $result1= null;

                if(isset($pDepartmentID1)){
                    $parameter1 = [];
                    $parameter1[] = $pDepartmentID1;
                    $query2 = \OC_DB::prepare('SELECT `departmentID`, `departName`,`pDepartmentID`  FROM `*PREFIX*department` WHERE `departmentID` = ?');
                    $result1 = $query2->execute($parameter1);
                    $pDepartmentID1 = null;
                }else{
                    $deptNames[$departmentID] = substr($deptName,0,-1);
                    $deptName = '';
                }
            }
        }

        return $deptNames;
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
        $parameters = [];
        if ($DepartId !== '') {
            $parameters[] = $DepartId;
            $searchDept = ' WHERE pDepartmentID = ? ';
        } else {
            $searchDept = ' AND pDepartmentID is null';
        }

        $subDeptInfo = array();
        $query = \OC_DB::prepare('SELECT `departmentID`, `departName`,`pDepartmentID` FROM `*PREFIX*department`'
            . $searchDept .' ORDER BY `sort` ASC');
        $result = $query->execute($parameters);

        while ($row = $result->fetchRow()) {
            $row['downDepart'] = $this->getSubDepartmentInfo($row['departmentID']); //调用函数，传入参数，继续查询下级
            $subDeptInfo[] = $row; //组合数组
        }

        return $subDeptInfo;
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
        $parameters = [];
        if ($DepartId !== '') {
            $parameters[] = $DepartId;
            $searchDept = ' WHERE departmentID = ? ';
        } else {
            $searchDept = ' AND departmentID is null';
        }

        $superDeptInfo = array();
        $query = \OC_DB::prepare('SELECT `departmentID`, `departName`,`pDepartmentID` FROM `*PREFIX*department`'
            . $searchDept .' ORDER BY `sort` ASC');
        $result = $query->execute($parameters);

        while ($row = $result->fetchRow()) {
            $row['superDepart'] = $this->getSuperDepartmentInfo($row['pDepartmentID']); //调用函数，传入参数，继续查询上级
            $superDeptInfo[] = $row; //组合数组
        }

        return $superDeptInfo;
    }

    /**
     * FIXME: This function should not be required!
     */
    private function fixDI() {
        if ($this->dbConn === null) {
            $this->dbConn = \OC::$server->getDatabaseConnection();
        }
    }

    /**
     * is user in department?
     * @param string $uid uid of the user
     * @param string $gid gid of the department
     * @return bool
     *
     * Checks whether the user is member of a group or not.
     */
    public function inDepartment($employeeID, $departmentID) {
        $this->fixDI();

        // check
        $qb = $this->dbConn->getQueryBuilder();
        $cursor = $qb->select('employeeId')
            ->from('map_dep_emp')
            ->where($qb->expr()->eq('departmentID', $qb->createNamedParameter($departmentID)))
            ->andWhere($qb->expr()->eq('employeeId', $qb->createNamedParameter($employeeID)))
            ->execute();

        $result = $cursor->fetch();
        $cursor->closeCursor();

        return $result ? true : false;
    }

    /**
     * Get all departments a user belongs to
     * @param string $uid Name of the user
     * @return array an array of group names
     *
     * This function fetches all groups a user belongs to. It does not check
     * if the user exists at all.
     */
    public function getUserDeparts($empId)
    {
        $this->fixDI();

        // No magic!
        $qb = $this->dbConn->getQueryBuilder();
        $cursor = $qb->select('departmentID')
            ->from('map_dep_emp')
            ->where($qb->expr()->eq('employeeId', $qb->createNamedParameter($empId)))
            ->execute();

        $departs = [];
        while ($row = $cursor->fetch()) {
            $departs[] = $row["departmentID"];
            $this->groupCache[$row['departmentID']] = $row['departmentID'];
        }
        $cursor->closeCursor();

        return $departs;
    }

    /**
     * Department Admin
     *
     * @param string $empid
     * @param string|null $departid
     * @return true|false
     */
    public function getDepartAdmin($empid,$departid = null)
    {
        $this->fixDI();

        $searchDept = '';
        $parameters = [];
        $parameters[] = $empid;
        if (!is_null($departid)) {
            $parameters[] = $departid;
            $searchDept = ' AND did = ? ';
        }

        $departAdmin = array();
        $query = \OC_DB::prepare('SELECT * FROM `*PREFIX*department_admin` WHERE uid=? ' . $searchDept);
        $result = $query->execute($parameters);

        while ($row = $result->fetchRow()) {
            $departAdmin[$row["did"]] = $row["uid"];
        }

        return $departAdmin;
    }

    /**
     * Create a department admin
     * @param string $empid
     * @param string $departid
     * @return bool
     *
     * Creates a new user. Basic checking of username is done in OC_User
     * itself, not in its subclasses.
     */
    public function addDepartAdmin($empid,$departid) {
        $query = \OC_DB::prepare('INSERT INTO `*PREFIX*department_admin` ( `did`,`uid` ) VALUES( ?, ? )');
        $result = $query->execute(array($departid, $empid));

        return $result ? true : false;
    }

    /**
     * delete a department admin
     * @param string $empid
     * @param string $departid
     * @return bool
     *
     * Deletes a user
     */
    public function delDepartAdmin($empid,$departid) {
        // Delete department admin
        $query = \OC_DB::prepare('DELETE FROM `*PREFIX*department_admin` WHERE `did` = ? and `uid` = ?');
        $result = $query->execute(array($departid,$empid));


        return $result ? true : false;
    }
    /**--------------------------------2016-07-11 jiangzhe 增加部门共享 end--------------------------------*/
    /**
     * 添加部门
     *
     * @param int $pDepartmentId
     * @param string $departName
     * @param int $sort
     * @return boolean
     */
    public function addDepartment($pDepartmentId,$departName,$sort){

        if(!$pDepartmentId){
            $pDepartmentId = NULL;
        }

        $maxId = time();

        $query = \OC_DB::prepare('INSERT INTO `*PREFIX*department` (`departmentID`, `pDepartmentID`,`departName`,`sort`) VALUES(?, ?, ?, ?)');

        $result = $query->execute(array($maxId, $pDepartmentId, $departName, $sort));

        return $result ? $maxId : false ;
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
        $query = \OC_DB::prepare('UPDATE `*PREFIX*department` SET `departName` = ?, `sort` = ? WHERE `departmentID` = ?');

        $result = $query->execute(array($departName, $sort, $id));

        return $result ? true : false;
    }

    /**
     * 删除部门
     *
     * @param int $id
     * @return boolean
     */
    public function delete($id){

        $query = \OC_DB::prepare('DELETE FROM `*PREFIX*department` WHERE `departmentID` = ?');

        $result = $query->execute(array($id));

        if($result){
            $query1 = \OC_DB::prepare('DELETE FROM `*PREFIX*department` WHERE `pDepartmentID` = ?');

            $query1->execute(array($id));
        }

        return $result ? true : false;
    }

}
