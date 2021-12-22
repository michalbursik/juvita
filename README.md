ADD PRICES
- prices on weekly bases
- products can have more prices (valid till end of week - friday/sunday)
- manually put price when receipt to warehouse (select with week prices of product)
- when receipt => specify price => it creates a price for product (valid current week) ? => what if the product 

Friday check
- potreba zadat stav prirucnich skladu (co mu zbylo na aute) => zjistit mnozstvi, ktere prodal

WarehouseCheck
- provided_at
- products (that are in temporary warehouse in some amount)
  - save state of warehouse before/after check
- warehouse_id

- actions (setToWarehouse(warehouse_id))

WarehouseCheck->Products
- warehouse_id
- product_id
- amount before
- amount after
- price


Products->Prices
- validFrom
- validTo
- price

employee => only prevodka ?
k cemu tam je prijemka/vydejka z prirucnich skladu ?
k cemu je vydejka z hlavniho skladu ? (prevodka na odpad) => zmenit na kos ?


soft deletes -> what should be possible to delete ? => skryvani produktu (potom i ze skladu)

bedynky - ??
