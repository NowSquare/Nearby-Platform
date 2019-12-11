var ladda_button, ajax_form_opts;

/*
 * Particles.js
 * http://vincentgarreau.com/particles.js/
 */

if (document.getElementById('particles-js-hexagon') !== null)
{
	particlesJS.load('particles-js-hexagon', '/assets/js/particles/particles-hexagon.json');
}

if (document.getElementById('particles-js-bubble') !== null)
{
	particlesJS.load('particles-js-bubble', '/assets/js/particles/particles-bubble.json');
}

if (document.getElementById('particles-js-connect') !== null)
{
	particlesJS.load('particles-js-connect', '/assets/js/particles/particles-connect.json');
}

if (document.getElementById('particles-js-diamonds') !== null)
{
	particlesJS.load('particles-js-diamonds', '/assets/js/particles/particles-diamonds.json');
}

if (document.getElementById('particles-js-nasa') !== null)
{
	particlesJS.load('particles-js-nasa', '/assets/js/particles/particles-nasa.json');
}

if (document.getElementById('particles-js-snow') !== null)
{
	particlesJS.load('particles-js-snow', '/assets/js/particles/particles-snow.json');
}

/*
 * Code Prettify
 * https://github.com/google/code-prettify
 */

window.addEventListener('load', function (event) { prettyPrint() }, false);

function windowPopup(url, width, height) {
  // Calculate the position of the popup so
  // it’s centered on the screen.
  var left = (window.screen.width / 2) - (width / 2),
      top = (window.screen.height / 2) - (height / 2);

  left = (window.screen.availLeft + (window.screen.availWidth / 2)) - (width / 2);
  top = (window.screen.availTop + (window.screen.availHeight / 2)) - (height / 2);

  window.open(
    url,
    "",
    "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,width=" + width + ",height=" + height + ",top=" + top + ",left=" + left
  );
}

