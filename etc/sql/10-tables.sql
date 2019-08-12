/*
 *	Base SQL Tables for OpenTHC Cannabis Reporting Engine
 */

CREATE TABLE auth_company (
	id varchar(26) PRIMARY KEY,
	code varchar(256) not null
);


CREATE TABLE auth_contact (
	id varchar(26) PRIMARY KEY,
	username varchar(256) not null,
	password varchar(256) not null
);


CREATE TABLE auth_program (
	id varchar(26) PRIMARY KEY,
	company_id varchar(26) not null,
	code varchar(32) not null
);


CREATE TABLE auth_program_secret (
	id varchar(26) PRIMARY KEY,
	program_id varchar(26) not null,
	code varchar(32) not null
);


CREATE TABLE company (
	id varchar(26) PRIMARY KEY,
	created_at timestamp with time zone not null DEFAULT now(),
	updated_at timestamp with time zone not null DEFAULT now(),
	deleted_at timestamp with time zone,
	stat int not null DEFAULT 200,
	flag int not null DEFAULT 0,
	hash varchar(64) not null,
	name varchar(256) not null,
	meta jsonb
);


CREATE TABLE license (
	id varchar(26) PRIMARY KEY,
	company_id varchar(26) not null,
	created_at timestamp with time zone not null DEFAULT now(),
	updated_at timestamp with time zone not null DEFAULT now(),
	deleted_at timestamp with time zone,
	stat int not null DEFAULT 200,
	flag int not null DEFAULT 0,
	hash varchar(64) not null,
	name varchar(256) not null,
	meta jsonb
);


CREATE TABLE contact (
	id varchar(26) PRIMARY KEY,
	company_id varchar(26) not null,
	created_at timestamp with time zone not null DEFAULT now(),
	updated_at timestamp with time zone not null DEFAULT now(),
	deleted_at timestamp with time zone,
	stat int not null DEFAULT 200,
	flag int not null DEFAULT 0,
	hash varchar(64) not null,
	name varchar(256) not null,
	meta jsonb
);


CREATE TABLE program (
	id varchar(26) PRIMARY KEY,
	company_id varchar(26) not null,
	created_at timestamp with time zone not null DEFAULT now(),
	updated_at timestamp with time zone not null DEFAULT now(),
	deleted_at timestamp with time zone,
	stat int not null DEFAULT 200,
	flag int not null DEFAULT 0,
	hash varchar(64) not null,
	name varchar(256) not null,
	meta jsonb
);


CREATE TABLE zone (
	id varchar(26) PRIMARY KEY,
	license_id varchar(26) not null,
	created_at timestamp with time zone not null DEFAULT now(),
	updated_at timestamp with time zone not null DEFAULT now(),
	deleted_at timestamp with time zone,
	stat int not null DEFAULT 200,
	flag int not null DEFAULT 0,
	hash varchar(64) not null,
	name varchar(256) not null,
	meta jsonb
);


CREATE TABLE strain (
	id varchar(26) PRIMARY KEY,
	license_id varchar(26) not null,
	created_at timestamp with time zone not null DEFAULT now(),
	updated_at timestamp with time zone not null DEFAULT now(),
	deleted_at timestamp with time zone,
	stat int not null DEFAULT 200,
	flag int not null DEFAULT 0,
	hash varchar(64) not null,
	name varchar(256) not null,
	meta jsonb
);


CREATE TABLE product (
	id varchar(26) PRIMARY KEY,
	license_id varchar(26) not null,
	product_type_id varchar(26) not null,
	created_at timestamp with time zone not null DEFAULT now(),
	updated_at timestamp with time zone not null DEFAULT now(),
	deleted_at timestamp with time zone,
	stat int not null DEFAULT 200,
	flag int not null DEFAULT 0,
	hash varchar(64) not null,
	name varchar(256) not null,
	meta jsonb
);


CREATE TABLE product_type (
	id varchar(26) PRIMARY KEY,
	created_at timestamp with time zone not null DEFAULT now(),
	updated_at timestamp with time zone not null DEFAULT now(),
	deleted_at timestamp with time zone,
	stat int not null DEFAULT 200,
	flag int not null DEFAULT 0,
	hash varchar(64) not null,
	name varchar(256) not null,
	meta jsonb
);


CREATE TABLE lot (
	id varchar(26) PRIMARY KEY,
	license_id varchar(26) not null,
	product_id varchar(26) not null,
	strain_id varchar(26) not null,
	zone_id varchar(26) not null,
	created_at timestamp with time zone not null DEFAULT now(),
	updated_at timestamp with time zone not null DEFAULT now(),
	deleted_at timestamp with time zone,
	stat int not null DEFAULT 200,
	flag int not null DEFAULT 0,
	hash varchar(64) not null,
	meta jsonb
);


CREATE TABLE lab_result_lot (
	lab_result_id varchar(26) not null,
	lot_id varchar(26) not null
);


CREATE TABLE plant (
	id varchar(26) PRIMARY KEY,
	license_id varchar(26) not null,
	strain_id varchar(26) not null,
	zone_id varchar(26) not null,
	created_at timestamp with time zone not null DEFAULT now(),
	updated_at timestamp with time zone not null DEFAULT now(),
	deleted_at timestamp with time zone,
	stat int not null DEFAULT 200,
	flag int not null DEFAULT 0,
	hash varchar(64) not null,
	meta jsonb
);


