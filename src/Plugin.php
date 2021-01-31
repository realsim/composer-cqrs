<?php
/**
 * Created by simpson <simpsonwork@gmail.com>
 * Date: 14.01.2021
 * Time: 16:13
 */

namespace Mechanic\ComposerCqrs;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\Capable;
use Composer\Plugin\PluginInterface;
use Composer\Plugin\Capability\CommandProvider as CommandProviderCapability;

class Plugin implements PluginInterface, Capable
{
    public function activate(Composer $composer, IOInterface $io)
    {
    }

    public function deactivate(Composer $composer, IOInterface $io)
    {
    }

    public function uninstall(Composer $composer, IOInterface $io)
    {
    }

    public function getCapabilities(): array
    {
        return [
            CommandProviderCapability::class => CommandProvider::class,
        ];
    }
}
