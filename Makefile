ifneq ($(if $(MAKECMDGOALS),$(words $(MAKECMDGOALS)),1),1)
.SUFFIXES:
TARGET := $(if $(findstring :,$(firstword $(MAKECMDGOALS))),,$(firstword $(MAKECMDGOALS)))
PARAMS := $(if $(findstring :,$(firstword $(MAKECMDGOALS))),$(MAKECMDGOALS),$(wordlist 2,100000,$(MAKECMDGOALS)))
.DEFAULT_GOAL = help
.PHONY: ONLY_ONCE
ONLY_ONCE:
	$(MAKE) $(TARGET) COMMAND_ARGS="$(PARAMS)"
%: ONLY_ONCE
	@:
else

.PHONY: start
start:
	docker-compose up -d
.PHONY: stop
stop:
	docker-compose down -v
.PHONY: c # запуск команд с консоли
c:
	@docker-compose exec app sh -c "php artisan $(COMMAND_ARGS)"
.PHONY: composer
composer:
	@docker-compose exec app sh -c "composer $(COMMAND_ARGS)"
endif
