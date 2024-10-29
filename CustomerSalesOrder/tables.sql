CREATE TABLE product (
    id int AUTO_INCREMENT PRIMARY KEY,
    name varchar(100) NOT NULL,
    price decimal(10,2) NOT NULL
);

CREATE TABLE orders (
    id int AUTO_INCREMENT PRIMARY KEY,
    cust_ref varchar(100) NOT NULL,
    order_date date NOT NULL,
    total decimal(10,2)
);

CREATE TABLE order_products (
    id int AUTO_INCREMENT PRIMARY KEY,
    order_id int,
    product_id int,
    qty int,
    discount decimal(5,2),
    subtotal decimal(10,2),
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES product(id)
);

INSERT INTO product (name, price) VALUES
    ('Battery', 50000),
    ('Charger', 100000);