<?php
/**
 * Created by simpson <simpsonwork@gmail.com>
 * Date: 14.01.2021
 * Time: 22:19
 */

namespace Mechanic\ComposerCqrs;

use Symfony\Component\Filesystem\Filesystem;

class Generator
{
    private Filesystem $fs;

    public function __construct()
    {
        $this->fs = new Filesystem();
    }

    public function writeFile(string $targetPath, string $templatePath, array $parameters)
    {
        $contents = $this->parseTemplate($templatePath, $parameters);

        $this->fs->dumpFile($targetPath, $contents);
    }

    private function parseTemplate(string $templatePath, array $parameters): string
    {
        ob_start();
        extract($parameters, EXTR_SKIP);
        include $templatePath;

        return ob_get_clean();
    }
}
