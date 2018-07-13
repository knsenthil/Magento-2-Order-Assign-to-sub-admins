define([
    'Magento_Ui/js/grid/columns/column',
    'jquery',
    'mage/template',
    'text!Picturesalon_Assignorder/templates/grid/cells/assign/assignpopup.html',
    'Magento_Ui/js/modal/modal'
], function (Column, $, mageTemplate, sendmailPreviewTemplate) {
    'use strict';
 
    return Column.extend({
        defaults: {
            bodyTmpl: 'ui/grid/cells/html',
            fieldClass: {
                'data-grid-html-cell': true
            }
        },
        gethtml: function (row) {
            return row[this.index + '_html'];
        },
        getFormaction: function (row) {
            return row[this.index + '_formaction'];
        },
        getCustomerid: function (row) {
            return row[this.index + '_customerid'];
        },
        getLabel: function (row) {
            return row[this.index + '_html']
        },
        getTitle: function (row) {
            return row[this.index + '_title']
        },
        getSubmitlabel: function (row) {
            return row[this.index + '_submitlabel']
        },
		
        getCancellabel: function (row) {
            return row[this.index + '_cancellabel']
        },
		
		getFormkey: function (row) {
            return row[this.index + '_formkey']
        },
		
		getAdmins: function (row) {
            return  row[this.index + '_admins']
        },
		getAssigned: function (row) {
			return  row[this.index + '_assigned']
		},
		
        preview: function (row) {
            var modalHtml = mageTemplate(
                sendmailPreviewTemplate,
                {
                    html: this.gethtml(row), 
                    title: this.getTitle(row), 
                    label: this.getLabel(row), 
                    formaction: this.getFormaction(row),
                    customerid: this.getCustomerid(row),
                    submitlabel: this.getSubmitlabel(row), 
                    cancellabel: this.getCancellabel(row), 
					formkey: this.getFormkey(row), 
					admins: this.getAdmins(row), 
					assigned: this.getAssigned(row), 
                    linkText: $.mage.__('Go to Details Page')
                }
            );
            var previewPopup = $('<div/>').html(modalHtml);
            previewPopup.modal({
                title: this.getTitle(row),
                innerScroll: true,
                modalClass: '_image-box',
                buttons: []}).trigger('openModal');
        },
        getFieldHandler: function (row) {
            return this.preview.bind(this, row);
        }
    });
});