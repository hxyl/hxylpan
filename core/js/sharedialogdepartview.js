/**
 * Created by root on 16-7-5.
 */
/*
 * Copyright (c) 2015
 *
 * This file is licensed under the Affero General Public License version 3
 * or later.
 *
 * See the COPYING-README file.
 *
 */

(function() {
    if (!OC.Share) {
        OC.Share = {};
    }

    var TEMPLATE ='<div id="dialog-form" title="组织结构" >'  +
    '<div style="z-index:990;">'  +
    '<ul id="popdepartmenttree" class="ztree"></ul>'  +
    '</div>'  +
    '</div>' +
    '<div id="dialog-confirm" title="警告" style="display: none">' +
    '<p>' +
    '<span class="ui-icon ui-icon-alert">'+
    '</span>' +
    '请先选择部门或人员!</p>' +
    '</div>';

    /**
     * @class OCA.Share.ShareDialogView
     * @member {OC.Share.ShareItemModel} model
     * @member {jQuery} $el
     * @memberof OCA.Sharing
     * @classdesc
     *
     * Represents the GUI of the share dialogue
     *
     */
    var ShareDialogDepartView = OC.Backbone.View.extend({
        /** @type {string} **/
        id: 'ShareDialogDepart',

        /** @type {Function} **/
        _template: undefined,


        initialize: function(options){
            var view = this;

        },
        
        render: function(e,onCheckedRecipient){


            var deptUrl = OC.generateUrl('/popdepartment/getDepart');


            var shareDepartTemplate = this.template();

            this.$el.html(shareDepartTemplate());



            var setting = {
                check: {
                    enable: true
                },
                data: {
                    simpleData: {
                        enable: true
                    }
                },
                view: {
                    selectedMulti: false
                },
                async: {
                    enable: true,
                    type: "get",
                    url: deptUrl,
                    autoParam:["id", "name=n", "level=lv"],
                    dataFilter: filter
                },
                callback: {
                    beforeClick: beforeClick,
                    beforeAsync: beforeAsync,
                    onAsyncError: onAsyncError,
                    onAsyncSuccess: onAsyncSuccess
                }
            };

            $(document).ready(function(){
                $.fn.zTree.init($("#popdepartmenttree"), setting);
                setCheck();
                $("#py").bind("change", setCheck);
                $("#sy").bind("change", setCheck);
                $("#pn").bind("change", setCheck);
                $("#sn").bind("change", setCheck);
                $("#refreshNode").bind("click", {type:"refresh", silent:false}, refreshNode);
                $("#refreshNodeSilent").bind("click", {type:"refresh", silent:true}, refreshNode);
                $("#addNode").bind("click", {type:"add", silent:false}, refreshNode);
                $("#addNodeSilent").bind("click", {type:"add", silent:true}, refreshNode);
            });


            $("#dialog-form").dialog({
                autoOpen: true,
                height: 400,
                width: 350,
                position: [200,120],
                modal: true,
                buttons: {
                    "全选":function (){
                        $.each($(this).find('#popdepartmenttree').children('li'),function (k,v){
                           $('#'+$(v).attr('id')+ '_check').removeClass('checkbox_false_full').addClass('checkbox_true_full');
                        });
                    },
                    "确定": function(){
                        var treeObj = $.fn.zTree.getZTreeObj("popdepartmenttree");

                        var nodes = treeObj.getCheckedNodes(true);

                        if (nodes.length == 0){
                            $("#dialog-confirm").dialog(
                                {
                                    modal: true,             // 创建模式对话框
                                    autoOpen: true,         // 初始化，显示
                                    position: [230,200],
                                    buttons: {
                                        "Ok": function(){
                                            $(this).dialog('close');
                                        }
                                    }
                                }
                            );
                            return;
                        }

                        for(var i = 0;i < nodes.length;i++){

                            var node = {};
                            node['id'] = nodes[i]['id'];
                            node['name'] = nodes[i]['name'];
                            node['isParent'] = nodes[i]['isParent'];
                            node['nodeFlg'] = nodes[i]['nodeFlg'];
                            node['PNodeID'] = nodes[i]['PNodeID'];
                            node['nodeUserId'] = nodes[i]['nodeUserId'];

                            if(node.nodeFlg == 0){
                                node['shareType'] = OC.Share.SHARE_TYPE_DEPARTMENT; // 0:部门 1:个人
                                node['shareWith'] = node.id;
                            }else{
                                node['shareType'] = OC.Share.SHARE_TYPE_USER; // 0:部门 1:个人
                                node['shareWith'] = node.nodeUserId;
                            }


                            onCheckedRecipient(e,node);
                        }

                        $(this).dialog( "close" );
                    },
                    "取消": function(){
                        $(e.target).attr('disabled', false);
                        $(this).dialog( "close" );
                    }
                },
                close: function(){
                    $(e.target).attr('disabled', false);
                }
                // ,
                // open: function(){
                //     $(this).parents(".ui-dialog:first").find(".ui-dialog-titlebar-close").remove();
                // }
            });

            return this;
        },

        /**
         * @returns {Function} from Handlebars
         * @private
         */
        template: function (){
            if (!this._template){
                this._template = Handlebars.compile(TEMPLATE);
            }
            return this._template;
        }
    });

    OC.Share.ShareDialogDepartView = ShareDialogDepartView;

})();