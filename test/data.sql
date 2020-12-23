/**
 * Test Data
 */

INSERT INTO company (id, hash, name) VALUES ('019KAGVX9M209XSPAMRCXSRYF2', '-', 'TEST - Grower');
INSERT INTO company (id, hash, name) VALUES ('019KAGVX9MY0BZDEJFRXBW82T4', '-', 'TEST - Processor');
INSERT INTO company (id, hash, name) VALUES ('019KAGVX9MXFHHT41X08Y6JX23', '-', 'TEST - Laboratory');
INSERT INTO company (id, hash, name) VALUES ('019KAGVX9MH3QJW8QGE7CXNAAQ', '-', 'TEST - Retail');
INSERT INTO company (id, hash, name) VALUES ('019KAGVX90R6XXEYT0NNB8SRRW', '-', 'TEST_COMPANY_E'); -- Retail
INSERT INTO company (id, hash, name) VALUES ('019KAGVX90VWP5ADM29VY7M8GA', '-', 'TEST_COMPANY_F');
INSERT INTO company (id, hash, name) VALUES ('019KAGVX90R042KKP7YPQXC51C', '-', 'TEST_COMPANY_G');

INSERT INTO auth_company (id, code) VALUES ('019KAGVX9M209XSPAMRCXSRYF2', '01DG9H1PSQG8VF1M0W3FANX4JE');
INSERT INTO auth_company (id, code) VALUES ('019KAGVX9MY0BZDEJFRXBW82T4', '01DG9H1PSQ3CW3DSM95YQ9D9DC');
INSERT INTO auth_company (id, code) VALUES ('019KAGVX9MXFHHT41X08Y6JX23', '01DG9H1PSQ6377JJ4XAE4SCWCV');
INSERT INTO auth_company (id, code) VALUES ('019KAGVX9MH3QJW8QGE7CXNAAQ', '01DG9H1PSQHQRQM8FRAGD5GTWH');


INSERT INTO license (id, company_id, hash, name) VALUES ('019KAGVX9MNVQVXP6A24ND2CBW', '019KAGVX9M209XSPAMRCXSRYF2', '-', 'TEST - Grower');
INSERT INTO license (id, company_id, hash, name) VALUES ('019KAGVX9MQQS4S9MC1WX5A76G', '019KAGVX9MY0BZDEJFRXBW82T4', '-', 'TEST - Processor');
INSERT INTO license (id, company_id, hash, name) VALUES ('019KAGVX9MCGZD0YMFKRHRV2F1', '019KAGVX9MY0BZDEJFRXBW82T4', '-', 'TEST - Processor - B');
INSERT INTO license (id, company_id, hash, name) VALUES ('019KAGVX9MMGS6HQZ6787CB92R', '019KAGVX9MXFHHT41X08Y6JX23', '-', 'TEST - Laboratory');
INSERT INTO license (id, company_id, hash, name) VALUES ('019KAGVX9MD60MW90N4GTB75Y1', '019KAGVX9MH3QJW8QGE7CXNAAQ', '-', 'TEST - Retail');
INSERT INTO license (id, company_id, hash, name) VALUES ('019KAGVX9MX1D3B84TP8GQNQ7K', '019KAGVX9MH3QJW8QGE7CXNAAQ', '-', 'TEST - Retail - B');


INSERT INTO contact (id, company_id, hash, name) VALUES ('019KAGVX9MSWAC5E0AZK1TNKED', '019KAGVX9M209XSPAMRCXSRYF2', '-', 'TEST_CONTACT_A');
INSERT INTO contact (id, company_id, hash, name) VALUES ('019KAGVX9M9HS5922QDYTJSPTA', '019KAGVX9M209XSPAMRCXSRYF2', '-', 'TEST_CONTACT_B');
INSERT INTO contact (id, company_id, hash, name) VALUES ('019KAGVX9MR4QW6XFFHF01ABRM', '019KAGVX9MY0BZDEJFRXBW82T4', '-', 'TEST_CONTACT_C');
INSERT INTO contact (id, company_id, hash, name) VALUES ('019KAGVX9MQKX23QCC53E86WJX', '019KAGVX9MY0BZDEJFRXBW82T4', '-', 'TEST_CONTACT_D');
INSERT INTO contact (id, company_id, hash, name) VALUES ('019KAGVX9MVQYGR3X21FAZHA5R', '019KAGVX9MXFHHT41X08Y6JX23', '-', 'TEST_CONTACT_E');
INSERT INTO contact (id, company_id, hash, name) VALUES ('019KAGVX9M279CP0C727WGNDFH', '019KAGVX9MXFHHT41X08Y6JX23', '-', 'TEST_CONTACT_F');
INSERT INTO contact (id, company_id, hash, name) VALUES ('019KAGVX9ME984HQCWNKJK1Y6D', '019KAGVX9MH3QJW8QGE7CXNAAQ', '-', 'TEST_CONTACT_G');
INSERT INTO contact (id, company_id, hash, name) VALUES ('019KAGVX9MW22K65EW5RT81YG3', '019KAGVX9MH3QJW8QGE7CXNAAQ', '-', 'TEST_CONTACT_H');

