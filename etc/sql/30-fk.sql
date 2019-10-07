
ALTER TABLE ONLY auth_company
    ADD FOREIGN KEY (id) REFERENCES company(id);

ALTER TABLE ONLY auth_contact
    ADD FOREIGN KEY (id) REFERENCES contact(id);

ALTER TABLE ONLY auth_program
    ADD FOREIGN KEY (id) REFERENCES program(id);

ALTER TABLE ONLY auth_program
    ADD FOREIGN KEY (company_id) REFERENCES company(id);

ALTER TABLE ONLY auth_program_secret
    ADD FOREIGN KEY (program_id) REFERENCES auth_program(id);


ALTER TABLE ONLY license
    ADD FOREIGN KEY (company_id) REFERENCES company(id);

ALTER TABLE ONLY product
    ADD FOREIGN KEY (license_id) REFERENCES license(id);

ALTER TABLE ONLY strain
    ADD FOREIGN KEY (license_id) REFERENCES license(id);

ALTER TABLE ONLY zone
    ADD FOREIGN KEY (license_id) REFERENCES license(id);

--
--
--

ALTER TABLE ONLY lot
    ADD FOREIGN KEY (license_id) REFERENCES license(id);

ALTER TABLE ONLY lot
    ADD FOREIGN KEY (product_id) REFERENCES product(id);

ALTER TABLE ONLY lot
    ADD FOREIGN KEY (strain_id) REFERENCES strain(id);

ALTER TABLE ONLY lot
    ADD FOREIGN KEY (zone_id) REFERENCES zone(id);

--
--
--

ALTER TABLE ONLY plant
    ADD FOREIGN KEY (license_id) REFERENCES license(id);

ALTER TABLE ONLY plant
    ADD FOREIGN KEY (zone_id) REFERENCES zone(id);

--
--
--

ALTER TABLE ONLY lab_result
    ADD FOREIGN KEY (license_id) REFERENCES license(id);


ALTER TABLE ONLY lab_result_metric
    ADD FOREIGN KEY (lab_metric_id) REFERENCES lab_metric(id);


--
--
--
ALTER TABLE ONLY b2b_incoming
    ADD FOREIGN KEY (source_license_id) REFERENCES license(id);

ALTER TABLE ONLY b2b_incoming
    ADD FOREIGN KEY (target_license_id) REFERENCES license(id);

ALTER TABLE ONLY b2b_incoming_item
    ADD FOREIGN KEY (id) REFERENCES b2b_outgoing_item(id);

ALTER TABLE ONLY b2b_incoming_item
    ADD FOREIGN KEY (b2b_incoming_id) REFERENCES b2b_incoming(id);

ALTER TABLE ONLY b2b_incoming_item
    ADD FOREIGN KEY (lot_id) REFERENCES lot(id);

--
--
--

-- Every Outgoing must exist as an Incoming
ALTER TABLE ONLY b2b_outgoing
    ADD FOREIGN KEY (id) REFERENCES b2b_incoming(id);

ALTER TABLE ONLY b2b_outgoing
    ADD FOREIGN KEY (source_license_id) REFERENCES license(id);

ALTER TABLE ONLY b2b_outgoing
    ADD FOREIGN KEY (target_license_id) REFERENCES license(id);

ALTER TABLE ONLY b2b_outgoing_item
    ADD FOREIGN KEY (b2b_outgoing_id) REFERENCES b2b_outgoing(id);

ALTER TABLE ONLY b2b_outgoing_item
    ADD FOREIGN KEY (lot_id) REFERENCES lot(id);


--
--
--

ALTER TABLE ONLY retail_sale
    ADD FOREIGN KEY (license_id) REFERENCES license(id);

ALTER TABLE ONLY retail_sale_item
    ADD FOREIGN KEY (retail_sale_id) REFERENCES retail_sale(id);

ALTER TABLE ONLY retail_sale_item
    ADD FOREIGN KEY (lot_id) REFERENCES lot(id);