/*
 *	Base SQL Tables for OpenTHC Cannabis Reporting Engine
 */

CREATE TABLE auth_company (
	id varchar(26) DEFAULT public.ulid_create() NOT NULL PRIMARY KEY,
	code varchar(256) not null
);


CREATE TABLE auth_contact (
	id varchar(26) DEFAULT public.ulid_create() NOT NULL PRIMARY KEY,
	username varchar(256) not null,
	password varchar(256) not null
);


CREATE TABLE auth_contact_ticket (
	id varchar(26) NOT NULL PRIMARY KEY,
	contact_id varchar(26) not null,
	code varchar(32) not null
);


CREATE TABLE auth_service (
	id varchar(26) DEFAULT public.ulid_create() NOT NULL PRIMARY KEY,
	company_id varchar(26) not null,
	stat integer not null default 100,
	flag integer not null default 0,
	code varchar(256) not null,
	hash varchar(256) not null,
	name varchar(256) not null
);


CREATE TABLE company (
	id varchar(26) DEFAULT public.ulid_create() NOT NULL PRIMARY KEY,
	created_at timestamp with time zone not null DEFAULT now(),
	updated_at timestamp with time zone,
	deleted_at timestamp with time zone,
	stat int not null DEFAULT 200,
	flag int not null DEFAULT 0,
	hash varchar(64) not null,
	name varchar(256) not null,
	meta jsonb
);


CREATE TABLE license (
	id varchar(26) DEFAULT public.ulid_create() NOT NULL PRIMARY KEY,
	company_id varchar(26) not null,
	created_at timestamp with time zone not null DEFAULT now(),
	updated_at timestamp with time zone,
	deleted_at timestamp with time zone,
	stat int not null DEFAULT 200,
	flag int not null DEFAULT 0,
	hash varchar(64) not null,
	name varchar(256) not null,
	meta jsonb
);


CREATE TABLE contact (
	id varchar(26) DEFAULT public.ulid_create() NOT NULL PRIMARY KEY,
	company_id varchar(26) not null,
	created_at timestamp with time zone not null DEFAULT now(),
	updated_at timestamp with time zone,
	deleted_at timestamp with time zone,
	stat int not null DEFAULT 200,
	flag int not null DEFAULT 0,
	hash varchar(64) not null,
	name varchar(256) not null,
	meta jsonb
);


CREATE TABLE service (
	id varchar(26) DEFAULT public.ulid_create() NOT NULL PRIMARY KEY,
	company_id varchar(26) not null,
	created_at timestamp with time zone not null DEFAULT now(),
	updated_at timestamp with time zone,
	deleted_at timestamp with time zone,
	stat int not null DEFAULT 200,
	flag int not null DEFAULT 0,
	hash varchar(64) not null,
	name varchar(256) not null,
	meta jsonb
);


CREATE TABLE section (
	id varchar(26) DEFAULT public.ulid_create() NOT NULL PRIMARY KEY,
	license_id varchar(26) not null,
	created_at timestamp with time zone not null DEFAULT now(),
	updated_at timestamp with time zone,
	deleted_at timestamp with time zone,
	stat int not null DEFAULT 200,
	flag int not null DEFAULT 0,
	hash varchar(64) not null,
	name varchar(256) not null,
	meta jsonb
);


CREATE TABLE variety (
	id varchar(26) DEFAULT public.ulid_create() NOT NULL PRIMARY KEY,
	license_id varchar(26) not null,
	created_at timestamp with time zone not null DEFAULT now(),
	updated_at timestamp with time zone,
	deleted_at timestamp with time zone,
	stat int not null DEFAULT 200,
	flag int not null DEFAULT 0,
	hash varchar(64) not null,
	name varchar(256) not null,
	meta jsonb
);


CREATE TABLE vehicle (
	id varchar(26) DEFAULT public.ulid_create() NOT NULL PRIMARY KEY,
	license_id varchar(26) not null,
	created_at timestamp with time zone not null DEFAULT now(),
	updated_at timestamp with time zone,
	deleted_at timestamp with time zone,
	stat int not null DEFAULT 200,
	flag int not null DEFAULT 0,
	hash varchar(64) not null,
	name varchar(256) not null,
	meta jsonb
);


CREATE TABLE product (
	id varchar(26) DEFAULT public.ulid_create() NOT NULL PRIMARY KEY,
	license_id varchar(26) not null,
	product_type_id varchar(26) not null,
	created_at timestamp with time zone not null DEFAULT now(),
	updated_at timestamp with time zone,
	deleted_at timestamp with time zone,
	stat int not null DEFAULT 200,
	flag int not null DEFAULT 0,
	hash varchar(64) not null,
	name varchar(256) not null,
	meta jsonb
);


