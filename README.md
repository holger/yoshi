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

Features
--------

* HTTP Routing (supported methods: GET, POST, UPDATE, DELETE, HEAD & OPTIONS)
* Views & View Helpers (only UrlHelper included right now)
* Custom Error Views


[1]: http://silex.sensiolabs.org/
[2]: http://www.slimframework.com/