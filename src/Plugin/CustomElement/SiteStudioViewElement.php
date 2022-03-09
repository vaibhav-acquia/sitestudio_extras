<?php

namespace Drupal\sitestudio_extras\Plugin\CustomElement;

use Drupal\cohesion_elements\CustomElementPluginBase;
use Drupal\views\Views;

/**
 * View element plugin for Site Studio.
 *
 * @package Drupal\cohesion\Plugin\CustomElement
 *
 * @CustomElement(
 *   id = "sitestudio_view_element",
 *   label = @Translation("Site Studio View element")
 * )
 */
class SiteStudioViewElement extends CustomElementPluginBase {

  /**
   * {@inheritdoc}
   */
  public function getFields() {
    return [
      'view' => [
        'htmlClass' => 'col-xs-6',
        'title' => 'View',
        'type' => 'select',
        'options' => $this->getViews(),
        'required' => TRUE,
        'validationMessage' => 'This field is required.',
      ],
      'view_mode' => [
        'htmlClass' => 'col-xs-6',
        'title' => 'View mode',
        'type' => 'select',
        'options' => $this->getViewModes(),
        'required' => TRUE,
        'validationMessage' => 'This field is required.',
      ],
      'number_per_page' => [
        'htmlClass' => 'col-xs-6',
        'title' => 'Items per page',
        'type' => 'textfield',
        'required' => TRUE,
        'validationMessage' => 'This field is required.',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function render($element_settings, $element_markup, $element_class, $element_context = []) {
    // Get selected View from form.
    $view_input = explode(':', $element_settings['view']);

    // Set View and display.
    $view = Views::getView($view_input[0]);
    $view->setDisplay($view_input[1]);

    // Get the number of pages that was passed
    // from the field and pass it as a views argument.
    $view->setItemsPerPage($element_settings['number_per_page']);

    // Execute the view.
    $view->preExecute();
    $view->execute();
    $view->render();

    // Set View mode.
    if ($element_settings['view_mode'] !== NULL) {
      $view->rowPlugin->options['view_mode'] = $element_settings['view_mode'];
    }

    // Render the element.
    return [
      '#theme' => 'sitestudio_view_element_template',
      '#elementSettings' => $element_settings,
      '#elementMarkup' => $element_markup,
      '#elementClass' => $element_class,
      '#sitestudioViewElement' => $view->buildRenderable(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getViews() {
    return Views::getViewsAsOptions(FALSE, $filter = 'enabled');
  }

  /**
   * {@inheritdoc}
   */
  public function getViewModes() {
    return \Drupal::service('entity_display.repository')->getViewModeOptions('node');
  }

}
