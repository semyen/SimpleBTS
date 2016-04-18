/*global define*/
define([
    'jquery',
    'orotranslation/js/translator',
    'oroui/js/delete-confirmation',
    'oroui/js/messenger'
], function($, __, DeleteConfirmation, Messenger) {
    'use strict';

    /**
     * @export  oroacademicsimplebts/js/deletion-handler
     * @class   oroacademicsimplebts.DeletionHandler
     */
    return function(url, subtasksCount) {
        var element = this;

        var confirmDeletion = new DeleteConfirmation({
            content:
                'Are you sure that you want delete Issue' +
                (subtasksCount > 0 ? ' with <b>' + subtasksCount + '</b> sub-task(s)': '') +
                '?'
        });

        confirmDeletion.on('ok', function() {
            element.trigger('deletion_start');
            $.ajax({
                url: url,
                type: 'DELETE',
                success: function() {
                    element.trigger('deletion_success');
                },
                error: function() {
                    Messenger.notificationFlashMessage('error', 'You do not have permission to perform this action.');
                    element.trigger('deletion_error');
                }
            })
        });

        confirmDeletion.open();
    }
});
