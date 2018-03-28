<?php
/**
 * @author Arthur Schiwon <blizzz@owncloud.com>
 * @author Bart Visscher <bartv@thisnet.nl>
 * @author Björn Schießle <schiessle@owncloud.com>
 * @author Clark Tomlinson <fallen013@gmail.com>
 * @author Daniel Molkentin <daniel@molkentin.de>
 * @author Georg Ehrke <georg@owncloud.com>
 * @author Jakob Sack <mail@jakobsack.de>
 * @author Joas Schilling <nickvergessen@owncloud.com>
 * @author Jörn Friedrich Dreyer <jfd@butonic.de>
 * @author Lukas Reschke <lukas@owncloud.com>
 * @author Morris Jobke <hey@morrisjobke.de>
 * @author Robin Appelman <icewind@owncloud.com>
 * @author Robin McCorkell <robin@mccorkell.me.uk>
 * @author Roeland Jago Douma <rullzer@owncloud.com>
 * @author Stephan Peijnik <speijnik@anexia-it.com>
 * @author Thomas Müller <thomas.mueller@tmit.eu>
 *
 * @copyright Copyright (c) 2016, ownCloud, Inc.
 * @license AGPL-3.0
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 */

OC_Util::checkSubAdminUser();

\OC::$server->getNavigationManager()->setActiveEntry('department');

\OC_Util::addVendorStyle('ztree/css/zTreeStyle');
\OC_Util::addVendorStyle('ztree/css/navigationtree');
\OC_Util::addVendorScript('ztree/js/jquery.ztree.core');

$departmentManager = \OC::$server->getDepartmentManager();
$departmentBackend = new OC\Department\Database();
$departmentManager->registerBackend($departmentBackend);
$deparments = $departmentManager->getDepartmentsByPid('');

$isAdmin = OC_User::isAdminUser(OC_User::getUser());
$config = \OC::$server->getConfig();
$subAdmins = [];

// load preset quotas
$quotaPreset=$config->getAppValue('files', 'quota_preset', '1 GB, 5 GB, 10 GB');
$quotaPreset=explode(',', $quotaPreset);
foreach($quotaPreset as &$preset) {
    $preset=trim($preset);
}
$quotaPreset=array_diff($quotaPreset, array('default', 'none'));

$defaultQuota=$config->getAppValue('files', 'default_quota', 'none');
$defaultQuotaIsUserDefined=array_search($defaultQuota, $quotaPreset)===false
    && array_search($defaultQuota, array('none', 'default'))===false;

\OC::$server->getEventDispatcher()->dispatch('OC\Settings\Users::loadAdditionalScripts');

$tmpl = new OC_Template("settings", "department/main", "user");
$tmpl->assign('department', $deparments);
$tmpl->assign('isAdmin', (int)$isAdmin);
$tmpl->assign('subadmins', $subAdmins);
$tmpl->assign('quota_preset', $quotaPreset);
$tmpl->assign('default_quota', $defaultQuota);
$tmpl->assign('defaultQuotaIsUserDefined', $defaultQuotaIsUserDefined);
$tmpl->assign('enableAvatars', \OC::$server->getConfig()->getSystemValue('enable_avatars', true) === true);

$tmpl->assign('show_storage_location', $config->getAppValue('core', 'umgmt_show_storage_location', 'false'));
$tmpl->assign('show_last_login', $config->getAppValue('core', 'umgmt_show_last_login', 'false'));
$tmpl->assign('show_email', $config->getAppValue('core', 'umgmt_show_email', 'false'));
$tmpl->assign('show_backend', $config->getAppValue('core', 'umgmt_show_backend', 'false'));
$tmpl->assign('send_email', $config->getAppValue('core', 'umgmt_send_email', 'false'));

$tmpl->printPage();