### Variables

# Applications
COMPOSER ?= composer

### Helpers
all: clean depend

.PHONY: all

### Dependencies
depend:
	${COMPOSER} install --no-progress --optimize-autoloader

.PHONY: depend

### Cleaning
clean:
	rm -rf vendor

git-master:
	git checkout master

.PHONY: clean git-master

### QA
qa: lint

lint:
	find ./src -name "*.php" -exec /usr/bin/env php -l {} \; | grep "Parse error" > /dev/null && exit 1 || exit 0

.PHONY: qa lint

### Testing
tests:
	php vendor/bin/phpunit -v --colors --coverage-text

tests-report:
	php vendor/bin/phpunit -v --colors --coverage-html ./build/tests

.PHONY: tests tests-report
