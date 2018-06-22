## Code review estimator

Simple showcase of machine learning for code review cost estimation.

## Install

```
composer install 
```

## Usage

```
bin/console list
bin/console <command> --help
```

### Train

Store your dataset in `data/code-reviews.csv
```
> bin/console train
R2: 0.90124676557598
New model trained! :rocket:
```

### Estimate
```
> bin/console estimate symfony/symfony/pull/27647
Fetching symfony/symfony/pull/27647 pull request data
Price for symfony/symfony/pull/27647 is: $51.36
```


## License

PHP-ML is released under the MIT Licence. See the bundled LICENSE file for details.

## Author

Arkadiusz Kondas (@ArkadiuszKondas)