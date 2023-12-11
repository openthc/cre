
ALTER TABLE ONLY auth_company
    ADD FOREIGN KEY (id) REFERENCES company(id);

ALTER TABLE ONLY auth_contact
    ADD FOREIGN KEY (id) REFERENCES contact(id);

ALTER TABLE ONLY auth_service
    ADD FOREIGN KEY (id) REFERENCES service(id);

ALTER TABLE ONLY auth_service
    ADD FOREIGN KEY (company_id) REFERENCES company(id);

ALTER TABLE ONLY auth_service_ticket
    ADD FOREIGN KEY (service_id) REFERENCES auth_service(id);


ALTER TABLE ONLY license
    ADD FOREIGN KEY (company_id) REFERENCES company(id);

ALTER TABLE ONLY product
    ADD FOREIGN KEY (license_id) REFERENCES license(id);

ALTER TABLE ONLY variety
    ADD FOREIGN KEY (license_id) REFERENCES license(id);

ALTER TABLE ONLY vehicle
    ADD FOREIGN KEY (license_id) REFERENCES license(id);

ALTER TABLE ONLY section
    ADD FOREIGN KEY (license_id) REFERENCES license(id);

--
--
--

ALTER TABLE ONLY inventory
    ADD FOREIGN KEY (license_id) REFERENCES license(id);

ALTER TABLE ONLY inventory
    ADD FOREIGN KEY (product_id) REFERENCES product(id);

ALTER TABLE ONLY inventory
    ADD FOREIGN KEY (variety_id) REFERENCES variety(id);

ALTER TABLE ONLY inventory
    ADD FOREIGN KEY (section_id) REFERENCES section(id);


ALTER TABLE ONLY inventory_adjust
    ADD FOREIGN KEY (inventory_id) REFERENCES inventory(id);

--
-- Inventory Family
--

ALTER TABLE ONLY inventory_family
    ADD FOREIGN KEY (inventory_id) REFERENCES inventory(id);

ALTER TABLE ONLY inventory_family
    ADD FOREIGN KEY (inventory_id_output) REFERENCES inventory(id);

ALTER TABLE ONLY inventory_family
    ADD FOREIGN KEY (crop_id) REFERENCES crop(id);

ALTER TABLE ONLY inventory_family
    ADD FOREIGN KEY (crop_collect_id) REFERENCES crop_collect(id);


--
-- Crop
--

ALTER TABLE ONLY crop
    ADD FOREIGN KEY (license_id) REFERENCES license(id);

ALTER TABLE ONLY crop
    ADD FOREIGN KEY (section_id) REFERENCES section(id);

--
--
--

ALTER TABLE ONLY lab_result
    ADD FOREIGN KEY (license_id) REFERENCES license(id);

ALTER TABLE ONLY public.lab_result_file
    ADD CONSTRAINT lab_result_file_lab_result_id_fkey FOREIGN KEY (lab_result_id) REFERENCES public.lab_result(id);

ALTER TABLE ONLY lab_result_metric
    ADD FOREIGN KEY (lab_metric_id) REFERENCES lab_metric(id);


--
--
--
ALTER TABLE ONLY b2b_incoming
    ADD FOREIGN KEY (license_id_source) REFERENCES license(id);

ALTER TABLE ONLY b2b_incoming
    ADD FOREIGN KEY (license_id_target) REFERENCES license(id);

ALTER TABLE ONLY b2b_incoming_item
    ADD FOREIGN KEY (id) REFERENCES b2b_outgoing_item(id);

ALTER TABLE ONLY b2b_incoming_item
    ADD FOREIGN KEY (b2b_incoming_id) REFERENCES b2b_incoming(id);

ALTER TABLE ONLY b2b_incoming_item
    ADD FOREIGN KEY (inventory_id) REFERENCES inventory(id);

--
--
--

-- Every Outgoing must exist as an Incoming
ALTER TABLE ONLY b2b_outgoing
    ADD FOREIGN KEY (id) REFERENCES b2b_incoming(id);

ALTER TABLE ONLY b2b_outgoing
    ADD FOREIGN KEY (license_id_source) REFERENCES license(id);

ALTER TABLE ONLY b2b_outgoing
    ADD FOREIGN KEY (license_id_target) REFERENCES license(id);

ALTER TABLE ONLY b2b_outgoing_item
    ADD FOREIGN KEY (b2b_outgoing_id) REFERENCES b2b_outgoing(id);

ALTER TABLE ONLY b2b_outgoing_item
    ADD FOREIGN KEY (inventory_id) REFERENCES inventory(id);


--
--
--

ALTER TABLE ONLY b2c_sale
    ADD FOREIGN KEY (license_id) REFERENCES license(id);

ALTER TABLE ONLY b2c_sale_item
    ADD FOREIGN KEY (b2c_sale_id) REFERENCES b2c_sale(id);

ALTER TABLE ONLY b2c_sale_item
    ADD FOREIGN KEY (inventory_id) REFERENCES inventory(id);
