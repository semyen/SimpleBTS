/*global define*/
define([
    'oro/datagrid/action/delete-action',
    'oroacademicsimplebts/js/datagrid/deletion-handler',
    'oroui/js/messenger'
], function (DeleteAction, DeletionHandler, Messenger) {
    'use strict';
    /**
     * @export oro/datagrid/action/issue-delete-action
     * @class oro.datagrid.action.IssueDeleteAction
     * @extends oro.datagrid.action.DeleteAction
     */
    var IssueDeleteAction = DeleteAction.extend({
        execute: function () {
            var datagrid = this.datagrid;

            this.on('deletion_start', function () {
                datagrid.showLoading();
            });
            this.on('deletion_success', function () {
                Messenger.notificationFlashMessage('success', 'Issue Deleted');

                datagrid.hideLoading();
                datagrid.collection.fetch({reset: true});
            });
            this.on('deletion_error', function () {
                datagrid.hideLoading();
            });

            DeletionHandler.call(this, this.getLink(), this.model.get('subtasksCount'));
        }
    });
    return IssueDeleteAction;
});