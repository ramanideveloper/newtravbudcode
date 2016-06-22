/* set floating chat */
	$(".chat-button,.close-chat").click(function(){
		
		$(this).parents(".float-chat").toggleClass("chat-open");
	});
/* end set floating chat */

/* search result */
	$(".search-btn").click(function (e) {
		
		var isSearchResult=$(".search-result").css("display");
		if(isSearchResult=="none"){
			manageSearchSection(this);
			
			var isSearchText=$(".search-input").val();
			if(isSearchText!="")
				setTimeout(function(){$(".search-result").slideDown();},400);			
		}
		else{
			//$(".search-input").val("");
			$(".search-result").hide();
			manageSearchSection(this);
			//setTimeout(function(){manageSearchSection(this);},1000);
		}
		
		
	});
	$(".search-input").keyup(function() {
	  var textValue = $(this).val();
	   var id = $(this).attr('data-id');
	   //alert(textValue);
				var textValue = $(this).val();
                if (textValue != '') {
                    data = 'key=' + textValue;
                    $.ajax({
                        url: websitelink+"r=site/search", //Server script to process data
                        type: 'GET',
                        data: data,
                        success: function (data) {
                            $("#"+id).html(data).show();
							//$(".search-result").slideDown();
                        }
                    });
                } else {
                    closeSearchDrawer(id);
					closeSearchSection(e);
                }
	 // var isSearchResult=$(".search-result").css("display");
	  
	  if(isSearchResult=="none"){
		$(".search-result").slideDown();
	  }
	});
/* end search result */

/* side float icons */
	$(".btnbox").click(function(){
		//var isOpen=$(this).hasClass("box-open");	
		
		var cls=$(this).attr("class");
		cls=cls.replace("box-open","").trim();		
		closeAllSideDrawer(cls);
		$(this).toggleClass("box-open");
		
	});
/* end side float icons */

/* new post options */
	$("ul.echeck-list > li").click(function(){
		var isSelected=$(this).hasClass("selected");
		if(isSelected)
			$(this).removeClass("selected");
		else
			$(this).addClass("selected");
	});
/* end new post options */

/* new post clicked */
	$(".new-post").click(function(){
		$(this).addClass("active");
	});
/* end new post clicked */

/* open post location */
	$(".add-location").click(function(){
		$(this).parents(".npost-content").find(".post-location").toggle("slow");
	});
/* end open post location */

/* open post photos */
	$(".add-photos").click(function(){	
		$(this).parents(".npost-content").find(".post-photos").slideDown();
	});
/* end open post photos */

/* open post tag */
	$(".add-tag").click(function(){	
		$(this).parents(".npost-content").find(".post-tag").toggle("slow");
	});
/* end open post tag */

/* open post title */
	$(".add-title").click(function(e){
		openTitle(this);
	});
/* end open post title */

/* set security option dropdown */
	$(".custom-drop .dropdown-menu li a").click(function(){
		
	  $(this).parents(".dropdown").find('.dropdown-toggle').html($(this).html()+ " <span class='caret'></span>");
	  $(this).parents(".dropdown").find('.dropdown-toggle').val($(this).html());
	});
/* end set security option dropdown */

/* text input animation */
	$(".sliding-middle-out").click(function(){			
		titleUnderline(this);			
	});
/* end text input animation */

/* side icons visibility */
	$(".scrollup-float").click(function(){ 
		$('html,body').animate({ scrollTop: 0 }, 'slow');
		return false; 
	});
	$(document).ready(function(){
		$(".scrollup-float").hide();
		$(".newpost-float").hide();
	});
	$(window).scroll(function() {
		
		var fromTop=$(window).scrollTop();
		var winh=$(window).height();
		
		if(fromTop > 100){
			$(".scrollup-float").show();		
			$(".theme-colorbox").hide();
			$(".floating .chat-button").hide();
		}
		else{
			$(".scrollup-float").hide();		
			$(".theme-colorbox").show();
			$(".floating .chat-button").show();
		}
		
		if(fromTop > 150){
			$(".newpost-float").show();		
		}
		else{
			$(".newpost-float").hide();		
		}
		
		/* fixed header */
		if(fromTop > 0)
			$(".header-section").addClass("fixed-header");
		else
			$(".header-section").removeClass("fixed-header");
		/* end fixed header */
		
	});
/* end side icons visibility */

/* load more comments */
	$(".view-morec").click(function(){
		$(this).parents(".post-holder").find(".pcomment-earlier").slideDown();
		$(this).fadeOut();
	});
/* end load more comments */

/* document click */

	$(document).click(function(event) { 

		if(!$(event.target).closest('.btnbox').length) {
			if($('.btnbox').is(":visible")) {
				$('.btnbox').removeClass('box-open');
			}
		}
	})

/* end document click */

/* body click */
	$('.dropdown.resist a.dropdown-toggle').on('click', function (event) {
		$(this).parent().toggleClass('open');
	});
	$('body').on('click', function (e) {
		/* close resist dropdrown */
		var rtrgt = $('.dropdown.resist');
		var rotrgt = $('.dropdown.resist.open');
		if ((!rtrgt.is(e.target) & rtrgt.has(e.target).length === 0) ||
			(!rotrgt.is(e.target) & rotrgt.has(e.target).length === 0)
		){
			$('.dropdown.resist').removeClass('open');
		}
		/* end close resist dropdrown */
		if (!$('.sliding-middle-out').is(e.target) & $('.sliding-middle-out').has(e.target).length === 0)
			clearUnderline();
	});
