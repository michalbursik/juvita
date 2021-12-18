Hlavni sklad
Prirucni sklady

ADMIN user does not need to have WAREHOUSE, or he will have MAIN WAREHOUSE ?

users: admins, employees

USERS 1-N WAREHOUSES
WAREHOUSES M-N PRODUCTS

Warehouses:
name

Users:
first_name, last_name, email, password, role, warehouse_id

Product_warehouse:
product_id, warehouse_id, count, price

Products:
name, image, unit

StorageMovements
storage_id, user_id, type (issue/receipt/transmission), timestamps

StorageMovement:
WarehouseReceiptEvent: Modify main warehouse
WarehouseIssueEvent: modify given warehouse
WarehouseTransmissionEvent: Move from one to another warehouse (two records)
