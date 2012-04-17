Yoshi
=====

A PHP micro-framework. Similar to [Silex][1] or [Slim][2].

```php
<?php
$app = new yoshi\Application();

$app->get('/hello/{name}', function ($name) {
  return "Hello, $name!";
});

$app->run();
?>
```

Yoshi works with PHP 5.3.2 or later.

Feature Requests
----------------

* HTTP error for non existing routes or wrong HTTP methods
* Custom error pages

[1]: http://silex.sensiolabs.org/
[2]: http://www.slimframework.com/