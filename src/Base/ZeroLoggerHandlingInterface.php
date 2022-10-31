<?php

namespace Drupal\zero_logger\Base;

use Drupal\zero_logger\Handler\ZeroLoggerHandler;

interface ZeroLoggerHandlingInterface {

  public function logger(): ZeroLoggerHandler;

  public function setLogger(ZeroLoggerHandler $logger): self;

}
