<?php
/**
 * Created by PhpStorm.
 * User: duyanjun
 * Date: 16-5-17
 * Time: 上午10:35
 */

namespace OC\Department;

/**
 * Class MetaData 部门实体类
 * @package OC\Department
 */
class MetaData
{

    // 部门ID
    private $departmentID;

    // 父部门ID
    private $pDepartmentID;

    // 部门名称
    private $departName;

    // 排序
    private $sort;

    /**
     * @return mixed
     */
    public function getDepartmentID()
    {
        return $this->departmentID;
    }

    /**
     * @param mixed $departmentID
     * @return MetaData
     */
    public function setDepartmentID($departmentID)
    {
        $this->departmentID = $departmentID;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPDepartmentID()
    {
        return $this->pDepartmentID;
    }

    /**
     * @param mixed $pDepartmentID
     * @return MetaData
     */
    public function setPDepartmentID($pDepartmentID)
    {
        $this->pDepartmentID = $pDepartmentID;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDepartName()
    {
        return $this->departName;
    }

    /**
     * @param mixed $departName
     * @return MetaData
     */
    public function setDepartName($departName)
    {
        $this->departName = $departName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param mixed $sort
     * @return MetaData
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
        return $this;
    }

}