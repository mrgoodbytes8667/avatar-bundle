# avatar-bundle
[![Packagist Version](https://img.shields.io/packagist/v/mrgoodbytes8667/avatar-bundle?logo=packagist&logoColor=FFF&style=flat)](https://packagist.org/packages/mrgoodbytes8667/avatar-bundle)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/mrgoodbytes8667/avatar-bundle?logo=php&logoColor=FFF&style=flat)](https://packagist.org/packages/mrgoodbytes8667/avatar-bundle)
![Symfony Versions Supported](https://img.shields.io/endpoint?url=https%3A%2F%2Fshields.mrgoodbytes.dev%2Fshield%2Fsymfony%2F%255E5.3%2520%257C%2520%255E6.0&logoColor=FFF&style=flat)
![Symfony Versions Tested](https://img.shields.io/endpoint?url=https%3A%2F%2Fshields.mrgoodbytes.dev%2Fshield%2Fsymfony-test%2F%255E5.3%2520%257C%2520%255E6.0&logoColor=FFF&style=flat)
![Packagist License](https://img.shields.io/packagist/l/mrgoodbytes8667/avatar-bundle?logo=creative-commons&logoColor=FFF&style=flat)  
![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/mrgoodbytes8667/avatar-bundle/release.yml?label=stable&logo=github&logoColor=FFF&style=flat)
![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/mrgoodbytes8667/avatar-bundle/run-tests.yml?logo=github&logoColor=FFF&style=flat)
![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/mrgoodbytes8667/avatar-bundle/run-tests-by-version.yml?logo=github&logoColor=FFF&style=flat)
[![codecov](https://img.shields.io/codecov/c/github/mrgoodbytes8667/avatar-bundle?logo=codecov&logoColor=FFF&style=flat)](https://codecov.io/gh/mrgoodbytes8667/avatar-bundle)  
A Symfony bundle for avatar caching

## Installation

Make sure Composer is installed globally, as explained in the
[installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### All applications
Add the Multiavatar repo to your composer.json file.

```json
"repositories": [
     {
       "type": "vcs",
       "url":  "https://github.com/bakame-php/multiavatar-php.git"
   }
]
```

### Applications that use Symfony Flex

Open a command console, enter your project directory and execute:

```console
$ composer require mrgoodbytes8667/avatar-bundle
```

### Applications that don't use Symfony Flex

#### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require mrgoodbytes8667/avatar-bundle
```

#### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    Bytes\AvatarBundle\BytesAvatarBundle::class => ['all' => true],
];
```

### All applications

Create a routing file named `config\routes\bytes_avatar.yaml` with the content below, changing the `prefix` value to whatever you want the routes changed to.

```yaml
_bytes_avatar:
  resource: '@BytesAvatarBundle/Resources/config/routing.php'
  prefix: /avatar
```

Create a config file named `config\packages\bytes_avatar.yaml` with the content below, changing the `user_class` value to be the fully qualified name of your User Entity that implements `Bytes\AvatarBundle\Entity\UserInterface`.

```yaml
bytes_avatar:
  multiavatar: ~
```

Sample implementation for a User entity that already has a required email field:

```php
<?php

use Doctrine\ORM\Mapping as ORM;
use Bytes\AvatarBundle\Avatar\Gravatar;

/**
 * @var string|null
 * @ORM\Column(type="string", length=3000, nullable=true)
 */
private $avatar;

/**
 * @return string|null
 */
public function getAvatar(): ?string
{
    return $this->avatar;
}

/**
 * @param string|null $avatar
 * @return $this
 */
public function setAvatar(?string $avatar): self
{
    $this->avatar = $avatar;

    return $this;
}

/**
 * @param int $size
 * @return string
 */
public function getGravatar(int $size = 80)
{
    return Gravatar::url($this->email, $size);
}
```

## License
[![License](https://i.creativecommons.org/l/by-nc/4.0/88x31.png)]("http://creativecommons.org/licenses/by-nc/4.0/)  
avatar-bundle by [MrGoodBytes](https://www.mrgoodbytes.dev) is licensed under a [Creative Commons Attribution-NonCommercial 4.0 International License](http://creativecommons.org/licenses/by-nc/4.0/).  
Based on a work at [https://github.com/mrgoodbytes8667/avatar-bundle](https://github.com/mrgoodbytes8667/avatar-bundle).
