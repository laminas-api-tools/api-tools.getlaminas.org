# api-tools.getlaminas.org Makefile
#
# Primary purpose is for updating the video feed.
#
# Configurable variables:
# - PHP - PHP executable to use, if not in path
# - YT_KEY - YouTube API key to use when generating the video page
#
# Available targets:
# - videos  = update video page wtih latest YouTube video releases

PHP ?= $(/usr/bin/env php)
YT_KEY=

BIN = $(CURDIR)/bin

videos:
	@echo "Generating video page..."
ifeq ($(YT_KEY),)
	@echo "Youtube API key not defined, exiting..."
	exit 1
else
	- $(PHP) $(BIN)/youtube.php --key=$(YT_KEY)
	@echo "[DONE] generating video page"
endif
