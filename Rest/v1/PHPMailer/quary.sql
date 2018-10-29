UPDATE price
SET price.price = 110
where price.book_id = 1
and price.quality_id = 1




/* Book price update  */
UPDATE price
SET Price.price = Price.price + 10
WHERE Price.price <= 120

/*                   */

UPDATE price
SET Price.price = Price.price + 15
WHERE Price.Price >= 130
and Price.Price <= 220


/*        */

UPDATE price
SET Price.price = Price.price + 20
WHERE Price.price > 220
and Price.price <= 500



UPDATE Price
SET Price.price = Price.price + 10
WHERE Price.price > 500





/* Inseerting into cartitem  */

INSERT INTO cartitem 
(cartitem.item_id,cartitem.book_id,cartitem.book_order_id,cartitem.price_id,cartitem.promo_id)
VALUES (NULL,1,(SELECT MAX(bookorder.book_order_id) FROM bookorder),1,1)