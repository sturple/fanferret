# FanFerret Question Bundle

## Installation

** Install With Composer **

```json
{
    "repositories" : [
        {
            "type" : "git",
            "url" : "https://github.com/sturple/fanferret-questionbundle"
        }
    ],    
   "require": {
       "sturpe/fanferret-questionbundle": "dev-master"
   }
}

```

and then execute

```json
$ composer update
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
             new FanFerret\QuestionBundle\FanFerretQuestionBundle();
        ]
    }
}            

```