CREATE TABLE product_type (
	id varchar(26) DEFAULT public.ulid_create() NOT NULL PRIMARY KEY,
	created_at timestamp with time zone not null DEFAULT now(),
	updated_at timestamp with time zone,
	deleted_at timestamp with time zone,
	stat int not null DEFAULT 200,
	flag int not null DEFAULT 0,
	hash varchar(64) not null,
	name varchar(256) not null,
	meta jsonb
);


CREATE TABLE inventory (
	id varchar(26) DEFAULT public.ulid_create() NOT NULL PRIMARY KEY,
	license_id varchar(26) not null,
	product_id varchar(26) not null,
	variety_id varchar(26) not null,
	section_id varchar(26) not null,
	created_at timestamp with time zone not null DEFAULT now(),
	updated_at timestamp with time zone,
	deleted_at timestamp with time zone,
	stat int not null DEFAULT 200,
	flag int not null DEFAULT 0,
	hash varchar(64) not null,
	qty numeric(16,4),
	meta jsonb
);


CREATE TABLE public.inventory_adjust (
	id character varying(64) DEFAULT public.ulid_create() NOT NULL PRIMARY KEY,
	inventory_id character varying(64) NOT NULL,
	license_id character varying(64) NOT NULL,
	flag integer DEFAULT 0 NOT NULL,
	stat integer DEFAULT 100 NOT NULL,
	created_at timestamp with time zone DEFAULT now() NOT NULL,
	updated_at timestamp with time zone,
	hash character varying(64) NOT NULL,
	data jsonb,
	name text
);


CREATE TABLE inventory_family (
	id varchar(26) DEFAULT public.ulid_create() NOT NULL PRIMARY KEY,
	inventory_id varchar(26) not null,
	inventory_id_output varchar(26),
	crop_id varchar(26),
	crop_collect_id varchar(26),
	created_at timestamp with time zone not null default now(),
	type varchar(4),
	meta jsonb
);


CREATE TABLE crop (
	id varchar(26) DEFAULT public.ulid_create() NOT NULL PRIMARY KEY,
	license_id varchar(26) not null,
	variety_id varchar(26) not null,
	section_id varchar(26) not null,
	created_at timestamp with time zone not null DEFAULT now(),
	updated_at timestamp with time zone,
	deleted_at timestamp with time zone,
	stat int not null DEFAULT 200,
	flag int not null DEFAULT 0,
	hash varchar(64) not null,
	qty numeric(16,4),
	meta jsonb
);


CREATE TABLE crop_collect (
	id varchar(26) DEFAULT public.ulid_create() NOT NULL PRIMARY KEY,
	license_id varchar(26) not null,
	created_at timestamp with time zone not null DEFAULT now(),
	updated_at timestamp with time zone,
	deleted_at timestamp with time zone,
	stat int not null DEFAULT 200,
	flag int not null DEFAULT 0,
	hash varchar(64) not null,
	raw numeric(16,4),
	net numeric(16,4),
	meta jsonb
);


CREATE TABLE crop_collect_crop (
	id varchar(26) DEFAULT public.ulid_create() NOT NULL PRIMARY KEY,
	crop_collect_id varchar(26) not null,
	crop_id varchar(26) not null,
	created_at timestamp with time zone not null DEFAULT now(),
	updated_at timestamp with time zone,
	deleted_at timestamp with time zone,
	stat int not null DEFAULT 200,
	flag int not null DEFAULT 0,
	hash varchar(64) not null,
	type varchar(16) not null,
	qty numeric(16,4),
	uom varchar(2),
	meta jsonb
);


CREATE TABLE lab_metric (
	id varchar(26) NOT NULL PRIMARY KEY,
	stat int not null DEFAULT 200,
	flag int not null DEFAULT 0,
	sort int not null DEFAULT 0,
	type varchar(32) not null,
	name varchar(256) not null,
	meta jsonb
);


CREATE TABLE lab_result (
	id varchar(26) DEFAULT public.ulid_create() NOT NULL PRIMARY KEY,
	license_id varchar(26) not null,
	created_at timestamp with time zone not null DEFAULT now(),
	updated_at timestamp with time zone,
	deleted_at timestamp with time zone,
	expires_at timestamp with time zone,
	stat int not null DEFAULT 200,
	flag int not null DEFAULT 0,
	sort int not null DEFAULT 0,
	type varchar(32) not null,
	name varchar(256) not null,
	meta jsonb
);

CREATE TABLE lab_result_file (
	id character varying(26) DEFAULT public.ulid_create() NOT NULL PRIMARY KEY,
	lab_result_id character varying(26) NOT NULL,
	created_at timestamp with time zone not null DEFAULT now(),
	updated_at timestamp with time zone,
	deleted_at timestamp with time zone,
	stat integer DEFAULT 100 NOT NULL,
	flag integer DEFAULT 0 NOT NULL,
	size integer,
	type character varying(64),
	name text,
	hash text,
	body bytea,
	meta jsonb,
	jtag jsonb
);


