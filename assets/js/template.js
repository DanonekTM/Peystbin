(function($) 
{
	'use strict';
	$(function() 
	{
		function addActiveClass(element) 
		{
			// for root
			if (current === "")
			{
				document.getElementById("index").classList.add("active");
			} 
			else 
			{
				//for other url
				if (element.attr('href').indexOf(current) !== -1) 
				{
					element.parents('.nav-item').last().addClass('active');
					if (element.parents('.sub-menu').length) 
					{
						element.closest('.collapse').addClass('show');
						element.addClass('active');
					}
					if (element.parents('.submenu-item').length) 
					{
						element.addClass('active');
					}
				}	
			}
		}

		var current = location.pathname.split("/").slice(-1)[0].replace(/^\/|\/$/g, '');
		var currentWindow = window.location.href;

		$('.horizontal-menu .nav li a').each(function() 
		{
			var $this = $(this);
			if (currentWindow)
			{
				addActiveClass($this);
			}
		});

		//checkbox and radios
		$(".form-check label,.form-radio label").append('<i class="input-helper"></i>');

		//Horizontal menu in mobile
		$('[data-toggle="horizontal-menu-toggle"]').on("click", function() 
		{
			$(".horizontal-menu .bottom-navbar").toggleClass("header-toggled");
		});
		// Horizontal menu navigation in mobile menu on click
		var navItemClicked = $('.horizontal-menu .page-navigation >.nav-item');
		navItemClicked.on("click", function(event) 
		{
			if(window.matchMedia('(max-width: 991px)').matches) 
			{
				if(!($(this).hasClass('show-submenu'))) 
				{
					navItemClicked.removeClass('show-submenu');
				}
				$(this).toggleClass('show-submenu');
			}				
		});

		$(window).scroll(function() 
		{
			if(window.matchMedia('(min-width: 992px)').matches) 
			{
				var header = $('.horizontal-menu');
				if ($(window).scrollTop() >= 70) 
				{
					$(header).addClass('fixed-on-scroll');
				} 
				else 
				{
					$(header).removeClass('fixed-on-scroll');
				}
			}
		});
	});
})(jQuery);