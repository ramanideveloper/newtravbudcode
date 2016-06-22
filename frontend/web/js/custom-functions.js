var postBtnEle = $(".postbtn");	
var postbtn_hide = "HIDE";
var postbtn_show = "SHOW";

var websitelink = "/travbudcode/frontend/web/index.php?";
//var websitelink = "/frontend/web/index.php?"; //For live

var lastModified = [];
var newct=0;

var storedFiles = [];

/* FUN set mobile menu */
	function setMobileMenu(){
		var isMobileMenu=$(".sidemenu-holder").hasClass("m-hide");
		if(isMobileMenu){
			var isMenuOpen=$(".sidemenu-holder").hasClass("m-open");
			if(!isMenuOpen)
				$(".sidemenu-holder").addClass("m-open");
			else
				$(".sidemenu-holder").removeClass("m-open");
		}
	}
	$(".mobile-menu a").click(function(){
		setMobileMenu();
	});
/* FUN end set mobile menu */

/* FUN set floating chat */
	function setFloatChat(){
		
		var w_width=$(window).width();
		
		if(w_width>=1170){
			$(".float-chat").removeClass("floating");
		}
		else{		
			$(".float-chat").addClass("floating");		
		}
	}
/* FUN end set floating chat */

/* FUN set left menu */
	function setLeftMenu(){
		
		var w_width=$(window).width();
		
		if(w_width>=1400){
			$(".sidemenu-holder").removeClass("m-hide");
			$(".sidemenu-holder").removeClass("m-open");
			$(".with-lmenu").removeClass("m-hide");
			$(".sidemenu-holder").addClass("open");
			$(".with-lmenu").addClass("open");
		}
		else if(w_width>=768 && w_width<1400){	
			$(".sidemenu-holder").removeClass("m-hide");
			$(".sidemenu-holder").removeClass("m-open");
			$(".with-lmenu").removeClass("m-hide");
			$(".sidemenu-holder").removeClass("open");
			$(".with-lmenu").removeClass("open");
		}	
		else{		
			$(".sidemenu-holder").removeClass("open");
			$(".with-lmenu").removeClass("open");
			$(".sidemenu-holder").addClass("m-hide");
			$(".with-lmenu").addClass("m-hide");
		}
		//setMobileMenu();
	}
/* FUN end set left menu */

/* FUN clear input text animation line */
	function titleUnderline(e){		
		clearUnderline();
		$(e).toggleClass("focused");
		$(e).children("input[type='text']").focus();
	}
	function clearUnderline(){
		$(".sliding-middle-out").each(function(){
			if($(this).hasClass("focused")) $(this).removeClass("focused");
		});
	}
/* FUN end clear input text animation line */

/* FUN open post title */
	function openTitle(obj){
		
		var getParent=$(obj).parents(".new-post");			
		getParent.find('.npost-title').toggle("slow");
		
		var slideOut=getParent.find(".sliding-middle-out");
				
		setTimeout(function(){titleUnderline(slideOut);},800);
		
	}
/* FUN end open post title */

/* FUN close all floating drawer */
	function closeAllSideDrawer(which){
		$(".btnbox").each(function(){
			
			//var isWhich=$(this).hasClass(which);
			
			var cls=$(this).attr("class");
			cls=cls.replace("box-open","").trim();		
			
			if(cls!=which){
				$(this).removeClass("box-open");
			}
		});	
	}
/* FUN end close all floating drawer */

/* FUN manage search section */
	function manageSearchSection(obj){
		//alert("here");
		var w_width=$(window).width();
		if(w_width>1000){
			var super_parent=$(obj).parents(".search-section");
		  
			var hasClass = super_parent.hasClass("opened");
						
			if (!hasClass) {
				$(".search-section").addClass("opened");
				$(".search-section").css("width", "100%");			
				$(".search-input",".search-input-span").css("width", "100%");		
				//$(".search-input").css("background", "#fff");		
				$(".search-input").css("color", "#666");		
				
			} else {
				
				$(".search-input-span",".search-input").css("width", "0%");
				//$(".search-input").css("background", "none");		
				$(".search-input").css("color", "rgba(0,0,0,0)");		
				$(".search-section").css("width", "0%");					
				$(".search-section").removeClass("opened");
			}
		}
		else{
			var isSearchShown=$(".abs-search").css("display");
			if(isSearchShown=="none")
				$(".abs-search").slideDown();
			else
				$(".abs-search").slideUp();
		}
	}
