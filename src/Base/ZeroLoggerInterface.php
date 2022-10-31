<?php

namespace Drupal\zero_logger\Base;

use Drupal\Component\Plugin\PluginInspectionInterface;

interface ZeroLoggerInterface extends PluginInspectionInterface {

  public const LOGGER_LEVEL_LOG = 0;
  public const LOGGER_LEVEL_NOTE = 10;
  public const LOGGER_LEVEL_WARNING = 20;
  public const LOGGER_LEVEL_ERROR = 30;

  public function id(): string;

  public function init($args);

  public function applyOptions(array $options = []): self;

  public function setOptions(array $options = []): self;

  public function getOptions(): array;

  public function option(string $key, array $override = []);

  public function log(string|array $message, array $options = []): void;

  public function note(string|array $message, array $options = []): void;

  public function warning(string|array $message, array $options = []): void;

  public function error(string|array $message, array $options = []): void;

}
