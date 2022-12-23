<?php
error_reporting(0);
require_once("DB.php");

@$orders = json_decode($_REQUEST['data'], true);
@$islem = ($_REQUEST['islem']);

switch ($islem) {
	case 'order_create':
		$json = [];
		foreach ($orders as $order) {
			$order_id = "IDEA-" . rand(88888, 99999);

			$customer_id = $order['customerId'];

			if (empty($customer_id)) {
				$json = [
					'status'	=> 'eksik',
					'message'	=> 'MÜŞTERİ ID EKSİK',
				]; 
			}


			$customer = $DB->prepare("SELECT * FROM customer WHERE id = ?");
			$customer->execute([$customer_id]);
			$customer_count = $customer->rowCount();

			if ($customer_count == 0) {
				$json[] = [
					'status'		=> 'hata',
					'message'		=> 'MÜŞTERİ BULUNAMADI',
					'customer_id'	=> $customer_id,
				];
			}

			foreach ($order['items'] as $items) {
				$item_id = $items['productId'];
				$quantity = $items['quantity'];


				$item_query = $DB->prepare("SELECT * FROM item WHERE id = ?");
				$item_query->execute([$item_id]);
				$item_query_count = $item_query->rowCount();
				$item_query = $item_query->fetch(PDO::FETCH_ASSOC);

				if ($item_query_count == 0) {
					$json[] = [
						'status'	=> 'hata',
						'message'	=> 'ÜRÜN BULUNAMADI',
						'item_id'	=> $item_id,
					];
					echo json_encode($json);
					die();
				}
				if ($item_query['stock'] < $quantity) {
					$json[] = [
						'status'	=> 'hata',
						'message'	=> 'ÜRÜN STOĞU YETERSİZ',
						'stock'		=> $item_query['stock'],
						'item_id'	=> $item_id,
					];
				} else {

					$get_item = $DB->prepare("SELECT * FROM item WHERE id = ?");
					$get_item->execute([$item_id]);
					$get_item = $get_item->fetch(PDO::FETCH_ASSOC);

					$price = $get_item['price'] * $quantity;

					$add_order = $DB->prepare("INSERT INTO orders SET order_id = ?, customer_id = ?, item_id = ?, item_cat = ?, quantity = ?, count = ?, status = 0, time_stamps = ?");
					$add_order->execute([$order_id, $customer_id, $item_id, $get_item['category'], $quantity, $price, time()]);
					$islem_durumu = $add_order->rowCount();

					$new_stock = $get_item['stock'] - $quantity;

					$update_item = $DB->prepare("UPDATE item SET stock = ? WHERE id = ?");
					$update_item->execute([$new_stock, $item_id]);
				}
			}

			if ($islem_durumu > 0) {
				$json[] = [
					'status'	=> "ok",
					'message'	=> 'SİPARİŞ EKLENDİ',
					'order_id'	=> $order_id,
				];


				$get_orders = $DB->prepare("SELECT * FROM orders WHERE order_id = ?");
				$get_orders->execute([$order_id]);
				$get_orders_count = $get_orders->rowCount();
				$get_orders = $get_orders->fetchAll(PDO::FETCH_ASSOC);

				$total_count = 0;
				foreach ($get_orders as $get_order) {
					if (($get_order['item_cat'] == 2) and ($get_order['quantity'] == 6)) {


						$get_item = $DB->prepare("SELECT * FROM item WHERE id = ?");
						$get_item->execute([$get_order['item_id']]);
						$get_item = $get_item->fetch(PDO::FETCH_ASSOC);

						$new_price = $get_order['count'] - $get_item['price'];

						$update_order_price = $DB->prepare("UPDATE orders SET count = ?, indirim = 1 WHERE id = ?");
						$update_order_price->execute([$new_price, $get_order['id']]);
					}

					if (($get_order['item_cat'] == 1) and ($get_order['quantity'] >= 2)) {

						$get_order = $DB->prepare("SELECT * FROM orders WHERE order_id = ? AND item_cat = 1 AND quantity >= 2 ORDER BY count ASC LIMIT 1");
						$get_order->execute([$order_id]);
						$get_order = $get_order->fetch(PDO::FETCH_ASSOC);

						$get_item = $DB->prepare("SELECT * FROM item WHERE id = ?");
						$get_item->execute([$get_order['item_id']]);
						$get_item = $get_item->fetch(PDO::FETCH_ASSOC);

						$new_price = $get_order['count'] - ($get_order['count'] * 20) / 100;

						$update_order_price = $DB->prepare("UPDATE orders SET count = ?, indirim = 20 WHERE id = ?");
						$update_order_price->execute([$new_price, $get_order['id']]);
					}



					$total_count += $get_order['count'];
				}

				if ($total_count >= 1000) {
					$total_count =  $total_count - (($total_count * 10) / 100);
					$add_order_total = $DB->prepare("INSERT INTO order_total SET order_id = ?, total_count = ?, indirim = ?");
					$add_order_total->execute([$order_id, $total_count, 10]);
				} else {

					$add_order_total = $DB->prepare("INSERT INTO order_total SET order_id = ?, total_count = ?, indirim = ?");
					$add_order_total->execute([$order_id, $total_count, 0]);
				}
			} else {
				$json[] = [
					'status'	=> "hata",
					'message'	=> 'SİSTEMSEL HATA',
				];
			}
		}


		echo json_encode($json);
		die();
		break;

	case 'order_delete':
		$json = [];

		foreach ($orders as $order) {

			$order_id = $order['order_id'];

			$delete_order = $DB->prepare("DELETE FROM orders WHERE order_id = ?");
			$delete_order->execute([$order_id]);
			$islem_durumu = $delete_order->rowCount();

			if ($islem_durumu > 0) {

				$json[] = [
					'status'	=> "ok",
					'message'	=> 'SİPARİŞ SİLİNDİ',
					'order_id'	=> $order_id,
				];
			} else {
				$json[] = [
					'status'	=> "hata",
					'message'	=> 'SİSTEMSEL HATA',
				];
			}
		}

		echo json_encode($json);
		die();
		break;


	case 'order_get':
		$json = [];

		foreach ($orders as $order) {

			$order_id = $order['order_id'];


			$get_orders = $DB->prepare("SELECT * FROM orders WHERE order_id = ?");
			$get_orders->execute([$order_id]);
			$get_orders_count = $get_orders->rowCount();
			$get_orders = $get_orders->fetchAll(PDO::FETCH_ASSOC);

			if ($get_orders_count == 0) {
				$json[] = [
					'status'	=> "hata",
					'message'	=> 'SİPARİŞ BULUNAMADI!',
					'order_id'	=> $order_id,
				];
			} else {

				$get_orders = $DB->prepare("SELECT * FROM orders WHERE order_id = ? GROUP BY order_id LIMIT 1");
				$get_orders->execute([$order_id]);
				$get_orders = $get_orders->fetch(PDO::FETCH_ASSOC);

				$get_customers = $DB->prepare("SELECT * FROM customer WHERE id = ?");
				$get_customers->execute([$get_orders['customer_id']]);
				$get_customers = $get_customers->fetch(PDO::FETCH_ASSOC);

				$json[$order_id] = [
					$get_orders['customer_id'] =
						[
							'customer_name' => $get_customers['name']
						]

				];

				$get_orders = $DB->prepare("SELECT * FROM orders WHERE order_id = ?");
				$get_orders->execute([$order_id]);
				$get_orders_count = $get_orders->rowCount();
				$get_orders = $get_orders->fetchAll(PDO::FETCH_ASSOC);

				foreach ($get_orders as $order) {

					$item_query = $DB->prepare("SELECT * FROM item WHERE id = ?");
					$item_query->execute([$order['item_id']]);
					$item_query = $item_query->fetch(PDO::FETCH_ASSOC);

					$json[$order_id] = [
						$item_query['id'] =
							[
								'item_name' => $item_query['name'],
								'item_category' => $item_query['category'],
								'item_stock' => $item_query['stock'],
								'item_price' => $item_query['price'],
							]

					];
				}
			}
		}

		echo json_encode($json);
		die();
		break;
	case 'items':

		$items = $DB->prepare("SELECT * FROM item ORDER BY id DESC");
		$items->execute();
		$items = $items->fetchAll(PDO::FETCH_ASSOC);

		foreach ($items as $item) {
			$json[] = [
				$item['id'] = [
					'name' => $item['name'],
					'stock' => $item['stock'],
					'category' => $item['category'],
					'price' => $item['price'],

				]
			];
		}


		echo json_encode($json);
		die();
		break;
}
