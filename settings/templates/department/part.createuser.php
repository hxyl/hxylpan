<div id="controls" style="display: none">
	<form id="newdepartment" autocomplete="off">
        <input id="departname" type="text" name="departname"
               placeholder="<?php p($l->t('部门名称'))?>"
               autocomplete="off" autocapitalize="off" autocorrect="off"/>
        <input type="hidden" id="departmentid" name="departmentid" value="">
		<input type="submit" class="button" value="<?php p($l->t('提交'))?>" />
	</form>
</div>
