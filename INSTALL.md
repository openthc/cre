# Installation

- Clone this Repository
- Run the SQL scripts in ./etc/sql in order
- Configure Apache from `./etc/apache2.conf`
- Success!

## Create Database

	su - postgres
	psql
	create user openthc_cre with encrypted password 'NOT_A_SECRET';
	create database openthc_cre with owner openthc_cre;
	\c openthc_cre openthc_cre
	\i 10-tables.sql
	\i 20-pk.sql
	\i 30.fk.sql
	\i 40-constraint.sql
	\i 50-udx.sql
	\i 60-idx.sql
	\i 70-trigger.sql
	\i 90-data.sql

## Configure Apache

Just add in the application apache.conf file to the system apache.conf.

	echo "Include "/opt/openthc/etc/apache.conf" >> /etc/apache2/apache2.conf

On Debian you can symlink it into /etc/apache2/sites-enabled

Update the `boot.php` file to replace APP_ROOT and APP_HOST with the correct values.
Update the `etc/app.ini` file with necessary values.


## Initialise the System

	./bin/cli init


## Configure LetsEncrypt

	apt-get install certbot python-certbot-apache
	certbot certonly --webroot --webroot-path APP_ROOT/webroot --domain APP_HOST
