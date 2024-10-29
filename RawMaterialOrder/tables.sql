CREATE TABLE material (
    id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name varchar(100) NOT NULL,
    days int(11) NOT NULL
);

CREATE TABLE orders (
    id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    order_no varchar(20) NOT NULL,
    client varchar(100) NOT NULL,
    work_date date NOT NULL
);

CREATE TABLE order_materials (
    id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    order_id int(11) NOT NULL,
    material_id int(11) NOT NULL,
    days int(11) NOT NULL,
    percentage decimal(5, 2) NOT NULL,
    finish_date date NOT NULL, 
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (material_id) REFERENCES material(id)
);

INSERT INTO material(name, days) VALUES
    ('Cable', 2),
    ('Bolt', 1),
    ('Cap', 3)
