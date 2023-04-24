<?php


namespace Bytes\AvatarBundle\Request;


use Bytes\AvatarBundle\Entity\UserInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UserParamConverter
 * @package Bytes\AvatarBundle\Request
 * @deprecated since 0.10.0, replace with a ValueResolver: https://symfony.com/doc/current/controller/value_resolver.html
 */
class UserParamConverter implements ParamConverterInterface
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    private string $userClass;

    /**
     * UserParamConverter constructor.
     * @param EntityManagerInterface $entityManager
     * @param string $userClass
     */
    public function __construct(EntityManagerInterface $entityManager, string $userClass)
    {
        $this->entityManager = $entityManager;
        $this->userClass = $userClass;
    }

    /**
     * Checks if the object is supported.
     *
     * @param ParamConverter $configuration
     *
     * @return bool True if the object is supported, else false
     */
    public function supports(ParamConverter $configuration)
    {
        if (null === $configuration->getClass()) {
            return false;
        }

        return is_subclass_of($configuration->getClass(), UserInterface::class) || $configuration->getClass() === UserInterface::class;
    }

    /**
     * Stores the object in the request.
     *
     * @param Request $request
     * @param ParamConverter $configuration Contains the name, class and options of the object
     *
     * @return bool|void True if the object has been successfully set, else false
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $param = $configuration->getName();

        // If the param already exists, we're done and it's already set
        if ($request->attributes->has($param)) {
            return true;
        }

        $value = $request->attributes->get('id');

        if (!$value && $configuration->isOptional()) {
            $request->attributes->set($param, null);

            return true;
        }

        $user = $this->entityManager->getRepository($this->userClass)->find($value);

        if (empty($user)) {
            return false;
        }

        $request->attributes->set($param, $user);

        return true;
    }
}
