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


/*If shipping address is default.  (OEDER placement)    */

INSERT INTO bookorder
(bookorder.book_order_id,bookorder.user_id,bookorder.shipping_address,bookorder.total_cost,bookorder.delivery_confirmed,bookorder.order_issue)
VALUES (NULL,2,(SELECT user.address FROM user where user.user_id = 2),1390,0,NULL)



/*If shipping address is default.  (OEDER placement)    */

INSERT INTO bookorder
(bookorder.book_order_id,bookorder.user_id,bookorder.shipping_address,bookorder.total_cost,bookorder.delivery_confirmed,bookorder.order_issue)
VALUES (NULL,2,"bosundhora",1390,0,NULL)


/* Updating order .... we can keep 2 buttons .. one for update, one for delete  */

/* if update order, then this. number_of_items should be > 0*/


UPDATE cartitem
SET cartitem.number_of_item = 5
WHERE cartitem.book_id = 5
and cartitem.book_order_id = 1

/*if we want to cancel order */

DELETE FROM cartitem
WHERE cartitem.book_order_id = 1

DELETE FROM bookorder
WHERE bookorder.book_order_id = 1

/* done updating order */