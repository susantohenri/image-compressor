<?php

/**
 * Plugin Name:       Image Compressor
 * Version:           1.0
 */
require 'vendor/autoload.php';
add_action('init', 'image_compressor_ph4ntomphoton');

add_action('wp_enqueue_scripts', 'enqueue_image_optimizer_scripts');
function enqueue_image_optimizer_scripts()
{
	wp_enqueue_script('dropzone_script', 'https://unpkg.com/dropzone@5/dist/min/dropzone.min.js', array('jquery')/* , ,$in_footer = false*/);
	wp_enqueue_style('dropzone_style', 'https://unpkg.com/dropzone@5/dist/min/dropzone.min.css'/*, , ,$in_footer = false*/);
	wp_enqueue_style('custom_styles', plugin_dir_url(__FILE__) . 'style.css'/*, , ,$in_footer = false*/);
	// wp_enqueue_style('vertical_range_slider_css', plugin_dir_url( __FILE__ ).'jquery-ui-slider-pips.css'/*, , ,$in_footer = false*/);
	wp_enqueue_script('vertical_range_slider', 'https://cdn.jsdelivr.net/npm/svelte-range-slider-pips@2.0.1/dist/svelte-range-slider-pips.js');

	// wp_enqueue_style('flick_theme', 'https://code.jquery.com/ui/1.10.4/themes/flick/jquery-ui.css');
	// wp_enqueue_script('hammer-js', "https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.8/hammer.min.js",array( 'jquery' ),NULL,true);
	wp_enqueue_script('images-compare', 'https://cdn.knightlab.com/libs/juxtapose/latest/js/juxtapose.min.js', array('jquery'), NULL, true);
	wp_enqueue_script('serializejson', plugin_dir_url(__FILE__) . 'serializejson.js', array('jquery'));
	wp_enqueue_style('images-compare-style', 'https://cdn.knightlab.com/libs/juxtapose/latest/css/juxtapose.css'/*, , ,$in_footer = false*/);
	wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css'/*, , ,$in_footer = false*/);

	wp_enqueue_style('image_compare_style_ph4ntomphoton', plugin_dir_url(__FILE__) . '/image-compare/style.css');
	wp_enqueue_script('image_compare_style_ph4ntomphoton', plugin_dir_url(__FILE__) . '/image-compare/script.js');
}

