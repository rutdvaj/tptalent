/**
 * Homepage Content meta boxes: repeater add/remove + WP media picker.
 * Event delegation throughout so dynamically-added rows work with no rebind.
 */
(function ($) {
  'use strict';

  var rowCounters = {};

  function nextIndex(section, rep) {
    var key = section + '::' + rep;
    if (!(key in rowCounters)) {
      var $rep = $('.tp-repeater[data-section="' + section + '"][data-rep="' + rep + '"]');
      rowCounters[key] = $rep.find('.tp-repeater__rows > .tp-repeater__row').length;
    }
    rowCounters[key] += 1;
    return rowCounters[key] - 1;
  }

  $(document).on('click', '.tp-repeater__add', function () {
    var $repeater = $(this).closest('.tp-repeater');
    var section = $repeater.data('section');
    var rep = $repeater.data('rep');
    var idx = nextIndex(section, rep);
    var html = $repeater.find('.tp-repeater__template').html().split('__INDEX__').join(idx);
    $repeater.find('.tp-repeater__rows').append(html);
  });

  $(document).on('click', '.tp-repeater__remove', function () {
    $(this).closest('.tp-repeater__row').remove();
  });

  $(document).on('click', '.tp-image-select', function (e) {
    e.preventDefault();
    var $field = $(this).closest('.tp-image-field');
    var frame = wp.media({ title: 'Select image', multiple: false });
    frame.on('select', function () {
      var att = frame.state().get('selection').first().toJSON();
      var thumb = (att.sizes && (att.sizes.thumbnail || att.sizes.medium) ? (att.sizes.thumbnail || att.sizes.medium).url : att.url);
      $field.find('.tp-image-preview').attr('src', thumb).show();
      $field.find('.tp-image-id').val(att.id);
      $field.find('.tp-image-select').text('Change image');
      $field.find('.tp-image-clear').show();
    });
    frame.open();
  });

  $(document).on('click', '.tp-image-clear', function (e) {
    e.preventDefault();
    var $field = $(this).closest('.tp-image-field');
    $field.find('.tp-image-preview').hide().attr('src', '');
    $field.find('.tp-image-id').val('0');
    $field.find('.tp-image-select').text('Select image');
    $(this).hide();
  });
})(jQuery);
