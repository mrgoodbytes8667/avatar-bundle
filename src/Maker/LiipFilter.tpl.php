<?= "<?php\n" ?>

namespace <?= $namespace; ?>;


use Bytes\EnumSerializerBundle\Enums\Enum;

/**
 * Class <?= $class_name; ?><?= "\n" ?>
 * @package <?= $namespace; ?><?= "\n" ?>
 *
<?php foreach ($filters as $filter): ?>
 * @method static self <?= $filter; ?>()
<?php endforeach; ?>
*/
class <?= $class_name; ?> extends Enum<?= "\n" ?>
{
}