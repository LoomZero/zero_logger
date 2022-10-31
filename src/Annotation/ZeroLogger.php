<?php

namespace Drupal\zero_logger\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * @see \Drupal\zero_importer\Service\ZeroImporterPluginManager
 * @see plugin_api
 *
 * @Annotation
 */
class ZeroLogger extends Plugin {

  /** @var string */
  public $id;

  /** @var array */
  public $required;

}
