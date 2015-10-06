create table cart(id INT NOT NULL Auto_INCREMENT,
				  Primary key (id));

create table user (id INT(11) NOT NULL Auto_INCREMENT ,
				   first_name varchar(255) ,
				   last_name varchar(255), 
				   email varchar(255) NOT NULL UNIQUE, 
				   password_hash varchar(255) NOT NULL, 
				   credit float Default 0, avatar varchar(255), 
				   cart_id int NOT NULL, PRIMARY KEY (id), 
				   Foreign Key (cart_id) REFERENCES cart (id));

create table item(id INT Auto_INCREMENT NOT Null,
				  name varchar(255),
				  price float Default 0,
				  quantity int DEFAULT 1 , 
				  primary key(id));

create table cart_item(id INT NOT null Auto_INCREMENT,cart_id INT,
       order_quantity INT Default 1,
       primary key(id),
       item_id int ,
       foreign key(cart_id) REFERENCES cart(id),
       foreign key(item_id) REFERENCES item(id));
UPDATE item SET quantity = 10;

INSERT INTO item (name,price,quantity)  VALUES ("product1",10,2);

CREATE TABLE purchase_history(id INT not null Auto_Increment,user_id int NOT NULL,product_name varchar(255) NOT NULL, product_price float NOT NULL,order_quantity int NOT NULL,purchase_time varchar(255) NOT NULL,primary key(id),foreign key (user_id) REFERENCES user(id));
