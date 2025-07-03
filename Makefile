.PHONY: test test-one stan cs cbf migrate serve test-one-%

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

serve:
	php -S localhost:8000 -t public

git-amend:
	git commit --amend --no-edit
