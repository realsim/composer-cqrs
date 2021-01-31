<?php
/**
 * Created by simpson <simpsonwork@gmail.com>
 * Date: 14.01.2021
 * Time: 16:42
 */

namespace Mechanic\ComposerCqrs\Command;

use Mechanic\ComposerCqrs\Generator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\String\UnicodeString;

class MakeCommand extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('cqrs:command');
        $this->setDescription('Creates command and its handler classes for CQRS');
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'Command name')
            ->addArgument('path', InputArgument::OPTIONAL, 'Path to parent directory of Message and MessageHandler folders', 'src')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $commandName = (new UnicodeString($input->getArgument('name')))
            ->camel()
            ->title();
        $handlerName = $commandName.'Handler';

        $rootPath = $this->rootPath();
        $targetDirectory = $this->targetDirectory();
        $commandDirectory = "$targetDirectory/Message/Command";
        $handlerDirectory = "$targetDirectory/MessageHandler/Command";

        $commandNamespace = $this->directoryNamespace($commandDirectory);
        $handlerNamespace = $this->directoryNamespace($handlerDirectory);
        $commandFqcn = $commandNamespace.'\\'.$commandName;
        $handlerFqcn = $handlerNamespace.'\\'.$handlerName;

        $generator = new Generator();
        $generator->writeFile(
            "$rootPath/$commandDirectory/$commandName.php",
            __DIR__.'/../../resources/skeleton/Command.tpl.php',
            [
                'class_name' => $commandName,
                'namespace' => $commandNamespace,
                'handler_fqcn' => $handlerFqcn,
            ]
        );
        $generator->writeFile(
            "$rootPath/$handlerDirectory/$handlerName.php",
            __DIR__.'/../../resources/skeleton/CommandHandler.tpl.php',
            [
                'class_name' => $handlerName,
                'namespace' => $handlerNamespace,
                'command' => $commandName,
                'command_fqcn' => $commandFqcn,
            ]
        );

        $message = <<<MESSAGE
            <fg=white;bg=blue>
            
                Generated classes:
                $commandFqcn
                $handlerFqcn
            </>
            MESSAGE;
        $this->getIO()->write($message);
        $this->getIO()->write('<fg=green>Next: open your command and handler classes and start customizing it.</>');

        return 0;
    }
}