/* FUN end manage search section */

/* FUN fix image centering */
	function resizeToHeight(image_width,image_height, container_width) {
		
		var newWidth = container_width;
		var width = image_width;
		var proportion = newWidth/width;
		var newHeight =image_height  * proportion;

		return newHeight;
	}
	function resizeToWidth(image_width,image_height, container_height) {
		
		var newHeight = container_height;
		var height = image_height;
		var proportion = newHeight/height;
		var newWidth =image_width  * proportion;

		return newWidth;
	}
	function fixImageUI(){

		var pimgholder_count=0;	
		var pcount=0;
		
		$(".post-list .post-holder").each(function(){
			pcount++;
			
			//if(pcount > 3 && pcount<5){
				if($(this).find(".post-img").length > 0 ){				
					pimgholder_count++;
					
					//alert(pimgholder_count);
					$(this).find(".pimg-holder").each(function(e, v){
						
						//alert($(this).width()+ " - "+$(this).height());
						var cont_width=$(v).width();
						var cont_height=$(v).height();
						
						//alert($(this).find("img").width()+" - "+$(this).find("img").height());
						var img_width=$(v).find("img").width();
						var img_height=$(v).find("img").height();
						
						if($(v).hasClass("himg-box")){
							if(img_width < cont_width){
								$(v).addClass("imgfix");
							}
						} 
						
						//alert($(v).find("img").width()+" - "+$(v).width());
						
						if(img_width > cont_width){
							
							//console.log(img_width+" - "+cont_width);
							var iwidth = resizeToWidth(img_width,img_height,cont_height);
							var lfix= ( iwidth - cont_width ) / 2;
							$(v).find("img").css("margin-left","-"+lfix+"px");							
						}
						
						
						if(img_height > cont_height || $(v).hasClass("imgfix")){
							var iheight = resizeToHeight(img_width,img_height,cont_width);
							var tfix= ( iheight - cont_height ) / 2;
							$(v).find("img").css("margin-top","-"+tfix+"px");							
						}
						
					});
				
				}
				
			//}

		});
	}

/* FUN end fix image centering */

/* FUN post button manage */
function managePostButton(action){
	if(action == "SHOW"){
		$(postBtnEle).attr("disabled",false);
	}else{
		$(postBtnEle).attr("disabled",true);
	}
}
/* FUN end post button manage */

