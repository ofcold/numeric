# About Math Precision Calculator

### Installed
```shell
composer require ofcold/numeric-component
```

### Example:

```php

require __DIR__.'/vendor/autoload.php';

$result = num(3.141592698)->add(1111)->mul(2)->div(100);

```

#### Result:
```
Ofcold\NumericComponent\Numeric^ {#2
  #dirtyValue: "22.28283185"
  #scale: 8
}

```
