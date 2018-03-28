/**
 * Created by tianquanjun on 17-10-13.
 */

var deptUrl = OC.generateUrl('/settings/department/department');
var setting = {
    view: {
        selectedMulti: false
    },
    async: {
        enable: true,
        type: "get",
        url:deptUrl,
        //url: '../getNodes.php',
        autoParam:["id", "name=n", "level=lv"],
        dataFilter: filter
    },
    callback: {
        beforeClick: beforeClick
    }
};

function filter(treeId, parentNode, childNodes) {
    if (!childNodes) return null;
    for (var i=0, l=childNodes.length; i<l; i++) {
        childNodes[i].name = childNodes[i].name.replace(/\.n/g, '.');
    }
    return childNodes;
}
function beforeClick(treeId, treeNode) {
    $("input[name='did']").val(treeNode.id);
    $("input[name='dname']").val(treeNode.name);
    UserList.updateForDept(treeNode.id);

    if (!treeNode.isParent) {
        alert("请选择父节点");
        return false;
    } else {
        return true;
    }
}

function refreshNode(e){
    var zTree = $.fn.zTree.getZTreeObj("treeDemo"),
        type = e.data.type,
        silent = e.data.silent,
        nodes = zTree.getSelectedNodes();
    if (nodes.length == 0){
        /**--------------------------------2016-07-11 jiangzhe 修改提示信息 start--------------------------------*/
        alert("请先选择一个父节点");
        /**--------------------------------2016-07-11 jiangzhe 修改提示信息 end--------------------------------*/
    }
    for (var i=0, l=nodes.length; i<l; i++) {
        zTree.reAsyncChildNodes(nodes[i], type, silent);
        if (!silent) zTree.selectNode(nodes[i]);
    }
}

$(document).ready(function(){
    $.fn.zTree.init($("#treeDemo1"), setting);

    $("#refreshNode").bind("click", {type:"refresh", silent:false}, refreshNode);
    $("#refreshNodeSilent").bind("click", {type:"refresh", silent:true}, refreshNode);
    $("#addNode").bind("click", {type:"add", silent:false}, refreshNode);
    $("#addNodeSilent").bind("click", {type:"add", silent:true}, refreshNode);
});