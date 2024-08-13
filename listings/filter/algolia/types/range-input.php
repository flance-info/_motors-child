<?php

$random_digit = get_random_digit();

?>
<div class="stm-accordion-single-unit">
		<a class="title collapsed" data-toggle="collapse" :href="'#' + filter.slug + '-<?php echo $random_digit ?>'" aria-expanded="false">
			<div class="filter-title">
				<h5>
					<span class="filter-title__text">
						{{ filter.title }}
					</span>
				</h5>
			</div>
			<span class="minus"></span>
		</a>
		<div class="stm-accordion-content">
			<div :id="filter.slug + '-<?php echo $random_digit ?>'" aria-expanded="false" style="height: 0px;"
					 :class="filter.slug + '-<?php echo $random_digit ?>'"
					 class="content collapse"
			>
        <div class="price_filter">
          <ais-range-input  :min="0" :max="10000000" attribute="price">
            <form
              slot-scope="{
                currentRefinement,
                range,
                canRefine,
                refine,
              }"
            >
              <label >
                <cleave class="form-control"
                        placeholder="Min"
                        v-model="inputMin"
                        :options="options">
                </cleave>
              </label>
              <span>to</span>
              <label>
                <cleave class="form-control"
                        placeholder="Max"
                        v-model="inputMax"
                        :options="options">
                </cleave>
              </label>
              <button style="padding: 8px 12px;" type="button" @click="applyPriceFilter(refine)">Go</button>
            </form>
          </ais-range-input>
        </div>
        <div ref="errorMsg" style="padding: 14px 5px; font-size: 12px; text-align: center; margin-top: -23px; color: #db1c22;">
        </div>
      </div>
		</div>
	</div>
