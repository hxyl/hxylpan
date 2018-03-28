function filter(treeId, parentNode, childNodes){
    if (!childNodes) return null;
    try {
        for (var i = 0, l = childNodes.length; i < l; i++) {
            childNodes[i].name = childNodes[i].name.replace(/\.n/g, '.');
        }
    }
    catch (e) {
        console.log(e);
    }
    return childNodes;
}
function beforeClick(treeId, treeNode){
    UserList.empty();
    UserList.updateForDept(treeNode.id);
    $userGroupList.find('li').removeClass('active');
    if (!treeNode.isParent){
        alert("请选择父节点");
        return false;
    } else {
        return true;
    }
}
var log, className = "dark";
function beforeAsync(treeId, treeNode){
    className = (className === "dark" ? "":"dark");
    showLog("[ "+getTime()+" beforeAsync ]&nbsp;&nbsp;&nbsp;&nbsp;" + ((!!treeNode && !!treeNode.name) ? treeNode.name : "root") );
    return true;
}
function onAsyncError(event, treeId, treeNode, XMLHttpRequest, textStatus, errorThrown){
    showLog("[ "+getTime()+" onAsyncError ]&nbsp;&nbsp;&nbsp;&nbsp;" + ((!!treeNode && !!treeNode.name) ? treeNode.name : "root") );
}
function onAsyncSuccess(event, treeId, treeNode, msg){
    showLog("[ "+getTime()+" onAsyncSuccess ]&nbsp;&nbsp;&nbsp;&nbsp;" + ((!!treeNode && !!treeNode.name) ? treeNode.name : "root") );
}

function showLog(str){
    if (!log) log = $("#log");
    log.append("<li class='"+className+"'>"+str+"</li>");
    if(log.children("li").length > 8){
        log.get(0).removeChild(log.children("li")[0]);
    }
}
function getTime(){
    var now= new Date(),
        h=now.getHours(),
        m=now.getMinutes(),
        s=now.getSeconds(),
        ms=now.getMilliseconds();
    return (h+":"+m+":"+s+ " " +ms);
}


function refreshNode(e){
    var zTree = $.fn.zTree.getZTreeObj("popdepartmenttree"),
        type = e.data.type,
        silent = e.data.silent,
        nodes = zTree.getSelectedNodes();
    if (nodes.length == 0){
        alert("请先选择一个父节点");
    }
    for (var i=0, l=nodes.length; i<l; i++){
        zTree.reAsyncChildNodes(nodes[i], type, silent);
        if (!silent) zTree.selectNode(nodes[i]);
    }
}

var code;

function setCheck(){
    var zTree = $.fn.zTree.getZTreeObj("popdepartmenttree"),
        py = $("#py").attr("checked")? "p":"",
        sy = $("#sy").attr("checked")? "s":"",
        pn = $("#pn").attr("checked")? "p":"",
        sn = $("#sn").attr("checked")? "s":"",
        type = { "Y" : "", "N" : "" };
        // type = { "Y":py + sy, "N":pn + sn};



    zTree.setting.check.chkboxType = type;

    showCode('setting.check.chkboxType = { "Y" : "' + type.Y + '", "N" : "' + type.N + '" };');
}

function setCheck1(){
    var zTree = $.fn.zTree.getZTreeObj("department-list"),
        py = $("#py").attr("checked")? "p":"",
        sy = $("#sy").attr("checked")? "s":"",
        pn = $("#pn").attr("checked")? "p":"",
        sn = $("#sn").attr("checked")? "s":"",
        type = { "Y" : "", "N" : "" };
    // type = { "Y":py + sy, "N":pn + sn};



    zTree.setting.check.chkboxType = type;

    showCode('setting.check.chkboxType = { "Y" : "' + type.Y + '", "N" : "' + type.N + '" };');
}

function showCode(str){
    if (!code) code = $("#code");
    code.empty();
    code.append("<li>"+str+"</li>");
}
