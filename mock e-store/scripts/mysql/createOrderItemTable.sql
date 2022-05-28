DROP TABLE IF EXISTS as_Order_Items;
create table as_Order_Items 
(
        order_item_name VARCHAR(40) NOT NULL,
        order_item_status_code VARCHAR(2) NOT NULL,
        order_item_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
        product_item_id INT NOT NULL,
        order_item_quantity INT NOT NULL,
        order_item_price DOUBLE(6,2) NOT NULL,
        other_order_item_details VARCHAR(120) NULL
)
;