<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 16-7-6
 * Time: 下午4:29
 */

namespace OC\Department;


class PopDepartMetaData
{
    // 节点ID
    private $nodeID;

    // $nodeUserID
    private $nodeUserID;

    // 父节点ID
    private $pNodeID;

    // 节点名称
    private $nodeName;

    // 节点标识 0:部门 1:个人
    private $nodeFlg;

    // 排序
    private $sort;

    /**
     * @return mixed
     */
    public function getNodeID()
    {
        return $this->nodeID;
    }

    /**
     * @param mixed $nodeID
     */
    public function setNodeID($nodeID)
    {
        $this->nodeID = $nodeID;
    }

    /**
     * @return mixed
     */
    public function getPNodeID()
    {
        return $this->pNodeID;
    }

    /**
     * @param mixed $pNodeID
     */
    public function setPNodeID($pNodeID)
    {
        $this->pNodeID = $pNodeID;
    }

    /**
     * @return mixed
     */
    public function getNodeName()
    {
        return $this->nodeName;
    }

    /**
     * @param mixed $nodeName
     */
    public function setNodeName($nodeName)
    {
        $this->nodeName = $nodeName;
    }

    /**
     * @return mixed
     */
    public function getNodeFlg()
    {
        return $this->nodeFlg;
    }

    /**
     * @param mixed $nodeFlg
     */
    public function setNodeFlg($nodeFlg)
    {
        $this->nodeFlg = $nodeFlg;
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
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
    }
    /**
     * @return mixed
     */
    public function getNodeUserID()
    {
        return $this->nodeUserID;
    }

    /**
     * @param mixed $nodeUserID
     */
    public function setNodeUserID($nodeUserID)
    {
        $this->nodeUserID = $nodeUserID;
    }
}