<?php defined('SYSPATH') or die('No direct script access.');

class Task_Sitemap extends Minion_Task
{
    protected $_options = array(
    );
	
    protected function _execute(array $params)
    {
        $categories = ORM::factory('Category')->fetchActive();
		$products = ORM::factory('Product')->fetchActive();
		$pages = ORM::factory('Page')->fetchActive();

        $sitemap = new Sitemap;
        $url = new Sitemap_URL;
		
		$page = ORM::factory('Page')->where('url', '=', '')->find();
		
		$url->set_loc("http://cosm.by")
			->set_last_mod(strtotime($page->updated_at))
			->set_priority(1);
		$sitemap->add($url);
        
		foreach ($pages as $page) {
			if ($page->url) {
				$url->set_loc("http://cosm.by/page/" . $page->url)
					->set_last_mod(strtotime($page->updated_at))
					->set_change_frequency('monthly')
					->set_priority(0.2);
				$sitemap->add($url);
			}
		}
		
		$PDO = ORM::factory('Brand')->PDO();
		$brandsQuery = "SELECT br.id, br.url FROM brand br
						LEFT JOIN product pr ON pr.brand_id = br.id
						WHERE pr.active = 1
						GROUP BY br.id
						HAVING COUNT(pr.id) > 0";
		$brands = $PDO->query($brandsQuery)->fetchAll(PDO::FETCH_ASSOC);
		foreach ($brands as $brand) {
			$url->set_loc("http://cosm.by/brand/" . $brand['url'])
				->set_change_frequency('monthly')
				->set_priority(0.5);
			$sitemap->add($url);
		}
		
		foreach ($brands as $brand) {
			$query = "SELECT line.url, line.name, product.updated_at FROM categories
					LEFT JOIN product ON product.category_id = categories.id
					LEFT JOIN line ON line.id = product.line_id
					WHERE product.brand_id = {$brand['id']}
					AND product.active = 1
					GROUP BY line.id
					HAVING COUNT(product.id) > 0
					ORDER BY line.name ASC";
					
			$brandCategories = $PDO->query($query)->fetchAll(PDO::FETCH_ASSOC);
			foreach ($brandCategories as $category) {
				$url->set_loc("http://cosm.by/brand/" . $brand['url'] . "/" . $category['url'])
					->set_last_mod(strtotime($category['updated_at']))
					->set_change_frequency('weekly');
				$sitemap->add($url);
			}
		}
		
		foreach ($categories as $category) {
            $url->set_loc("http://cosm.by/" . $category->url)
                ->set_last_mod(strtotime($category->updated_at))
                ->set_change_frequency('weekly');
            $sitemap->add($url);
        }
		
		foreach ($products as $product) {
            $url->set_loc("https://cosm.by".$product->getSiteUrl())
                ->set_last_mod(strtotime($product->updated_at))
                ->set_change_frequency('daily')
                ->set_priority(1);
            $sitemap->add($url);
        }

		$response = $sitemap->render();
        file_put_contents('sitemap.xml', $response);
    }
}