function image_compressor_ph4ntomphoton()
{
	add_shortcode('image-compressor', 'image_compressor_shortcode_callback');
}
function image_compressor_shortcode_callback($atts, $content = "")
{
	ob_start();
?>

	<script type="text/javascript">
		Dropzone.autoDiscover = false;
	</script>
	<div class="display-none" id="previewTemplate">
		<div class="dz-preview dz-file-preview">
			<div class="dz-image">
				<div class="dz-c-filename"><span data-dz-name></span></div>
				<img data-dz-thumbnail />
				<div class="download-link button display-none"><a href=""><i class="fa-solid fa-download"></i>Download</a></div>
				<div class="percentage"></div>
			</div>
			<div class="dz-details">
				<div class="dz-size display-none"><span data-dz-size></span></div>
			</div>
			<div class="dz-progress">
				<span class="upload-progress-percentage"><strong></strong></span>
				<span class="dz-upload" data-dz-uploadprogress></span>
			</div>
			<div class="dz-error-message"><span data-dz-errormessage></span></div>
			<i data-dz-remove class="fa-solid fa-circle-xmark remove-icon"></i>
			<div class="dz-success-mark">
				<svg width="54" height="54" viewBox="0 0 54 54" fill="white" xmlns="http://www.w3.org/2000/svg">
					<path d="M10.2071 29.7929L14.2929 25.7071C14.6834 25.3166 15.3166 25.3166 15.7071 25.7071L21.2929 31.2929C21.6834 31.6834 22.3166 31.6834 22.7071 31.2929L38.2929 15.7071C38.6834 15.3166 39.3166 15.3166 39.7071 15.7071L43.7929 19.7929C44.1834 20.1834 44.1834 20.8166 43.7929 21.2071L22.7071 42.2929C22.3166 42.6834 21.6834 42.6834 21.2929 42.2929L10.2071 31.2071C9.81658 30.8166 9.81658 30.1834 10.2071 29.7929Z" />
				</svg>
			</div>
			<div class="dz-error-mark">
				<svg width="54" height="54" viewBox="0 0 54 54" fill="white" xmlns="http://www.w3.org/2000/svg">
					<path d="M26.2929 20.2929L19.2071 13.2071C18.8166 12.8166 18.1834 12.8166 17.7929 13.2071L13.2071 17.7929C12.8166 18.1834 12.8166 18.8166 13.2071 19.2071L20.2929 26.2929C20.6834 26.6834 20.6834 27.3166 20.2929 27.7071L13.2071 34.7929C12.8166 35.1834 12.8166 35.8166 13.2071 36.2071L17.7929 40.7929C18.1834 41.1834 18.8166 41.1834 19.2071 40.7929L26.2929 33.7071C26.6834 33.3166 27.3166 33.3166 27.7071 33.7071L34.7929 40.7929C35.1834 41.1834 35.8166 41.1834 36.2071 40.7929L40.7929 36.2071C41.1834 35.8166 41.1834 35.1834 40.7929 34.7929L33.7071 27.7071C33.3166 27.3166 33.3166 26.6834 33.7071 26.2929L40.7929 19.2071C41.1834 18.8166 41.1834 18.1834 40.7929 17.7929L36.2071 13.2071C35.8166 12.8166 35.1834 12.8166 34.7929 13.2071L27.7071 20.2929C27.3166 20.6834 26.6834 20.6834 26.2929 20.2929Z" />
				</svg>
			</div>
		</div>
	</div>
	<div style="display: none;" id="clickPreventer"></div>
	<div class="custom_wrapper_two">
		<div class="buttonWrapper">
			<div class="button" id="uploadBtn">
				<i class="fa-regular fa-circle-up"></i>&nbsp;Upload
			</div>
			<div class="button disabled" id="clearQBtn"><i class="fa-regular fa-circle-xmark"></i>&nbsp;Clear Queue</div>
		</div>
		<div class="custom_wrapper">
			<div class="scroll-button left"><i class="fa-solid fa-less-than"></i></div>
			<form action="<?php echo plugin_dir_url(__FILE__) . "upload.php"; ?>" id="dropzone" class="dropzone">


			</form>
			<div class="scroll-button right"><i class="fa-solid fa-greater-than"></i></div>
		</div>
		<div class="buttonWrapper">
			<div class="button disabled" id="downloadAllBtn">
				<i class="fa-regular fa-circle-down"></i>&nbsp;Download All
			</div>
		</div>
	</div>
	<!-- Main div container -->
	<div class="custom_wrapper before-after-texts  custom_wrapper_two">
		<div class="text-before"><strong></strong></div>
		<div class="text-after"><strong></strong></div>
	</div>
	<div class="custom_wrapper custom_wrapper_two display-none">
		<!-- <div id="myImageCompare">
		</div> -->
		<div id='container' class="img-comp-container">
			<div class="img-comp-img">
				<img class='image' src="<?php echo plugin_dir_url(__FILE__); ?>giphy.gif">
			</div>
			<div class="img-comp-img img-comp-overlay">
				<img class='image' src="<?php echo plugin_dir_url(__FILE__); ?>giphy2.gif">
			</div>
		</div>

		<div id="quality_range">
			<form id='quality_form'>
				<div id="quality_value_wrapper">
					<span>Compression</span>
					<div name="quality" id="value_in_number">80</div>
				</div>
				<input type='hidden' value='' name='originalfilename'>
				<input type='hidden' value='' name='optimizedfilename'>
				<!-- <input type='range' name='quality' class='vranger' min='1' max='100' value="80"> -->
				<div id="my-slider" class="slider">
					<input class="button" type='button' value='Apply' name='submit' id='quality_form_submit'>
				</div>

			</form>
		</div>
	</div>
	<script type="text/javascript">
		var mySlider = new RangeSliderPips({
			target: document.querySelector("#my-slider"),
			props: {
				min: 1,
				max: 100,
				vertical: true,
				values: [80],
				float: true,
			}
		});
		mySlider.$on('change', function(e) {
			jQuery('#value_in_number').html(e.detail.value);
		});
		jQuery(document).ready(function($) {
			function downloadURI(uri, name) {
				var link = document.createElement("a");
				// If you don't know the name or want to use
				// the webserver default set name = ''
				link.setAttribute('download', name);
				link.href = uri;
				document.body.appendChild(link);
				link.click();
				link.remove();
			}

			function formatBytes(bytes, decimals = 2) {
				if (!+bytes) return '0 Bytes'
				const k = 1024
				const dm = decimals < 0 ? 0 : decimals
				const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB']
				const i = Math.floor(Math.log(bytes) / Math.log(k))
				return `${parseFloat((bytes / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`
			}
			var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";

			function getImageSize(URL) {
				return new Promise(function(resolve, reject) {
					console.log('url is: ' + URL);
					var dataToSend = {
						action: 'get_image_size',
						url: URL
					};
					$.ajax({
						type: "POST",
						url: ajaxurl,
						data: dataToSend,
						success: function(data) {
							console.log("image size is: " + data);
							resolve(data);
						},
						error: function(err) {
							reject(err);
						}
					});
				});
			}

			function updateCompressionPercentage(img2source, previewElem) {
				var img2size = 0;
				getImageSize(img2source).then(function(size) {
					img2size = formatBytes(size);
					var img2 = $('#container .img-comp-img img').first();
					var newSize = 100 - parseFloat((100 * parseFloat(size)) / parseFloat($(previewElem).find('[data-dz-size]').html()));
					newSize = Number(newSize).toFixed(2);
					console.log('image 2 latest size is: ' + img2size);
					$(previewElem).find('.percentage').html('-' + newSize + '%');
					$(previewElem).data('image2Size', img2size);
					$(previewElem).data('reducedPercentage', newSize);
				}).catch(function(err) {
					// Run this when promise was rejected via reject()
					console.log('error: ' + err);
				})
			}
			var myDropzone = new Dropzone("#dropzone", {
				previewTemplate: document.getElementById('previewTemplate').innerHTML,
				filesizeBase: 1024,
				maxThumbnailFilesize: 1000,
				clickable: '#uploadBtn',
				dictDefaultMessage: "<span style='cursor: default;'>Drop your files here to upload</span>",
				renameFile: function(file) {
					var str = file.name;
					str = str.replace(/\s+/g, '');
					file.name = str;
					console.log('old filename: ' + file.name + ' new filename: ' + str);
					return str;
				},
				init: function() {
					var $dzElem = this;
					this.on("removedfile", file => {
						$('.custom_wrapper.custom_wrapper_two').addClass('display-none');
						var errored = 0;
						for (var i = this.files.length - 1; i >= 0; i--) {
							if (this.files[i].status == 'error') {
								errored++;
							}
						}
						if (this.files.length == 0 || errored == this.files.length) {
							$('#clearQBtn').addClass('disabled');
							$('#downloadAllBtn').addClass('disabled');
						}
					});
					this.on("addedfile", file => {
						file.previewElement.querySelector('[data-dz-name]').textContent = file.upload.filename;
						for (node of file.previewElement.querySelectorAll("[data-dz-size]")) {
							node.innerHTML = file.size;
						}
					});
					this.on('success', function(file) {
						$('#clearQBtn').removeClass('disabled');
						$('#downloadAllBtn').removeClass('disabled');

						var img2source = '<? echo plugin_dir_url(__FILE__) . "uploads/"; ?>';
						img2source += 'optimized' + file.upload.filename;
						file.previewElement.addEventListener("click", previewFunc);
						registerBorderCss(file.previewElement);

						//make download button working
						var downloadButton = $(file.previewElement).find('.download-link');
						downloadButton.on('click', function(e) {
							e.preventDefault();
							downloadURI(img2source + '?time=' + Math.floor(Date.now() / 1000), $(file.previewElement).find('.dz-c-filename span').html());
							downloadButton.off('click');
						});

						//show percentage of compression;
						updateCompressionPercentage(img2source, file.previewElement);


						$(file.previewElement).trigger('click');
						if (file.previewElement) {
							$(file.previewElement).find('.download-link').removeClass('display-none');
							$(file.previewElement).find('.loader').fadeOut();
							return file.previewElement.classList.add("dz-success");
						}
					});
					this.on('uploadprogress', function(file, progress, bytesSent) {
						console.log(progress);
						if (file.previewElement) {
							for (let node of file.previewElement.querySelectorAll(
									"[data-dz-uploadprogress]"
								)) {
								if (node.nodeName === "PROGRESS") {
									(node.value = progress);
								} else {
									(node.style.width = `${progress}%`);
								}
								$(node).parent().find('.upload-progress-percentage > strong').html(parseInt(progress) + '%');
								if (progress == 100) {
									$(node).parent().fadeOut();
									$(node).parent().parent().append('<div class="loader"></div>').fadeIn();
								}
							}
						}
					});

					this.on('error', function(file, message) {
						$("#clickPreventer").fadeOut();
						$(file.previewElement).find('.loader').fadeOut();
						if (this.files.length > 20 /* maxFiles=20*/ ) {
							if (file.previewElement) {
								file.previewElement.remove();
								this.files.pop();
							}
						}
					})
				},
				maxFiles: 20,
				acceptedFiles: "image/jpeg, image/png, image/gif",
				// addRemoveLinks: true,
				thumbnailWidth: 150,
				thumbnailHeight: 150,
			});
			// $('.vranger').on('change', function(){
			// 	$('#quality_form_submit').trigger('click');
			// });
			$('.scroll-button').on('click', function() {
				//commented out part is for snapping to item;
				var container = $(this).parent().find('form');
				// container.css('scroll-snap-type', 'none');
				var dir = $(this).hasClass('left') ? '-=' : '+=';
				container.stop().animate({
					scrollLeft: dir + container[0].offsetWidth
				}, 500 /*, ()=> container.css('scroll-snap-type', 'none')*/ );
			});

			function recompress(dataToSend) {
				return new Promise(function(resolve, reject) {
					console.log(dataToSend);
					$.ajax({
						url: ajaxurl,
						data: dataToSend,
						type: 'POST',
						success: function(data) {
							resolve(data);
						},
						error: function(err) {
							reject(err);
						}
					});
				});
			}
			var lastPreviewElement = '';
			$('#quality_form_submit').on('click', function(e) {
				e.preventDefault();
				$('#clickPreventer').fadeIn();
				var dataToSend = {};
				var optimizedfilename = $('#container .img-comp-img img').first()[0].src;
				var originalfilename = optimizedfilename.replace('optimized', '');
				dataToSend['action'] = 'quality_change_of_picture';
				dataToSend['quality'] = $('#value_in_number').html();
				dataToSend['originalfilename'] = originalfilename;
				dataToSend['optimizedfilename'] = optimizedfilename;
				recompress(dataToSend).then(function(data) {
					console.log(data);
					previewFunc();
					$('#clickPreventer').fadeOut();
				}).catch(function(err) {
					alert("error");
					console.log(err);
					$('#clickPreventer').fadeOut();
				});

			});
			$('#clearQBtn').on('click', function() {
				myDropzone.removeAllFiles();
				// $('dz-preview').remove();
			});

			$('#downloadAllBtn').on('click', function(e) {
				e.preventDefault();
				console.log(myDropzone.files);
				var links = [];
				for (var i = myDropzone.files.length - 1; i >= 0; i--) {
					if (myDropzone.files[i].status == "success") {
						links.push('optimized' + myDropzone.files[i].name);
					}
				}
				// console.log(links);
				var dataToSend = {};
				dataToSend['files'] = links;
				dataToSend['action'] = 'download_all_button';
				console.log(dataToSend);
				$.ajax({
					url: ajaxurl,
					data: dataToSend,
					type: 'POST',
					success: function(data) {
						console.log(data);
						downloadURI(data, 'compressedImages.zip');
					},
					failure: function(data) {},
					error: function(data) {}
				})
			});

			function registerBorderCss(preview) {
				$(preview).on('click', function() {
					$('.dz-preview').each(function() {
						$(this).removeClass('borderedPreview');
					});
					$(this).addClass('borderedPreview');
				});
			}

			function previewFunc() {
				$('.custom_wrapper.custom_wrapper_two').removeClass('display-none');

				if (this === window) {
					console.log('recompression');
				} else {
					console.log('first compression, here this is: ');
					console.log(this);
					lastPreviewElement = this;
				}
				var filename = $(lastPreviewElement).find('.dz-c-filename span').html();
				var img1source = '<? echo plugin_dir_url(__FILE__) . "uploads/"; ?>';
				img1source += filename;
				var img2source = '<? echo plugin_dir_url(__FILE__) . "uploads/"; ?>';
				img2source += 'optimized' + filename;

				var previewElementV = $(lastPreviewElement);
				console.log('img1source is: ' + img1source + ' img2source: ' + img2source);
				//change image one
				console.log($('#container .img-comp-img img'));
				var afterImage = new Image();
				$(afterImage).on('load', function() {
					console.log(afterImage);
					$('#container .img-comp-img img').first().attr('src', this.src);
					updateCompressionPercentage(this.src, lastPreviewElement);
					var img2size = 0;
					getImageSize(this.src).then(function(size) {
						img2size = formatBytes(size);
						var img2 = $('#container .img-comp-img img').first();
						var newSize = 100 - parseFloat((100 * parseFloat(size)) / parseFloat($(lastPreviewElement).find('[data-dz-size]').html()));
						newSize = Number(newSize).toFixed(2);
						console.log('image 2 latest size is: ' + img2size);
						$(lastPreviewElement).find('.percentage').html('-' + newSize + '%');
						$(lastPreviewElement).data('image2Size', img2size);
						$(lastPreviewElement).data('reducedPercentage', newSize);
						var image2Size = $(lastPreviewElement).data('image2Size');
						$('.text-after > strong').html('Compressed: ' + image2Size + ' (-' + $(lastPreviewElement).data('reducedPercentage') + '%)');
						$(afterImage).off('load');
					}).catch(function(err) {
						// Run this when promise was rejected via reject()
						console.log('error: ' + err);
					})


				})

				afterImage.src = img2source;
				$('#container .img-comp-img img').last().attr('src', img1source);
				var imgSize = $(lastPreviewElement).find('.dz-size span').html();
				imgSize = formatBytes(imgSize);
				$('.text-before > strong').html('Original: ' + imgSize);
			}

		});
	</script>

<?php
	$html = ob_get_clean();
	return $html;
	// return "<pre>".print_r(var_dump($logger), 1)."</pre>";
}

