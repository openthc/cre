/**
 * Test Data
 */

INSERT INTO company (id, hash, name) VALUES ('010PENTHC0TEST0C0MPANY000A', '-', 'TEST - Grower');
INSERT INTO company (id, hash, name) VALUES ('010PENTHC0TEST0C0MPANY000B', '-', 'TEST - Processor');
INSERT INTO company (id, hash, name) VALUES ('010PENTHC0TEST0C0MPANY000C', '-', 'TEST - Laboratory');
INSERT INTO company (id, hash, name) VALUES ('010PENTHC0TEST0C0MPANY000D', '-', 'TEST - Retail');
INSERT INTO company (id, hash, name) VALUES ('010PENTHC0TEST0C0MPANY000E', '-', 'TEST_COMPANY_E');
INSERT INTO company (id, hash, name) VALUES ('010PENTHC0TEST0C0MPANY000F', '-', 'TEST_COMPANY_F');
INSERT INTO company (id, hash, name) VALUES ('010PENTHC0TEST0C0MPANY000G', '-', 'TEST_COMPANY_G');

INSERT INTO auth_company (id, code) VALUES ('010PENTHC0TEST0C0MPANY000A', '010PENTHC0TEST0C0MPANY000A');
INSERT INTO auth_company (id, code) VALUES ('010PENTHC0TEST0C0MPANY000B', '010PENTHC0TEST0C0MPANY000B');
INSERT INTO auth_company (id, code) VALUES ('010PENTHC0TEST0C0MPANY000C', '010PENTHC0TEST0C0MPANY000C');
INSERT INTO auth_company (id, code) VALUES ('010PENTHC0TEST0C0MPANY000D', '010PENTHC0TEST0C0MPANY000D');


INSERT INTO license (id, company_id, hash, name) VALUES ('010PENTHC0TEST0L1CENSE000A', '010PENTHC0TEST0C0MPANY000A', '-', 'TEST - Grower');
INSERT INTO license (id, company_id, hash, name) VALUES ('010PENTHC0TEST0L1CENSE000B', '010PENTHC0TEST0C0MPANY000B', '-', 'TEST - Processor');
INSERT INTO license (id, company_id, hash, name) VALUES ('010PENTHC0TEST0L1CENSE000C', '010PENTHC0TEST0C0MPANY000C', '-', 'TEST - Laboratory');
INSERT INTO license (id, company_id, hash, name) VALUES ('010PENTHC0TEST0L1CENSE000D', '010PENTHC0TEST0C0MPANY000D', '-', 'TEST - Retail');
-- INSERT INTO license (id, company_id, hash, name) VALUES ('010PENTHC0TEST0L1CENSE000E', '010PENTHC0TEST0C0MPANY000E', '-', 'TEST - Retail - B');


INSERT INTO contact (id, company_id, hash, name) VALUES ('010PENTHC0TEST0C0NTACT000A', '010PENTHC0TEST0C0MPANY000A', '-', 'TEST_CONTACT_A');
INSERT INTO contact (id, company_id, hash, name) VALUES ('010PENTHC0TEST0C0NTACT000B', '010PENTHC0TEST0C0MPANY000A', '-', 'TEST_CONTACT_B');
INSERT INTO contact (id, company_id, hash, name) VALUES ('010PENTHC0TEST0C0NTACT000C', '010PENTHC0TEST0C0MPANY000B', '-', 'TEST_CONTACT_C');
INSERT INTO contact (id, company_id, hash, name) VALUES ('010PENTHC0TEST0C0NTACT000D', '010PENTHC0TEST0C0MPANY000B', '-', 'TEST_CONTACT_D');
INSERT INTO contact (id, company_id, hash, name) VALUES ('010PENTHC0TEST0C0NTACT000E', '010PENTHC0TEST0C0MPANY000C', '-', 'TEST_CONTACT_E');
INSERT INTO contact (id, company_id, hash, name) VALUES ('010PENTHC0TEST0C0NTACT000F', '010PENTHC0TEST0C0MPANY000C', '-', 'TEST_CONTACT_F');
INSERT INTO contact (id, company_id, hash, name) VALUES ('010PENTHC0TEST0C0NTACT000G', '010PENTHC0TEST0C0MPANY000D', '-', 'TEST_CONTACT_G');
INSERT INTO contact (id, company_id, hash, name) VALUES ('010PENTHC0TEST0C0NTACT000H', '010PENTHC0TEST0C0MPANY000D', '-', 'TEST_CONTACT_H');

