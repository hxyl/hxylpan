/**
 * Created by tianquanjun on 17-10-13.
 */
$(function () {
    var $el,operationType;
    $("body").on('click', function (e) {
        if(!$('#modify').is(':hidden')){
            $('#modify').hide();
        }
    });

    // 禁止回车提交表单
    $("#newdepartment").on("keypress", function (e) {
        if(e.which == 13){
            e.preventDefault();
        }
    });
    // 禁止右键
    $("#app-navigation-tree").on("contextmenu", function (e) {
        e.preventDefault();
        return false;

    });
    $("#app-navigation-tree").on("mousedown",'ul li',function (event) {

        event.stopPropagation();

        if (event.which == 3) {

            $el = event;

            var did = $(this).val();

            var dname = $(this).children().eq(1).text();

            var left = $(this).offset().left + ($(this).children().eq(0).width() + $(this).children().eq(1).width());

            var top = $(this).offset().top - 30;

            var view = '';
            view += '<dl>';
            view += '<dt id="insert" data-did='+did+'><a>添加</a></dt>';
            view += '<dt id="update" data-did='+did+' data-dname='+dname+'><a>修改</a></dt>';
            view += '<dt id="delete" data-did='+did+'><a>删除</a></dt>'
            view +='</dl>';

            $('#modify').show().css({"left": left,"top": top}).html(view);
        }
    });

    $("#app-navigation-tree").on("mousedown",function (event) {

        event.stopPropagation();

        if (event.which == 3) {

            $el = event;

            var did = $(this).val();

            var left = event.clientX;

            var top = event.clientY - 30;

            var view = '';
            view += '<dl>';
            view += '<dt id="insert" data-did='+did+'><a>添加</a></dt>';
            view +='</dl>';

            $('#modify').show().css({"left": left,"top": top}).html(view);
        }
    });

    $("#modify").on("click", "dl dt", function (e) {
        var type = $(this).attr("id");

        if(type === 'insert'){

            $('input[name="departname"]').val('');
            $('input[name="departmentid"]').val($(this).data('did'));

            operationType = type;

            $('#controls').show();

        }else if(type === 'update'){
            operationType = type;

            $('input[name="departname"]').val($(this).data('dname')).css('width','auto');
            $('input[name="departmentid"]').val($(this).data('did'));
            $('#controls').show();
        }else if(type === 'delete'){

            $('#controls').hide();

            $.ajax({
                type: 'DELETE',
                url: OC.generateUrl('/settings/department/department/{id}',{id: $(this).data('did')}),
                // FIXME: do not use synchronous ajax calls as they block the browser !
                async: false,
                success: function (result) {
                    if(result.status = 'success'){
                        $($el.currentTarget).remove();
                    }else{
                        OC.Notification.showTemporary(t('settings', 'Error delete department: {message}', {
                            message: t('settings', '无法删除')
                        }));
                    }
                }
            });
        }
    });

    $('#newdepartment').submit(function (event) {
        event.preventDefault();

        var departname = $("#departname").val();
        var departmentId = $("#departmentid").val();

        if ($.trim(departname) === '') {
            OC.Notification.showTemporary(t('settings', 'Error creating department: {message}', {
                message: t('settings', '必须提供合法的部门名称')
            }));
            return false;
        }

        if(operationType === 'insert'){

            $.post(
                OC.generateUrl('/settings/department/department'),
                {
                    departname: departname,
                    pDepartmentId: departmentId
                },
                function (result){
                    if(result.status){
                        var html = '';

                        html += '<li class="level0" tabindex="0" hidefocus="true" treenode="" value='+ result.data.id +'>';
                        html += '<span title="" class="button level0 switch bottom_close" treenode_switch=""></span>';
                        html += '<a class="level0" treenode_a="" onclick="" target="_blank" style="" title='+departname+'>';
                        html += '<span title="" treenode_ico="" class="button ico_close" style=""></span>';
                        html += '<span class="node_name">'+departname+'</span>';
                        html += '</a>';
                        html += '</li>';


                        $($el.currentTarget).children('ul').append(html);
                        $(event.currentTarget).parent().hide();
                    }else{
                        OC.Notification.showTemporary(t('settings', 'Error creating department: {message}', {
                            message: t('settings', '创建部门失败')
                        }));
                    }
                }
            );

        }else if (operationType === 'update'){

            $.post(
                OC.generateUrl('/settings/department/{departname}/departName',{departname: departname}),
                {
                    departname: departname,
                    id: departmentId
                },
                function (result){
                    if(result.status){

                        $($el.currentTarget).children().eq(1).children('.node_name').text(departname);
                        $(event.currentTarget).parent().hide();

                    }else{
                        OC.Notification.showTemporary(t('settings', 'Error creating department: {message}', {
                            message: t('settings', '修改部门失败')
                        }));
                    }
                }
            );
        }

    });
});