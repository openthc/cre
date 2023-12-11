/**
	Set the updated_at Value
*/
CREATE FUNCTION set_updated_at_trigger()
RETURNS TRIGGER AS
$FUNC$
BEGIN
	NEW.updated_at = now();
	RETURN NEW;
END;
$FUNC$
LANGUAGE 'plpgsql' SECURITY DEFINER;

/**
 * All Table
 */
CREATE TRIGGER set_updated_at_contact
	BEFORE UPDATE
	ON contact
	FOR EACH ROW
	EXECUTE PROCEDURE set_updated_at_trigger();


CREATE TRIGGER set_updated_at_company
	BEFORE UPDATE
	ON company
	FOR EACH ROW
	EXECUTE PROCEDURE set_updated_at_trigger();


CREATE TRIGGER set_updated_at_license
        BEFORE UPDATE
        ON license
        FOR EACH ROW
        EXECUTE PROCEDURE set_updated_at_trigger();


CREATE TRIGGER set_updated_at_section
        BEFORE UPDATE
        ON section
        FOR EACH ROW
        EXECUTE PROCEDURE set_updated_at_trigger();


CREATE TRIGGER set_updated_at_variety
        BEFORE UPDATE
        ON variety
        FOR EACH ROW
        EXECUTE PROCEDURE set_updated_at_trigger();


CREATE TRIGGER set_updated_at_product
        BEFORE UPDATE
        ON product
        FOR EACH ROW
        EXECUTE PROCEDURE set_updated_at_trigger();


CREATE TRIGGER set_updated_at_vehicle
        BEFORE UPDATE
        ON vehicle
        FOR EACH ROW
        EXECUTE PROCEDURE set_updated_at_trigger();


CREATE TRIGGER set_updated_at_crop
        BEFORE UPDATE
        ON crop
        FOR EACH ROW
        EXECUTE PROCEDURE set_updated_at_trigger();


CREATE TRIGGER set_updated_at_crop_collect
        BEFORE UPDATE
        ON crop_collect
        FOR EACH ROW
        EXECUTE PROCEDURE set_updated_at_trigger();


CREATE TRIGGER set_updated_at_inventory
        BEFORE UPDATE
        ON inventory
        FOR EACH ROW
        EXECUTE PROCEDURE set_updated_at_trigger();


CREATE TRIGGER set_updated_at_lab_result
        BEFORE UPDATE
        ON lab_result
        FOR EACH ROW
        EXECUTE PROCEDURE set_updated_at_trigger();


CREATE TRIGGER set_updated_at_b2b_incoming
        BEFORE UPDATE
        ON b2b_incoming
        FOR EACH ROW
        EXECUTE PROCEDURE set_updated_at_trigger();


CREATE TRIGGER set_updated_at_b2b_incoming_item
        BEFORE UPDATE
        ON b2b_incoming_item
        FOR EACH ROW
        EXECUTE PROCEDURE set_updated_at_trigger();


CREATE TRIGGER set_updated_at_b2b_outgoing
        BEFORE UPDATE
        ON b2b_outgoing
        FOR EACH ROW
        EXECUTE PROCEDURE set_updated_at_trigger();


CREATE TRIGGER set_updated_at_b2b_outgoing_item
        BEFORE UPDATE
        ON b2b_outgoing_item
        FOR EACH ROW
        EXECUTE PROCEDURE set_updated_at_trigger();


CREATE TRIGGER set_updated_at_b2c_sale
        BEFORE UPDATE
        ON b2c_sale
        FOR EACH ROW
        EXECUTE PROCEDURE set_updated_at_trigger();


CREATE TRIGGER set_updated_at_b2c_sale_item
        BEFORE UPDATE
        ON b2c_sale_item
        FOR EACH ROW
        EXECUTE PROCEDURE set_updated_at_trigger();
