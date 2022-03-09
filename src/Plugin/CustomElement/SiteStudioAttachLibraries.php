<?php

namespace Drupal\sitestudio_extras\Plugin\CustomElement;

use Drupal\cohesion_elements\CustomElementPluginBase;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Site Studio element to display the Add to wishlist form.
 *
 * @CustomElement(
 *   id = "sitestudio_extras_attach_libraries",
 *   label = @Translation("Attach Libraries")
 * )
 */
class SiteStudioAttachLibraries extends CustomElementPluginBase {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public function getFields() {

    return [
      'field_libraries_0' => [
        'title' => $this->t('Select Library'),
        'type' => 'textfield',
        'htmlClass' => 'settings-field_libraries',
        'placeholder' => $this->t('Select library from active module, themes to attach to the component.'),
      ],
      'field_libraries_1' => [
        'title' => $this->t('Select Library'),
        'type' => 'textfield',
        'htmlClass' => 'settings-field_libraries',
        'placeholder' => $this->t('Select library from active module, themes to attach to the component.'),
      ],
      'field_libraries_2' => [
        'title' => $this->t('Select Library'),
        'type' => 'textfield',
        'htmlClass' => 'settings-field_libraries',
        'placeholder' => $this->t('Select library from active module, themes to attach to the component.'),
      ],
      'field_libraries_3' => [
        'title' => $this->t('Select Library'),
        'type' => 'textfield',
        'htmlClass' => 'settings-field_libraries',
        'placeholder' => $this->t('Select library from active module, themes to attach to the component.'),
      ],
      'field_libraries_4' => [
        'title' => $this->t('Select Library'),
        'type' => 'textfield',
        'htmlClass' => 'settings-field_libraries',
        'placeholder' => $this->t('Select library from active module, themes to attach to the component.'),
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function render($element_settings, $element_markup, $element_class) {
    // Empty libraries array.
    $libraries = [];
    for ($i = 0; $i < 5; $i++) {
      $field_name = 'field_libraries_' . $i;
      // Check if field has value.
      if (!empty($element_settings[$field_name])) {
        $extension = explode('/', $element_settings[$field_name]);
        if (isset($extension[1]) && !empty($extension[1])) {
          // Check if the library exists.
          $library = \Drupal::service('library.discovery')->getLibraryByName($extension[0], $extension[1]);
          if ($library) {
            $libraries[] = $element_settings[$field_name];
          }
        }
      }
    }
    if (!empty($libraries)) {
      // Attach libraries.
      return [
        '#attached' => [
          'library' => $libraries,
        ],
      ];
    }
  }

}
