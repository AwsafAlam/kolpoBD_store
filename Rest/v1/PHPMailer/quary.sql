UPDATE price
SET price.price = 110
where price.book_id = 1
and price.quality_id = 1




/* Book price update  */
UPDATE price
SET Price.price = Price.price - 10
WHERE Price.price <= 120
and Price.price > 10

/*                   */

UPDATE price
SET Price.price = Price.price - 15
WHERE Price.Price > 120
and Price.Price <= 200


/*        */

UPDATE price
SET Price.price = Price.price - 20
WHERE Price.price > 200
and Price.price <= 500



UPDATE Price
SET Price.price = Price.price - 30
WHERE Price.price > 500
and Price.price <= 800


UPDATE Price
SET Price.price = Price.price - 40
WHERE Price.price > 800






/* Inseerting into cartitem  */

INSERT INTO cartitem 
(cartitem.item_id,cartitem.book_id,cartitem.book_order_id,cartitem.price_id,cartitem.promo_id)
VALUES (NULL,1,(SELECT MAX(bookorder.book_order_id) FROM bookorder),1,1)


/*          */

INSERT INTO bookorder
(bookorder.book_order_id,bookorder.user_id,bookorder.shipping_address,bookorder.total_cost,bookorder.delivery_confirmed)
VALUES (NULL,)