$(function($) {/*
  // Dropdown hover
  $('body').on('mouseenter mouseleave','.dropdown',function(e){
    var _d = $(e.target).closest('.dropdown');
    _d.addClass('show');
    var _dm = $(e.target).next('.dropdown-menu');
    _dm.addClass('show');
    setTimeout(function(){
      var hovered = _d.parent().find(".dropdown:hover").length;
      _d[hovered ? 'addClass':'removeClass']('show');
      _dm[hovered ? 'addClass':'removeClass']('show');
      $('[data-toggle="dropdown"]', _dm).attr('aria-expanded', hovered);
    }, 150);
  });

  $('body').on('mouseenter mouseleave','.dropdown',function(e){
    var _d=$(e.target).closest('.dropdown');_d.addClass('show');
    setTimeout(function(){
      _d[_d.is(':hover')?'addClass':'removeClass']('show');
      $('[data-toggle="dropdown"]', _d).attr('aria-expanded',_d.is(':hover'));
    }, 150);
  });
*/
  $('form:not(.ajax)').submit(function() {
    $(this).find("button[type='submit']").prop('disabled',true);
  });

  $('.lazyload img').lazyload({});


  if (jQuery().select2) {
    $('.select2-basic').select2({
      allowClear: true,
      theme: 'bootstrap'
    });

    $('.select2-required').select2({
      allowClear: false,
      placeholder: '',
      theme: 'bootstrap'
    });

    $('.select2-required-no-search').select2({
      allowClear: false,
      minimumResultsForSearch: -1,
      theme: 'bootstrap'
    });

    $('.select2-tags').select2({
      tags: [],
      tokenSeparators: [',', ';', ' '],
      theme: 'bootstrap'
    });

    $('.select2-multiple').select2({
      theme: 'bootstrap'
    });

    $('.select2-basic-xl').select2({
      allowClear: true,
      theme: 'bootstrap-xl'
    });

    $('.select2-required-xl').select2({
      allowClear: false,
      placeholder: '',
      theme: 'bootstrap-xl'
    });

    $('.select2-required-no-search-xl').select2({
      allowClear: false,
      minimumResultsForSearch: -1,
      theme: 'bootstrap-xl'
    });

    $('.select2-tags-xl').select2({
      tags: [],
      tokenSeparators: [',', ';', ' '],
      theme: 'bootstrap-xl'
    });

    $('.select2-multiple-xl').select2({
      theme: 'bootstrap-xl'
    });
  }

  /*
   * Flat Surface Shader
   * http://matthew.wagerfield.com/flat-surface-shader/
   */

	if ($('.polygon-bg').length) {
    $('.polygon-bg').each(function() {

      var color_bg = ($(this).is('[data-color-bg]')) ? $(this).attr('data-color-bg') : '2663d2';
      var color_light = ($(this).is('[data-color-light]')) ? $(this).attr('data-color-light') : '4c89f8';

      var container = $(this)[0];
      var renderer = new FSS.CanvasRenderer();
      var scene = new FSS.Scene();
      var light = new FSS.Light(color_bg, color_light);
      var geometry = new FSS.Plane(3000, 1000, 90, 33);
      var material = new FSS.Material('FFFFFF', 'FFFFFF');
      var mesh = new FSS.Mesh(geometry, material);
      var now, start = Date.now();

      function initialiseFss() {
        scene.add(mesh);
        scene.add(light);
        container.appendChild(renderer.element);
        window.addEventListener('resize', resizeFss);
      }

      function resizeFss() {
        renderer.setSize(container.offsetWidth, container.offsetHeight);
      }

      function animateFss() {
        now = Date.now() - start;
        light.setPosition(300*Math.sin(now*0.001), 200*Math.cos(now*0.0005), 60);
        renderer.render(scene);
        requestAnimationFrame(animateFss);
      }

      initialiseFss();
      resizeFss();
      animateFss();
    });
	}

  /*
   * jQuery.scrollTo
   * https://github.com/flesler/jquery.scrollTo
   */

	var onMobile = false;
  var onIOS = false;

	if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) { onMobile = true; }
	if (/iPhone|iPad|iPod/i.test(navigator.userAgent)) { onIOS = true; }

	if (onMobile === true) {
		$('a.scrollto').click(function (event) {
		  $('html, body').scrollTo(this.hash, 0, {offset: {top: -1200}, animation:  {easing: 'easeInOutCubic', duration: 0}});
		  event.preventDefault();
	  });
	} else {
		$('a.scrollto').click(function (event) {
		  $('html, body').scrollTo(this.hash, 1000, {offset: {top: -73}, animation:  {easing: 'easeInOutCubic', duration: 1500}});
			event.preventDefault();
	  });
	}

  var hash = window.location.hash;

  if(hash) {
    setTimeout(function() {
      $('html, body').scrollTo(hash + '_', 1000, {offset: {top: -73}, animation:  {easing: 'easeInOutCubic', duration: 1500}});
    }, 300);
  }

  $(window).on('hashchange', function(e) {
    var hash = window.location.hash;
  
    if(hash) {
      $('html, body').scrollTo(hash + '_', 1000, {offset: {top: -73}, animation:  {easing: 'easeInOutCubic', duration: 1500}});
    }
  });

	/*
	 * Bootstrap tooltips
	 */

  $('[data-toggle*="tooltip"]').tooltip({
    trigger : 'hover'
  });
  $('[data-toggle*="popover"]').popover({
    html: true
  });

	/*
	 * Ajax forms
	 */

    ajax_form_opts = {
        dataType: 'json',
        beforeSerialize: beforeSerialize,
        success: formResponse,
        error: formResponse
    };

  $('form.ajax').each(function() {
    $(this).validator().on('submit', function (e) {
      if (! e.isDefaultPrevented()) {
        $(this).ajaxSubmit(ajax_form_opts);
          e.preventDefault();
      }
    });
  });

  /*
   * Ekko Lightbox
   * http://ashleydw.github.io/lightbox/
   */

  $(document).delegate('*[data-toggle="lightbox"]', 'click', function(event) {
    var self = $(this);
    $(this).attr('href', $(this).attr('src'));

    $('*[data-gallery]').each(function() {
      $(this).attr('href', $(this).attr('src'));
    });

    event.preventDefault();
    $(self).ekkoLightbox();
  });

  /*
   * Owl Carousel
   * https://owlcarousel2.github.io/OwlCarousel2/
   */

	if ($('.owl-carousel').length) {
    /*
    $('.owl-carousel').owlCarousel({
      loop: true,
      dots: true,
      nav: false,
      lazyLoad:true,
      responsive:{
        0:{
          items:4
        },
        600:{
          items:8
        },
        1000:{
          items:12
        }
      }
    });*/
  }

  /*
   * Switch navbar class when scrolling
   */

  //if ($('body#home').length) {
    $(window).scroll(checkLogo);
    checkLogo();
  //}

  function checkLogo() {
    var el = $('.navbar');
    var scroll = $(window).scrollTop();

    if (scroll >= 80) {
      if ($('body#home').length) {
        el.removeClass('transparent').addClass('navbar-shadow');
      } else {
        el.removeClass('navbar-shadow-light').addClass('navbar-shadow');
      }
      //$('.navbar-logo').hide();
      //$('.navbar-logo-scroll').show();
    } else {
      if ($('body#home').length) {
        el.removeClass('navbar-shadow').addClass('transparent');
      } else {
        el.removeClass('navbar-shadow').addClass('navbar-shadow-light');
      }
      //$('.navbar-logo-scroll').hide();
      //$('.navbar-logo').show();
    }
  }

  $('#navbarNavDropdown').on('show.bs.collapse,shown.bs.collapse', function () {
    $('.navbar').addClass('navbar-opened');
  });

  $('#navbarNavDropdown').on('hide.bs.collapse,hidden.bs.collapse', function () {
    $('.navbar').removeClass('navbar-opened');
  });
});

