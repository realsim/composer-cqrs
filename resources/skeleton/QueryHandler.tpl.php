<?= "<?php\n" ?>

namespace <?= $namespace ?>;

use <?= $query_fqcn ?>;

/**
 * @see \<?= "$query_fqcn\n" ?>
 */
class <?= "$class_name\n" ?>
{
    public function __invoke(<?= $query ?> $query)
    {
    }
}
