# Yoshi

A PHP micro-framework.

```php
<?php
$app = new yoshi\Application();

$app->get('/hello/{name}', function ($name) {
  echo "Hello, $name!";
});

$app->run();
?>
```

Yoshi requires PHP 5.3.


## Features

* HTTP Routing (supported methods: GET, POST, UPDATE, DELETE, HEAD & OPTIONS)
* Views & View Helpers (only UrlHelper included right now)
* Custom Error Views
* Application & Route Filters


## Open Issues

* Redirects
* Authentication Helper
* Error Handling (e.g. Views render -> view not found)
* Set HTTP Headers inside route callbacks
* Enable filter to end route processing


## Similar PHP micro frameworks

Yoshi is nothing new. There are already some good PHP micro frameworks out there. I started Yoshi mainly for fun and to dig deeper into the development of a web framework.

Here's a list of similar PHP micro frameworks:

 * [Silex][1]
 * [Slim][2]
 * [Limonade][3]
 * [FatFree][4]
 * [verbier][5]
 * [GluePHP][6]
 * [Flight][7]
 * [Epiphany][8]

## Doku

* Installation using composer
# Using views
# Global variables
# Using callback methods for helpers (arrays vs. callback function objects)
# Redirect using headers


## License

Yoshi is released under the [MIT License][license].


[1]: http://silex.sensiolabs.org/
[2]: http://www.slimframework.com/
[3]: http://limonade-php.github.com/
[4]: http://bcosca.github.com/fatfree/
[5]: https://github.com/Hanse/verbier
[6]: http://gluephp.com/
[7]: http://flightphp.com/
[8]: https://github.com/jmathai/epiphany
[license]: https://github.com/holger/yoshi/blob/master/LICENSE