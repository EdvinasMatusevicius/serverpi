<?php

declare(strict_types = 1);

namespace App\Helpers;

/**
 * Class ShellCmdBuilder
 * @package App\Helpers
 */
class ShellCmdBuilder
{
 public function git(string $command, ?string $url = null): string
 {
     // all command builder
     return 'cd var/www/serverpi && git '.$command;
 }
}