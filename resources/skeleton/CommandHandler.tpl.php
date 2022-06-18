<?= "<?php\n" ?>

namespace <?= $namespace ?>;

use <?= $command_fqcn ?>;

/**
 * @see \<?= "$command_fqcn\n" ?>
 */
<?php if (class_exists('\Mechanic\CqrsKit\Attribute\CommandHandler'))
    echo '#[\Mechanic\CqrsKit\Attribute\CommandHandler]', PHP_EOL; ?>
class <?= "$class_name\n" ?>
{
    public function __invoke(<?= $command ?> $command)
    {
    }
}
