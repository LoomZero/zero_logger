<?php

namespace Drupal\zero_logger\Handler;

use Drupal;
use Drupal\zero_logger\Base\ZeroLoggerInterface;

class ZeroLoggerHandler {

  protected array $loggers = [];
  protected array $options = [];

  public function __construct(ZeroLoggerHandler $parent = NULL) {
    if ($parent !== NULL) {
      $this->loggers = $parent->loggers;
      $this->options = $parent->options;
    }
  }

  public function createChild(string $prompt = NULL): ZeroLoggerHandler {
    $logger = new ZeroLoggerHandler($this);
    if ($prompt) {
      $prompts = $this->getOption('prompts');
      if ($prompts) {
        $prompts[] = $prompt;
      }
      $logger->setOption('prompts', $prompts);
    }
    return $logger;
  }

  public function createLogger(string $id, array $args = [], array $override_annotation = []): self {
    /** @var \Drupal\zero_logger\Service\ZeroLoggerManager $manager */
    $manager = Drupal::service('zero_logger.manager');
    $this->addLogger($manager->getLogger($id, $args, $override_annotation));
    return $this;
  }

  public function addLogger(ZeroLoggerInterface $logger): self {
    $this->loggers[] = $logger;
    return $this;
  }

  public function getloggers(): array {
    return $this->loggers;
  }

  public function each(callable $callback): self {
    foreach ($this->getloggers() as $logger) {
      $callback($logger);
    }
    return $this;
  }

  public function getLogLevel(int|string $level): int {
    if (is_string($level)) {
      switch (strtolower($level)) {
        case 'log':
          return ZeroLoggerInterface::LOGGER_LEVEL_LOG;
        case 'note':
          return ZeroLoggerInterface::LOGGER_LEVEL_NOTE;
        case 'warning':
          return ZeroLoggerInterface::LOGGER_LEVEL_WARNING;
        case 'error':
          return ZeroLoggerInterface::LOGGER_LEVEL_ERROR;
      }
    }
    return $level;
  }

  public function log(string|array $message, array $options = []): self {
    $options = array_merge($this->options, $options);
    $this->each(function($logger) use ($message, $options) {
      $level = $logger->option('level', $options);
      if ($level !== NULL) $level = $this->getLogLevel($level);
      if ($level <= ZeroLoggerInterface::LOGGER_LEVEL_LOG) {
        $logger->log($message, $options);
      }
    });
    return $this;
  }

  public function note(string|array $message, array $options = []): self {
    $options = array_merge($this->options, $options);
    $this->each(function($logger) use ($message, $options) {
      $level = $logger->option('level', $options);
      if ($level !== NULL) $level = $this->getLogLevel($level);
      if ($level <= ZeroLoggerInterface::LOGGER_LEVEL_NOTE) {
        $logger->note($message, $options);
      }
    });
    return $this;
  }

  public function warning(string|array $message, array $options = []): self {
    $options = array_merge($this->options, $options);
    $this->each(function($logger) use ($message, $options) {
      $level = $logger->option('level', $options);
      if ($level !== NULL) $level = $this->getLogLevel($level);
      if ($level <= ZeroLoggerInterface::LOGGER_LEVEL_WARNING) {
        $logger->warning($message, $options);
      }
    });
    return $this;
  }

  public function error(string|array $message, array $options = []): self {
    $options = array_merge($this->options, $options);
    $this->each(function($logger) use ($message, $options) {
      $level = $logger->option('level', $options);
      if ($level !== NULL) $level = $this->getLogLevel($level);
      if ($level <= ZeroLoggerInterface::LOGGER_LEVEL_ERROR) {
        $logger->error($message, $options);
      }
    });
    return $this;
  }

  public function getOption(string $key) {
    return $this->options[$key] ?? NULL;
  }

  public function setOption(string $key, $value): self {
    $this->options[$key] = $value;
    return $this;
  }

  public function setOptions(array $options): self {
    $this->options = $options;
    return $this;
  }

  /**
   * Set options for the logger execution.
   *
   * @param array $options
   * @param string|NULL $id
   *
   * @return $this
   */
  public function applyOptions(array $options = [], string $id = NULL): self {
    $this->each(function($logger) use ($options, $id) {
      if ($id === NULL || $logger->getPluginId() === $id) {
        $logger->applyOptions($options);
      }
    });
    return $this;
  }

}