function beforeSerialize($jqForm, options) {
  
  ladda_button = Ladda.create( $jqForm.find('[type=submit]')[0] );
	//ladda_button = $jqForm.find('[type=submit]').ladda();

    // Loading state
	ladda_button.start();
}

function formResponse(responseText, statusText, xhr, $jqForm) {
  if (typeof responseText.responseJSON !== 'undefined' && typeof responseText.responseJSON.errors !== 'undefined') {
    $.each(responseText.responseJSON.errors, function(key, val) {
      $('[name=' + key + ']').after('<div class="text-danger font-weight-bold">' + val + '</div>');
    });

    // Loading state
    ladda_button.stop();
  } else if(typeof responseText.msg === 'undefined') {
    if(typeof responseText.redir !== 'undefined') {
      if (responseText.redir == 'reload') {
        window.top.location.reload();
      } else {
        window.top.location = responseText.redir;
      }
    } else {
      //window.top.location.reload();
    }
  } else {
    swal({
      type: responseText.type,
      title: responseText.msg
    }).then(function () {

      // Reset form
      var reset_form = (typeof responseText.reset !== 'undefined') ? responseText.reset : true;

      if (reset_form) {
        $jqForm[0].reset();
      } else {
        $('[type=password]').val('');
      }

      // Loading state
      ladda_button.stop();
    }, function (dismiss) {
      // Reset form
      var reset_form = (typeof responseText.reset !== 'undefined') ? responseText.reset : true;

      if (reset_form) {
        $jqForm[0].reset();
      } else {
        $('[type=password]').val('');
      }

      // Loading state
      ladda_button.stop();

    });
  }
}

/*
 * BlockUI
 */

function blockUI(el) {
  if (typeof el === 'undefined') {
    $.blockUI({
      message: '<div class="loader"></div>',
      fadeIn: 0,
      fadeOut: 100,
      baseZ: 21000,
      overlayCSS: {
        backgroundColor: '#000'
      },
      css: {
        border: 'none',
        padding: '0',
        backgroundColor: 'transparant',
        opacity: 1,
        color: '#fff'
      }
    });
  } else {
    $(el).block({
      message: '<div class="loader loader-xs"></div>',
      fadeIn: 0,
      fadeOut: 100,
      baseZ: 21000,
      overlayCSS: {
        backgroundColor: '#000',
        opacity: 0.1,
      },
      css: {
        border: 'none',
        padding: '0',
        backgroundColor: 'transparant',
        opacity: 1,
        color: '#fff'
      }
    });
  }
}

/*
 * unblockUI
 */

function unblockUI(el) {
  if (typeof el === 'undefined') {
    $.unblockUI();
  } else {
    $(el).unblock();
  }
}

/*
 * https://stackoverflow.com/questions/2068272/getting-a-jquery-selector-for-an-element/2068381#2068381
 */

function getElementPath($el) {
  var path, node = $el;
  while (node.length) {
    var realNode = node[0], name = realNode.localName;
    if (!name) break;
    name = name.toLowerCase();

    var parent = node.parent();

    var sameTagSiblings = parent.children(name);
    if (sameTagSiblings.length > 1) { 
      allSiblings = parent.children();
      var index = allSiblings.index(realNode) + 1;
      if (index > 1) {
        name += ':nth-child(' + index + ')';
      }
    }

    path = name + (path ? '>' + path : '');
    node = parent;
  }

  return path;
}

/*
 * jQuery.liveFilter
 *
 * Copyright (c) 2009 Mike Merritt
 *
 * Forked by Lim Chee Aun (cheeaun.com)
 * 
 */
 
(function($){
	$.fn.liveFilter = function(inputEl, filterEl, options){
		var defaults = {
			filterChildSelector: null,
			filter: function(el, val){
				return $(el).text().toUpperCase().indexOf(val.toUpperCase()) >= 0;
			},
			before: function(){},
			after: function(){}
		};
		var options = $.extend(defaults, options);
		
		var el = $(this).find(filterEl);
		if (options.filterChildSelector) el = el.find(options.filterChildSelector);

		var filter = options.filter;
		$(inputEl).keyup(function(){
			var val = $(this).val();
			var contains = el.filter(function(){
				return filter(this, val);
			});
			var containsNot = el.not(contains);
			if (options.filterChildSelector){
				contains = contains.parents(filterEl);
				containsNot = containsNot.parents(filterEl).hide();
			}
			
			options.before.call(this, contains, containsNot);
			
			contains.show();
			containsNot.hide();
			
			if (val === '') {
				contains.show();
				containsNot.show();
			}
			
			options.after.call(this, contains, containsNot);
		});
	}
})(jQuery);

function copyInput(id) {
  var copyText = document.getElementById(id);
  copyText.select();
  document.execCommand("Copy");
}