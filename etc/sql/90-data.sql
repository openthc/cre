/**
 * Base Data for an OpenTHC Cannabis Reporting Engine
 */

-- system records
INSERT INTO company (id, stat, hash, name) values ('019KAGVSC05RHV4QAS76VPV6J7',   0, '-', '-system-');
INSERT INTO license (id, company_id, hash, name) values ('019KAGVX9M1FRBJ7EZQDTMD6JA', '019KAGVSC05RHV4QAS76VPV6J7', '-', '-system-');
INSERT INTO contact (id, company_id, hash, name) values ('019KAGVX9MQRRV9H0G9N3Q9FMC', '019KAGVSC05RHV4QAS76VPV6J7', '-', '-system-');
INSERT INTO product (id, license_id,hash,name) values ('019KAGVX9MYDYS8M2FNABNKGV1', '019KAGVX9M1FRBJ7EZQDTMD6JA', '-', '-system-');
INSERT INTO strain (id, license_id, hash,name) values ('019KAGVX9MK1NZWTN7D14F09FC', '019KAGVX9M1FRBJ7EZQDTMD6JA', '-', '-system-');
INSERT INTO zone (id, license_id, hash,name) values ('019KAGVX9MYQCNKPGWMCA49EGW', '019KAGVX9M1FRBJ7EZQDTMD6JA', '-', '-system-');


INSERT INTO product_type (id, hash, name) VALUES ('019KAGVSC0C474J20SEWDM5XSJ', '-', 'Plant/Seed');
INSERT INTO product_type (id, hash, name) VALUES ('019KAGVSC0J008XMJ25DCBK17P', '-', 'Plant/Clone');
INSERT INTO product_type (id, hash, name) VALUES ('019KAGVSC0KANK9BMYFS5BDFCB', '-', 'Plant/Plant');
INSERT INTO product_type (id, hash, name) VALUES ('019KAGVSC0AT9P3779ATHDK6MC', '-', 'Plant/Tissue');

INSERT INTO product_type (id, hash, name) VALUES ('019KAGVSC01MVH9QAZ75KEPY4D', '-', 'Raw/Flower');
INSERT INTO product_type (id, hash, name) VALUES ('019KAGVSC05QXA1BCA13PNAK5J', '-', 'Raw/Trim');
-- INSERT INTO product_type (id, hash, name) VALUES ('', '-', 'Flower/Lot');
-- INSERT INTO product_type (id, hash, name) VALUES ('', '-', 'Trim/Raw');
-- INSERT INTO product_type (id, hash, name) VALUES ('', '-', 'Trim/Lot');

INSERT INTO product_type (id, hash, name) VALUES ('019KAGVSC0CYZQ68Q2184AE10A', '-', 'Process/Flower');
INSERT INTO product_type (id, hash, name) VALUES ('019KAGVSC0W8ESY93TK05TPFKN', '-', 'Process/Trim');
INSERT INTO product_type (id, hash, name) VALUES ('019KAGVSC0Q16ECBP02ET3RRMT', '-', 'Process/Mix');

INSERT INTO product_type (id, hash, name) VALUES ('019KAGVSC06Y7WBPCY2XNHP2FE', '-', 'Process/Extract/CO2');
INSERT INTO product_type (id, hash, name) VALUES ('019KAGVSC0K109NHQ92CCKEJW5', '-', 'Process/Extract/Hash');
INSERT INTO product_type (id, hash, name) VALUES ('019KAGVSC0VX8100VFN3YXKBYH', '-', 'Process/Extract/Kief');

INSERT INTO product_type (id, hash, name) VALUES ('019KAGVSC0WMF1XY879SECK50W', '-', 'Package/Flower');
INSERT INTO product_type (id, hash, name) VALUES ('019KAGVSC03MRD8MDZJXGM5MXF', '-', 'Package/Edible/Liquid');
INSERT INTO product_type (id, hash, name) VALUES ('019KAGVSC0QXVE5AC12DCNM6RS', '-', 'Package/Edible/Solid');
INSERT INTO product_type (id, hash, name) VALUES ('019KAGVSC0ZHAEQCFNYMXDXWKV', '-', 'Package/Capsule');
INSERT INTO product_type (id, hash, name) VALUES ('019KAGVSC062S4TRBT5FBJW98V', '-', 'Package/Tincture');
INSERT INTO product_type (id, hash, name) VALUES ('019KAGVSC0EFWWVV8DE89JVNTY', '-', 'Package/Transdermal');
INSERT INTO product_type (id, hash, name) VALUES ('019KAGVSC02549AK0RQWFAMNVB', '-', 'Package/Suppository');

-- INSERT INTO product_type (id, hash, name) VALUES ('', '-', 'Package/Tincture');
-- 019KAGVSC0XJFGAZ56F4K9MCBJ


-- root program -- for adminstrative API access
INSERT INTO program (id, company_id, hash, name) VALUES ('019KAGVSC0QPM7X728Z15ZTE37', '019KAGVSC05RHV4QAS76VPV6J7', '-', '-system-');
INSERT INTO auth_program (id, company_id, code) VALUES ('019KAGVSC0QPM7X728Z15ZTE37', '019KAGVSC05RHV4QAS76VPV6J7', '-system-');
INSERT INTO auth_program_secret (id, program_id, code) VALUES (ulid_create(), '019KAGVSC0QPM7X728Z15ZTE37', 'system-program-secret');


-- utility records

-- INSERT INTO company (id, stat, hash, name) VALUES ('019KAGVX9M70EDAH44N1B3JNSM', 200, '-', 'leafdata-import.openthc.com');
-- INSERT INTO auth_company values ('019KAGVX9M70EDAH44N1B3JNSM', 'leafdata-import.openthc.org');

-- INSERT INTO company (id, stat, hash, name) values ('', 200, '-', 'biotrack-import.openthc.com');
-- INSERT INTO auth_company values ('', 'biotrack-import.openthc.org');

-- INSERT INTO company values ('', 200, '-', 'metrc-import.openthc.com');
-- INSERT INTO auth_company values ('', 'metrc-import.openthc.org');

-- INSERT INTO company values ('', 200, '-', 'csv-import.openthc.com');
-- INSERT INTO auth_company values ('', 'csv-import.openthc.org');