/* FUN post photos input file */
	function setCurrClass(name){curr_cls=name;}	 
	function getCurrClass(){return curr_cls;}
	
	function removePhotoFile(e) {
	    var code = $(this).data("code");
		
	    for(var i=0;i<storedFiles.length;i++) {
	    	str = storedFiles[i].lastModified;
	        if(str == code) {
	            storedFiles.splice(i,1);
	            break;
	        }
	    }
	    $(this).parent().remove();
		$(this).closest('div').remove();

		//console.log(storedFiles);
	}
	
	function changePhotoFileInput(cls, obj, t){

		//console.log(cls);
		var valde = $(".imgfile-count").val();
		$(".imgfile-count").val(parseInt(valde,10)+1);
	   
	   //Get count of selected files
		var countFiles = $(obj)[0].files.length;	   
		if(t == true) {
			var formdata;
			formdata = new FormData($('form')[1]);
			var imgcounter=$(".counter").val(countFiles);
			formdata.append('counter', imgcounter);

		}
		
		var file, img; 
		var _URL = window.URL || window.webkitURL;			  
		var imgPath = $(obj)[0].value;
		var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
		var image_holder = $(cls);
		//image_holder.empty();

		if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg" || extn == "tif" || extn == "webp") {
			if (typeof (FileReader) != "undefined") {
				
				var imgCls=[];
				
				for (var i = 0; i < countFiles; i++) {
					file = obj.files[i];
					  
					  lastModified.push(file.lastModified);

					  storedFiles.push(file);
						var reader = new FileReader();
						reader.onload = function (e) {
							
							var img = new Image();							
							img.addEventListener("load", function () {
							  var imageInfo = file.name    +' '+
											  img.width  +'×'+
											  img.height +' '+
											  file.type    +' '+
											  Math.round(file.size/1024) +'KB';
								alert(imageInfo);
							  //$("#demo").appendChild( this );
							  //elPreview.insertAdjacentHTML("beforeend", imageInfo +'<br>');
							});
							
							img.src = window.URL.createObjectURL(file); //alert(getCurrClass());     
							
						}
					
					reader.readAsDataURL($(obj)[0].files[i]);
				}				
				
				//alert(imgCls.length);
				
				
				 //loop for each file selected for uploaded.
				 for (var i = 0; i < countFiles; i++) {
					  file = obj.files[i];
					  lastModified.push(file.lastModified);

					  storedFiles.push(file);
						var reader = new FileReader();
						reader.onload = function (e) {
							var img = new Image();
							
							img.onload = function() {
								var curr_cls='';
								
								if(this.width>this.height){
									setCurrClass("himg");
									imgCls.push("himg");
								} else if(this.width<this.height) {
									setCurrClass("vimg");
									imgCls.push("himg");
								} else {
									setCurrClass("himg");
									imgCls.push("himg");
								}

								$("<img />", {
								"src": e.target.result,
								"class": "thumb-image "+imgCls[i],
								})
								.add("<a href='javascript:void(0)' class='removePhotoFile' data-code='"+lastModified[newct]+"'><i class='fa fa-close' ></i></a>") 
								.wrapAll("<div class='img-box'></div>")
								.parents()
								.prependTo(image_holder);
								managePostButton(postbtn_show);
								newct++; 
							};
							
							img.onerror = function() { //alert( "not a valid file: " + file.type);
							};
							img.src = _URL.createObjectURL(file); //alert(getCurrClass());     
						}
					
					$(cls.replace(".img-row","").trim()).show();                              
					image_holder.show();
					reader.readAsDataURL($(obj)[0].files[i]);
				}	                 
			} else {
				//alert("This browser does not support FileReader.");
				BootstrapDialog.show({
				size: BootstrapDialog.SIZE_SMALL,
				title: 'FileReader Error',
				message: 'This browser does not support FileReader.'
				});
				//return false;
			}
			setTimeout(function(){
				if(valde == 0){
					//console.log('log' + cls);
					$(cls).append( "<div class='img-box'><div class='custom-file addimg-box'><div class='addimg-icon'><span class='glyphicon glyphicon-plus'></span></div><input type='file' name='upload' class='upload custom-upload' title='Choose a file to upload' required='' data-class='"+cls+"' multiple='true'/></div></div>" );  
					//console.log('log' + cls);
				}
				/*$("#imageFile2").on('change', function (){
				document.getElementById('imgfilecount').value++;
				managePostButton(postbtn_show);
				addmoreimg_filechange(this);
				});*/
			},400);    			
		} else {
			BootstrapDialog.show({
			title: 'Invalid Files',
			message: 'Please upload jpg, gif, png, tif and webp image.'
			});
			$("#imageFile1").val("");
				   //return false;
	   }
	}

/* FUN end post photos input file */

/* FUN remove video link */	
	function removeVideoLink(e){
		$(this).parents(".pvideo-holder").remove();
	}
/* FUN end remove video link */

