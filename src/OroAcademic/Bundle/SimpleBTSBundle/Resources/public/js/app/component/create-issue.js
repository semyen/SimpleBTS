define(function (require) {
    'use strict';

    var __ = require('orotranslation/js/translator');
    var mediator = require('oroui/js/mediator');
    var BaseComponent = require('oroui/js/app/components/base/component');
    var widgetManager = require('oroui/js/widget-manager');
    var messenger = require('oroui/js/messenger');

    var CreateIssueComponent = BaseComponent.extend({
        initialize: function (options) {
            widgetManager.getWidgetInstance(options.wid, function (widget) {
                messenger.notificationFlashMessage('success', __('Issue saved'));
                mediator.trigger('widget_success:' + widget.getAlias());
                mediator.trigger('widget_success:' + widget.getWid());
                widget.remove();
            });
        }
    });

    return CreateIssueComponent;
});
