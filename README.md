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
- user_id

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


seznam vsech pohybu - s filtraci i pro zamestnance
  (zamestnanec bude mit pevny filtr pro sebe)
  vyhledavani? - v cem ?


ukazat v prevodce max amount (could be per price)
block - amounts
editace pohybu? - storno

podklad textu

lepe predvybran sklad pro zamestnance

u docasnych skladu zobrazit mnozstvi na ten den misto celkoveho mnozstvi (u kazdeho produktu)
loadings + disabled buttons


AZ SE VYJASNI CENA:
obrazovka s cenama a zemi puvodu pro employees
zeme puvodu bude pokazde jina pri prijemce (pri zadavani - unikatni kombinace produkt, cena, puvod)
KLIK na cenu => smazat (set 0)
handlovani zmen cen v terenu (skolka, ...) sekce slevy na produkty
