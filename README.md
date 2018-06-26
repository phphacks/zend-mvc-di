# zend-mvc-di

[![Maintainability](https://api.codeclimate.com/v1/badges/4a1b45a04cf4e6d41de5/maintainability)](https://codeclimate.com/github/phphacks/zend-mvc-di/maintainability) [![Test Coverage](https://api.codeclimate.com/v1/badges/4a1b45a04cf4e6d41de5/test_coverage)](https://codeclimate.com/github/phphacks/zend-mvc-di/test_coverage)

Dependency injection made easy for Zend Framework 3. No configurations needed for simple use cases, all you need to do is to use our InjectableFactory as a controller factory in Zend Framework, like in the example below.

```php
'controllers' => [
  'factories' => [
    AuthenticationController::class => InjectableFactory::class
  ],
],
```
