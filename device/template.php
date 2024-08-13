<?php
/**
 * File: template.php
 */
$dont_see = ( $id = get_option( 'cruxdevice_dont_see_page' ) ) ? get_permalink( $id ) : '#';
$help = ( $id = get_option( 'cruxdevice_dont_see_page' ) ) ? get_permalink( $id ) : '#';
?>
<div id="cruxdevice" v-cloak style="margin-bottom: 200px">
	<!-- steps -->
	<div class="r-steps-section">
		<ul class="r-steps-wrap">
			<li class="r-step" :class="{ active: state.step == 1, done: state.step > 1 }">
				<a href @click.prevent="step1">
					<span class="r-step-num">1</span>
					<span class="r-step-name">Device</span>
				</a>
			</li>
			<li class="r-step" :class="{ active: state.step == 2, done: state.step > 2 }">
				<a href @click.prevent="step2">
					<span class="r-step-num">2</span>
					<span class="r-step-name">Vehicle</span>
				</a>
			</li>
			<li class="r-step" :class="{ active: state.step == 3, done: state.step > 3 }">
				<a href @click.prevent="step3">
					<span class="r-step-num">3</span>
					<span class="r-step-name">DIPS</span>
				</a>
			</li>
			<li class="r-step" :class="{ active: state.step == 4, done: state.step > 4 }">
				<a href @click.prevent="step4">
					<span class="r-step-num">4</span>
					<span class="r-step-name">Wiring</span>
				</a>
			</li>
		</ul>
	</div>

	<div v-if="state.step == 1">
		<!-- step content -->
		<div class="r-step-content">
			<div class="r-step-title">
				Pick your Device:
			</div>
			<owl v-bind:source="devices" v-on:change="deviceChanged"></owl>
		</div>
		<div class="r-step-buttons">
			<a href="#" @click.prevent="step2">
				Next
				<i class="fa fa-angle-double-right"></i>
			</a>
		</div>
	</div>

	<div v-if="state.step == 2">
		<!-- step content -->
		<div class="r-step-content">
			<div class="r-step-title">
				Vehicle Selection
			</div>
			<div class="container">
				<div class="row">
					<div class="col-md-7">
						<form>
							<div class="row">
								<div class="col-md-6">
									<div class="step-sub-title">Step 1.</div>
								</div>
							</div>
							<div class="row r-select-wrap">
								<div class="col-md-6">
									<select2 v-model.number="state.make_id">
										<option value="">Select Make</option>
										<option v-for="make in makes" :value="make.id">{{ make.name }}</option>
									</select2>
								</div>
								<div class="col-md-6">
									<select2 ref="model" v-model.number="state.model_id" :disabled="!state.make_id || !models.length">
										<option value="">Select Model</option>
										<option v-for="model in models" :value="model.id">{{ model.name }}</option>
									</select2>
								</div>
							</div>
							<div class="row r-select-wrap">
								<div class="col-md-6">
									<select2 v-model.number="state.year" :disabled="!state.model_id || !years.length">
										<option value="">Select Year</option>
										<option v-for="year in years" :value="year">{{ year }}</option>
									</select2>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<a href="<?php echo $dont_see ?>" target="_blank" class="r-btn">Don't see your vehicle?</a>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="step-sub-title">Step 2.</div>
								</div>
							</div>
							<div class="row r-select-wrap">
								<div class="col-md-6">
									<select2 v-model.number="state.radio_id">
										<option value="">Select Radio</option>
										<option v-for="radio in radios" :value="radio.id">{{ radio.name }}</option>
									</select2>
								</div>
							</div>
						</form>
					</div>
					<div class="col-md-5 r-step-2-img-wrap">

					</div>
				</div>
			</div>
		</div>
		<div class="r-step-buttons">
			<a href="#" @click.prevent="step1">
				<i class="fa fa-angle-double-left"></i>
				Previous
			</a>
			<a href="#" @click.prevent="step3">
				Next
				<i class="fa fa-angle-double-right"></i>
			</a>
		</div>
	</div>

	<div v-if="state.step == 3">
		<div class="r-step-content">
			<div class="r-step-title">
				Dip-Switch Settings
			</div>
			<div class="container">
				<div class="row">
					<div class="col-md-12 step3-description">
						<div>Your selection:</div>
						<div><b>Make:</b> {{ state.make.name }} <b>Model:</b> {{ state.model.name }} <b>Year:</b> {{ state.year }} <b>Option:</b> No Option <b>Radio:</b> {{ state.radio.name }}</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-7">
						<div class="fnt-s-18">Please set the dip-switches as shown in the image below.</div>
						<div class="switcers-wrap">
							<div class="row">
								<div class="col-sm-6">
									<p class="text-center">Radio</p>
									<div class="switchers">
										<div class="one-switcher" v-for="(sw, index) in state.radio.data.switches" :class="{ down: !sw }">
											<span class="switch-num">{{ index + 1 }}</span>
										</div>
									</div>
								</div>
								<div class="col-sm-6" v-show="manual.data.dips.length">
									<p class="text-center">Vehicle</p>
									<div class="switchers">
										<div class="one-switcher" v-for="(sw, index) in manual.data.dips" :class="{ down: !sw }">
											<span class="switch-num">{{ index + 5 }}</span>
										</div>
									</div>
								</div>
							</div>
							<div class="step3-bottom-text">
								ON <i class="fa fa-long-arrow-up"></i>
							</div>
						</div>
					</div>
					<div class="col-md-5 r-step-2-img-wrap"></div>
				</div>
			</div>
		</div>
		<div class="r-step-buttons">
			<a href="#" @click.prevent="step2">
				<i class="fa fa-angle-double-left"></i>
				Previous
			</a>
			<a href="#" @click.prevent="step4">
				Next
				<i class="fa fa-angle-double-right"></i>
			</a>
		</div>
	</div>

	<div v-if="state.step == 4 && manual">
		<div class="r-step-content">
			<div class="r-step-title">
				Wiring Diagram
			</div>
			<div class="container">
				<div class="row">
					<div class="col-md-12 step4-description">
						<div>Your selection:</div>
					</div>
					<div class="col-md-10 step4-description">
						<div><b>Make:</b> {{ state.make.name }} <b>Model:</b> {{ state.model.name }} <b>Year:</b> {{ state.year }} <b>Option:</b> No Option <b>Radio:</b> {{ state.radio.name }}</div>
						<b>Installation Note:</b>
						<div v-show="manual.notes.trim() !== ''" style="white-space: pre-wrap">{{ manual.notes }}</div>
						<div v-else>No Installation Notes.</div>
					</div>
					<div class="col-md-2">
						<a href="<?php echo $help ?>" target="_blank" class="r-btn r-sm">Connector help</a>
					</div>
				</div>
				<div class="r-lines-wrap">
					<div class="row r-config-title scrollable">
						<div class="col-md-4 col-xs-4">
							Crux Wiring
						</div>
						<div class="col-md-4 col-xs-4" style="padding: 0 81px 0 0;">
							Vehicle Wiring
						</div>
						<div class="col-md-4 col-xs-4" style="padding: 0 70px 0 0;">
							Connector Viewed From Pin Side
						</div>
					</div>
					<div v-for="group in manual.groups" class="r-lines-container">
						<div class="r-line r-line-1">
							<div v-for="conn in group.conns" class="r-one-line">
								<span class="line-name">{{ conn.c_color }} &middot {{ conn.label }}</span>
								<span class="r-connector" :style="conn.c_ccs"></span>
							</div>
						</div>
						<div class="r-line r-line-2">
							<div v-for="conn in group.conns" class="r-one-line">
								<span class="line-name">{{ conn.color }} &middot {{ conn.pin }}</span>
								<span class="r-connector" :style="conn.css"></span>
							</div>
						</div>
						<div class="r-line-img">
							<img :src="config.attachments_uri + group.img"  class="img-responsive" />
						</div>
					</div>
					<div v-if="state.radio.data.image || manual.data.radio_image" class="row r-config-title scrollable">
						<div class="col-md-4 col-xs-4">
							Crux Wiring
						</div>
						<div class="col-md-4 col-xs-4">
							Radio Wiring
						</div>
						<div class="col-md-4 col-xs-4">
							Connector Wiring
						</div>
					</div>
					<div v-if="manual.data.radio_image" class="r-lines-container">
						<img :src="config.attachments_uri + manual.data.radio_image" class="img-responsive r-wire-img">
					</div>
					<div v-else-if="state.radio.data.image" class="r-lines-container">
						<div class="r-two-line-wrap">
							<img :src="config.attachments_uri + state.radio.data.wiring_image" class="img-responsive r-wire-img">
						</div>
						<div class="r-line-img">
							<img :src="config.attachments_uri + state.radio.data.image"  class="img-responsive" />
						</div>
					</div>
				</div>
			</div>

		</div>
		<div class="r-step-buttons">
			<a href="#" @click.prevent="step3">
				<i class="fa fa-angle-double-left"></i>
				Previous
			</a>
		</div>
	</div>

</div>

<script type="x-template" id="cruxdevice-owl">
	<div v-if="devices.length" class="r-step-slider">
		<div v-for="device in devices" :rel="device.id" class="r-step-slide">
			<div class="r-slide-text ">
				<div v-if="device.title === 'SWC-100'">SWR 100</div>
				<div v-else>
					{{ device.title }}
				</div>
			</div>
			<img src="<?php echo cruxdevice_assets() ?>images/product.png" />
		</div>
	</div>
</script>

<style>
	[v-cloak] {
		display: none;
	}

</style>