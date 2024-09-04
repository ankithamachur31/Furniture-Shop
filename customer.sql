-- Assuming you have a 'customer' table with the specified columns
CREATE TABLE customer (
    customerId SERIAL PRIMARY KEY,
    firstName VARCHAR(50),
    lastName VARCHAR(50),
    email VARCHAR(100),
    password VARCHAR(50),
    address VARCHAR(255),
    postCode VARCHAR(20),
    lastModified TIMESTAMP
);

-- Create a function that updates the 'lastModified' timestamp
CREATE OR REPLACE FUNCTION update_last_modified()
RETURNS TRIGGER AS $$
BEGIN
    NEW.lastModified := NOW(); -- Set the 'lastModified' field to the current timestamp
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Create a trigger that fires the 'update_last_modified' function after an UPDATE operation on the 'customer' table
CREATE TRIGGER customer_update_trigger
AFTER UPDATE ON customer
FOR EACH ROW
EXECUTE FUNCTION update_last_modified();
