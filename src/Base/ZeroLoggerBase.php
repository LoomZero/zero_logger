<?php

namespace Drupal\zero_logger\Base;

use Drupal\Core\Plugin\PluginBase;
use Drupal\zero_util\Data\DataArray;

abstract class ZeroLoggerBase extends PluginBase implements ZeroLoggerInterface {

  protected array $options = [];

  public function id(): string {
    return $this->getPluginId();
  }

  public function applyOptions(array $options = []): self {
    $this->options = array_merge($this->options, $options);
    return $this;
  }

  public function setOptions(array $options = []): self {
    $this->options = $options;
    return $this;
  }

  public function getOptions(): array {
    return $this->options;
  }

  public function option(string $key, array $override = []) {
    return array_merge($this->options, $override)[$key] ?? NULL;
  }

  /**
   * @param string|array $message
   * @param array $options
   *
   * @return string[]
   */
  protected function prepare(string|array $message, array $options = []): array {
    if (is_string($message)) $message = [$message];
    foreach ($message as $index => $value) {
      if (!empty($options['placeholders'])) {
        $value = DataArray::replace($value, function(string $value, string $match, string $root) use ($options) {
          return DataArray::getNested($options['placeholders'], $match, '');
        });
      }
      if (!empty($options['prompts'])) $value = implode(' > ', $options['prompts']) . ': ' . $value;
      $value = ($options['prefix'] ?? '') . $value . ($options['suffix'] ?? '');
      $message[$index] = $value;
    }
    return $message;
  }

}
