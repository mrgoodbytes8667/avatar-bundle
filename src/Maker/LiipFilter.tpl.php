<?= "<?php\n" ?>

namespace <?= $namespace; ?>;


use Bytes\EnumSerializerBundle\Enums\StringBackedEnumInterface;
use Bytes\EnumSerializerBundle\Enums\StringBackedEnumTrait;
use JetBrains\PhpStorm\Deprecated;

/**
 * Class <?= $class_name; ?><?= "\n" ?>
 * @package <?= $namespace; ?><?= "\n" ?>
*/
enum <?= $class_name; ?>: string implements StringBackedEnumInterface<?= "\n" ?>
{
    use StringBackedEnumTrait;<?= "\n" ?>
<?php foreach ($groups as $group => $value): ?>
<?php foreach ($value as $name => $filter): ?>
    case <?= $name; ?> = '<?= $filter; ?>';
<?php endforeach; ?>
<?php endforeach; ?>

<?php foreach ($groups as $group => $value): ?>
    /**
     * @return ImagineFilterEnum[]
     */
    public static function get<?= $group; ?>s(): array
    {
        return [
<?php foreach ($value as $name => $filter): ?>
            <?= $class_name; ?>::<?= $name; ?>,
<?php endforeach; ?>
        ];
    }
<?php endforeach; ?><?= "\n" ?>

<?php foreach ($groups as $group => $value): ?>
<?php foreach ($value as $name => $filter): ?>
    #[Deprecated('Use the enum variant', '%class%::<?= $name; ?>')]
    public static function <?= $filter; ?>(): <?= $class_name; ?><?= "\n" ?>
    {
        return <?= $class_name; ?>::<?= $name; ?>;
    }<?= "\n" ?><?= "\n" ?>
<?php endforeach; ?>
<?php endforeach; ?>
}
