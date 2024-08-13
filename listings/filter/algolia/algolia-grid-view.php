<ais-hits
	:class-names="{
		'ais-Hits': 'row row-3 car-listing-row car-listing-modern-grid',
		'ais-Hits-list': 'products columns-3',
		'ais-Hits-item': 'col-md-4 col-sm-4 col-xs-12 product type-product has-post-thumbnail',
  }">

	<ol slot-scope="{ items, sendEvent }" class="ais-Hits-list products columns-3">
		<template v-for="(item, index) in items">

		<h3
			class="col-md-12 col-sm-12 col-xs-12"
			v-if="(!items[index-1] || items[index-1].product_cat !== item.product_cat) && item.product_cat !== false">
			{{item.product_cat}}
		</h3>
		<li :key="item.objectID" class="ais-Hits-item col-md-4 col-sm-4 col-xs-12 product type-product has-post-thumbnail">

		<div class="stm-product-inner">

			<a :href="item.url" class="rmv_txt_drctn">
				<div class="gallery-carousel" :class="{'wrap_sold': item.sold}">
					<div class="image">
						<div v-if="item.galleryUrls.length && !item.sold && false" class="image-carousel" v-carousel>
							<div v-for="(image, index) in item.galleryUrls" :key="index" class="image-item" >
								<a :href="item.url" class="rmv_txt_drctn">
									<div class="image-inner">
										<div v-if="item.sold" class="stm-badge-directory heading-font ">SOLD</div>
										<div v-if="image" :class="{'sold': item.sold}">
											<img :src="image" class="img-responsive" alt="Placeholder"/>
										</div>
										<div v-else :class="{'sold': item.sold}">
											<img src="<?php echo esc_url( '/wp-content/uploads/woocommerce-placeholder-543x407.png'); ?>" class="img-responsive" alt="Placeholder"/>
										</div>
									</div>
								</a>
							</div>
						</div>
						<div v-else>
							<div class="image">
								<a :href="item.url" class="rmv_txt_drctn">
									<div class="image-inner">
										<div v-if="item.sold" class="stm-badge-directory heading-font ">SOLD</div>
										<div v-if="item.thumbnail" :class="{'sold': item.sold}">
											<img :src="item.thumbnail" class="img-responsive" alt="Placeholder"/>
										</div>
										<div v-else :class="{'sold': item.sold}">
											<img src="<?php echo esc_url('/wp-content/uploads/woocommerce-placeholder-543x407.png'); ?>" class="img-responsive" alt="Placeholder"/>
										</div>
									</div>
								</a>
							</div>
						</div>
					</div>
				</div>
				<div class="hide_on_mobile">
					<div class="image">
						<a :href="item.url" class="rmv_txt_drctn">
							<div class="image-inner">
								<div v-if="item.sold" class="stm-badge-directory heading-font ">SOLD</div>
								<div v-if="item.thumbnail" :class="{'sold': item.sold}">
									<img :src="item.thumbnail" class="img-responsive" alt="Placeholder"/>
								</div>
								<div v-else :class="{'sold': item.sold}">
									<img src="<?php echo esc_url('/wp-content/uploads/woocommerce-placeholder-543x407.png'); ?>" class="img-responsive" alt="Placeholder"/>
								</div>
							</div>
						</a>
					</div>
				</div>
				<div class="listing-car-item-meta" :class="{'featured' : item.featured}">
					<div class="car-meta-top heading-font clearfix">
						<div v-if="item.price" class="price">
							<div class="normal-price">$ {{item.price | commaSeparator}}</div>
						</div>
						<div class="car-title">
							<ais-highlight :hit="item" attribute="title"/>
						</div>
					</div>
					<div class="car-meta-bottom">
						<a :href="item.url"><?php echo __( 'Read more', 'woocommerce' ) ?></a>
					</div>
				</div>
			</a>

		</div>

		</li>
		</template>
	</ol>

</ais-hits>
