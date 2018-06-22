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

### Estimate Github PR
```
> bin/console estimate:github symfony/symfony/pull/27647
Fetching symfony/symfony/pull/27647 pull request data
Price for symfony/symfony/pull/27647 is: $51.36
```

### Estimate wizard (ask questions)
```
> bin/console estimate:wizard 
Commits: 1
Additions: 100
Deletions: 20
Changed files: 2
Comments: 0
Review comments: 0
Price for PR is: $94.4
```


## License

PHP-ML is released under the MIT Licence. See the bundled LICENSE file for details.

## Author

Arkadiusz Kondas (@ArkadiuszKondas)