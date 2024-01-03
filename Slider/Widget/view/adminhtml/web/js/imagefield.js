/**
 * imagefield
 */
define([
    'jquery',
    'jquery/ui'
], function ($) {
    'use strict';

    $.widget('slider_widgetextrafields.imagefield', {
        options: {
            imagePathInputSelector: null,
            imagePreviewDivSelector: null,
            imageDeleteButtonSelector: null,
            mediaUrl: null
        },

        _create: function() {
            this.getImagePathInput().on('change', $.proxy(this.updateImage, this));
            this.getDeleteButton().on('click', $.proxy(this.deleteImage, this));
        },

        getImagePathInput: function() {
            return this.element.find(this.options.imagePathInputSelector).first();
        },

        getDeleteButton: function() {
            return this.element.find(this.options.imageDeleteButtonSelector).first();
        },

        getPreviewImageDiv: function() {
            return this.element.find(this.options.imagePreviewDivSelector).first();
        },

        getLinkElement: function() {
            return this.getPreviewImageDiv().find('a').first();
        },

        getImgElement: function() {
            return this.getPreviewImageDiv().find('img').first();
        },

        updateImage: function() {
            var newImagePath = this.getImagePathInput().val();
            this.getLinkElement().attr('href', newImagePath);
            this.getImgElement().attr('src', newImagePath);
            this.getPreviewImageDiv().show();
        },

        deleteImage: function() {
            this.getImagePathInput().val('');
            this.getLinkElement().attr('href', '');
            this.getImgElement().attr('src', '');
            this.getPreviewImageDiv().hide();
        }

    });

    return $.slider_widgetextrafields.imagefield;
});
