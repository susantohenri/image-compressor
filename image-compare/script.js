		jQuery(document).ready(function($){
		jQuery('.image').on('dragstart', function(e){
					// alert('lul');
					e.preventDefault();
				});
			function enableDragOnContainer(){
				const ele = document.getElementById('container');
            ele.style.cursor = 'grab';

            let pos = { top: 0, left: 0, x: 0, y: 0 };

            const mouseDownHandler = function (e) {
                ele.style.cursor = 'grabbing';
                ele.style.userSelect = 'none';

                pos = {
                    left: ele.scrollLeft,
                    top: ele.scrollTop,
                    // Get the current mouse position
                    x: e.clientX,
                    y: e.clientY,
                };


                document.addEventListener('mousemove', mouseMoveHandler);
                document.addEventListener('mouseup', mouseUpHandler);
            };

            const mouseMoveHandler = function (e) {
                // How far the mouse has been moved
                const dx = e.clientX - pos.x;
                const dy = e.clientY - pos.y;

                // Scroll the element
                ele.scrollTop = pos.top - dy;
                ele.scrollLeft = pos.left - dx;
                
                
            };

            const mouseUpHandler = function (e) {
                ele.style.cursor = 'grab';
                ele.style.removeProperty('user-select');
                // console.log(getCursorPos2(e));
                img.style.width = getCursorPos2(e) + "px";
                $(img).data('slided', 'true');
                document.removeEventListener('mousemove', mouseMoveHandler);
                document.removeEventListener('mouseup', mouseUpHandler);
            };

            // Attach the handler
            ele.addEventListener('mousedown', mouseDownHandler);
			}

			enableDragOnContainer();



			var slider, img, clicked = 0, w, h;
			img=document.getElementsByClassName("img-comp-overlay")[0];
			function initComparisons() {
			    /* Once for each "overlay" element:
			    pass the "overlay" element as a parameter when executing the compareImages function: */
			    compareImages(img);
			}
			function slideReady(e) {
		      /* Prevent any other actions that may occur when moving over the image: */
		      e.preventDefault();
		      /* The slider is now clicked and ready to move: */
		      clicked = 1;
		      /* Execute a function when the slider is moved: */
		      window.addEventListener("mousemove", slideMove);
		      window.addEventListener("touchmove", slideMove);
		    }
		    function slideFinish() {
		      /* The slider is no longer clicked: */
		      clicked = 0;
		    }
		    function slideMove(e) {
		      var pos;
		      /* If the slider is no longer clicked, exit this function: */
		      if (clicked == 0) return false;
		      /* Get the cursor's x position: */
		      pos = getCursorPos(e)
		      /* Prevent the slider from being positioned outside the image: */
		      // console.log($('#container')[0].getBoundingClientRect());
		      // console.log(window.pageXOffset+' '+$('#container').left+' '+$('#container').right+' '+w);
		      // console.log('container offsetWidth is: '+$('#container')[0].offsetWidth);
		      if (pos < 0) pos = 0;
		      if (pos > $('#container')[0].offsetWidth) pos = $('#container')[0].offsetWidth;
		      /* Execute a function that will resize the overlay image according to the cursor: */
		      // console.log('pos in slide is: '+pos+' slider.offsetWidth:'+slider.offsetWidth+' imgOffsetWidth: '+img.offsetWidth);
		      /* Resize the image: */
		      // console.log($(slider));
				img.style.width = pos+$('#container')[0].scrollLeft /*(sliderWidth/2)*/ + "px";
		      /* Position the slider: */
		      slider.style.left = parseFloat(img.offsetWidth - $('#container')[0].scrollLeft + 20)  + 'px';
		      if(Number(slider.style.left.replace('px',''))<0) slider.style.left=0+'px';
		    }
		    function getCursorPos(e) {
		      var a, x = 0;
		      e = (e.changedTouches) ? e.changedTouches[0] : e;
		      /* Get the x positions of the image: */
		      a = $('#container')[0].getBoundingClientRect();
		      /* Calculate the cursor's x coordinate, relative to the image: */
		      // console.log($('#container'));
		      // console.log('e.pageX:'+e.pageX+" a.left"+a.left+" $('#container')[0]scrollLeft"+$('#container')[0].scrollLeft);
		      x = e.pageX - a.left;
		      /* Consider any page scrolling: */
		      x = x - window.pageXOffset/* - $('#container')[0].scrollLeft*/;
		      return x;
		    }
		    function getCursorPos2(e) {
		      var a, x = 0;
		      e = (e.changedTouches) ? e.changedTouches[0] : e;
		      /* Get the x positions of the image: */
		      a = $('#container').scrollLeft();
		      b = $('.img-comp-slider')[0].getBoundingClientRect();
		      /* Calculate the cursor's x coordinate, relative to the image: */
		      // console.log($('#container')[0].getBoundingClientRect());
		      // console.log(window.pageXOffset+' '+$('#container').left+' '+$('#container').right+' '+b.left+' '+b.right);

		      x = e.pageX - a.left;
		      /* Consider any page scrolling: */
		      x = x - window.pageXOffset;
		      return Number(a + parseFloat(b.left+b.right)/2) - window.pageXOffset-$('#container')[0].getBoundingClientRect().left;
		      return x;
		    }
		    function slide(x) {
		    	
		    }
		  function compareImages(img) {
		    /* Get the width and height of the img element */
		    w = img.offsetWidth;
		    h = img.offsetHeight;
		    /* Set the width of the img element to 50%: */
		    img.style.width = ($('#container')[0].offsetWidth / 2) -20 + "px";
		    /* Create slider: */
		    slider = document.createElement("DIV");
		    slider.setAttribute("class", "img-comp-slider");
		    var handle=document.createElement('I');
		    handle.setAttribute('class', 'fa-solid fa-left-right slider-handle');
		    slider.appendChild(handle);
		    /* Insert slider */
		    $('#container').parent().prepend(slider);
		    // img.parentElement.insertBefore(slider, img.parentElement);
		    /* Position the slider in the middle: */
		    // console.log($('#container')[0].offsetHeight);
		    $('div.custom_wrapper.custom_wrapper_two.display-none').attr("style","display:flex!important");
		    // alert($('#container')[0].offsetWidth);
		    // slider.style.top = ($('#container')[0].offsetHeight / 2) - (slider.offsetHeight / 2) + "px";
		    slider.style.left = ($('#container')[0].offsetWidth / 2) - (slider.offsetWidth / 2) + "px";
		    $('div.custom_wrapper.custom_wrapper_two.display-none').attr("style","");

		    /* Execute a function when the mouse button is pressed: */
		    slider.addEventListener("mousedown", slideReady);
		    /* And another function when the mouse button is released: */
		    window.addEventListener("mouseup", slideFinish);
		    /* Or touched (for touch screens: */
		    slider.addEventListener("touchstart", slideReady);
		     /* And released (for touch screens: */
		    window.addEventListener("touchend", slideFinish);
		  }
			initComparisons();
		})