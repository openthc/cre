/**
 * This Function and Trigger traps all modifications to a table as diff-able JSON
 * @see https://www.postgresql.org/docs/9.1/static/plpgsql-trigger.html
*/

/**
	Create Log Delta Writer
*/
CREATE FUNCTION log_delta_trigger() RETURNS trigger AS
$FUNC$
BEGIN

	CASE TG_OP
	WHEN 'UPDATE' THEN
		INSERT INTO log_delta (id, op, tb, pk, v0, v1) VALUES (ulid_create(), TG_OP, TG_TABLE_NAME, OLD.id, row_to_json(OLD), row_to_json(NEW));
		RETURN NEW;
	WHEN 'INSERT' THEN
		INSERT INTO log_delta (id, op, tb, pk, v1) VALUES (ulid_create(), TG_OP, TG_TABLE_NAME, NEW.id, row_to_json(NEW));
		RETURN NEW;
	WHEN 'DELETE' THEN
		INSERT INTO log_delta (id, op, tb, pk, v0) VALUES (ulid_create(), TG_OP, TG_TABLE_NAME, OLD.id, row_to_json(OLD));
		RETURN OLD;
	END CASE;

END;
$FUNC$
LANGUAGE 'plpgsql' SECURITY DEFINER;


/**
	Recursive Object DIFFER?
	https://stackoverflow.com/questions/36041784/postgresql-compare-two-jsonb-objects#36043269
	https://stackoverflow.com/questions/25678509/postgres-recursive-query-with-row-to-json
*/

/**
	Create Trigger on so many tables
*/
CREATE TRIGGER log_delta_company
	AFTER INSERT OR UPDATE OR DELETE
	ON company
	FOR EACH ROW
	EXECUTE PROCEDURE log_delta_trigger();

CREATE TRIGGER log_delta_license
	AFTER INSERT OR UPDATE OR DELETE
	ON license
	FOR EACH ROW
	EXECUTE PROCEDURE log_delta_trigger();

CREATE TRIGGER log_delta_contact
	AFTER INSERT OR UPDATE OR DELETE
	ON contact
	FOR EACH ROW
	EXECUTE PROCEDURE log_delta_trigger();

CREATE TRIGGER log_delta_product
	AFTER INSERT OR UPDATE OR DELETE
	ON product
	FOR EACH ROW
	EXECUTE PROCEDURE log_delta_trigger();

CREATE TRIGGER log_delta_variety
	AFTER INSERT OR UPDATE OR DELETE
	ON variety
	FOR EACH ROW
	EXECUTE PROCEDURE log_delta_trigger();

CREATE TRIGGER log_delta_vehicle
	AFTER INSERT OR UPDATE OR DELETE
	ON vehicle
	FOR EACH ROW
	EXECUTE PROCEDURE log_delta_trigger();

CREATE TRIGGER log_delta_inventory
	AFTER INSERT OR UPDATE OR DELETE
	ON inventory
	FOR EACH ROW
	EXECUTE PROCEDURE log_delta_trigger();

CREATE TRIGGER log_delta_crop
	AFTER INSERT OR UPDATE OR DELETE
	ON crop
	FOR EACH ROW
	EXECUTE PROCEDURE log_delta_trigger();


CREATE TRIGGER log_delta_b2b_incoming
	AFTER INSERT OR UPDATE OR DELETE
	ON b2b_incoming
	FOR EACH ROW
	EXECUTE PROCEDURE log_delta_trigger();


CREATE TRIGGER log_delta_b2b_incoming_item
	AFTER INSERT OR UPDATE OR DELETE
	ON b2b_incoming_item
	FOR EACH ROW
	EXECUTE PROCEDURE log_delta_trigger();


CREATE TRIGGER log_delta_b2b_outgoing
	AFTER INSERT OR UPDATE OR DELETE
	ON b2b_outgoing
	FOR EACH ROW
	EXECUTE PROCEDURE log_delta_trigger();


CREATE TRIGGER log_delta_b2b_outgoing_item
	AFTER INSERT OR UPDATE OR DELETE
	ON b2b_outgoing_item
	FOR EACH ROW
	EXECUTE PROCEDURE log_delta_trigger();


/*
FOR tbl IN
	SELECT c.oid
	FROM   pg_class     c
	JOIN   pg_namespace n ON n.oid = c.relnamespace
	WHERE  relkind = 'r'
	AND    n.nspname !~~ 'pg_%'
	ORDER  BY n.nspname, c.relname
	LOOP
	EXECUTE 'SELECT count(*) FROM ' || tbl || ' INTO nbrow';
*/
