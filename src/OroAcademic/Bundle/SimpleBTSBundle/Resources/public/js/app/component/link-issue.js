/*jslint nomen:true*/
/*global define*/
define(function (require) {
    'use strict';

    var LinkIssueComponent,
        BaseComponent = require('oroui/js/app/components/base/component'),
        __ = require('orotranslation/js/translator'),
        widgetManager = require('oroui/js/widget-manager'),
        mediator = require('oroui/js/mediator'),
        messenger = require('oroui/js/messenger');

    LinkIssueComponent = BaseComponent.extend({
        initialize: function (options) {
            widgetManager.getWidgetInstance(options.wid, function (widget) {
                messenger.notificationFlashMessage('success', __('Issue linked successfully'));
                mediator.trigger('widget_success:' + widget.getAlias());
                mediator.trigger('widget_success:' + widget.getWid());
                widget.remove();
            });
        }
    });

    return LinkIssueComponent;
});
