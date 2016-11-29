<?php
namespace Gwa\Wpsh\Process;

interface CommandContract
{
  public function execute();
  public function build();
}
