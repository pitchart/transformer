# Transformer

A transducers implementation in PHP, with OOP powers

This package has been inspired by libraries provided by other languages, like clojure's transducers or JAVA Stream API. 

## Why ?

> Transducers are composable algorithmic transformations. They are independent from the context of their input and output sources and specify only the essence of the transformation in terms of an individual element. Because transducers are decoupled from input or output sources, they can be used in many different processes - collections, streams, channels, observables, etc. Transducers compose directly, without awareness of input or creation of intermediate aggregates.

[More information on the clojure reference](https://clojure.org/reference/transducers)

With classical pipeline pattern, the whole collection is iterated for each step of the transformation and creates an intermediate collection which is a massive waste in memory usage.

Transducers use functional composition to reduce the number of iterations made while applying transformation.

## Installation

```bash
composer require pitchart/transformer
```

## Usage

A transformation consists in a pipeline of transformation functions, called Reducers, ended by a reducing function, aka Termination.

```php
use function Pitchart\transform;

transform([0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 8, 7, 6, 5, 4, 3, 2, 1, 0])
    ->filter(function (int $integer) { return $int % 2 === 0;})
    ->map(function (int $integer) { return $int + 1; })
    ->dedupe()
    ->distinct()
    ->take(5)
    ->sum()
;
```

## API documentation

"Functional patterns" used by the library are discribed in the [Reducers documentation](docs/Reducers.md)

## Credits

- [Julien VITTE](https://github.com/pitchart)
- [All contributors](https://github.com/pitchart/transformer/graphs/contributors)

## Licence

The MIT License (MIT). See [License file](LICENCE.md) for more information.