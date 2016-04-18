/*global define*/
define([
    'oro/datagrid/action/delete-action',
    'oroacademicsimplebts/js/datagrid/unlink-handler',
    'oroui/js/messenger'
], function (DeleteAction, UnlinkHandler, Messenger) {
    'use strict';
    var UnlinkAction;
    /**
     * @export oro/datagrid/action/unlink-action
     * @class oro.datagrid.action.UnlinkAction
     * @extends oro.datagrid.action.DeleteAction
     */
    UnlinkAction = DeleteAction.extend({
        execute: function () {
            var datagrid = this.datagrid;

            this.on('unlink_start', function () {
                datagrid.showLoading();
            });
            this.on('unlink_success', function () {
                Messenger.notificationFlashMessage('success', 'Issue unlinked successfully');

                datagrid.hideLoading();
                datagrid.collection.fetch({reset: true});
            });
            this.on('unlink_error', function () {
                datagrid.hideLoading();
            });

            UnlinkHandler.call(this, this.getLink());
        }
    });
    return UnlinkAction;
});