/* FUN pre-edit post */	
	function hidePostExtras(areaid){		
		$("#"+areaid).find(".post-photos").hide();
		$("#"+areaid).find(".npost-title").hide();
		$("#"+areaid).find(".post-location").hide();
	}
	function preEditPostSetup(e){
		
		// hide all post extras : not input value inside - parameter = ID of super parent
		hidePostExtras("editpost-popup");
	
		var hasImages=$("#editpost-popup").find(".post-photos").find(".img-row").length;
		if(hasImages)
			$("#editpost-popup").find(".post-photos").show();
		
		var hasTitle=$("#editpost-popup").find(".npost-title").find(".title").val().trim();
		if(hasTitle!="")
			$("#editpost-popup").find(".npost-title").show();
		
		var hasLocation=$("#editpost-popup").find(".post-location").find(".plocation").val().trim();
		if(hasLocation!="")
			$("#editpost-popup").find(".post-location").show();
		
		var hasTag=$("#editpost-popup").find(".post-tag").find(".ptag").val().trim();
		if(hasTag!="")
			$("#editpost-popup").find(".post-tag").show();
		
		$("#editpost-popup").find(".desc").removeClass("more");
		$(".readmore").show();
	}
/* FUN end pre-edit post */

/* FUN pre-delete post */		
	function deletePost(e){
		openBootstrapPopup("deletepost",this);
	}
	function preDeletePostSetup(mainObj){
		
		//alert("preDeletePost funtion");				
		$(mainObj).parents(".post-holder").remove();
	}
/* FUN end pre-delete post */

/* FUN save post */		
	function savePost(e){
		openBootstrapPopup("savepost",this);
	}
	function preSavePostSetup(mainObj){
		
		//alert("This post is saved and added to the saved post list!");
		openBootstrapPopup("alert-savedpost",mainObj);
	}
/* FUN end save post */

/* FUN open read more */	
	function openReadMore(e){	
		$(this).parent(".desc").addClass("more");
		$(this).hide();
	}
/* FUN end open read more */	

/* FUN open full post */	
	function showFullPost(e){	
		
		var getText=$(this).html();
		if(getText.indexOf("Show All") >= 0){
			$(this).html("Collapse <span class='glyphicon glyphicon-arrow-up'></span>");
			$(this).parents(".org-post").addClass("open-fullpost");
		}
		else{
			$(this).html("Show All <span class='glyphicon glyphicon-arrow-down'></span>");
			$(this).parents(".org-post").removeClass("open-fullpost");
		}
	}
/* FUN end open full post */	

/* FUN open bootstrap popup */
	function openBootstrapPopup(fromWhere,mainObj=false) {

		if(fromWhere=="logout"){
			
			BootstrapDialog.show({
				size: BootstrapDialog.SIZE_SMALL,
				title: 'Logout',
				cssClass: 'custom-sdialog',
				message: 'Are you sure to Logout ?',
				buttons: [{
						label: 'Stay',
						action: function (dialogItself) {
							dialogItself.close();
						}
					}, {
						label: 'Logout',
						action: function (dialogItself) {
							$.post("<?php echo Yii::$app->urlManager->createUrl(['site/logout']); ?>");
						}
					}]
			});
		}
		if(fromWhere=="editpost" || fromWhere=="sharepost"){
			
			BootstrapDialog.show({
				size: BootstrapDialog.SIZE_SMALL,			
				message: 'Discard Changes ?',
				cssClass: 'custom-sdialog',
				buttons: [{
						label: 'Keep',
						action: function (dialogItself) {
							dialogItself.close();
						}
					}, {
						label: 'Discard',
						action: function (dialogItself) {

							dialogItself.close();
							$.magnificPopup.close();
						}
					}]
			});
		}
		if(fromWhere=="deletepost"){
			
			BootstrapDialog.show({
				size: BootstrapDialog.SIZE_SMALL,			
				message: 'Are you sure, you want to delete this post ?',
				cssClass: 'custom-sdialog',
				buttons: [{
						label: 'Delete',
						action: function (dialogItself) {
							preDeletePostSetup(mainObj);
							dialogItself.close();
						}
					}, {
						label: 'Cancel',
						action: function (dialogItself) {

							dialogItself.close();
							$.magnificPopup.close();
						}
					}]
			});
		}
		if(fromWhere=="savepost"){
			
			BootstrapDialog.show({
				size: BootstrapDialog.SIZE_SMALL,			
				message: 'Are you sure, you want to save this post ?',
				cssClass: 'custom-sdialog',
				buttons: [{
						label: 'Save',
						action: function (dialogItself) {
							preSavePostSetup(mainObj);
							dialogItself.close();
						}
					}, {
						label: 'Cancel',
						action: function (dialogItself) {

							dialogItself.close();
							$.magnificPopup.close();
						}
					}]
			});
		}
		if(fromWhere=="alert-savedpost"){
			BootstrapDialog.show({
				size: BootstrapDialog.SIZE_SMALL,			
				message: 'This post is saved and added to the saved post list!',
				cssClass: 'custom-alert',
				buttons: [{
						label: 'ok',
						action: function (dialogItself) {

							dialogItself.close();
							$.magnificPopup.close();
						}
					}]
			});
		}
    }
