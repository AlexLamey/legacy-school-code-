--#createCustomerTable.sql
--#Table_ structure for #table_ my_customers_

-- #DROP TABLE IF EXISTS my_customers;
CREATE TABLE my_customers
(
 customer_id INT NOT NULL AUTO_INCREMENT,
 salutation VARCHAR(4) NOT NULL,
 first_name VARCHAR(15) NOT NULL,
 middle_initial VARCHAR(2) NOT NULL,
 last_name VARCHAR(15) NOT NULL,
 gender VARCHAR(6) NOT NULL,
 email VARCHAR(30) NOT NULL UNIQUE,
 phone VARCHAR(15) DEFAULT NULL,
 street VARCHAR(30) NOT NULL,
 city VARCHAR(15) NOT NULL,
 region VARCHAR(2) NOT NULL,
 postal_code VARCHAR(7) NOT NULL,
 date_time DATETIME NOT NULL,
 login_name VARCHAR(15) NOT NULL,
 login_password VARCHAR(32) NOT NULL,
 PRIMARY KEY (customer_id)
);  