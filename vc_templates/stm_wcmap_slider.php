<?php

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
?>

<?php
$videos = vc_param_group_parse_atts( $atts['videos'] );
if ( ! empty( $videos ) ) : ?>
	<div class="section-video">
		<div class="section-video-span">
			<div class="button-74">
				<div class="icon-75">
					<div class="elements-76">
						<div class="vector-77"></div>
					</div>
				</div>
			</div>
			<?php foreach ( $videos as $k=>$video ) :
				?>

				<div class="frame-6c" style="<?php if ($k == 1) echo 'display:none'; ?>">
					<div class="video">
						<div class="embed-responsive embed-responsive-16by9">
							<iframe class="embed-responsive-item" src="<?php echo esc_url( $video['video_url'] ); ?>" allowfullscreen></iframe>
						</div>
					</div>
					<div class="info-6f">
						<?php if ( ! empty( $video['video_title'] ) ) : ?>
							<span class="video-heading"><?php echo esc_html( $video['video_title'] ); ?></span>
						<?php endif; ?>
						<?php if ( ! empty( $video['video_description'] ) ) : ?>
							<span class="video-text"><?php echo esc_html( $video['video_description'] ); ?></span>
						<?php endif; ?>
					</div>
				</div>

			<?php endforeach; ?>

			<div class="button-70">
				<div class="icon-71">
					<div class="elements-72">
						<div class="vector-73"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>

<div class="section-video container-fluid">
    <div class="row section-video-span">
        <!-- Previous Button -->
        <div class="col-xs-12 col-sm-1 text-center stm-button-arrow">
            <div class="button-74">
                <div class="icon-75">
                    <div class="elements-76">
                        <div class="vector-77"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Video Slider Area -->
        <div class="col-xs-12 col-sm-10 ">
            <!-- First Video Frame -->
            <div class="frame-6c">
                <div class="video embed-responsive ">
                    <iframe allowfullscreen data-src="https://www.youtube.com/embed/BGU58eoAOaw" class="embed-responsive-item lazyloaded" src="https://www.youtube.com/embed/BGU58eoAOaw" loading="lazy"></iframe>
                </div>
                <div class="info-6f">
                    <span class="video-heading">Video: How to install into the car our product</span>
                    <span class="video-text">Video instruction, demo text You’ll be able to effortlessly build cars listings, set taxonomies, make use of the optimized search filter.</span>
                </div>
            </div>

            <!-- Second Video Frame -->
            <div class="frame-6c" style="display: none;">
                <div class="video embed-responsive embed-responsive-16by9">
                    <iframe allowfullscreen data-src="https://www.youtube.com/embed/2Z4m4lnjxkY" class="embed-responsive-item lazyloaded" src="https://www.youtube.com/embed/2Z4m4lnjxkY" loading="lazy"></iframe>
                </div>
                <div class="info-6f">
                    <span class="video-heading">Video: Another Product Installation</span>
                    <span class="video-text">Text about the video</span>
                </div>
            </div>
        </div>

        <!-- Next Button -->
        <div class="col-xs-12 col-sm-1 text-center stm-button-arrow">
            <div class="button-70">
                <div class="icon-71">
                    <div class="elements-72">
                        <div class="vector-73"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
jQuery(document).ready(function($) {
    var currentIndex = 0;
    var frames = $('.frame-6c');

    function showFrame(index) {
        frames.hide();
        $(frames[index]).show();
    }

    // Initialize by showing the first frame
  // showFrame(currentIndex);

    $('.button-74').click(function() {
        // Move to the previous frame
        currentIndex = (currentIndex > 0) ? currentIndex - 1 : frames.length - 1;
        showFrame(currentIndex);
    });

    $('.button-70').click(function() {
        // Move to the next frame
        currentIndex = (currentIndex < frames.length - 1) ? currentIndex + 1 : 0;
        showFrame(currentIndex);
    });
});


</script>
