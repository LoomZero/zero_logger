<?php

namespace Drupal\zero_logger\Service;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;

class ZeroLoggerPluginManager extends DefaultPluginManager {

  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/Zero/Logger', $namespaces, $module_handler,
      'Drupal\zero_logger\Base\ZeroLoggerInterface',
      'Drupal\zero_logger\Annotation\ZeroLogger');

    $this->alterInfo('zero_logger_info');
    $this->setCacheBackend($cache_backend, 'zero_logger_info');
  }

}
