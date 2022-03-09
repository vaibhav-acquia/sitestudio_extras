<?php

namespace Drupal\sitestudio_extras;

use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Extension\ThemeHandlerInterface;
use Drupal\Core\Asset\LibraryDiscoveryInterface;

/**
 * Class SitestudioExtrasLibrariesManager to get libraries list.
 *
 * @package Drupal\sitestudio_extras\Services
 */
class SitestudioExtrasLibrariesManager {

  /**
   * The module handler service.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * The theme handler.
   *
   * @var \Drupal\Core\Extension\ThemeHandlerInterface
   */
  protected $themeHandler;

  /**
   * The library discovery service.
   *
   * @var \Drupal\Core\Asset\LibraryDiscoveryInterface
   */
  protected $libraryDiscovery;

  /**
   * SitestudioExtrasLibrariesManager constructor.
   *
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler class to use for loading includes.
   * @param \Drupal\Core\Asset\LibraryDiscoveryInterface $library_discovery
   *   The library discovery service.
   * @param \Drupal\Core\Extension\ThemeHandlerInterface $theme_handler
   *   The theme handler.
   */
  public function __construct(ModuleHandlerInterface $module_handler, LibraryDiscoveryInterface $library_discovery, ThemeHandlerInterface $theme_handler) {
    $this->moduleHandler = $module_handler;
    $this->libraryDiscovery = $library_discovery;
    $this->themeHandler = $theme_handler;
  }

  /**
   * Return the list of libraries.
   *
   * @return array
   *   Data array for all libraries.
   */
  public function getData() {
    $libraries = [];
    // Modules List.
    $module_list = array_keys($this->moduleHandler->getModuleList());
    // Get libraries from modules.
    $this->fetchLibraries($module_list, $libraries, 'module');

    // Themes list.
    $all_themes = array_keys($this->themeHandler->listInfo());
    // Get libraries from themes.
    $this->fetchLibraries($all_themes, $libraries, 'theme');

    return $libraries;
  }

  /**
   * Get libraries from modules & themes.
   *
   * @param array $array
   *   Array of enabled modules/themes.
   * @param array $libraries
   *   Array of libraries.
   * @param string $type
   *   String module/theme type.
   */
  public function fetchLibraries(array $array, array &$libraries, $type) {

    foreach ($array as $extension) {

      if ($type = 'module' || ($this->themeHandler->hasUi($extension) && $type = 'theme')) {
        $libs = $this->libraryDiscovery->getLibrariesByExtension($extension);
        if (!empty($libs)) {
          foreach ($libs as $k => $lib) {
            $lib_key = sprintf("%s/%s", $extension, $k);
            $libraries[$lib_key] = $lib_key;
          }
        }
      }
    }
  }

}
