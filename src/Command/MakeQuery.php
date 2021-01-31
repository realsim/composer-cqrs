<?php
/**
 * Created by simpson <simpsonwork@gmail.com>
 * Date: 14.01.2021
 * Time: 16:43
 */

namespace Mechanic\ComposerCqrs\Command;

use Mechanic\ComposerCqrs\Generator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\String\UnicodeString;

class MakeQuery extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('cqrs:query');
        $this->setDescription('Creates query and its handler classes for CQRS');
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'Query name')
            ->addArgument('path', InputArgument::OPTIONAL, 'Path to parent directory of Message and MessageHandler folders', 'src')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $queryName = (new UnicodeString($input->getArgument('name')))
            ->camel()
            ->title();
        $handlerName = $queryName.'Handler';

        $rootPath = $this->rootPath();
        $targetDirectory = $this->targetDirectory();
        $queryDirectory = "$targetDirectory/Message/Query";
        $handlerDirectory = "$targetDirectory/MessageHandler/Query";

        $queryNamespace = $this->directoryNamespace($queryDirectory);
        $handlerNamespace = $this->directoryNamespace($handlerDirectory);
        $queryFqcn = $queryNamespace.'\\'.$queryName;
        $handlerFqcn = $handlerNamespace.'\\'.$handlerName;

        $generator = new Generator();
        $generator->writeFile(
            "$rootPath/$queryDirectory/$queryName.php",
            __DIR__.'/../../resources/skeleton/Query.tpl.php',
            [
                'class_name' => $queryName,
                'namespace' => $queryNamespace,
                'handler_fqcn' => $handlerFqcn,
            ]
        );
        $generator->writeFile(
            "$rootPath/$handlerDirectory/$handlerName.php",
            __DIR__.'/../../resources/skeleton/QueryHandler.tpl.php',
            [
                'class_name' => $handlerName,
                'namespace' => $handlerNamespace,
                'query' => $queryName,
                'query_fqcn' => $queryFqcn,
            ]
        );

        $message = <<<MESSAGE
            <fg=white;bg=blue>
            
                Generated classes:
                $queryFqcn
                $handlerFqcn
            </>
            MESSAGE;
        $this->getIO()->write($message);
        $this->getIO()->write('<fg=green>Next: open your query and handler classes and start customizing it.</>');

        return 0;
    }
}
