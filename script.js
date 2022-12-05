	jQuery(document).ready(function($){
		function downloadURI(uri, name) 
		{
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
		function getImageSize(URL){
			return $.ajax({
				type: "HEAD",
		        async: true,
		        url: URL
			});
		}
		function Graphic(properties, slider) {
	        var self = this;
	        this.image = new Image();

	        this.loaded = false;
	        this.image.onload = function() {
	            self.loaded = true;
	            // slider._onLoaded();
	        };

	        this.image.src = properties.src;
	        this.image.alt = properties.alt || '';
	        this.label = properties.label || false;
	        this.credit = properties.credit || false;
	    }
		var slider='';
		
		initiateJuxtapose();
		
		$('.vranger').on('input', function(){
			var value=$(this).val();
			$('#value_in_number').html(value);
			
		});
		// $('.vranger').on('change', function(){
		// 	$('#quality_form_submit').trigger('click');
		// });
		$('.scroll-button').on('click', function(){
			var container=$(this).parent().find('form');
			var dir = $(this).hasClass('left') ? '-=' : '+=' ;
			container.stop().animate({scrollLeft: dir+'300'}, 300);
		})
		$('#quality_form_submit').on('click', function(e){
			e.preventDefault();
			$('#clickPreventer').fadeIn();
			var dataToSend=$('#quality_form').serializeJSON();
			dataToSend['action']='quality_change_of_picture';
			console.log(dataToSend);
			$.ajax({
				url: ajaxurl, 
				data: dataToSend, 
				type: 'POST',
				success: function(data){
					console.log('success:');
					// $('#myImageCompare').JXSlider.updateSlider(50);
					previewFunc(dataToSend['originalfilename'], dataToSend['optimizedfilename'], 1);
					$('#clickPreventer').fadeOut();
					console.log(slider);
				},
				failure: function(data){
					alert('Error: '+data.responseText+'\nPlease try again.');
					$('#clickPreventer').fadeOut();
					console.log('failure:');

					console.log(data.responseText);
				},
				error: function(data){
					alert('Error: '+data.responseText+'\nPlease try again.');
					$('#clickPreventer').fadeOut();
					console.log('error:');

					console.log(data.responseText);
				}
			})
		});
	});