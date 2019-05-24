
CREATE TABLE `paiements` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT, 
  `payment_id` varchar(255) NOT NULL,
  `orders_id` int(11) DEFAULT NULL,
  `payment_status` text NOT NULL,
  `payment_amount` text NOT NULL,
  `payment_currency` text NOT NULL,
  `payment_date` datetime NOT NULL,
  `payer_email` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
