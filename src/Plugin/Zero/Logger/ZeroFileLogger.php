<?php

namespace Drupal\zero_logger\Plugin\Zero\Logger;

use Drupal\zero_logger\Annotation\ZeroLogger;
use Drupal\zero_logger\Base\ZeroLoggerBase;
use Drupal\zero_logger\Base\ZeroLoggerInterface;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

/**
 * @ZeroLogger(
 *   id = "file",
 *   required = {
 *     "path" = "string",
 *   },
 * )
 */
class ZeroFileLogger extends ZeroLoggerBase {

  private string $path = '';
  private string $date = '';
  private string $channel = '';
  private array $format = [];
  private ?LoggerInterface $log = NULL;

  public function init($args) {
    $this->path = $args['path'];
    $this->date = $args['date'] ?? NULL;
    $this->channel = $args['channel'] ?? 'file';
    $this->options['level'] = $args['level'] ?? ZeroLoggerInterface::LOGGER_LEVEL_LOG;
    $this->format['message'] = $args['format']['message'] ?? '[%datetime%] %channel%/%level_name% %message% %context% %extra%\n';
    $this->format['message'] = str_replace('\n', "\n", $this->format['message']);
    $this->format['date'] = $args['format']['date'] ?? 'Y-m-d H:i:s';
  }

  public function getFilePath(): string {
    $basename = basename($this->path);
    $date = '';
    if ($this->date) {
      $date = date($this->date) . '-';
    }
    $path = $this->path;
    if (str_starts_with($path, '/') || str_starts_with($path, '\\')) {
      $path = substr($path, 1);
    }
    return '../' . substr($path, 0, strlen($basename) * -1) . $date . $basename;
  }

  public function getFileLogger(): LoggerInterface {
    if ($this->log === NULL) {
      $handler = new StreamHandler($this->getFilePath());
      $handler->setFormatter(new LineFormatter($this->format['message'], $this->format['date'], TRUE, TRUE));
      $this->log = new Logger($this->channel, [$handler]);
    }
    return $this->log;
  }

  public function log(string|array $message, array $options = []): void {
    $message = $this->prepare($message, array_merge($this->options, $options));
    foreach ($message as $line) {
      $this->getFileLogger()->info($line);
    }
  }

  public function note(string|array $message, array $options = []): void {
    $message = $this->prepare($message, array_merge($this->options, $options));
    foreach ($message as $line) {
      $this->getFileLogger()->notice($line);
    }
  }

  public function warning(string|array $message, array $options = []): void {
    $message = $this->prepare($message, array_merge($this->options, $options));
    foreach ($message as $line) {
      $this->getFileLogger()->warning($line);
    }
  }

  public function error(string|array $message, array $options = []): void {
    $message = $this->prepare($message, array_merge($this->options, $options));
    foreach ($message as $line) {
      $this->getFileLogger()->error($line);
    }
  }

}
