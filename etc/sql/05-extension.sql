/**
 * The pg_ulid modules must be built & installed first
 */

CREATE EXTENSION pg_ulid;

CREATE FUNCTION ulid_create() RETURNS text
AS '$libdir/pg_ulid'
LANGUAGE C; -- IMUTABLE STRICT
