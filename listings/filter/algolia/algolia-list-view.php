<ais-hits
	:class-names="classNames.list"
>
<template slot="item" slot-scope="{ item }">
	<div class="gallery-carousel" :class="{'wrap_sold': item.sold}" :data-rank="item.buy_order">
		<div class="image">
			<div class="stm-car-medias"></div>
			<!--<div class="stm-listing-compare" :data-id="item.post_id" :data-title="item.title" data-toggle="tooltip" data-placement="auto left" title="" data-original-title="Add to compare">
				<i class="stm-service-icon-compare-new"></i>
			</div>
			<div class="stm-listing-favorite" :data-id="item.post_id" data-toggle="tooltip" data-placement="auto left" title="" data-original-title="Add to favorites">
				<i class="stm-service-icon-staricon"></i>
			</div>-->
			<div v-if="item.galleryUrls.length && !item.sold && false" class="image-carousel" v-carousel>
				<div v-for="(image, index) in item.galleryUrls" :key="index" class="image-item" >
					<a :href="item.url" class="rmv_txt_drctn">
						<div class="image-inner" style="padding-bottom: 0 !important; ">
							<div v-if="item.sold" class="stm-badge-directory heading-font ">SOLD</div>
							<div v-if="image" :class="{'sold': item.sold}">
								<img :src="image" class="img-responsive" alt="Placeholder"/>
							</div>
							<div v-else :class="{'sold': item.sold}">
								<img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/images/plchldr255.png'); ?>" class="img-responsive" alt="Placeholder"/>
							</div>
						</div>
					</a>
				</div>
			</div>
			<div v-else>
				<div class="image">
					<div class="stm-car-medias"></div>
					<!--<div class="stm-listing-compare" :data-id="item.post_id" :data-title="item.title" data-toggle="tooltip" data-placement="auto left" title="" data-original-title="Add to compare">
						<i class="stm-service-icon-compare-new"></i>
					</div>
					<div class="stm-listing-favorite" :data-id="item.post_id" data-toggle="tooltip" data-placement="auto left" title="" data-original-title="Add to favorites">
						<i class="stm-service-icon-staricon"></i>
					</div>-->
					<a :href="item.url" class="rmv_txt_drctn">
						<div class="image-inner" style="padding-bottom: 0 !important;">
							<div v-if="item.sold" class="stm-badge-directory heading-font ">SOLD</div>
							<div v-if="item.thumbnail" :class="{'sold': item.sold}">
								<img :src="item.thumbnail" class="img-responsive" alt="Placeholder"/>
							</div>
							<div v-else :class="{'sold': item.sold}">
								<img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/images/plchldr255.png'); ?>" class="img-responsive" alt="Placeholder"/>
							</div>
						</div>
					</a>
				</div>
			</div>
		</div>
	</div>
	<div class="hide_on_mobile">
		<div class="image">
			<div class="stm-car-medias"></div>
			<!--<div :class="{'active': isAddedToCompare(item.post_id)}"
           :data-id="item.post_id"
           :data-title="item.title"
           data-toggle="tooltip"
           data-placement="auto left"
           class="stm-listing-compare"
           title="" data-original-title="Add to compare">
				<i class="stm-service-icon-compare-new"></i>
			</div>
			<div class="stm-listing-favorite" :data-id="item.post_id" data-toggle="tooltip" data-placement="auto left" title="" data-original-title="Add to favorites">
				<i class="stm-service-icon-staricon"></i>
			</div>-->
			<a :href="item.url" class="rmv_txt_drctn">
				<div class="image-inner">
					<div v-if="item.sold" class="stm-badge-directory heading-font ">SOLD</div>
					<div v-if="item.thumbnail" :class="{'sold': item.sold}">
						<img :src="item.thumbnail" class="img-responsive" alt="Placeholder"/>
					</div>
					<div v-else :class="{'sold': item.sold}">
						<img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/images/plchldr255.png'); ?>" class="img-responsive" alt="Placeholder"/>
					</div>
				</div>
			</a>
		</div>
	</div>
	<div class="content" :class="{'featured' : item.featured}">
		<div class="meta-top">
			<!--Price-->
			<div class="price">
				<div class="normal-price">
					<span class="heading-font"> $ {{item.price | commaSeparator}}</span>
				</div>
			</div>
			<!--Title-->
			<div class="title heading-font">
				<a :href="item.url" class="rmv_txt_drctn post_title_link">
					{{item.title}}</a>
			</div>
		</div>
		<!--Item parameters-->
			<div class="meta-middle">
				<div v-if="item.mileage" class="meta-middle-unit font-exists mileage">
					<div class="meta-middle-unit-top">
						<div class="icon"><i class="stm-icon-road"></i></div>
						<div class="name">Mileage</div>
					</div>
					<div class="value h5"> {{item.mileage | commaSeparator}}</div>
				</div>
				<div v-if="item.extColor" class="meta-middle-unit font-exists exterior-color">
					<div class="meta-middle-unit-top">
						<div class="icon"><i class="fa fa-eyedropper"></i></div>
						<div class="name">Exterior color</div>
					</div>
					<div class="value h5">{{ item.extColor }}</div>
				</div>
				<div v-if="item.for_faceting.city" class="meta-middle-unit font-exists city">
					<div class="meta-middle-unit-top">
						<div class="icon"><i class="fa fa-building-o"></i></div>
						<div class="name">City</div>
					</div>

					<div class="value h5">{{item.for_faceting.city}}</div>
				</div>
				<div v-if="item.state" class="meta-middle-unit font-exists state">
					<div class="meta-middle-unit-top">
						<div class="icon"><i class="fa fa-map-marker"></i></div>
						<div class="name">Location</div>
					</div>

					<div class="value h5">{{item.city_single}}, {{item.state_two}}</div>
				</div>

				<!-- <div v-if="item.for_faceting.drive" class="meta-middle-unit font-exists drive">
					<div class="meta-middle-unit-top">
						<div class="icon"><i class="stm-icon-drive_2"></i></div>
						<div class="name">Drive</div>
					</div>
					<div class="value h5">{{item.for_faceting.drive}}</div>
				</div>
				<div v-if="item.for_faceting.sellerType" class="meta-middle-unit font-exists seller-type">
					<div class="meta-middle-unit-top">
						<div class="icon"><i class="stm-service-icon-user-2"></i></div>
						<div class="name">Seller Type</div>
					</div>
					<div class="value h5">{{item.for_faceting.sellerType}}</div>
				</div> -->
				<div v-if="zip && item._rankingInfo.geoDistance !== 10646242 && item._rankingInfo.geoDistance !== 0" class="meta-middle-unit font-exists seller-type">
					<div class="meta-middle-unit-top">
						<div class="icon"><i class="fa fa-flag-checkered"></i></div>
						<div class="name">Location</div>
					</div>
					<div class="value h5">{{Math.round(item._rankingInfo.geoDistance * 0.000621371192)}} miles</div>
				</div>
			</div>
		<!--Item options-->
		<div class="meta-bottom">
			<div class="single-car-actions">
				<ul class="list-unstyled clearfix">
				</ul>
			</div>
		</div>
	</div>
	<h1></h1>

	<div></div>

</template>
</ais-hits>
