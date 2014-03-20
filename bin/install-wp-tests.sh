#!/usr/bin/env bash

if [ $# -lt 3 ]; then
	echo "usage: $0 <db-name> <db-user> <db-pass> [db-host] [wp-version]"
	exit 1
fi

DB_NAME=$1
DB_USER=$2
DB_PASS=$3
DB_HOST=${4-localhost}
WP_VERSION=${5-latest}

# set dir vars
DIR_WP_DEVELOP=tmp-wp-dev
WP_CORE_DIR=${DIR_WP_DEVELOP}/wordpress/
export WP_TESTS_DIR=${DIR_WP_DEVELOP}/wordpress-tests/

set -ex

# cleanup
rm -rf ${DIR_WP_DEVELOP}
mysql -e "DROP DATABASE IF EXISTS ${DB_NAME};" --user="${DB_USER}" --password="${DB_PASS}"

install_wp() {
	mkdir -p ${WP_CORE_DIR}

	local ARCHIVE_NAME
	if [ ${WP_VERSION} == 'latest' ]; then
		ARCHIVE_NAME='latest'
	else
		ARCHIVE_NAME="wordpress-$WP_VERSION"
	fi

	wget -nv -O ${DIR_WP_DEVELOP}/wordpress.tar.gz http://wordpress.org/${ARCHIVE_NAME}.tar.gz
	tar --strip-components=1 -zxmf ${DIR_WP_DEVELOP}/wordpress.tar.gz -C ${WP_CORE_DIR}
	rm -f ${DIR_WP_DEVELOP}/wordpress.tar.gz

#	wget -nv -O ${WP_CORE_DIR}/wp-content/db.php https://raw.github.com/markoheijnen/wp-mysqli/master/db.php
}

install_test_suite() {
	# portable in-place argument for both GNU sed and Mac OSX sed
	local ioption
	if [[ $(uname -s) == 'Darwin' ]]; then
		ioption='-i ""'
	else
		ioption='-i'
	fi

	# set up testing suite
	mkdir -p ${WP_TESTS_DIR}
	cd ${WP_TESTS_DIR}
	svn co --quiet http://develop.svn.wordpress.org/trunk/tests/phpunit/includes/ .

	# setup tests config file
	cd ..
	wget -nv -O wp-tests-config.php http://develop.svn.wordpress.org/trunk/wp-tests-config-sample.php

	sed ${ioption} "s:dirname( __FILE__ ) . '/src/':'${WP_CORE_DIR}':" wp-tests-config.php
	sed ${ioption} "s/youremptytestdbnamehere/${DB_NAME}/" wp-tests-config.php
	sed ${ioption} "s/yourusernamehere/${DB_USER}/" wp-tests-config.php
	sed ${ioption} "s/yourpasswordhere/${DB_PASS}/" wp-tests-config.php
	sed ${ioption} "s|localhost|${DB_HOST}|" wp-tests-config.php
}

install_db() {
	# parse DB_HOST for port or socket references
#	local PARTS=(${DB_HOST//\:/ })
#	local DB_HOSTNAME=${PARTS[0]};
#	local DB_SOCK_OR_PORT=${PARTS[1]};
#	local EXTRA=""
#
#	if ! [ -z ${DB_HOSTNAME} ] ; then
#		if [[ "${DB_SOCK_OR_PORT}" =~ ^[0-9]+$ ]] ; then
#			EXTRA=" --host=${DB_HOSTNAME} --port=${DB_SOCK_OR_PORT} --protocol=tcp"
#		elif ! [ -z ${DB_SOCK_OR_PORT} ] ; then
#			EXTRA=" --socket=${DB_SOCK_OR_PORT}"
#		elif ! [ -z ${DB_HOSTNAME} ] ; then
#			EXTRA=" --host=${DB_HOSTNAME} --protocol=tcp"
#		fi
#	fi

	# create database
	mysql -e "CREATE DATABASE IF NOT EXISTS ${DB_NAME};" --user="${DB_USER}" --password="${DB_PASS}"
#	mysqladmin create ${DB_NAME} --user="$DB_USER" --password="$DB_PASS"$EXTRA
}

install_wp
install_test_suite
install_db