add_action('wp_ajax_get_image_size', 'get_image_size');
add_action('wp_ajax_nopriv_get_image_size', 'get_image_size');
function get_image_size()
{
	// echo "<pre>".print_r($_POST['url'], true)."</pre>";
	$image_url = $_POST['url'];
	$image_path = parse_url($image_url, PHP_URL_PATH);
	clearstatcache();
	echo filesize($_SERVER['DOCUMENT_ROOT'] . $image_path);
	wp_die();
}

add_action('wp_ajax_quality_change_of_picture', 'quality_change_callback');
add_action('wp_ajax_nopriv_quality_change_of_picture', 'quality_change_callback');
function quality_change_callback()
{
	require dirname(__FILE__) . '/upload.php';
	compressor($_POST, true);
	wp_die();
}

add_action('wp_ajax_download_all_button', 'download_all_button_callback');
add_action('wp_ajax_nopriv_download_all_button', 'download_all_button_callback');

function download_all_button_callback()
{
	$fileLinks = $_POST['files'];
	$zip = new ZipArchive;
	$ds = '/';
	$storeFolder = 'uploads';
	$targetPath = dirname(__FILE__) . $ds . $storeFolder . $ds;  //4
	$tmp_file = $targetPath . 'compressedImages.zip';
	if (file_exists($tmp_file)) {
		unlink($tmp_file);
	}
	if ($zip->open($tmp_file,  ZipArchive::CREATE)) {
		for ($i = 0; $i < sizeof($fileLinks); $i++) {
			// echo 'trying to add: '.dirname(__FILE__).'/uploads/'.$fileLinks[$i]."\n";
			$zip->addFile(dirname(__FILE__) . '/uploads/' . $fileLinks[$i], $fileLinks[$i]);
		}
		$zip->close();
		echo plugin_dir_url(__FILE__) . $storeFolder . $ds . 'compressedImages.zip' . "\n";
		// echo $tmp_file;
	} else {
		die('could not open archive');
	}


	// echo 'Archive created!';
	// header('Content-disposition: attachment; filename=files.zip');
	// header('Content-type: application/zip');
	// readfile($tmp_file);
	wp_die();
}
