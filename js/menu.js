jQuery(document).ready(function(){
		/* for top navigation */
		jQuery(" #menu ul ").css({display: "none"}); // Opera Fix
		jQuery(" #menu li").hover(function(){
		jQuery(this).find('ul:first').css({visibility: "visible",display: "none"}).slideDown("fast");
		},function(){
		jQuery(this).find('ul:first').css({visibility: "hidden"});
		});
		
		/* for middle navigation */
		jQuery(" #menu2 ul ").css({display: "none"}); // Opera Fix
		jQuery(" #menu2 li").hover(function(){
		jQuery(this).find('ul:first').css({visibility: "visible",display: "none"}).slideDown("fast");
		},function(){
		jQuery(this).find('ul:first').css({visibility: "hidden"});
		});
		
		/* for middle navigation */
		jQuery(" #menu3 ul ").css({display: "none"}); // Opera Fix
		jQuery(" #menu3 li").hover(function(){
		jQuery(this).find('ul:first').css({visibility: "visible",display: "none"}).slideDown("fast");
		},function(){
		jQuery(this).find('ul:first').css({visibility: "hidden"});
		});
});		 
	
