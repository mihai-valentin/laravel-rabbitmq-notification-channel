test-and-check: test check

test:
	vendor/bin/phpunit

coverage:
	vendor/bin/phpunit --coverage-filter=src --coverage-html=./phpunit-coverage-report

check:
	vendor/bin/phpstan --xdebug
