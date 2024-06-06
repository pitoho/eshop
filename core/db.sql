-- eshop
CREATE TABLE catalog (
  id SERIAL PRIMARY KEY,
  title VARCHAR(255),
  author VARCHAR(255),
  price INT,
  pubyear INT
);

CREATE TABLE orders (
  id SERIAL PRIMARY KEY,
  order_id VARCHAR(50) UNIQUE,
  customer VARCHAR(50),
  email VARCHAR(50),
  phone VARCHAR(50),
  address VARCHAR(255),
  datetime TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW()
);

CREATE TABLE ordered_items (
  id SERIAL PRIMARY KEY,
  order_id VARCHAR(50),
  item_id INT REFERENCES catalog(id) ON DELETE RESTRICT ON UPDATE CASCADE,
  quantity INT
);

CREATE TABLE users (
  id SERIAL PRIMARY KEY,
  login VARCHAR(255),
  password VARCHAR(255),
  email VARCHAR(50),
  created TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW()
);

-- ---------------------------
CREATE OR REPLACE FUNCTION spAddItemToCatalog(
  title VARCHAR(255),
  author VARCHAR(255),
  price INT,
  pubyear INT
) RETURNS VOID AS $$
BEGIN
  INSERT INTO catalog (title, author, price, pubyear)
  VALUES (title, author, price, pubyear);
END;
$$ LANGUAGE plpgsql;


CREATE OR REPLACE FUNCTION spGetCatalog() RETURNS TABLE (
  id INT,
  title VARCHAR(255),
  author VARCHAR(255),
  price INT,
  pubyear INT
) AS $$
BEGIN
	RETURN QUERY
    SELECT * FROM catalog;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION spGetItemsForBasket(ids VARCHAR(255)) RETURNS TABLE (
  id INT,
  title VARCHAR(255),
  author VARCHAR(255),
  price INT,
  pubyear INT
) AS $$
BEGIN
  RETURN QUERY
    SELECT * FROM catalog
    WHERE catalog.id = ANY(string_to_array(ids, ',')::INT[]);
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION spSaveOrder(
  order_id VARCHAR(50),
  customer VARCHAR(50),
  email VARCHAR(50),
  phone VARCHAR(50),
  address VARCHAR(255)
) RETURNS VOID AS $$
BEGIN
  INSERT INTO orders (order_id, customer, email, phone, address)
  VALUES (order_id, customer, email, phone, address);
END;
$$ LANGUAGE plpgsql;

	

CREATE OR REPLACE FUNCTION spSaveOrderedItems(
  order_id VARCHAR(50),
  item_id INT,
  quantity INT
) RETURNS VOID AS $$
BEGIN
  INSERT INTO ordered_items (order_id, item_id, quantity)
  VALUES (order_id, item_id, quantity);
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION spGetOrders() RETURNS TABLE (
  id INT,
  customer VARCHAR(50),
  email VARCHAR(50),
  phone VARCHAR(50),
  address VARCHAR(255),
  date BIGINT
) AS $$
BEGIN
  RETURN QUERY
    SELECT orders.id, orders.customer, orders.email, orders.phone, orders.address, CAST(EXTRACT(EPOCH FROM datetime) AS BIGINT) AS date
    FROM orders;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION spGetOrderedItems(order_id VARCHAR(255)) RETURNS TABLE (
  title VARCHAR(255),
  author VARCHAR(255),
  price INT,
  pubyear INT,
  quantity INT
) AS $$
BEGIN
  RETURN QUERY
    SELECT c.title, c.author, c.price, c.pubyear, oi.quantity
    FROM catalog c
    JOIN ordered_items oi ON c.id = oi.item_id
    WHERE oi.order_id = oi.order_id;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION spSaveUser(
  u_login VARCHAR(255),
  u_password VARCHAR(255),
  u_email VARCHAR(255)
) RETURNS VOID AS $$
BEGIN
  INSERT INTO users (login, password, email)
  VALUES (u_login, u_password, u_email);
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION spGetUser(u_login VARCHAR(255)) RETURNS TABLE (
  id INT,
  login VARCHAR(255),
  hash VARCHAR(255),
  email VARCHAR(50)
) AS $$
BEGIN
  RETURN QUERY
    SELECT u.id, u.login, u.password as hash, u.email  -- Добавлен email
    FROM users u
    WHERE u.login = u_login;
END;
$$ LANGUAGE plpgsql;

select spGetUser('pitoho')

select * from users




