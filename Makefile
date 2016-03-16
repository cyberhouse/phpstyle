default: vendor/autoload.php

test: vendor/autoload.php
	./vendor/bin/phpcs --encoding=utf-8 --colors -p --standard=ruleset.xml --extensions=php --severity=1 src/
	./vendor/bin/phpcs --encoding=utf-8 --colors -p --standard=ruleset.xml --extensions=php --severity=1 tests/
	./vendor/bin/phpunit -c phpunit.xml tests/

fix: composer.phar
	./vendor/bin/php-cs-fixer fix --config-file=.php_cs

vendor/autoload.php: composer.phar
	php composer.phar install -o

composer.phar:
	php -r "readfile('https://getcomposer.org/installer');" > composer-setup.php && \
	php composer-setup.php && \
    php -r "unlink('composer-setup.php');"

.PHONY: test fix default
