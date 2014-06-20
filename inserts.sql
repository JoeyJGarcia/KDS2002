INSERT INTO `accounts` ( `accounts_id` , `accounts_company_name` , `accounts_address1` , `accounts_address2` , `accounts_city` , `accounts_state` , `accounts_zip` , `accounts_phone` , `accounts_fax` , `accounts_email` , `accounts_url` , `accounts_poc` , `accounts_username` , `accounts_number` , `accounts_reset_password` )
VALUES (
'', 'The Good News Clothing Co.', '4950 Cherry Springs Dr.', NULL , 'Colorado Springs', 'Colorado', '80918', '(719) 598-3645', NULL , 'jnc@ourhacienda.com', 'www.goodnewsclothing.com', 'Joey and Cindy Garcia', 'goodnews', '123456', '0'
)



INSERT INTO `login` ( `login_id` , `login_username` , `login_password` , `login_to_accounts_id` , `login_reset_password` )
VALUES (
'', 'goodnews', 'goodnews', '1', '1'
);





SELECT  count(*) AS Count,  login_reset_password
FROM  `login`
WHERE login_username = 'goodnews' and login_password='goodnews'
GROUP BY login_reset_password




UPDATE `products` SET `product_model` = 'APTJCRX',
`product_type` = '2',
`product_sizes` = 'S-5X',
`product_desc` = 'Here is another comment' WHERE `product_id` = '5' LIMIT 1



SELECT o.customer_name, o.purchase_date, s.shipping_name, os.order_status_name, o.purchase_order_number, o.customer_invoice_number, o.order_comments
FROM orders o, accounts a, shipping s, order_status os
WHERE purchase_date <  '2005-02-02' AND purchase_date >=  '2005-01-11' AND o.accounts_number =  '11001' AND a.accounts_number =  '11001' AND s.shipping_id = o.customer_shipping_method and os.order_status_id=10