CREATE TABLE lab_metric (
	id varchar(26) PRIMARY KEY,
	stat int not null DEFAULT 200,
	flag int not null DEFAULT 0,
	type varchar(32) not null,
	name varchar(256) not null,
	meta jsonb
);


CREATE TABLE lab_result (
	id varchar(26) PRIMARY KEY,
	license_id varchar(26) not null,
	created_at timestamp with time zone not null DEFAULT now(),
	updated_at timestamp with time zone not null DEFAULT now(),
	deleted_at timestamp with time zone,
	stat int not null DEFAULT 200,
	flag int not null DEFAULT 0,
	type varchar(32) not null,
	name varchar(256) not null,
	meta jsonb
);


CREATE TABLE lab_result_metric (
	id varchar(26) PRIMARY KEY,
	lab_result_id varchar(26) not null,
	lab_metric_id varchar(26) not null,
	created_at timestamp with time zone not null DEFAULT now(),
	updated_at timestamp with time zone not null DEFAULT now(),
	deleted_at timestamp with time zone,
	stat int not null DEFAULT 200,
	flag int not null DEFAULT 0,
	qom numeric(16,4) not null,
	uom varchar(8)
);


CREATE TABLE b2b_incoming (
	id varchar(26) PRIMARY KEY,
	source_license_id varchar(26) not null,
	target_license_id varchar(26) not null,
	created_at timestamp with time zone not null DEFAULT now(),
	updated_at timestamp with time zone not null DEFAULT now(),
	deleted_at timestamp with time zone,
	stat int not null DEFAULT 200,
	flag int not null DEFAULT 0,
	hash varchar(64) not null,
	name varchar(256) not null,
	meta jsonb
);


CREATE TABLE b2b_outgoing (
	id varchar(26) PRIMARY KEY,
	source_license_id varchar(26) not null,
	target_license_id varchar(26) not null,
	created_at timestamp with time zone not null DEFAULT now(),
	updated_at timestamp with time zone not null DEFAULT now(),
	deleted_at timestamp with time zone,
	stat int not null DEFAULT 200,
	flag int not null DEFAULT 0,
	hash varchar(64) not null,
	name varchar(256) not null,
	meta jsonb
);


CREATE TABLE b2b_incoming_item (
	id varchar(26) PRIMARY KEY,
	b2b_incoming_id varchar(26) not null,
	lot_id varchar(26) not null,
	created_at timestamp with time zone not null DEFAULT now(),
	updated_at timestamp with time zone not null DEFAULT now(),
	deleted_at timestamp with time zone,
	stat int not null DEFAULT 200,
	flag int not null DEFAULT 0,
	hash varchar(64) not null,
	name varchar(256) not null,
	meta jsonb
);


CREATE TABLE b2b_outgoing_item (
	id varchar(26) PRIMARY KEY,
	b2b_outgoing_id varchar(26) not null,
	lot_id varchar(26) not null,
	created_at timestamp with time zone not null DEFAULT now(),
	updated_at timestamp with time zone not null DEFAULT now(),
	deleted_at timestamp with time zone,
	stat int not null DEFAULT 200,
	flag int not null DEFAULT 0,
	hash varchar(64) not null,
	name varchar(256) not null,
	meta jsonb
);


CREATE TABLE b2c_sale (
	id varchar(26) PRIMARY KEY,
	license_id varchar(26) not null,
	created_at timestamp with time zone not null DEFAULT now(),
	updated_at timestamp with time zone not null DEFAULT now(),
	deleted_at timestamp with time zone,
	stat int not null DEFAULT 200,
	flag int not null DEFAULT 0,
	hash varchar(64) not null,
	name varchar(256) not null,
	meta jsonb
);


CREATE TABLE b2c_sale_item (
	id varchar(26) PRIMARY KEY,
	b2c_sale_id varchar(26) not null,
	lot_id varchar(26) not null,
	created_at timestamp with time zone not null DEFAULT now(),
	updated_at timestamp with time zone not null DEFAULT now(),
	deleted_at timestamp with time zone,
	qty numeric(16,4) not null,
	unit_price numeric(12,4) not null,
	stat int not null DEFAULT 200,
	flag int not null DEFAULT 0,
	hash varchar(64) not null,
	meta jsonb
);


/*
CREATE TABLE log_alert (
	id bigserial PRIMARY KEY,
	created_at timestamp with time zone DEFAULT now() not null,
	owner_contact_id varchar(64),
	code varchar(64) not null,
	meta jsonb
);
*/


CREATE TABLE log_audit (
	id varchar(26) PRIMARY KEY,
	created_at timestamp with time zone DEFAULT now() not null,
	pk varchar(26) not null,
	code varchar(64) not null,
	meta jsonb
);


CREATE TABLE log_delta (
	id varchar(26) PRIMARY KEY,
	op char(6) not null,
	created_at timestamp with time zone DEFAULT now() not null,
	pk varchar(26),
	tb varchar(64),
	v0 jsonb,
	v1 jsonb
);
