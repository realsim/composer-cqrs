<?= "<?php\n" ?>

namespace <?= $namespace ?>;

use <?= $query_fqcn ?>;

/**
 * @see \<?= "$query_fqcn\n" ?>
 */
<?php if (class_exists('\Mechanic\CqrsKit\Attribute\QueryHandler'))
    echo '#[\Mechanic\CqrsKit\Attribute\QueryHandler]', PHP_EOL; ?>
class <?= "$class_name\n" ?>
{
    public function __invoke(<?= $query ?> $query)
    {
    }
}
