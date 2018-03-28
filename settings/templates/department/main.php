<?php
/**
 * Copyright (c) 2011, Robin Appelman <icewind1991@gmail.com>
 * This file is licensed under the Affero General Public License version 3 or later.
 * See the COPYING-README file.
 */
style('settings','settings');
script('settings', [
	'users/deleteHandler',
	'users/filter',
	'users/users',
	'users/groups',
	'departments/department',
    'departments/modifyDepartment'
]);

script('core', [
	'multiselect',
	'singleselect'
]);

$userlistParams = array();
$allGroups=array();
foreach($_["adminGroup"] as $group) {
    $allGroups[] = $group['name'];
}
foreach($_["groups"] as $group) {
    $allGroups[] = $group['name'];
}
$userlistParams['subadmingroups'] = $allGroups;
$userlistParams['allGroups'] = json_encode($allGroups);
$items = array_flip($userlistParams['subadmingroups']);
unset($items['admin']);
$userlistParams['subadmingroups'] = array_flip($items);

translation('settings');
?>
	<div id="app-navigation-tree" class="aaa">
		<?php print_unescaped($this->inc('department/part.departmenttree')); ?>
	<div id="app-settings">
		<div id="app-settings-header">
			<button class="settings-button" tabindex="0" data-apps-slide-toggle="#app-settings-content"></button>
		</div>
		<div id="app-settings-content">
			<?php print_unescaped($this->inc('department/part.setquota')); ?>

			<div id="userlistoptions">
				<p>
					<input type="checkbox" name="StorageLocation" value="StorageLocation" id="CheckboxStorageLocation"
						class="checkbox" <?php if ($_['show_storage_location'] === 'true') print_unescaped('checked="checked"'); ?> />
					<label for="CheckboxStorageLocation">
						<?php p($l->t('Show storage location')) ?>
					</label>
				</p>
				<p>
					<input type="checkbox" name="LastLogin" value="LastLogin" id="CheckboxLastLogin"
						class="checkbox" <?php if ($_['show_last_login'] === 'true') print_unescaped('checked="checked"'); ?> />
					<label for="CheckboxLastLogin">
						<?php p($l->t('Show last log in')) ?>
					</label>
				</p>
				<p>
					<input type="checkbox" name="UserBackend" value="UserBackend" id="CheckboxUserBackend"
						class="checkbox" <?php if ($_['show_backend'] === 'true') print_unescaped('checked="checked"'); ?> />
					<label for="CheckboxUserBackend">
						<?php p($l->t('Show user backend')) ?>
					</label>
				</p>
				<p>
					<input type="checkbox" name="MailOnUserCreate" value="MailOnUserCreate" id="CheckboxMailOnUserCreate"
						class="checkbox" <?php if ($_['send_email'] === 'true') print_unescaped('checked="checked"'); ?> />
					<label for="CheckboxMailOnUserCreate">
						<?php p($l->t('Send email to new user')) ?>
					</label>
				</p>
				<p>
					<input type="checkbox" name="EmailAddress" value="EmailAddress" id="CheckboxEmailAddress"
						class="checkbox" <?php if ($_['show_email'] === 'true') print_unescaped('checked="checked"'); ?> />
					<label for="CheckboxEmailAddress">
						<?php p($l->t('Show email address')) ?>
					</label>
				</p>
			</div>
		</div>
	</div>
    <div id="modify" style=""></div>
</div>

<div id="app-content">
	<!-- -------------------------------2016-05-20 jiangzhe modify start -------------------- -->
<?php print_unescaped($this->inc('department/part.createuser')); ?>
	<!-- -------------------------------2016-05-20 jiangzhe modify end   -------------------- -->
	<?php print_unescaped($this->inc('department/part.userlist', $userlistParams)); ?>
</div>