INSERT INTO auth_contact (id, username, password) VALUES ('010PENTHC0TEST0C0NTACT000A', 'test+a@openthc.org', '01DG9H1PSQNPKQC8HV479FZ5TM');
INSERT INTO auth_contact (id, username, password) VALUES ('010PENTHC0TEST0C0NTACT000B', 'test+b@openthc.org', '01DG9H1PSQF783Z3EPYJFX46KE');
INSERT INTO auth_contact (id, username, password) VALUES ('010PENTHC0TEST0C0NTACT000C', 'test+c@openthc.org', '01DG9H1PSQ0PGZEXAXZBA8KF62');
INSERT INTO auth_contact (id, username, password) VALUES ('010PENTHC0TEST0C0NTACT000D', 'test+d@openthc.org', '01DG9H1PSQZK955E2CDGAC53D0');
INSERT INTO auth_contact (id, username, password) VALUES ('010PENTHC0TEST0C0NTACT000E', 'test+e@openthc.org', '01DG9H1PSQ9TJRH5YG6Q0G54FD');
INSERT INTO auth_contact (id, username, password) VALUES ('010PENTHC0TEST0C0NTACT000F', 'test+f@openthc.org', '01DG9H1PSQ7EM7D1ETTZR5GAX5');


INSERT INTO service (id, company_id, hash, name) VALUES ('010PENTHC0TEST0SERV1CE000A', '010PENTHC0TEST0C0MPANY000A', '-', 'TEST SERVICE A');
INSERT INTO service (id, company_id, hash, name) VALUES ('010PENTHC0TEST0SERV1CE000B', '010PENTHC0TEST0C0MPANY000A', '-', 'TEST SERVICE B');
INSERT INTO service (id, company_id, hash, name) VALUES ('010PENTHC0TEST0SERV1CE000C', '010PENTHC0TEST0C0MPANY000A', '-', 'TEST SERVICE C');

INSERT INTO auth_service (id, company_id, name, code, hash) VALUES ('010PENTHC0TEST0SERV1CE000A', '010PENTHC0TEST0C0MPANY000A', 'TEST SERVICE A', 'test-service-a-public', 'test-service-a-secret');
INSERT INTO auth_service (id, company_id, name, code, hash) VALUES ('010PENTHC0TEST0SERV1CE000B', '010PENTHC0TEST0C0MPANY000B', 'TEST SERVICE B', 'test-service-b-public', 'test-service-b-secret');
INSERT INTO auth_service (id, company_id, name, code, hash) VALUES ('010PENTHC0TEST0SERV1CE000C', '010PENTHC0TEST0C0MPANY000C', 'TEST SERVICE C', 'test-service-c-public', 'test-service-c-secret');

INSERT INTO auth_service (id, company_id, stat, name, code, hash)
        VALUES ('010PENTHC0SERV1CE000000CRE', '010PENTHC0C0MPANY000000000', 200, 'demo CRE', 'cre.openthc.example', 'SK/cre.openthc.example');

DELETE FROM section WHERE name LIKE 'UNITTEST%';
DELETE FROM variety WHERE name LIKE 'UNITTEST%';
DELETE FROM product WHERE name LIKE 'UNITTEST%';
DELETE FROM license WHERE name LIKE 'UNITTEST%';
DELETE FROM company WHERE name LIKE 'UNITTEST%';
DELETE FROM contact WHERE name LIKE 'UNITTEST%';


-- root service -- for adminstrative API access
-- INSERT INTO service (id, company_id, hash, name) VALUES ('019KAGVSC0QPM7X728Z15ZTE37', '019KAGVSC05RHV4QAS76VPV6J7', '-', '-system-');
-- INSERT INTO auth_service (id, company_id, code) VALUES ('019KAGVSC0QPM7X728Z15ZTE37', '019KAGVSC05RHV4QAS76VPV6J7', '-system-');

-- utility records

-- INSERT INTO company (id, stat, hash, name) VALUES ('019KAGVX9M70EDAH44N1B3JNSM', 200, '-', 'leafdata-import.openthc.com');
-- INSERT INTO auth_company values ('019KAGVX9M70EDAH44N1B3JNSM', 'leafdata-import.openthc.org');

-- INSERT INTO company (id, stat, hash, name) values ('', 200, '-', 'biotrack-import.openthc.com');
-- INSERT INTO auth_company values ('', 'biotrack-import.openthc.org');

-- INSERT INTO company values ('', 200, '-', 'metrc-import.openthc.com');
-- INSERT INTO auth_company values ('', 'metrc-import.openthc.org');

-- INSERT INTO company values ('', 200, '-', 'csv-import.openthc.com');
-- INSERT INTO auth_company values ('', 'csv-import.openthc.org');
