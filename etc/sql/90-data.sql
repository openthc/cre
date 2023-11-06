/**
 * Base Data for the OpenTHC Cannabis Reporting Engine
 */

-- system data
INSERT INTO company (id, stat, hash, name) VALUES ('010PENTHC0C0MPANY000000000', 0, '-', '-system-');
INSERT INTO company (id, stat, hash, name) VALUES ('010PENTHC0C0MPANY000000001', 0, '-', '-orphan-');
INSERT INTO service (id, company_id, stat, hash, name) VALUES ('010PENTHC0SERV1CE000000000', '010PENTHC0C0MPANY000000000', 0, '-', '-system-');

INSERT INTO license (id, company_id, stat, hash, name) VALUES ('010PENTHC0L1CNSE0000000000', '010PENTHC0C0MPANY000000000', 0, '-', '-system-');
INSERT INTO license (id, company_id, stat, hash, name) VALUES ('010PENTHC0L1CNSE0000000001', '010PENTHC0C0MPANY000000001', 0, '-', '-orphan-');

INSERT INTO contact (id, company_id, hash, name) VALUES ('010PENTHC0C0NTACT000000000', '010PENTHC0C0MPANY000000000', '-', '-system-');


-- global data
INSERT INTO product_type (id, hash, name) VALUES ('010PENTHC0PR0DUCTTYPE00000', '-', '-system-');
INSERT INTO product_type (id, hash, name) VALUES ('010PENTHC0PR0DUCTTYPE00001', '-', '-orphan-');

INSERT INTO product_type (id, hash, name) VALUES ('010PENTHC0PTY9THKSEQ8NFS1J', '-', 'Plant/Seed');
INSERT INTO product_type (id, hash, name) VALUES ('010PENTHC0PT3EZZ4GN6105M64', '-', 'Plant/Clone');
INSERT INTO product_type (id, hash, name) VALUES ('010PENTHC0PTRPPDT8NJY2MWQW', '-', 'Plant/Plant');
INSERT INTO product_type (id, hash, name) VALUES ('010PENTHC0PT2BKFPCEFB9G1Z2', '-', 'Plant/Tissue');

INSERT INTO product_type (id, hash, name) VALUES ('010PENTHC0PTYM8J81K9HFGEMQ', '-', 'Bulk/Grade A');
INSERT INTO product_type (id, hash, name) VALUES ('010PENTHC0PTGBW49J6YD3WM84', '-', 'Bulk/Grade B');

INSERT INTO product_type (id, hash, name) VALUES ('010PENTHC0PTAF3TFBB51C8HX6', '-', 'Process/Grade A');
INSERT INTO product_type (id, hash, name) VALUES ('010PENTHC0PT8ZPGMPR8H2TAXH', '-', 'Process/Grade B');
INSERT INTO product_type (id, hash, name) VALUES ('010PENTHC0PT63ECNBAZH32YC3', '-', 'Process/Blend');

INSERT INTO product_type (id, hash, name) VALUES ('010PENTHC0PTR9M5Z9S4T31C4R', '-', 'Process/Extract/CO2');
INSERT INTO product_type (id, hash, name) VALUES ('010PENTHC0PTACC942KY9DCERR', '-', 'Process/Extract/Hash');
INSERT INTO product_type (id, hash, name) VALUES ('010PENTHC0PTNPA4TPCYSKD5XN', '-', 'Process/Extract/Kief');

INSERT INTO product_type (id, hash, name) VALUES ('010PENTHC0PTGMB39NHCZ8EDEZ', '-', 'Package/Flower');
INSERT INTO product_type (id, hash, name) VALUES ('010PENTHC0PTSF5NTC899SR0JF', '-', 'Package/Extract');
INSERT INTO product_type (id, hash, name) VALUES ('010PENTHC0PTGRX4Q9SZBHDA5Z', '-', 'Package/Mixed/Infused');
INSERT INTO product_type (id, hash, name) VALUES ('010PENTHC0PT7N83PFNCX8ZFEF', '-', 'Package/Edible/Liquid');
INSERT INTO product_type (id, hash, name) VALUES ('010PENTHC0PTBNDY5VJ8JQ6NKP', '-', 'Package/Edible/Solid');
INSERT INTO product_type (id, hash, name) VALUES ('010PENTHC0PT25F95HPG583AJB', '-', 'Package/Capsule');
INSERT INTO product_type (id, hash, name) VALUES ('010PENTHC0PTD9Q4QPFBH0G9H2', '-', 'Package/Tincture');
INSERT INTO product_type (id, hash, name) VALUES ('010PENTHC0PTHPB8YG56S0MCAC', '-', 'Package/Transdermal');
INSERT INTO product_type (id, hash, name) VALUES ('010PENTHC0PTBJ3G5FDAJN60EX', '-', 'Package/Suppository');
INSERT INTO product_type (id, hash, name) VALUES ('010PENTHC0PT8AXVZGNZN3A0QT', '-', 'Waste');

INSERT INTO product (id, license_id, product_type_id, hash, name) VALUES ('010PENTHC0PR0DUCT000000000', '010PENTHC0L1CNSE0000000000', '010PENTHC0PR0DUCTTYPE00000', '-', '-system-');
INSERT INTO product (id, license_id, product_type_id, hash, name) VALUES ('010PENTHC0PR0DUCT000000001', '010PENTHC0L1CNSE0000000000', '010PENTHC0PR0DUCTTYPE00001', '-', '-orphan-');

INSERT INTO variety (id, license_id, hash, name) VALUES ('010PENTHC0VAR1ETY000000000', '010PENTHC0L1CNSE0000000000', '-', '-system-');

INSERT INTO section (id, license_id, hash, name) VALUES ('010PENTHC0SECT10N000000000', '010PENTHC0L1CNSE0000000000', '-', '-system-');