INSERT INTO auth_contact (id, username, password) VALUES ('019KAGVX9MSWAC5E0AZK1TNKED', 'test+a@openthc.org', '01DG9H1PSQNPKQC8HV479FZ5TM');
INSERT INTO auth_contact (id, username, password) VALUES ('019KAGVX9M9HS5922QDYTJSPTA', 'test+b@openthc.org', '01DG9H1PSQF783Z3EPYJFX46KE');
INSERT INTO auth_contact (id, username, password) VALUES ('019KAGVX9MR4QW6XFFHF01ABRM', 'test+c@openthc.org', '01DG9H1PSQ0PGZEXAXZBA8KF62');
INSERT INTO auth_contact (id, username, password) VALUES ('019KAGVX9MQKX23QCC53E86WJX', 'test+d@openthc.org', '01DG9H1PSQZK955E2CDGAC53D0');
-- INSERT INTO auth_contact (id, username, password) VALUES ('', 'test+f@openthc.org', '01DG9H1PSQ9TJRH5YG6Q0G54FD');
-- INSERT INTO auth_contact (id, username, password) VALUES ('', 'test+g@openthc.org', '01DG9H1PSQ7EM7D1ETTZR5GAX5');


INSERT INTO program (id, company_id, hash, name) VALUES ('019KAGVSC04QR1WM5H86QRCGXG', '019KAGVX90R6XXEYT0NNB8SRRW', '-', 'test-program-e');
INSERT INTO program (id, company_id, hash, name) VALUES ('019KAGVSC0NHRP3ANZD1YT9ZQ4', '019KAGVX90VWP5ADM29VY7M8GA', '-', 'test-program-f');
INSERT INTO program (id, company_id, hash, name) VALUES ('019KAGVSC0XHVSZMJGXA37B05S', '019KAGVX90R042KKP7YPQXC51C', '-', 'test-program-g');

INSERT INTO auth_program (id, company_id, code) VALUES ('019KAGVSC04QR1WM5H86QRCGXG', '019KAGVX90R6XXEYT0NNB8SRRW', 'test-program-e-public');
INSERT INTO auth_program (id, company_id, code) VALUES ('019KAGVSC0NHRP3ANZD1YT9ZQ4', '019KAGVX90VWP5ADM29VY7M8GA', 'test-program-f-public');
INSERT INTO auth_program (id, company_id, code) VALUES ('019KAGVSC0XHVSZMJGXA37B05S', '019KAGVX90R042KKP7YPQXC51C', 'test-program-g-public');

--
-- Run these manually, then trap the results
--
-- INSERT INTO auth_program_secret (id, program_id, code) VALUES (ulid_create(), '019KAGVSC04QR1WM5H86QRCGXG', 'test-program-e-secret-0');
-- INSERT INTO auth_program_secret (id, program_id, code) VALUES (ulid_create(), '019KAGVSC04QR1WM5H86QRCGXG', 'test-program-e-secret-1');
-- INSERT INTO auth_program_secret (id, program_id, code) VALUES (ulid_create(), '019KAGVSC0NHRP3ANZD1YT9ZQ4', 'test-program-f-secret-0');
-- INSERT INTO auth_program_secret (id, program_id, code) VALUES (ulid_create(), '019KAGVSC0XHVSZMJGXA37B05S', 'test-program-g-secret-0');


DELETE FROM zone    WHERE name LIKE 'UNITTEST%';
DELETE FROM strain  WHERE name LIKE 'UNITTEST%';
DELETE FROM license WHERE name LIKE 'UNITTEST%';
DELETE FROM contact WHERE name LIKE 'UNITTEST%';
DELETE FROM company WHERE name LIKE 'UNITTEST%';
DELETE FROM product WHERE name LIKE 'UNITTEST%';
