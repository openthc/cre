/**
 * Base Data for an OpenTHC Cannabis Reporting Engine
 */

-- system records
INSERT INTO company (id, stat, hash, name) VALUES ('018NY6XC00CP00000000000000', 0, '-', '-system-');
INSERT INTO license (id, company_id, stat, hash, name) VALUES ('018NY6XC00LC00000000000000', '018NY6XC00CP00000000000000', 0, '-', '-system-');

INSERT INTO company (id, stat, hash, name) VALUES ('018NY6XC00CP00000000000001', 0, '-', '-unknown-');
INSERT INTO license (id, company_id, stat, hash, name) VALUES ('018NY6XC00LC00000000000001', '018NY6XC00CP00000000000001', 0, '-', '-unknown-');

INSERT INTO contact (id, company_id, hash, name) VALUES ('018NY6XC00CT00000000000000', '018NY6XC00CP00000000000000', '-', '-system-');

INSERT INTO product_type (id, hash, name) VALUES ('018NY6XC00PT00000000000000', '-', '-system-');
INSERT INTO product (id, license_id, product_type_id, hash, name) VALUES ('018NY6XC00PR00000000000000', '018NY6XC00LC00000000000000', '018NY6XC00PT00000000000000', '-', '-system-');

INSERT INTO product_type (id, hash, name) VALUES ('018NY6XC00PT00000000000001', '-', '-unknown-');
INSERT INTO product (id, license_id, product_type_id, hash, name) VALUES ('018NY6XC00PR00000000000001', '018NY6XC00LC00000000000001', '018NY6XC00PT00000000000001', '-', '-unknown-');

INSERT INTO product_type (id, hash, name) VALUES ('018NY6XC00PTY9THKSEQ8NFS1J', '-', 'Plant/Seed');
INSERT INTO product_type (id, hash, name) VALUES ('018NY6XC00PT3EZZ4GN6105M64', '-', 'Plant/Clone');
INSERT INTO product_type (id, hash, name) VALUES ('018NY6XC00PTRPPDT8NJY2MWQW', '-', 'Plant/Plant');
INSERT INTO product_type (id, hash, name) VALUES ('018NY6XC00PT2BKFPCEFB9G1Z2', '-', 'Plant/Tissue');

INSERT INTO product_type (id, hash, name) VALUES ('018NY6XC00PTYM8J81K9HFGEMQ', '-', 'Raw/Grade A');
INSERT INTO product_type (id, hash, name) VALUES ('018NY6XC00PTGBW49J6YD3WM84', '-', 'Raw/Grade B');

INSERT INTO product_type (id, hash, name) VALUES ('018NY6XC00PTAF3TFBB51C8HX6', '-', 'Process/Grade A');
INSERT INTO product_type (id, hash, name) VALUES ('018NY6XC00PT8ZPGMPR8H2TAXH', '-', 'Process/Grade B');
INSERT INTO product_type (id, hash, name) VALUES ('018NY6XC00PT63ECNBAZH32YC3', '-', 'Process/Blend');

INSERT INTO product_type (id, hash, name) VALUES ('018NY6XC00PTR9M5Z9S4T31C4R', '-', 'Process/Extract/CO2');
INSERT INTO product_type (id, hash, name) VALUES ('018NY6XC00PTACC942KY9DCERR', '-', 'Process/Extract/Hash');
INSERT INTO product_type (id, hash, name) VALUES ('018NY6XC00PTNPA4TPCYSKD5XN', '-', 'Process/Extract/Kief');

INSERT INTO product_type (id, hash, name) VALUES ('018NY6XC00PTGMB39NHCZ8EDEZ', '-', 'Package/Flower');
INSERT INTO product_type (id, hash, name) VALUES ('018NY6XC00PTSF5NTC899SR0JF', '-', 'Package/Extract');
INSERT INTO product_type (id, hash, name) VALUES ('018NY6XC00PTGRX4Q9SZBHDA5Z', '-', 'Package/Mixed/Infused');
INSERT INTO product_type (id, hash, name) VALUES ('018NY6XC00PT7N83PFNCX8ZFEF', '-', 'Package/Edible/Liquid');
INSERT INTO product_type (id, hash, name) VALUES ('018NY6XC00PTBNDY5VJ8JQ6NKP', '-', 'Package/Edible/Solid');
INSERT INTO product_type (id, hash, name) VALUES ('018NY6XC00PT25F95HPG583AJB', '-', 'Package/Capsule');
INSERT INTO product_type (id, hash, name) VALUES ('018NY6XC00PTD9Q4QPFBH0G9H2', '-', 'Package/Tincture');
INSERT INTO product_type (id, hash, name) VALUES ('018NY6XC00PTHPB8YG56S0MCAC', '-', 'Package/Transdermal');
INSERT INTO product_type (id, hash, name) VALUES ('018NY6XC00PTBJ3G5FDAJN60EX', '-', 'Package/Suppository');

INSERT INTO product_type (id, hash, name) VALUES ('018NY6XC00PT8AXVZGNZN3A0QT', '-', 'Waste');

INSERT INTO variety (id, license_id, hash, name) VALUES ('018NY6XC00VR00000000000000', '018NY6XC00LC00000000000000', '-', '-system-');

INSERT INTO section (id, license_id, hash, name) VALUES ('018NY6XC00ZN00000000000000', '018NY6XC00LC00000000000000', '-', '-system-');