/* end body click */

/* document ready */
	$(document).ready(function(){
		/* manage left menu */
			setLeftMenu();
		/* end manage left menu */
		
		/* set floating chat */
			setFloatChat();		
		/* end set floating chat */

		/* theme box */
			// togle body theme
			$( ".colorbox-coloricon a" ).bind( "click", function() {
				//$('body').removeClass("loaded");
				var clickedClass = $(this).attr('body-color'); // or var clickedBtnID = this.id
				$("body").attr("class",clickedClass);
				setCookie('bodyClass',clickedClass);
				//$('body').addClass("loaded");
			});
			checkCookie();
		/* end theme box */
		
		/* fix centering image */
			setTimeout(function(){fixImageUI();},400);
		/* end fix centering image */
		
		/* prettyphoto */
			$("area[rel^='prettyPhoto']").prettyPhoto();
			$(".gallery a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'normal',theme:'light_square',social_tools: ' '});
		/* end prettyphoto */

		/* popup */

			$('.popup-modal').magnificPopup({
				type: 'inline',
				preloader: false,
				focus: '#username',
				modal: true
			});
			$(document).on('click', '.close-popup', function (e) {
				
				var modalDiv = $(this).parents(".popup-area");
				var modalId=modalDiv.attr("id");
				
				if(modalId=="newpost-popup"){
					e.preventDefault();
					$.magnificPopup.close();
				}
				else if(modalId=="editpost-popup"){
					openBootstrapPopup("editpost");
					//$("#frm_edit_post")[0].reset();
				}
				else if(modalId=="sharepost-popup"){
					openBootstrapPopup("sharepost");
				}
				else{
					e.preventDefault();
					$.magnificPopup.close();
				}
				
			});
		/* end popup */
	});
/* end document ready */

/* window resize */
	var resizeId;
	$(window).resize(function() {		
		clearTimeout(resizeId);
		resizeId = setTimeout(doneResizing, 300);
	});
	function doneResizing(){
		/* manage left menu */
		setLeftMenu();		
		/* end manage left menu */
		/* set floating chat */
		setFloatChat();		
		/* end set floating chat */
	}
/* end window resize */

/* add photos to new post */
	// Span
	var span = document.getElementsByClassName('upload-path');
	// Button
	var uploader = document.getElementsByName('upload');
	// On change
	for( item in uploader ) {
	  // Detect changes
	  uploader[item].onchange = function() {
		// Echo filename in span
		//span[0].innerHTML = this.files[0].name;
	  }
	}	
	$(document.body).on("change", ".custom-upload", function () {
		var cls = $(this).data("class");
        managePostButton(postbtn_hide);
		changePhotoFileInput(cls, this, true);        
	});
		
/* end add photos to new post */

	$(document).ready(function() {
		/* remove added photo */
		$("body").on("click", ".removePhotoFile", removePhotoFile);
		/* end remove added photo */
		
		/* remove video link */
		$("body").on("click", ".remove-vlink", removeVideoLink);
		/* end remove video link */
		
		/* edit post popup pre-setup */
		$("body").on("click", ".editpost-link", preEditPostSetup);
		/* end edit post popup pre-setup */
		
		/* delete post popup pre-setup */
		$("body").on("click", ".deletepost-link", deletePost);
		/* end delete post popup pre-setup */
		
		/* save post popup pre-setup */
		$("body").on("click", ".savepost-link", savePost);
		/* end save post popup pre-setup */

		/* readmore click */
		$("body").on("click", ".readmore", openReadMore);
		/* end readmore click */
		
		/* show fullpost click */
		$("body").on("click", ".show-fullpost", showFullPost);		
		/* end show fullpost click */

		/* open reply comment textarea */
		$("body").on("click", ".reply-comment", openReplyComment);
		/* end open reply comment textarea */
		
		/* open edit comment textarea */
		$("body").on("click", ".edit-comment", openEditComment);
		/* end open edit comment textarea */
		
		/* open delete comment */
		$("body").on("click", ".delete-comment", deleteEditComment);
		/* end delete edit comment */
		
		/* enter on edit comment textarea */
		$("body").on("keyup", "textarea.editcomment-tt", saveEditComment);
		/* end enter on edit comment textarea */
		
		/* enter on edit comment textarea */
		$("body").on("click", ".editcomment-cancel", cancelEditComment);
		/* enter on edit comment textarea */
		
		/* like tooltip creator */
		$(document).ready(function(){initLikeTooltip();});
		/* end like tooltip creator */		
		
		/* like clicked */
		$("body").on("click", ".pa-like", likeClicked);
		/* end like clicked */		
		
		/* profiletip tooltip creator */
		$(document).ready(function(){initProfileTooltip();});
		/* end profiletip tooltip creator */		
		
		/* page logo loader */
		$(document).ready(function(){
			setTimeout(function(){$("#loader-wrapper").fadeOut(400);},5000);
			setTimeout(function(){$('body').addClass("loaded");},1000);
			setTimeout(function(){$('.page-wrapper').addClass("showpage");},1400);
		});
		/* end page logo loader */
		
		/* select-2 */
		$(".select2").select2();
		/* end select-2 */
		
	});
