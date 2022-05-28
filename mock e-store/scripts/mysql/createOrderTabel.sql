DROP TABLE IF EXISTS as_Order;
CREATE TABLE as_Order
(
        order_id INT NOT NULL AUTO_INCREMENT,
        customer_id INT NOT NULL,
        order_status_code VARCHAR(2) NOT NULL,
        date_order_placed DATETIME NOT NULL,
        order_details VARCHAR(120) NULL,
        PRIMARY KEY(order_id)
);

