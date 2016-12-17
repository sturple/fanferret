# FanFerret Question Bundle

## Installation

** Install With Composer **

**Note** fos urser bundle requires symfony to be 3.1.
https://symfony.com/doc/master/bundles/FOSUserBundle/index.html

```json
{
   "require": {
        "sturpe/fanferret": "~0.0",
        "friendsofsymfony/user-bundle": "~2.0@dev",
   }
}

```

and then execute

```json
$ composer update
```

or 

```
composer require sturple/fanferret:~0.0
composer require friendsofsymfony/user-bundle "~2.0@dev"
```

## Configuration

**Add to ```app/AppKernal.php``` file**

```php

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            ...
             new \FanFerret\QuestionBundle\FanFerretQuestionBundle(),
             new \Fos\UserBundle\FOSUserBundle(),
        ]
    }
}            

```

## Routing

**app/config/routing.yml**

```yml
fanferret:
    resource: "@FanFerretQuestionBundle/Resources/config/routing.yml"
    prefix: '/survey/'
```

## Survey Creation 

### survey-{name}.yml

**see sample-survey.yml**