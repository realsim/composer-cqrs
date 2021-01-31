<?php
/**
 * Created by simpson <simpsonwork@gmail.com>
 * Date: 14.01.2021
 * Time: 17:18
 */

namespace Mechanic\ComposerCqrs;

use Composer\Plugin\Capability\CommandProvider as CommandProviderCapability;
use Mechanic\ComposerCqrs\Command\MakeCommand;
use Mechanic\ComposerCqrs\Command\MakeQuery;

class CommandProvider implements CommandProviderCapability
{
    public function getCommands(): array
    {
        return [
            new MakeCommand(),
            new MakeQuery(),
        ];
    }
}
