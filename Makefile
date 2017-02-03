default: vendor/autoload.php

test: vendor/autoload.php
	./vendor/bin/php-cs-fixer fix --dry-run --diff --config=.php_cs
	./vendor/bin/phpunit -c phpunit.xml tests/

fix: composer.phar
	./vendor/bin/php-cs-fixer fix --config=.php_cs

vendor/autoload.php: composer.phar
	php composer.phar install -o

composer.phar:
	php -r "readfile('https://getcomposer.org/installer');" > composer-setup.php && \
	php composer-setup.php && \
	php -r "unlink('composer-setup.php');"

.PHONY: test fix default