CREATE TABLE inventory_lab_result (
	inventory_id varchar(26) not null,
	lab_result_id varchar(26) not null
);


CREATE TABLE lab_result_metric (
	id varchar(26) DEFAULT public.ulid_create() NOT NULL PRIMARY KEY,
	lab_result_id varchar(26) not null,
	lab_metric_id varchar(26) not null,
	created_at timestamp with time zone not null DEFAULT now(),
	updated_at timestamp with time zone,
	deleted_at timestamp with time zone,
	stat int not null DEFAULT 200,
	flag int not null DEFAULT 0,
	qom numeric(16,4) not null,
	uom varchar(8)
);


CREATE TABLE b2b_incoming (
	id varchar(26) DEFAULT public.ulid_create() NOT NULL PRIMARY KEY,
	license_id_source varchar(26) not null,
	license_id_target varchar(26) not null,
	created_at timestamp with time zone not null DEFAULT now(),
	updated_at timestamp with time zone,
	deleted_at timestamp with time zone,
	stat int not null DEFAULT 200,
	flag int not null DEFAULT 0,
	hash varchar(64) not null,
	name varchar(256) not null,
	meta jsonb
);


CREATE TABLE b2b_outgoing (
	id varchar(26) DEFAULT public.ulid_create() NOT NULL PRIMARY KEY,
	license_id_source varchar(26) not null,
	license_id_target varchar(26) not null,
	created_at timestamp with time zone not null DEFAULT now(),
	updated_at timestamp with time zone,
	deleted_at timestamp with time zone,
	stat int not null DEFAULT 200,
	flag int not null DEFAULT 0,
	hash varchar(64) not null,
	name varchar(256) not null,
	meta jsonb
);


CREATE TABLE b2b_incoming_item (
	id varchar(26) DEFAULT public.ulid_create() NOT NULL PRIMARY KEY,
	b2b_incoming_id varchar(26) not null,
	inventory_id varchar(26),
	created_at timestamp with time zone not null DEFAULT now(),
	updated_at timestamp with time zone,
	deleted_at timestamp with time zone,
	stat int not null DEFAULT 200,
	flag int not null DEFAULT 0,
	qty numeric(16,4),
	hash varchar(64) not null,
	name varchar(256) not null,
	meta jsonb
);


CREATE TABLE b2b_outgoing_item (
	id varchar(26) DEFAULT public.ulid_create() NOT NULL PRIMARY KEY,
	b2b_outgoing_id varchar(26) not null,
	inventory_id varchar(26) not null,
	created_at timestamp with time zone not null DEFAULT now(),
	updated_at timestamp with time zone,
	deleted_at timestamp with time zone,
	stat int not null DEFAULT 200,
	flag int not null DEFAULT 0,
	qty numeric(16,4),
	hash varchar(64) not null,
	name varchar(256) not null,
	meta jsonb
);


CREATE TABLE b2c_sale (
	id varchar(26) DEFAULT public.ulid_create() NOT NULL PRIMARY KEY,
	license_id varchar(26) not null,
	contact_id varchar(26) NOT NULL,
	created_at timestamp with time zone not null DEFAULT now(),
	updated_at timestamp with time zone,
	deleted_at timestamp with time zone,
	stat int not null DEFAULT 200,
	flag int not null DEFAULT 0,
	hash varchar(64) not null,
	name varchar(256) not null,
	meta jsonb
);


CREATE TABLE b2c_sale_item (
	id varchar(26) DEFAULT public.ulid_create() NOT NULL PRIMARY KEY,
	b2c_sale_id varchar(26) NOT NULL,
	inventory_id varchar(26) not null,
	created_at timestamp with time zone not null DEFAULT now(),
	updated_at timestamp with time zone,
	deleted_at timestamp with time zone,
	stat integer DEFAULT 200 NOT NULL,
	flag integer DEFAULT 0 NOT NULL,
	sort integer DEFAULT 0 NOT NULL,
	hash varchar(64) not null,
	unit_price numeric(12,4) not null,
	unit_count numeric(16,4) NOT NULL,
	uom varchar(8),
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
	id varchar(26) DEFAULT public.ulid_create() NOT NULL PRIMARY KEY,
	created_at timestamp with time zone DEFAULT now() not null,
	pk varchar(26) not null,
	code varchar(64) not null,
	meta jsonb
);


CREATE TABLE log_delta (
	id varchar(26) DEFAULT public.ulid_create() NOT NULL PRIMARY KEY,
	op char(6) not null,
	created_at timestamp with time zone DEFAULT now() not null,
	pk varchar(26),
	tb varchar(64),
	v0 jsonb,
	v1 jsonb
);
