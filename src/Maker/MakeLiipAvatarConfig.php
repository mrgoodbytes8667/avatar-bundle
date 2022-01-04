<?php


namespace Bytes\AvatarBundle\Maker;


use Bytes\AvatarBundle\Enums\AvatarSize;
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
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use function Symfony\Component\String\u;

/**
 * Class MakeLiipAvatarConfig
 * @package Bytes\AvatarBundle\Maker
 */
class MakeLiipAvatarConfig extends AbstractMaker
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var string
     */
    private $filePath;

    /**
     * MakeLiipAvatarConfig constructor.
     * @param UrlGeneratorInterface $urlGenerator
     * @param ValidatorInterface $validator
     */
    public function __construct(UrlGeneratorInterface $urlGenerator, ValidatorInterface $validator, string $projectDirectory)
    {
        $this->urlGenerator = $urlGenerator;
        $this->validator = $validator;
        $this->directory = u($projectDirectory)->ensureEnd(DIRECTORY_SEPARATOR)->append('liip_imagine.yaml')->toString();
    }

    /**
     * Return the command name for your maker (e.g. make:report).
     */
    public static function getCommandName(): string
    {
        return 'make:liip:avatar';
    }

    /**
     * @return string
     */
    public static function getCommandDescription(): string
    {
        return 'Creates a new liip_imagine.yaml file';
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
                sprintf('Full path to config file destination, writes to project root if left empty (e.g. <fg=yellow>%s</>)', '/path/to/liip_config.yaml')
            )
            ->addOption(
                'deleteFile',
                'd',
                InputOption::VALUE_NONE,
                'Delete config file before generating'

            )
            ->addOption(
                'wrapperUrl',
                'u',
                InputOption::VALUE_REQUIRED,
                sprintf('Stream wrapper base URL (otherwise uses the URL generator) (e.g. <fg=yellow>%s</>)', 'https://www.example.com/')
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

        $url = $input->getOption('wrapperUrl');
        if (!empty($url)) {
            $constraints = [
                new Assert\Url([
                    'message' => 'The supplied value {{ value }} is not a valid wrapper URL',
                    'protocols' => ['http', 'https'],
                    'relativeProtocol' => false,
                ]),
                new Assert\NotBlank()
            ];

            // use the validator to validate the value
            $errors = $this->validator->validate(
                $url,
                $constraints
            );

            if (0 !== count($errors)) {
                // this is *not* a valid url
                $errorMessage = $errors[0]->getMessage();

                $io->error($errorMessage);
                return;
            }
        } else {

            $parsedFullUrl = parse_url($this->urlGenerator->generate('bytes_avatarbundle_select2', [], UrlGeneratorInterface::ABSOLUTE_URL));
            $url = $parsedFullUrl['scheme'] . '://';
            if (array_key_exists('user', $parsedFullUrl)) {
                $url .= $parsedFullUrl['user'];
                if (array_key_exists('pass', $parsedFullUrl)) {
                    $url .= ':' . $parsedFullUrl['pass'] . '@';
                }
            }
            $url .= $parsedFullUrl['host'];
            if (array_key_exists('port', $parsedFullUrl)) {
                $url = sprintf('%s:%d', $url, $parsedFullUrl['port']);
            }
            $url .= DIRECTORY_SEPARATOR;
        }
        $url = u($url)->ensureEnd(DIRECTORY_SEPARATOR)->toString();

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

        $gravatarSizes = [];
        foreach (AvatarSize::cases() as $size) {
            $gravatarSizes[] = ['x' => $size, 'y' => $size];
        }

        $generator->generateFile($file, __DIR__ . DIRECTORY_SEPARATOR . 'liip_imagine.tpl.php', [
            'gravatarSizes' => $gravatarSizes,
            'url' => $url,
        ]);

        $generator->writeChanges();

        $this->writeSuccessMessage($io);
        $io->text([
            'Next: copy your contents over the existing liip bundle!',
        ]);
    }


}