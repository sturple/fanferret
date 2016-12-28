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
## Config

**app/config/config.yml**

```yml
fos_user:
    db_driver: orm
    firewall_name: main
    user_class: FanFerret\QuestionBundle\Entity\User
```

## Routing

**app/config/routing.yml**

```yml
fanferret:
    resource: "@FanFerretQuestionBundle/Resources/config/routing.yml"
    prefix: '/survey/'
```

## Security 

**app/config/security.yml

```yml

security:
    providers:
        fos_userbundle:
            id: fos_user.user_provider.username
    encoders:
        FOS\UserBundle\Model\UserInterface: bcrypt            
    firewalls:
       
        dev:
            pattern: ^/(_(profiler|wdt)|css|images|js)/
            security: false

        main:
            anonymous: true
            pattern: 
            form_login:
                login_path: /login
                provider: fos_userbundle
                csrf_token_generator: security.csrf.token_manager
           
            form_login:
              login_path: /login
            logout: true
        
         
    role_hierarchy:
        ROLE_ADMIN: ROLE_USER
    access_control:
        - { path: ^/login$, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/register, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/resetting, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/admin, role: ROLE_USER }
        

```

## Survey Creation 

### survey-{name}.yml

**see sample-survey.yml**