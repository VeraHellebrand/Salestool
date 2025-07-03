.PHONY: test one stan cs cbf migrate reset-db serve git-amend clean-logs clean-cache

test:
	vendor/bin/phpunit

# Spustí konkrétní test podle názvu třídy nebo metody: make one name=TariffFactoryTest
one:
	vendor/bin/phpunit --filter $(name)

stan:
	composer stan

cs:
	composer cs

cbf:
	composer cbf

migrate:
	composer migrate

reset-db:
	rm -f database/database.sqlite
	$(MAKE) migrate

serve:
	php -S localhost:8000 -t public

git-amend:
	git commit --amend --no-edit

clean-logs:
	rm -f log/*.log

clean-cache:
	rm -rf temp/*

