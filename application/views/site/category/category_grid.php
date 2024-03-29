<div id="main-container">
    <div class="row">
        <!-- Sidebar Starts -->
        
        <div class="col-md-9 col-md-push-3">
		
            <ol class="breadcrumb">
                <li><a href="/">Главная</a></li>
                <li><?php echo $category->name; ?></li>
            </ol>
            <h2 class="main-heading2">
                <?php echo $category->name; ?>
            </h2>
            <div class="row cat-intro">
                <div class="col-sm-3">
                    <?php if ($category->image) { ?>
                    <img src="<?php echo Lib_Image::resize_bg($category->image, 'category', $category->id, 300, 300); ?>" alt="<?php echo $category->name; ?>" class="img-responsive img-thumbnail" />
                    <?php } ?>
                </div>
                <div class="col-sm-9 cat-body">
                    <p>
                        <?php echo $category->description; ?>
                    </p>

                </div>
            </div>
            <!-- Category Intro Content Ends -->
            <!-- Product Filter Starts -->
            <div class="product-filter">
                <div class="row">
                    <div class="col-md-4">
                        <div class="display">
                            <a href="?page_view=list">
                                <i class="fa fa-th-list" title="List View"></i>
                            </a>
                            <a href="?page_view=grid" class="active">
                                <i class="fa fa-th" title="Grid View"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-5 text-right">
                        <label class="control-label">Сортировать по:</label>
                    </div>
                    <div class="col-md-2 text-right">
                        <div class="sort_list">
                            <?php echo Sortable::get_button('price', 'цене', null, true); ?>
                            <?php echo Sortable::get_button('name', 'названию', null, true); ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Product Filter Ends -->
            <!-- Product Grid Display Starts -->
            <section class="products-list">
            <div class="row">
                <?php $clearfix = 0; foreach ($product as $index => $prod) {
                    $price = ORM::factory('Product')->getPriceValue($prod->id);?>
                    <input type="hidden" name="quantity" value="1"  />
                    <div class="col-md-4 col-sm-6">
                        <div class="product-col">
                            <div class="image">
                                <a href="/<?php echo $category->url;?>/<?php echo $prod->url; ?>">
                                    <img src="<?php echo Lib_Image::resize_bg($prod->main_image, 'product', $prod->id, 250, 250); ?>" alt="product" class="img-responsive" />
                                </a>
                            </div>
                            <div class="caption">
                                <div class="price">
                                    <span class="price-new"><?php echo number_format($price, 0, ' ', ' '); ?> руб.</span>
                                </div>
                                <?php if($prod->new_price){ ?>
                                <div class="price">
                                    <span class="price-old"><?php echo number_format($prod->price, 0, ' ', ' '); ?> руб.</span>
                                </div>
                                <?php } ?>
                                <h4><a href="/<?php echo $category->url; ?>/<?php echo $prod->url; ?>"><?php echo $prod->name; ?> </a></h4>
                                <div class="cart-button button-group">
                                    <button type="button" class="btn btn-cart add_cart" data-id="<?php echo $prod->id; ?>" data-price="<?php echo $price ?>">
                                        <i class="fa fa-shopping-cart"></i><br>
                                        Добавить в корзину
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php $clearfix++; if($clearfix % 3 == 0) { ?><div class="clearfix"></div>  <?php }?> <?php  } ?>
            </div>
            </section>
            <div class="row">
                <div class="paginator">
                    <div class="col-sm-10 pagination-block">
                        <?php echo $pagination; ?>
                    </div>
                </div>
            </div>
        </div>
   
		<div class="col-md-3 col-md-pull-9">
            <!-- Shopping Options Starts -->
            <?php echo View::factory('site/filter/index', array(
                'category' => $category, 
                'brand' => $brand, 
                'line' => $line, 
                'filters' => $filters, 
                'max_price' => $max_price, 
                'min_price' => $min_price,
                'current_min_price' => $current_min_price,
                'current_max_price' => $current_max_price
            ));?>
			<?php $specialProduct = ORM::factory('Product')->fetchProdSpecial($category->id); ?>
			<?php if ($specialProduct->as_array()) { ?>
            <h3 class="side-heading">Спецпредложение</h3>
            <?php foreach($specialProduct as $topprod){
                $price = ORM::factory('Product')->getPriceValue($topprod->id);?>
            <div class="product-col">
                <div class="image">
                    <a href="<?php echo $category->url; ?>/<?php echo $topprod->url; ?>">
                        <img src="<?php echo Lib_Image::resize_width($topprod->main_image, 'product', $topprod->id, 250, 250); ?>" alt="product" class="img-responsive" />
                    </a>
                </div>
                <div class="caption">
                    <div class="price">
                        <span class="price-new"><?php echo number_format($price, 0, ' ', ' '); ?> руб.</span>
                    </div>
                    <?php if($topprod->new_price){ ?>
                        <div class="price">
                            <span class="price-old"><?php echo number_format($topprod->price, 0, ' ', ' '); ?> руб.</span>
                        </div>
                    <?php } ?>
                    <h4><a href="<?php echo $category->url; ?>/<?php echo $topprod->url; ?>"><?php echo $topprod->name;?></a></h4>
                    <div class="cart-button button-group">
                        <button type="button" class="btn btn-cart add_cart" data-id="<?php echo $topprod->id; ?>" data-price="<?php echo $price ?>">
                            <i class="fa fa-shopping-cart"></i><br>
                            Добавить в корзину
                        </button>
                    </div>
                </div>
            </div>
            <?php } ?>
			<?php } ?>
            <div id="vk_groups"></div>
            <script type="text/javascript">
                VK.Widgets.Group("vk_groups", {mode: 0, width: "250", height: "400"}, 73341072);
            </script>
            <!-- Bestsellers Links Ends -->
        </div>
   </div>
</div>