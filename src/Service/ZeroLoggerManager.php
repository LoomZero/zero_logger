<?php

namespace Drupal\zero_logger\Service;

use BadFunctionCallException;
use Drupal\zero_logger\Base\ZeroLoggerInterface;
use Drupal\zero_util\Service\ZeroUtil;

class ZeroLoggerManager {

  private ZeroLoggerPluginManager $logger;
  private ZeroUtil $util;

  public function __construct(ZeroLoggerPluginManager $logger, ZeroUtil $util) {
    $this->logger = $logger;
    $this->util = $util;
  }

  public function getLogger(string $id, array $args = [], array $override_annotation = []): ZeroLoggerInterface {
    $definition = $this->logger->getDefinition($id);

    if (!empty($definition['required'])) {
      $result = $this->util->checkFullRequirements($definition['required'], $args);
      if (!$result['result']) {
        throw new BadFunctionCallException('The args don`t match the requirements of the logger with id "' . $id . '"');
      }
    }

    /** @var ZeroLoggerInterface $plugin */
    $plugin = $this->logger->createInstance($id, $override_annotation);
    $plugin->init($args);
    return $plugin;
  }

}
