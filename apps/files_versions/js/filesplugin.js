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
	OCA.Versions = OCA.Versions || {};

	/**
	 * @namespace
	 */
	OCA.Versions.Util = {
		/**
		 * Initialize the versions plugin.
		 *
		 * @param {OCA.Files.FileList} fileList file list to be extended
		 */
		attach: function(fileList) {
			if (fileList.id === 'trashbin' || fileList.id === 'files.public') {
				return;
			}
			/*----- 2018-03-27 tianquanjun 修改页面显示 start -----*/
			//fileList.registerTabView(new OCA.Versions.VersionsTabView('versionsTabView', {order: -10}));
			/*----- 2018-03-27 tianquanjun 修改页面显示 end -----*/
		}
	};
})();

OC.Plugins.register('OCA.Files.FileList', OCA.Versions.Util);

