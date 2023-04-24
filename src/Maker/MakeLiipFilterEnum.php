<?php


namespace Bytes\AvatarBundle\Maker;


use Bytes\AvatarBundle\Imaging\Cache;
use Liip\ImagineBundle\LiipImagineBundle;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Filesystem\Filesystem;
use function Symfony\Component\String\u;

/**
 * Class MakeLiipFilterEnum
 * @package Bytes\AvatarBundle\Maker
 */
class MakeLiipFilterEnum extends AbstractMaker
{
    /**
     * @var string
     */
    private $filePath;

    /**
     * @var string
     */
    private $directory;

    /**
     * MakeLiipFilterEnum constructor.
     * @param Cache $cache
     * @param string $projectDirectory
     */
    public function __construct(private Cache $cache, string $projectDirectory)
    {
        $this->directory = u($projectDirectory)->ensureEnd(DIRECTORY_SEPARATOR)->append('ImagineFilter.php')->toString();
    }

    /**
     * Return the command name for your maker (e.g. make:report).
     */
    public static function getCommandName(): string
    {
        return 'make:liip:filter-enum';
    }

    /**
     * @return string
     */
    public static function getCommandDescription(): string
    {
        return 'Creates a new ImagineFilter.php file';
    }

    /**
     * Configure the command: set description, input arguments, options, etc.
     *
     * By default, all arguments will be asked interactively. If you want
     * to avoid that, use the $inputConfig->setArgumentAsNonInteractive() method.
     *
     * @param Command $command
     * @param InputConfiguration $inputConfig
     */
    public function configureCommand(Command $command, InputConfiguration $inputConfig)
    {
        $command
            ->addOption(
                'filePath',
                'f',
                InputOption::VALUE_REQUIRED,
                sprintf('Full path to config file destination, writes to project root if left empty (e.g. <fg=yellow>%s</>)', '/path/to/ImagineFilter.php')
            )
            ->addOption(
                'deleteFile',
                'd',
                InputOption::VALUE_NONE,
                'Delete file before generating'

            );
    }

    /**
     * Configure any library dependencies that your maker requires.
     * @param DependencyBuilder $dependencies
     */
    public function configureDependencies(DependencyBuilder $dependencies)
    {
        $dependencies->addClassDependency(LiipImagineBundle::class, 'liip-imagine');
    }

    /**
     * Called after normal code generation: allows you to do anything.
     *
     * @param InputInterface $input
     * @param ConsoleStyle $io
     * @param Generator $generator
     */
    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        $deleteFile = $input->getOption('deleteFile');
        $file = $input->getOption('filePath') ?? $this->directory;
        if ($deleteFile) {
            $fs = new Filesystem();

            $comment = '';
            if ($fs->exists($file)) {
                $fs->remove($file);
                $comment = '<fg=red>deleted</>';
            } else {
                $comment = '<fg=yellow>not found</>';
            }
            $io->comment(sprintf(
                    '%s: %s',
                    $comment,
                    $file)
            );
        }

        $filters = $this->cache->getFilters();
        $groups = [];
        foreach ($filters as $i) {
            $filter = u($i);
            $name = $filter->upper()->toString();
            $filter = $filter->beforeLast('x')->beforeLast('_')->camel()->title()->toString();
            $groups[$filter][$name] = $i;
        }

        $extensionClassNameDetails = $generator->createClassNameDetails(
            'ImagineFilter',
            'Enums\\',
            'Enum'
        );
        $generator->generateClass(
            $extensionClassNameDetails->getFullName(),
            __DIR__ . DIRECTORY_SEPARATOR . 'LiipFilter.tpl.php',
            [
                'filters' => $filters,
                'groups' => $groups,
            ]
        );

        $generator->writeChanges();

        $this->writeSuccessMessage($io);
        $io->text([
            'Next: copy your contents over the existing file!',
        ]);
    }
}
