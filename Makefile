test: phpunit phpstan

phpunit:
	vendor/bin/phpunit

phpstan:
	vendor/bin/phpstan --xdebug

coverage:
	vendor/bin/phpunit --coverage-filter=src --coverage-html=./phpunit-coverage-report
