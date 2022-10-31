<?php

namespace Drupal\zero_logger\Plugin\Zero\Logger;

use Drupal\zero_logger\Annotation\ZeroLogger;
use Drupal\zero_logger\Base\ZeroLoggerBase;
use Drupal\zero_logger\Base\ZeroLoggerInterface;
use Drush\Style\DrushStyle;

/**
 * @ZeroLogger(
 *   id = "drush",
 *   required = {
 *     "input" = "\Symfony\Component\Console\Input\InputInterface",
 *     "output" = "\Symfony\Component\Console\Output\OutputInterface",
 *   },
 * )
 */
class ZeroDrushLogger extends ZeroLoggerBase {

  private DrushStyle $style;

  public function init($args) {
    $this->style = new DrushStyle($args['input'], $args['output']);
    $this->options['level'] = $args['level'] ?? ZeroLoggerInterface::LOGGER_LEVEL_LOG;
  }

  public function log(string|array $message, array $options = []): void {
    $message = $this->prepare($message, array_merge($this->options, $options));
    foreach ($message as $delta => $line) {
      if ($delta === count($message) - 1 && isset($options['newline']) && !$options['newline']) {
        $this->style->write($message);
      } else {
        $this->style->writeln($message);
      }
    }
  }

  public function note(string|array $message, array $options = []): void {
    $message = $this->prepare($message, array_merge($this->options, $options));
    $this->style->block($message, 'NOTE', 'fg=black;bg=cyan');
  }

  public function warning(string|array $message, array $options = []): void {
    $message = $this->prepare($message, array_merge($this->options, $options));
    $this->style->warning($message);
  }

  public function error(string|array $message, array $options = []): void {
    $message = $this->prepare($message, array_merge($this->options, $options));
    $this->style->error($message);
  }

}
