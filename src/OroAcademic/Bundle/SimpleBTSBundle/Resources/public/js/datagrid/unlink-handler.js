/*global define*/
define([
    'jquery',
    'orotranslation/js/translator',
    'oroui/js/delete-confirmation',
    'oroui/js/messenger'
], function($, __, DeleteConfirmation, Messenger) {
    'use strict';

    /**
     * @export  oro_academic_sbts/js/unlink-handler
     * @class   oro_academic_sbts.UnlinkHandler
     */
    return function(url) {
        var element = this;

        var confirmUnlink = new DeleteConfirmation({
            content: 'Are you sure that you want unlink Issue?',
            okText: 'Unlink'
        });

        confirmUnlink.on('ok', function() {
            element.trigger('unlink_start');
            $.ajax({
                url: url,
                type: 'DELETE',
                success: function() {
                    element.trigger('unlink_success');
                },
                error: function() {
                    Messenger.notificationFlashMessage('error', 'You do not have permission to perform this action.');
                    element.trigger('unlink_error');
                }
            })
        });

        confirmUnlink.open();
    }
});
