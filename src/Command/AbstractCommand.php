<?php
/**
 * Created by simpson <simpsonwork@gmail.com>
 * Date: 14.01.2021
 * Time: 21:54
 */

namespace Mechanic\ComposerCqrs\Command;

use Composer\Command\BaseCommand;
use Symfony\Component\Finder\Finder;

abstract class AbstractCommand extends BaseCommand
{
    protected function vendorPath(): string
    {
        return $this->getComposer()->getConfig()->get('vendor-dir');
    }

    protected function rootPath(): string
    {
        return \dirname($this->vendorPath());
    }

    protected function targetDirectory(): string
    {
        $rootPath = $this->rootPath();

        $finder = Finder::create()
            ->directories()
            ->in($rootPath)
            ->exclude(\basename($this->vendorPath()))
            ->depth('< 4')
            ->name('Message')
            ->name('MessageHandler');
        $pathCandidates = [];
        foreach ($finder as $directory) {
            $path = \trim(\str_replace($rootPath, '', $directory->getPath()), '/\\');

            if (!isset($pathCandidates[$path])) {
                $pathCandidates[$path] = 0;
            }
            $pathCandidates[$path]++;
        }
        $pathCandidates = \array_keys($pathCandidates, 2, true);

        switch (\count($pathCandidates)) {
            case 0:
                ask_user_path:
                $path = $this->getIO()->ask("Where should we create CQRS classes <options=underscore>(directory with Message and MessageHandler folders)</>? \n> ");
                if (null === $path) {
                    $this->getIO()->writeError('<fg=white;bg=red>Path should not be empty</>');
                    goto ask_user_path;
                }
                break;
            case 1:
                $path = \array_shift($pathCandidates);
                if (!$this->getIO()->askConfirmation(sprintf('Should we create CQRS classes in <fg=green>%s</> <options=underscore>(directory with Message and MessageHandler folders)</>? [Y/n] ', $path), true)) {
                    goto ask_user_path;
                }
                break;
            default:
                $choices = \array_merge([0 => 'New Path'], $pathCandidates);
                $userChoice = $this->getIO()->select('Select where we should create CQRS classes <options=underscore>(directory with Message and MessageHandler folders)</>: ', $choices, 0);
                if (0 == $userChoice) {
                    goto ask_user_path;
                }
                $path = $choices[$userChoice];
        }

        $path = \trim(\str_replace('\\', '/', $path), '/');

        return $path;
    }

    protected function directoryNamespace(string $directory): string
    {
        $namespace = $directory;

        $rootPackage = $this->getComposer()->getPackage();
        foreach ($rootPackage->getAutoload() as $prefixes) {
            foreach ($prefixes as $prefix => $replace) {
                if (empty($replace)) {
                    continue;
                }

                $replace = \rtrim($replace, '/\\').'/';
                $namespace = \str_replace($replace, $prefix, $namespace);
            }
        }

        $namespace = \trim(\str_replace('/', '\\', $namespace), '\\');

        return $namespace;
    }
}
