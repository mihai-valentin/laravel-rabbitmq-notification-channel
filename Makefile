test: phpunit phpstan php-cs-fixer

phpunit:
	vendor/bin/phpunit --testsuite "Unit"

phpstan:
	vendor/bin/phpstan --xdebug

coverage:
	docker-compose up -d
	vendor/bin/phpunit --coverage-filter=src --coverage-html=./phpunit-coverage-report

php-cs-fixer:
	vendor/bin/php-cs-fixer fix -v src/
