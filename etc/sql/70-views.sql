--
---
--


CREATE VIEW transfer AS SELECT
 o.id AS id
, o.license_id_source
, o.license_id_target
, o.stat AS b2b_outgoing_stat
, i.stat AS b2b_incoming_stat
FROM b2b_outgoing AS o
JOIN b2b_incoming AS i ON o.id = i.id;


CREATE VIEW b2b_item AS SELECT
 toi.id AS id
, toi.b2b_outgoing_id AS b2b_id
, toi.lot_id AS source_lot_id
, toi.lot_id AS target_lot_id
, toi.stat AS b2b_outgoing_item_stat
, tii.stat AS b2b_incoming_item_stat
FROM b2b_outgoing_item AS toi
JOIN b2b_incoming_item AS tii ON toi.id = tii.id;
