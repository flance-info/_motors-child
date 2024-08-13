<?php $random_digit = get_random_digit(); ?>
<div>
	<div class="stm-accordion-single-unit">
		<a
			class="title collapsed"
			data-toggle="collapse"
			href="#searchZip-<?php echo $random_digit ?>"
			aria-expanded="false">
			<h5>Zip Search</h5>
			<span class="minus"></span>
		</a>
		<div class="stm-accordion-content">
			<div
				class="content collapse"
				id="searchZip-<?php echo $random_digit ?>"
				aria-expanded="false"
				style="height: 0px;">
				<div style="padding: 5px 20px 17px 25px;">
					<h5>Zip Code</h5>
					<div class="input-group">
						<input
							style="border-width: 1px;"
							type="number"
							class="noSpinner form-control"
							v-model.lazy="zip"
							v-on:keyup.enter="getLocation(zip)">
						<span class="input-group-btn">
							<button @click="getLocation(zip)" style="height: 39px;" class="btn" type="button">Search</button>
						</span>
					</div>
				</div>
				<div style="padding: 5px 20px 17px 25px;">
					<h5>Search Radius</h5>
					<select v-model="radius" class="no-select2" style="opacity: 1; visibility: visible;">
						<option value="40233">Up to 25 miles</option>
						<option value="80467">Up to 50 miles</option>
						<option value="160934">Up to 100 miles</option>
						<option value="321869">Up to 200 miles</option>
						<option value="482803">Up to 300 miles</option>
						<option value="643738">Up to 400 miles</option>
						<option value="804672">Up to 500 miles</option>
						<option value="1000000000">Any distance</option>
					</select>
				</div>
			</div>
		</div>
	</div>
</div>
