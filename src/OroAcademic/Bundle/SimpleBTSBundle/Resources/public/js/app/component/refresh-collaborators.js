/*jslint nomen:true*/
/*global define*/
define(function (require) {
    'use strict';

    var RefreshCollaboratorsComponent,
        BaseComponent = require('oroui/js/app/components/base/component'),
        mediator = require('oroui/js/mediator');

    RefreshCollaboratorsComponent = BaseComponent.extend({
        initialize: function (options) {
            mediator.subscribe('widget_success:note-dialog', function () {
                mediator.trigger('datagrid:doRefresh:oro_academic_sbts_issue_collaborators_datagrid');
            });
        }
    });

    return RefreshCollaboratorsComponent;
});