/* FUN end open bootstrap popup */

/* FUN open reply comment textarea*/
	function openReplyComment(e){		
		$(this).parents(".pcomment-holder").find(".comment-addreply").find(".addnew-comment").slideDown();
	}
/* FUN end open reply comment textarea*/

/* FUN open edit comment textarea*/
	function openEditComment(e){		
		$(this).parents(".pcomment").find(".normal-mode").slideUp();
		$(this).parents(".pcomment").find(".edit-mode").slideDown();
	}
/* FUN end open edit comment textarea*/

/* FUN delete edit comment*/
	function deleteEditComment(e){		
		var isMainComment=$(this).parents(".pcomment").hasClass("main-comment");
		if(isMainComment)
			$(this).parents(".pcomment-holder").remove();
		else
			$(this).parents(".pcomment").remove();			
	}
/* FUN end delete edit comment */

/* FUN cancel edit comment textarea*/
	function cancelEditComment(e){		
		$(this).parents(".pcomment").find(".normal-mode").slideDown();
		$(this).parents(".pcomment").find(".edit-mode").slideUp();
	}
/* FUN end cancel edit comment textarea*/

/* FUN save edited comment */
	function saveEditComment(e){		
		if((e.keyCode || e.which) == 13) { //Enter keycode		  
			$(this).parents(".pcomment").find(".normal-mode").slideDown();
			$(this).parents(".pcomment").find(".edit-mode").slideUp();		  
		}		
	}
/* FUN end save edited comment */

/* FUN like tooltip creator */
	function initLikeTooltip(){
		
		$(".likeholder").each(function(){
			showLikeTooltip($(this).attr("id"));
		});
	}
	function showLikeTooltip(pid){
		$('#'+pid+' .like-tooltip').tooltipster({
			// we detach the content from the page and give it to Tooltipster
			side: ['top', 'bottom'],theme: ['tooltipster-borderless'],
			content: $('#'+pid+' .tooltip_content').detach()
		});
	}
/* FUN end like tooltip creator */

/* FUN like clicked */
	function likeClicked(e){		
		var likeid=$(this).parents(".likeholder").attr("id");
		alert(likeid);
	}
/* FUN end like clicked */


/* FUN profile tooltip creator */
	function initProfileTooltip(){
		
		$(".profiletipholder").each(function(){
			showProfileTooltip($(this).attr("id"));
		});
	}
	function showProfileTooltip(pid){
		$('#'+pid+' .profile-tooltip').tooltipster({
			// we detach the content from the page and give it to Tooltipster
			side: ['top', 'bottom'],theme: ['tooltipster-profiletip'],
			content: $('#'+pid+' .profiletooltip_content').detach(),
			interactive:true,
			//anchor: 'top-left',
			//plugins: ['follower'],
			//trigger:'click'
		});
	}
/* FUN end profile tooltip creator */