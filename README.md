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

A transformation consists in a pipeline of transformation functions, called Reducers, ended by a 


## API documentation

"Functional patterns" used by the library are discribed in the [Reducers documentation](docs/Reducers.md)

## Credits

- [Julien VITTE](https://github.com/pitchart)
- [All contributors](../../contributors)

## Licence

The MIT License (MIT). See [License file](LICENCE.md) for more information.