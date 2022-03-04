(function ($) {
  'use strict';

  /**
   * Search for libraries.
   *
   * Triggers when select libraries text field is changed by user.
   */
  $('body').on('input', '[id^="settings-field_libraries_"]', function () {

    // Call autocomplete function on select libraries text field.
    var librariesData = drupalSettings.sitestudio_extras.libraries;
    $('[id^=settings-field_libraries_]').autocomplete({
      source: function (request, response) {
        var results = handleAutocomplete(request.term);
        if (!results.length) {
          results = [{ name: 'No Results' }];
        }

        response(results);
      },
      /**
       * Triggers when suggestion menu is opened/updated on typeahead.
       * Add CSS properties on open event to style autocomplete widget.
       */
      open: function () {
        // Calculate effective z-index of the layout wrapper.
        var zIndex = $('.modal, .coh-layout-canvas-settings, .in').css('z-index');
        var name = $(this).attr('name');
        // Find field [name="field_libraries_0"] DOM position:bottom relative to the viewport.
        var element = $('[name="' + name + '"]');
        var bottom = element[0].getBoundingClientRect().bottom;

        // top: bottom places library Suggestions List at the bottom of autocomplete text field.
        // z-index : zIndex makes the suggestion list visible on site studio settings modal.
        $('.sitestudio-extras-autocomplete-list').css({
          'z-index': zIndex,
          'position': 'fixed',
          'top': bottom,
          'display': 'block',
        });

        return false;
      },
      select: function (event, ui) {
        $(this).val(ui.item.value);
        $('ul.sitestudio-extras-autocomplete-list').empty();
        return false;
      },
      focus: function (event, ui) {
        $(this).val(ui.item.value);
        return false;
      }
    }).each(function () {
      $(this).data("ui-autocomplete")._renderItemData = function (ul, item) {
        ul.addClass('sitestudio-extras-autocomplete-list');
        if (item.id == '0') {
          return jQuery("<li></li>")
            .data("item.autocomplete", item)
            .append("<div class='no-result'>" + item.value + "</div>")
            .appendTo(ul);
        } else {
          return this._renderItem(ul, item).data("ui-autocomplete-item", item);
        }

      };
    });

    /**
     * Returns suggestions based on search keyword.
     */
    function handleAutocomplete(term) {
      var keywords = term.split(' '); // Split search terms into list.
      var suggestions = [];
      Object.entries(librariesData).forEach(([key, element]) => {
        var label = element.toLowerCase();

        // Add exact matches.
        if (label.indexOf(term.toLowerCase()) >= 0) {
          suggestions.push(element);
        }
        else {
          // Add suggestions where it matches all search terms.
          var matchCount = 0;
          keywords.forEach(function (keyword) {
            if (label.indexOf(keyword.toLowerCase()) >= 0) {
              matchCount++;
            }
          });
          if (matchCount == keywords.length) {
            suggestions.push(element);
          }
        }
      });
      return suggestions;
    }
    
  })

}) (jQuery);