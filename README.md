# zend-mvc-di

[![Maintainability](https://api.codeclimate.com/v1/badges/4a1b45a04cf4e6d41de5/maintainability)](https://codeclimate.com/github/phphacks/zend-mvc-di/maintainability) [![Test Coverage](https://api.codeclimate.com/v1/badges/4a1b45a04cf4e6d41de5/test_coverage)](https://codeclimate.com/github/phphacks/zend-mvc-di/test_coverage)

Dependency injection made easy for Zend Framework 3. No configuration needed, all you need to do is to use our [InjectableFactory](https://github.com/phphacks/zend-mvc-di/blob/master/src/Dependency/Injection/InjectableFactory.php) as a controller factory in Zend Framework, like in the example below.

## Example

**Setup your injectable controller in `module.config.php`.**

This configuration file can be found at any module in Zend Framework.
```php
'controllers' => [
  'factories' => [
    AuthenticationController::class => InjectableFactory::class
  ],
],
```

**Create your data repository**

The repository will acess your database and get the records.
```php
<?php

class CredentialnRepository
{
   public function getRecordsByUsernameAndPassword($username, $password)
   {
      return [
        [
           'name' => 'A fake record',
           'accessLevel' => 1
        ]
      ];
   }
}
```
**Create your service for authentication**

The authentication service will check if credentials match and then will provide a token.
```php
<?php

class AuthenticationService
{
   private $repository;
   
   public function __construct(CredentialRepository $repository)
   {
      $this->repository = $repository;
   }
   
   public function createToken($username, $password): string
   {
      $rows = $this->repository->getRecordsByUsernameAndPassword($username, $password);
      
      if(count($rows) != 1) {
         throw new \InvalidArgumentException('Username or password incorrect');
      }
      
      return md5($username . $password);
   }
}
```

**Create your controller**
<?php

class AuthenticationController extends AbstractActionController
{
   private $authenticationService;
   
   public function __construct(AuthenticationService $authenticationService)
   {
      $this->authenticationService = $authenticationService.
   }
   
   public function loginAction()
   {
   }
}
