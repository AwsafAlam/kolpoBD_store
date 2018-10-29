UPDATE price
SET price.price = 110
where price.book_id = 1
and price.quality_id = 1




/* Book price update  */
UPDATE price
SET price.price = price.price + 10
WHERE price.price <= 120

/*                   */

UPDATE price
SET price.price = price.price + 15
WHERE price.price >= 130
and price.price <= 220


/*        */

UPDATE price
SET price.price = price.price + 20
WHERE price.price > 220
and price.price <= 500

/*    */

UPDATE price
SET price.price = price.price + 10
WHERE price.price > 500





/* Inseerting into cartitem  */

INSERT INTO cartitem 
(cartitem.item_id,cartitem.book_id,cartitem.book_order_id,cartitem.price_id,cartitem.promo_id)
VALUES (NULL,1,(SELECT MAX(bookorder.book_order_id) FROM bookorder),1,1)