<div id="controls">
	<form id="newuser" autocomplete="off">
		<input id="newusername" type="text"
			placeholder="<?php p($l->t('Username'))?>"
			autocomplete="off" autocapitalize="none" autocorrect="off" />
		<input
			type="password" id="newuserpassword"
			placeholder="<?php p($l->t('Password'))?>"
			autocomplete="off" autocapitalize="none" autocorrect="off" />
		<input id="newemail" type="text" style="display:none"
			   placeholder="<?php p($l->t('E-Mail'))?>"
			   autocomplete="off" autocapitalize="none" autocorrect="off" />
		<div class="groups"><div class="groupsListContainer multiselect button" data-placeholder="<?php p($l->t('Groups'))?>"><span class="title groupsList"></span><span class="icon-triangle-s"></span></div></div>
        <input class="shareWithDepart" name="dname" type="Button" id="shareDepart"
               value="组织结构" style="height: 36px"/>
        <input type="hidden" name="did" value="">
		<input type="submit" class="button" value="<?php p($l->t('Create'))?>" />
	</form>
	<?php if((bool)$_['recoveryAdminEnabled']): ?>
	<div class="recoveryPassword">
	<input id="recoveryPassword"
		   type="password"
		   placeholder="<?php p($l->t('Admin Recovery Password'))?>"
		   title="<?php p($l->t('Enter the recovery password in order to recover the users files during password change'))?>"
		   alt="<?php p($l->t('Enter the recovery password in order to recover the users files during password change'))?>"/>
	</div>
	<?php endif; ?>

    <div id="dialog-core" title="组织结构" style="display: none;">
        <div style="z-index:990;">
            <ul id="treeDemo1" class="ztree"></ul>
        </div>
    </div>
    <div id="dialog-confirm" title="警告" style="display: none">
        <p><span class="ui-icon ui-icon-alert"></span>请先选择部门!</p>
    </div>
</div>
