<?php defined('SYSPATH') or die('No direct script access.');class Controller_Site_Cart extends Controller_Site{    public function action_index()    {        $this->set_metatags_and_content('', 'page');        $cart = Session::instance()->get('cart');        $cartitems = json_decode($cart['cart']);        $cart_certificate = Session::instance()->get('cart_certificate');        $certificate = json_decode($cart_certificate['cart_certificate']);        $this->template->cartitems = $cartitems;        $this->template->certificate = $certificate;        $this->template->set_layout('layout/site/global');    }    public function action_add()    {        $this->set_metatags_and_content('', 'page');        if ($this->request->is_ajax()) {            $id = $this->request->post('id');            $quantity = $this->request->post('quantity');            $price = $this->request->post('price');            $color = $this->request->post('color');            $choose_color = $this->request->post('choose_color');            if ($choose_color)                $color = $choose_color;            $cart_items = Session::instance()->get('cart');            $array_key = $id;            $cart = array();            if (isset($cart_items['cart'])) {                $cart = json_decode($cart_items['cart']);            }            $items = array();            if ($cart) {                foreach ($cart as $key => $item) {                    $items[$key] = array(                        'id' => $item->id,                        'quantity' => $item->quantity,                        'price' => $item->price,                        'color' => $item->color                    );                }            }            if (isset($items[$array_key])) {                $items[$array_key]['quantity'] += $quantity;                $items[$array_key]['color'] = $color;            } else {                $items[$array_key] = array(                    'id' => $array_key,                    'quantity' => $quantity,                    'price' => $price,                    'color' => $color                );            }            $cart_items['cart'] = json_encode($items);            Session::instance()->set('cart', $cart_items);            $cart = Session::instance()->get('cart');            $cartitems = json_decode($cart['cart']);            $certificate_session = Session::instance()->get('cart_certificate');            $certificate = json_decode($certificate_session['cart_certificate']);            $result_quantity = 0;            $result_price = 0;            $certificate_quantity = 0;            if ($certificate) {                foreach ($certificate as $cnt) {                    $certificate_quantity = $cnt->quantity + $certificate_quantity;                }            }            if ($cartitems) {                foreach ($cartitems as $key => $item) {                    if ($item->quantity > 1) {                        $result_price = $item->price * $item->quantity + $result_price;                    } else {                        $result_price = $item->price + $result_price;                    }                    $result_quantity = $item->quantity + $result_quantity;                }                $result_quantity += $certificate_quantity;                $price_view = number_format($result_price, 0, '', ' ');                exit(json_encode(array('price' => $result_price, 'quantity' => $result_quantity, 'price_view' => $price_view, 'id' => $id, 'color' => $color)));            }        }        $this->forward_404();    }    public function action_delete()    {        $this->set_metatags_and_content('', 'page');        $this->template->set_layout('site/global');        if ($this->request->is_ajax()) {            $id = $this->request->post('id');            $products_s = Session::instance()->get('cart');            $cart = json_decode($products_s['cart']);            $items = array();            foreach ($cart as $key => $item) {                if ($id != $item->id) {                    $items[$key] = array(                        'id' => $item->id,                        'quantity' => $item->quantity,                        'price' => $item->price,                        'color' => $item->color                    );                }            }            $products_s['cart'] = json_encode($items);            Session::instance()->set('cart', $products_s);            $cart = Session::instance()->get('cart');            $cartitems = json_decode($cart['cart']);            $result_quantity = 0;            $result_price = 0;            $certificate_session = Session::instance()->get('cart_certificate');            $cart_certificate = json_decode($certificate_session['cart_certificate']);            $certificate_quantity = 0;            $certificate_price = 0;            if ($cart_certificate) {                foreach ($cart_certificate as $certificate) {                    $certificate_quantity = $certificate->quantity + $certificate_quantity;                    $certificate_price = $certificate->price * $certificate->quantity + $certificate_price;                }            }            if ($cartitems) {                foreach ($cartitems as $key => $item) {                    if ($item->quantity > 1) {                        $result_price = $item->price * $item->quantity + $result_price;                    } else {                        $result_price = $item->price + $result_price;                    }                    $result_quantity = $item->quantity + $result_quantity;                }            }            $result_quantity += $certificate_quantity;            $result_price += $certificate_price;            $price_view = number_format($result_price, 0, '', ' ');            exit(json_encode(array('quantity' => $result_quantity, 'price_view' => $price_view, 'id' => $id)));        }        $this->forward_404();    }    public function action_empty_cart()    {        $products_s = Session::instance()->get('cart');        $cart = json_decode($products_s['cart']);        $items = array();        foreach ($cart as $key => $item) {            if ($item->id) {                $items[$key] = array(                    'id' => '',                    'quantity' => '',                    'price' => ''                );            }        }        $products_s['cart'] = json_encode($items);        $cart = Session::instance()->get('cart');        $cartitems = json_decode($cart['cart']);        Session::instance()->set('cart', $products_s);        $cart = Session::instance()->get('cart');        $cartitems = json_decode($cart['cart']);        exit(json_encode(array('cartitems' => $cartitems)));    }    public function action_recount()    {        $this->set_metatags_and_content('', 'page');        if ($this->request->is_ajax()) {            $id = $this->request->post('id');            $quantity = $this->request->post('quantity');            $cart_items = Session::instance()->get('cart');            $array_key = $id;            if (isset($cart_items['cart'])) {                $cart = json_decode($cart_items['cart']);            } else {                $cart = array();            }            $items = array();            if ($cart) {                foreach ($cart as $key => $item) {                    $items[$key] = array(                        'id' => $item->id,                        'quantity' => $item->quantity,                        'price' => $item->price,                        'color' => $item->color                    );                }            }            if (isset($items[$array_key])) {                $items[$array_key]['quantity'] = $quantity;            }            $cart_items['cart'] = json_encode($items);            Session::instance()->set('cart', $cart_items);            $cart = Session::instance()->get('cart');            $cartitems = json_decode($cart['cart']);            $result_quantity = 0;            $result_price = 0;            if ($cartitems) {                foreach ($cartitems as $key => $item) {                    if ($item->quantity > 1) {                        $result_price = $item->price * $item->quantity + $result_price;                    } else {                        $result_price = $item->price + $result_price;                    }                    $result_quantity = $item->quantity + $result_quantity;                    $prodprice = $quantity * $item->price;                }                exit(json_encode(array('price' => $result_price, 'quantity' => $result_quantity, 'quantity_prod' => $quantity, 'prodprice' => $prodprice)));            }        }        $this->forward_404();    }    public function action_order()    {        $this->set_metatags_and_content('', 'page');        $this->template->set_layout('site/global');        if ($this->request->is_ajax()) {            $name = $this->request->post('name');            $email = $this->request->post('email');            $phone = $this->request->post('phone');            $adress = $this->request->post('adress');            $city = $this->request->post('city');            $index = $this->request->post('index');            $delivery = $this->request->post('delivery');            $admin_order = $this->request->post('admin_order');            $comment = $this->request->post('comment');            $PDO_coupons = ORM::factory('Coupons')->PDO();            $date = date('Y-m-d');            $stmt = $PDO_coupons->prepare("SELECT coupons.code, coupons.discount                                    FROM coupons                                    WHERE code = :code AND active = 1 AND time_end > '{$date}'");            $stmt->bindParam(':code', $this->request->post('coupon'));            $stmt->execute();            foreach ($stmt as $row) {                $code = $row['code'];                $coupon_discount = $row['discount'];            }            $PDO_order_certificate = ORM::factory('OrderCertificate')->PDO();            $date = date('Y-m-d');            $stmt = $PDO_order_certificate->prepare("SELECT order_certificate.code, to_amount                                                        FROM order_certificate                                                        WHERE code = :code_certificate AND active = 1 AND time_end > '{$date}'");            $stmt->bindParam(':code_certificate', $this->request->post('certificate'));            $stmt->execute();            foreach ($stmt as $row) {                $code_certificate = $row['code'];                $to_amount = $row['to_amount'];            }            $cart = Session::instance()->get('cart');            $certificate = Session::instance()->get('cart_certificate');            $cartitems = json_decode($cart['cart']);            $certificateitems = json_decode($certificate['cart_certificate']);            $order = ORM::factory('Orders');            $order->name = $name;            $order->email = $email;            $order->phone = $phone;            $order->adress = $adress;            $order->code_coupon = $code;            $order->delivery = $delivery;            $order->code_certificate = $code_certificate;            $order->city = $city;            $order->index = $index;            $order->comment = $comment;            $order->save();            $PDO_order_product = ORM::factory('OrderProduct')->PDO();            $stmt = $PDO_order_product->prepare("INSERT INTO order_product (order_id, price, product_id, quantity, color)                                                  VALUES(:order_id, :price, :product_id, :quantity, :color)");            $fullprice_product = 0;            $fullprice_certificate = 0;            if ($cartitems) {                foreach ($cartitems as $items) {                    $price = ORM::factory('Product')->getPriceValue($items->id);                    if ($items->color) {                        $color = $items->color;                    } else {                        $color = "";                    }                    $stmt->bindParam(':price', $price, PDO::PARAM_INT);                    $stmt->bindParam(':product_id', $items->id, PDO::PARAM_INT);                    $stmt->bindParam(':quantity', $items->quantity, PDO::PARAM_INT);                    $stmt->bindParam(':order_id', $order->id, PDO::PARAM_INT);                    $stmt->bindParam(':color', $color);                    $stmt->execute();                    $price_product = $price * $items->quantity;                    $fullprice_product += $price_product;                }            }            if ($certificateitems) {                foreach ($certificateitems as $crcitems) {                    $validity = ORM::factory('Certificate')->PDO()->query("SELECT certificate.validity, certificate.sum, certificate.price                                                                       FROM certificate                                                                       WHERE id = '{$crcitems->id}'")->fetch();                    $i = 0;                    while ($i < $crcitems->quantity) {                        $certificate = $PDO_order_certificate->prepare("INSERT INTO order_certificate (certificate_id, order_id, code, price, time_end, to_amount)                                              VALUES(:certificate_id, :order_id, :certificate_code, :price, :time_end, :to_amount)");                        $certificate_code = substr(md5(microtime()), rand(0, 5), rand(11, 16));                        $certificate->bindParam(':certificate_id', $crcitems->id, PDO::PARAM_INT);                        $certificate->bindParam(':order_id', $order->id, PDO::PARAM_INT);                        $certificate->bindParam(':price', $validity['price'], PDO::PARAM_INT);                        $certificate->bindParam(':certificate_code', $certificate_code, PDO::PARAM_STR);                        $certificate->bindParam(':time_end', $validity['validity']);                        $certificate->bindParam(':to_amount', $validity['sum']);                        $certificate->execute();                        $i++;                        $fullprice_certificate +=  $validity['price'];                    }                }            }            $fullprice = $fullprice_product + $fullprice_certificate;            switch ($fullprice) {                case ($fullprice >= 2000000):                    $discount = 20;                    break;                case ($fullprice >= 1800000):                    $discount = 15;                    break;                case ($fullprice >= 1500000):                    $discount = 13;                    break;                case ($fullprice >= 1200000):                    $discount = 10;                    break;                case ($fullprice >= 900000):                    $discount = 7;                    break;                case ($fullprice >= 600000):                    $discount = 5;                    break;                case ($fullprice >= 300000):                    $discount = 3;                    break;            }            $time_end = 60;            $active = 0;            $stmt = $PDO_coupons->prepare("UPDATE coupons SET active = :active WHERE code = :code");            $stmt->bindParam(':code', $code);            $stmt->bindParam(':active', $active);            $stmt->execute();            if ($discount) {                $create_code = substr(md5(microtime()), rand(0, 5), rand(11, 16));                $stmt = $PDO_coupons->prepare("INSERT INTO coupons (code, time_end, active, discount, order_id) VALUES(:code, :time_end, :active, :discount, :order_id)");                $stmt->bindParam(':code', $create_code);                $stmt->bindParam(':time_end', $time_end);                $stmt->bindParam(':active', $active, PDO::PARAM_INT);                $stmt->bindParam(':discount', $discount, PDO::PARAM_INT);                $stmt->bindParam(':order_id', $order->id, PDO::PARAM_INT);                $stmt->execute();            }            $PDO_order_certificate->query("UPDATE order_certificate SET active = $active WHERE code = '{$code_certificate}'");            $cart_mail = $cart['cart'];            if ($email) {                $user_message = View::factory('site/order/usermessage', array(                    'name' => $name,                    'email' => $email,                    'phone' => $phone,                    'adress' => $adress,                    'city' => $city,                    'index' => $index,                    'delivery' => $delivery,                    'cart' => json_decode($cart_mail),                    'certificate_mail' => $certificateitems,                    'code_certificate' => $code_certificate,                    'code' => $code,                    'coupon_discount' => $coupon_discount,                    'to_amount' => $to_amount,                    'comment' => $comment                ))->render();            }            $admin_message = View::factory('site/order/adminmessage', array(                'name' => $name,                'email' => $email,                'phone' => $phone,                'adress' => $adress,                'city' => $city,                'index' => $index,                'delivery' => $delivery,                'cart' => json_decode($cart_mail),                'certificate_mail' => $certificateitems,                'code_certificate' => $code_certificate,                'code' => $code,                'coupon_discount' => $coupon_discount,                'to_amount' => $to_amount,                'comment' => $comment            ))->render();            Helpers_Email::send(Kohana::$config->load('mailer.admin'), 'Новый заказ '.$name.' '.$phone, $admin_message, true);            Helpers_Email::send($email, 'Новый заказ '.$name.' '.$phone, $user_message, true);            if ($admin_order != true) {                Session::instance()->destroy('cart');                Session::instance()->destroy('cart_certificate');            }            exit(json_encode(array('order_id' => $order->id)));        }        $this->forward_404();    }    public function action_ajax()    {        $cart = Session::instance()->get('cart');        $cartitems = json_decode($cart['cart']);        $this->template->cartitems = $cartitems;        $this->template->cartjson = $cart['cart'];        $view = View::factory('site/cart/ajax', array(            'cartitems' => $cartitems,            'cartjson' => $cart['cart'],        ))->render();        echo json_encode(array('view' => $view));        exit();    }    public function action_coupon()    {        if ($this->request->is_ajax()) {            $code = $this->request->post('coupon');            $price = $this->request->post('price');            $price_certificate = $this->request->post('price_certificate');            $total_price = $this->request->post('total_price');            $price_start = number_format($price, 0, '', ' ');            $PDO = ORM::factory('Coupons')->PDO();            $date = date('Y-m-d');            $stmt = $PDO->prepare("SELECT coupons.code, coupons.discount                                    FROM coupons                                    WHERE code = :code AND active = 1 AND time_end > '{$date}'");            $stmt->bindParam(':code', $code);            $stmt->execute();            foreach ($stmt as $row) {                $code = $row['code'];                $discount = $row['discount'];            }            $price_discount = $price - (($price / 100) * $discount);            $price_delivery = $total_price - $price_certificate - $price;            $total_price_start = $total_price;            $total_price = $price_delivery + $price_discount + $price_certificate;            $total_price_view = number_format($total_price, 0, '', ' ');            if ($price_discount <= 0) {                $price_discount = 0;            }            $price_discount_view = number_format($price_discount, 0, '', ' ');        }        exit(json_encode(array('discount' => $discount,                                'price' => $price_discount,                                'price_view' => $price_discount_view,                                'code' => $code,                                'price_start' => $price_start,                                'total_price' => $total_price,                                'total_price_view' => $total_price_view,                                'total_price_start' => $total_price_start,                                'price_delivery' => $price_delivery)));    }    public function action_certificate()    {        if ($this->request->is_ajax()) {            $code = $this->request->post('certificate');            $price = $this->request->post('price');            $price_certificate = $this->request->post('price_certificate');            $price_delivery = $this->request->post('price_delivery');            $total_price = $this->request->post('total_price');            $price_start_view = number_format($price, 0, '', ' ');            $PDO = ORM::factory('OrderCertificate')->PDO();            $date = date('Y-m-d');            $stmt = $PDO->prepare("SELECT order_certificate.code, order_certificate.to_amount                                    FROM order_certificate                                    WHERE code = :code AND time_end > '{$date}'");            $stmt->bindParam(':code', $code);            $stmt->execute();            foreach ($stmt as $row) {                $code_certificate = $row['code'];                $sum = $row['to_amount'];            }            if(!$price_delivery) {                $price_delivery = $total_price - $price - $price_certificate;            }            $price -= $sum;            $to_amount = $sum;            if ($price <= 0) {                $price = 0;            }            $total_price_start = $total_price;            $total_price = $price_delivery + $price + $price_certificate;            $total_price_view = number_format($total_price, 0, '', ' ');            $sum = number_format($sum, 0, '', ' ');            $price_view = number_format($price, 0, '', ' ');        }        exit(json_encode(array('sum' => $sum,                                'to_amount' => $to_amount,                                'price' => $price,                                'price_view' => $price_view,                                'price_start_view' => $price_start_view,                                'code' => $code,                                'total_price' => $total_price,                                'total_price_view' => $total_price_view,                                'price_delivery' => $price_delivery,                                'total_price_start' => $total_price_start)));     }}