<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 17-4-27
 * Time: 下午7:20
 */

    require_once "../lib/nusoap.php";

    require_once "../Db/Db.class.php";

    /**
     * 根据返回的票据信息获取令牌信息，返回是json格式的字符串。
     * @param string $appMark   应用标识
     * @param string $time      时间戳
     * @param string $enckey    签名   md5($appMark.$appword.$time)
     * @param string $json json数据
     * @param string return
     */
    function receive($json){

        if(empty($json)){
            return response(array(array('id' => 0,'message' => '参数有误','success' => false)));
        }

        $connect = Db::getInstance()->connect();

        $getJsonInfo = json_decode($json,true);

        $status = '执行成功';

        foreach($getJsonInfo as $v){
            
            switch ($v['state']){
                case "T":
                    // 用户查询
                    $getUserCheckResult = getInfoCheck($v['employeeId'],$connect);

                    // 人员机构中间表查询
                    $getMiddleCheckResult = getMiddleCheck($v['employeeId'],$connect);

                    if($getUserCheckResult){
                        // 修改用户信息
                        $Result = editUserInfo($v,$connect);
                        if(!$Result){
                            $status = '修改人员失败';
                        }

                        if($getMiddleCheckResult){
                            // 修改人员机构中间表
                            editMiddleInfo($v,$connect);
                        }
                    }else{

                         $Result = addUserInfo($v,$connect);
                        if(!$Result){
                            $status = '添加人员失败';
                        }

                        if(!$getMiddleCheckResult){
                            // 添加人员机构中间表
                            addMiddleInfo($v,$connect);
                        }
                    }
                    break;
                case "D":
                    // 删除人员信息
                    $Result = delUserInfo($v['employeeId'],$connect);
                    // 人员机构中间表查询
                    $getMiddleCheckResult = getMiddleCheck($v['employeeId'],$connect);

                    if(!$Result){
                        $status = '删除人员失败';
                    }

                    if($getMiddleCheckResult){
                        // 删除人员机构中间表
                        delMiddleInfo($v['employeeId'],$connect);
                    }
                    break;

                case "TG":

                    // 机构查询
                    $MechanismCheckRusult = getMechanismCheck($v['departmentID'],$connect);

                    if($MechanismCheckRusult){

                        // 机构修改
                        $Result = editMechanism($v,$connect);
                        if(!$Result){
                            $status = '修改机构失败';
                        }
                    }else{
                        // 机构添加
                        $Result = addMechanism($v,$connect);

                        if(!$Result){
                            $status = '添加机构失败';
                        }
                    }
                    break;
                case "DG":
                    // 机构删除
                    $Result = delMechanism($v['departmentID'],$connect);

                    if(!$Result){
                        $status = '删除机构失败';
                    }
                    break;
                case "DN":
                    // 删除人员信息
                    $Result = delUserInfo($v['employeeId'],$connect);
                    // 人员机构中间表查询
                    $getMiddleCheckResult = getMiddleCheck($v['employeeId'],$connect);

                    if(!$Result){
                        $status = '删除人员失败';
                    }

                    if($getMiddleCheckResult){
                        // 删除人员机构中间表
                        delMiddleInfo($v['employeeId'],$connect);
                    }
                    break;

                default:
                    $status = '参数有误';
                    $Result = false;
            }
            $arr[] = array("id" => $v['employeeId'],"message" => $status,"success" => $Result);
        }

        return response($arr);
    }

    // 用户check
    function getInfoCheck($userSeqId,$connect){

        if(empty($userSeqId) && !isset($userSeqId)){
            return false;
        }

        $sqlInfo = "select count(employeeId) from oc_users where employeeId='{$userSeqId}'";

        $checkResult = $connect->query($sqlInfo);

        return !$checkResult->fetchColumn() ? false : true;
    }

    // 用户修改
    function editUserInfo($userInfo=array(),$connect){

        if(empty($userInfo['employeeId'])){
            return false;
        }

        $editSql = "update oc_users 
                    set uid='{$userInfo['uid']}',
                        displayname='{$userInfo['displayname']}',
                        password = '{$userInfo['password']}',
                        sort = '{$userInfo['orderid']}'
                    where employeeId='{$userInfo['employeeId']}'";

        $editResult = $connect->exec($editSql);

        return $editResult === false ? false : true;
    }

    // 用户添加
    function addUserInfo($userInfo=array(),$connect){

        if(empty($userInfo['employeeId'])){
            return false;
        }

        $addSql = "insert into oc_users(employeeId,uid,displayname,password,sort)
                   values('{$userInfo['employeeId']}','{$userInfo['uid']}','{$userInfo['displayname']}','{$userInfo['password']}','{$userInfo['orderid']}')";

        $addResult = $connect->exec($addSql);

        return !$addResult ? false : true;
    }
    // 删除人员信息
        function delUserInfo($userSeqId,$connect){

        if(empty($userSeqId)){
            return false;
        }

        $delSql = "delete from oc_users where employeeId='{$userSeqId}'";

        $delResult = $connect->exec($delSql);

        return !$delResult ? false : true;
    }

    // 机构check
    function getMechanismCheck($groupcode,$connect){

        if(empty($groupcode) && !isset($groupcode)){
            return false;
        }
        $sqlInfo = "select count(departmentID) from oc_department where departmentID='{$groupcode}'";

        $checkResult = $connect->query($sqlInfo);

        return !$checkResult->fetchColumn() ? false : true;
    }
    // 机构修改
    function editMechanism($mechanismInfo = array(),$connect) {

        if(empty($mechanismInfo['departmentID'])){
            return false;
        }

        if(empty($mechanismInfo['pDepartmentID'])){
            $editSql = "update oc_department 
                    set departName='{$mechanismInfo['departName']}',
                        pDepartmentID = NULL,
                        sort = '{$mechanismInfo['orderid']}'
                    where departmentID='{$mechanismInfo['departmentID']}'";
        }else{
            $editSql = "update oc_department 
                    set pDepartmentID='{$mechanismInfo['pDepartmentID']}',
                        departName='{$mechanismInfo['departName']}',
                        sort = '{$mechanismInfo['orderid']}'
                    where departmentID='{$mechanismInfo['departmentID']}'";
        }

        $editResult = $connect->exec($editSql);

        return $editResult === false ? false : true;
    }

    // 机构添加
    function addMechanism($mechanismInfo = array(),$connect){

        if(empty($mechanismInfo['pDepartmentID'])){

            $addSql = "insert into oc_department(departmentID,departName)
                   values('{$mechanismInfo['departmentID']}','{$mechanismInfo['departName']}')";

        }else{
            $addSql = "insert into oc_department(departmentID,pDepartmentID,departName)
                   values('{$mechanismInfo['departmentID']}','{$mechanismInfo['pDepartmentID']}','{$mechanismInfo['departName']}')";
        }

        $addResult = $connect->exec($addSql);

        return !$addResult ? false : true;
    }

    // 机构删除
    function delMechanism($groupcode,$connect){

        if(empty($groupcode)){
            return false;
        }

        $delSql = "delete from oc_department where departmentID='{$groupcode}'";

        $delResult = $connect->exec($delSql);

        return !$delResult ? false : true;
    }

    // 人员机构 中间表 check
    function getMiddleCheck($userSeqId,$connect){
        if(empty($userSeqId) && !isset($userSeqId)){
            return false;
        }

        $sqlInfo = "select count(employeeId) from oc_map_dep_emp where employeeId='{$userSeqId}'";

        $checkResult = $connect->query($sqlInfo);

        return !$checkResult->fetchColumn() ? false : true;
    }

    // 人员机构中间表添加
    function addMiddleInfo($info,$connect){
        if(empty($info['employeeId'])){
            return false;
        }

        $addSql = "insert into oc_map_dep_emp(employeeId,departmentID)
                   values('{$info['employeeId']}','{$info['departmentID']}')";

        $addResult = $connect->exec($addSql);

        return !$addResult ? false : true;
    }

    function editMiddleInfo($info,$connect){
        if(empty($info['employeeId'])){
            return false;
        }

        $editSql = "update oc_map_dep_emp 
                    set departmentID='{$info['departmentID']}' 
                    where employeeId='{$info['employeeId']}'";

        $editResult = $connect->exec($editSql);

        return $editResult === false ? false : true;
    }

    // 人员机构中间表删除
    function delMiddleInfo($userSeqId,$connect){
        if(empty($userSeqId)){
            return false;
        }

        $delSql = "delete from oc_map_dep_emp where employeeId='{$userSeqId}'";

        $delResult = $connect->exec($delSql);

        return !$delResult ? false : true;
    }
    // 处理返回数据
    function response($data){
        return json_encode($data);
    }
    $server = new nusoap_server();

    // 避免乱码
    $server -> soap_defencoding = 'UTF-8';
    $server -> decode_utf8 = false;
    $server -> xml_encoding = 'UTF-8';
    $server -> configureWSDL('receive');
    $server -> register( 'receive',    //方法名
        array(
            "json" => "xsd:string",
        ),    //参数，默认为"xsd:string"
        array("return" => "xsd:string"));//返回值，默认为"xsd:string"

    $server->service(file_get_contents("php://input"));

