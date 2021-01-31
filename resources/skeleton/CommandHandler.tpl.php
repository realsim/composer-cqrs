<?= "<?php\n" ?>

namespace <?= $namespace ?>;

use <?= $command_fqcn ?>;

/**
 * @see \<?= "$command_fqcn\n" ?>
 */
class <?= "$class_name\n" ?>
{
    public function __invoke(<?= $command ?> $command)
    {
    }
}
