<?php

namespace Bytes\AvatarBundle\Tests\Avatar;

use Bytes\AvatarBundle\Avatar\AvatarTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class AvatarTraitTest
 * @package Bytes\AvatarBundle\Tests\Avatar
 */
class AvatarTraitTest extends TestCase
{
    /**
     *
     */
    public function testGetSetUrlGenerator()
    {
        $trait = $this->getMockForTrait(AvatarTrait::class);
        $urlGenerator = $this->getMockBuilder(UrlGeneratorInterface::class)->getMock();
        $trait->setUrlGenerator($urlGenerator);
        $this->assertInstanceOf(UrlGeneratorInterface::class, $trait->getUrlGenerator());
    }
}