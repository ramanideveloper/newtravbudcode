<input type="hidden" name="login_id" id="login_id" value="<?php echo $session->get('user_id'); ?>">
<input type="hidden" id="url_new" name="url_new" value="<?php echo Yii::$app->urlManager->createUrl(['site/new-post']); ?>" />
<input type="hidden" id="url_upload" name="url_upload" value="<?php echo Yii::$app->urlManager->createUrl(['site/upload']); ?>" />
<input type="hidden" id="url_addfriend" name="url_addfriend" value="<?php echo Yii::$app->urlManager->createUrl(['friend/add-friend']); ?>" />
<input type="hidden" id="url_acceptfriend" name="url_acceptfriend" value="<?php echo Yii::$app->urlManager->createUrl(['friend/accept-friend']); ?>" /> 

   
<?php if (isset($count)) { ?>
    <input type="hidden" id="hiddenCount" value="<?php echo $count; ?>">
<?php } else {
    $count =
            0; ?>
    <input type="hidden" id="hiddenCount" value="<?php echo $count; ?>">
<?php } ?>
<!-- footer section -->

<div class="np-fotter clearfix fb-page">
    <div class="container np-fotter-link"><a href="#">About</a> <span>|</span> <a href="#">Privacy</a> <span>|</span> <a href="#">Invite</a> <span>|</span> <a href="#">Terms</a> <span>|</span> <a href="#">Contact Us</a> <span>|</span> <a href="#">Features</a> <span>|</span> <a href="#">Mobile</a> <span>|</span> <a href="#">Developers</a></div>
</div>
<!-- end of footer section -->
<script type="text/javascript">
    function readmore()
    {
        var showChar = 645;
	var ellipsestext = "...";
	var moretext = "read more";
	var lesstext = "read less";
	$('.moretext').each(function()
        {
            var content = $(this).html();
            if(content.length > showChar)
            {
                var c = content.substr(0, showChar);
                var h = content.substr(showChar, content.length - showChar);
                var html = c + '<span class="moreelipses">'+ellipsestext+'</span><span class="morecontent"><span>'+h+'</span>&nbsp;<a href="" class="morelink">'+moretext+'</a></span>';
                $(this).html(html);
            }
	});
	$(".morelink").click(function()
        {
            if($(this).hasClass("less"))
            {
                $(this).removeClass("less");
                $(this).html(moretext);
            }
            else
            {
                $(this).addClass("less");
                $(this).html(lesstext);
            }
            $(this).parent().prev().toggle();
            $(this).prev().toggle();
            return false;
	});
    }
    $(document).ready(function()
    {
        readmore();
    });
	var storedFiles = [];

	$(document).ready(function() {
		$("body").on("click", ".removeclose", removeFile);
	});

	function removeFile(e) {
	    var code = $(this).data("code");
		//console.log("Stored FIles " + storedFiles);
		//console.log("slected COde " + code);
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

	var postBtnEle = $("#post");
	var HIDE = "HIDE";
	var SHOW = "SHOW";
        function toggleAbilityPostButton(action){
		if(action == "SHOW"){
			$(postBtnEle).attr("disabled",false);
		}else{
			$(postBtnEle).attr("disabled",true);
		}
	}
	function toggleAbilityPostBtn(action){
		if(action == "SHOW"){
			$(postBtnEle).attr("disabled",false);
			$(".post_loadin_img").css("display","none");
		}else{
			$(postBtnEle).attr("disabled",true);
			$(".post_loadin_img").css("display","inline-block");
		}
	}
	
	$(document.body).bind('input propertychange', '.textInput', function() {
		        var expression = /[-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&//=]*)?/gi;
                var regex = new RegExp(expression);
                var url = $("#textInput").val();
                if (url.match(regex)) {
					toggleAbilityPostBtn(HIDE);
					

                    urls = "<?php echo \Yii::$app->getUrlManager()->createUrl('site/curl_fetch') ?>";
                    link = "url=" + $('#textInput').val();
                    $('#load').show();

                    $.ajax({
                        url: urls, //Server script to process data
                        type: 'POST',
                        data: link,
                        success: function (data) {
							
							if ($("div.preview_wrap").length)
							$("div.preview_wrap").remove();

                            var result = $.parseJSON(data);
							
							
							if(! (result['title'] && result['desc']) ) {
							$('#load').hide();
							$("div.preview_wrap").remove();
							return false;
						}
                            $("#link_description").val(result['desc']);
                            $("#link_title").val(result['title']);
                            $("#link_image").val(result['image']);
                            $("#link_url").val(result['url']);
                            $("#newPost").hide();

                            selector = result['title'];
							
							if(result['image'] != 'No Image')
							{
								$("#textInput").after('<div class="preview_wrap"><div class="linkinfo-holder"><div class="previewImage"><img height="100" width="100" src="' + result['image'] + '"/></div> <div class="previewDesc"><div class="previewLoading">' + result['title'] + '</div>' + result['desc'] + '</div></div></div>');
							}
							else
							{
								
								$("#textInput").after('<div class="preview_wrap"><div class="previewLoading">' + result['title'] + '</div> <div class="previewDesc">' + result['desc'] + '</div></div>');
							
							}
                            
							// enable post button
							toggleAbilityPostBtn(SHOW);

                        }
                    });
                } else {
                     $('#load').hide();
					$("div.preview_wrap").remove();
					return false;

                }
            });


    //var jq = $.noConflict(); 
    /*wall script start*/
    function load_data(page, uid)
    {
        if (page != '')
        {
            $("li").each(function () {
                $(this).removeClass("selected");
            });
            $("#" + page).addClass('selected');

            $("ul.wallsub").each(function () {
                //$(this).children().removeClass("current");
                $(this).children().removeClass("selected");
            });
            $("ul.wallsub #" + page).addClass('selected');

        }

        $.ajax({
            type: 'POST',
            url: '<?php echo Yii::$app->urlManager->createUrl(['userwall/getdata']); ?>',
            data: "page=" + page + "&uid=" + uid,
            success: function (data) {
                if (data)
                {
                    $('#wall-wrapper').html(data);
                  /*  jq('a.popup-modal').on("click", function (e) {
                    jq(this).magnificPopup({
                        type: 'inline',
                        preloader: false,
                        focus: '#username',
                        modal: true
                    });
                    });
                    jq("#post-status-list").fadeIn(300);
                  
                    jq(".post_loadin_img").fadeOut(300);
                    }, 300);*/
                    
                 
                if(page == 'posts'){
               
                $(".comment_class").on("keypress", function (e) {
                    if (e.which == 13) {
                       comment_event(this, e);
                   }
                });
                $(".reply_class").on("keypress", function (e) {
                    reply_event(this, e);
                });
    			
    			$(document.body).on("click", ".tarrow .alink", function (e) {
					closeAllDrawers(e);
					closeSearchSection(e);
                    showDrawer(this, e);
                });
                $(".frnduname").on("keyup", function (e) {
                    friendnames(this, e);

                });
            }
			 if(page == 'friends'){
			 		$(document.body).on("click", ".tarrow .alink", function (e) {
					closeAllDrawers(e);
					closeSearchSection(e);
                    showDrawer(this, e);
                });
			 }
			
                   /*jq('a.popup-modal').on("click", function (e) {
                             jq(this).magnificPopup({
                                     type: 'inline',
                                     preloader: false,
                                     modal: true
                             });
                     });*/
            jq(document).on('click', "a.popup-modal", function(e){
                jq.magnificPopup.open({
                      items: {
                        src: jq(this).attr('href')
                      },
                      type: 'inline',
                      preloader: false,
                      modal: true
                });
                e.preventDefault();       
            });
					
					/*
                    jq(document).on('click', '.popup-modal-dismiss', function (e) {
                                    e.preventDefault();
                                    jq.magnificPopup.close();
                    });	
					*/
					jq(document).on('click', '.popup-modal-dismiss', function (e) {
            
			
						var modalDiv = $(this).parents(".white-popup-block");

						if(modalDiv.length > 0){
							var modalId=modalDiv.attr("id");
							
							if(modalId=="add-newpost"){
								post_block(this,e);	
							}
							if(modalId=="custom-share"){
								e.preventDefault();
								jq.magnificPopup.close();
							}
                                                        if(modalId=="addalbum-popup"){
								e.preventDefault();
								jq.magnificPopup.close();
							}
							if(modalId.indexOf("edit_post_") >= 0){
								confirm_block(this,e);
							}
							if(modalId.indexOf("share_post_") >= 0){
								confirm_block(this,e);
							}
						}
					});
                }
            }
        });
    }
    /*wall script end*/

    // new UISearch(document.getElementById('sb-search'));

    /* DRAWER SCRIPT */

    /* open sorting drawer */

    function showDrawer(obj, e) {
	     e.stopPropagation();

        var thisparent = $(obj).parent();	
        var shown = $(obj).parent().children(".drawer").css("display");
        var dw = $(obj).parent().children(".drawer");
        clearActive();//top menu remove active class

        closeTopDrawer();//top menu close drawer menu

        if (shown == "none") {
            dw.fadeIn(500);
        } else {
            dw.fadeOut(500);
        }
    }

	function clearOtherDrawer(){
		
		$("body .drawer").each(function(){
			var shown=$(this).parent().children(".drawer").css("display");			
			if(shown=="block"){
				if($(this).parents(".tb-home-iconbox").length <= 0){					
					$(this).fadeOut(500);
				}	
			}			
		});
	}



	$(document.body).on("click", ".tarrow .alink", function (e) {
		closeAllDrawers(e);
		closeSearchSection(e);
        showDrawer(this, e);
    });

	 $(document.body).on("click",".droparea .alink", function(e){
			
			e.stopPropagation();
			
			var thisparent=$(this).parent();
				
			var shown=$(this).parent().children(".drawer").css("display");
			var dw=$(this).parent().children(".drawer");
			
			$(".tarrow .drawer").fadeOut(500);
			
			if(thisparent.hasClass("topbar-ul")){
				
				clearActive();
				if(thisparent.children(".drawer").length > 0){
					
					if(thisparent.hasClass("active")){
						thisparent.removeClass("active");
					}
					else{
						thisparent.addClass("active");
					}
				}
			}
			
			if($(this).parents(".tb-home-iconbox").length > 0){			
				
				var thisId=$(this).attr("id");
				if(thisId=="open_requests"){
					setNotificationContent($(this), "pending");
				} else if(thisId=="open_notifications"){
					setNotificationContent($(this), "notifications");
				} else {
					setNotificationContent($(this), "notfound");
				}
				clearOtherDrawer();
				
			} else {
				
				$(".droparea .drawer").fadeOut(500);
				if(shown=="none"){			
					dw.fadeIn(500);
				}
				else{			
					dw.fadeOut(500);
				}
			}
		});
	function setNotificationContent(e, thisId){
			
			var prevShown="none";
			
			$(".notification-content").each(function(){
				if($(this).css("display")=="block"){
					prevShown=$(this).attr("id");				
				}			
			});
			
			$(".notification-content").each(function(){
				if(prevShown!=thisId){							
					$(this).hide();						
				}
			});
			
			$("#"+thisId).show();
			
			var dw = e.parent().children(".drawer");			
			var shown=dw.css("display");
						
			if(prevShown==thisId){								
				if(shown=="none"){			
					dw.fadeIn(300);					
					clearNotificationClass(e,thisId);
				}
				else{			
					dw.fadeOut(300);
					setTimeout(function(){clearNotificationClass(e,"none");},300);
				}
			}
			else{
				if(shown=="none"){			
					dw.fadeIn(300);
					clearNotificationClass(e,thisId);						
				}
				else{
					setTimeout(function(){clearNotificationClass(e,thisId);},300);
				}
			}
			
		}
		function clearNotificationClass(e, thisId){
			
			var dw = e.parent().children(".drawer");			
			
			var thisContent="none";
			if(thisId=="pending"){
				thisContent="fr-notification";	
			}
			if(thisId=="notifications"){
				thisContent="gen-notification";
			}
			
			if(thisContent=="none"){
				dw.removeClass("fr-notification");
				dw.removeClass("gen-notification");
			}
			else{
				dw.addClass(thisContent);
				
				if(thisContent=="fr-notification"){
					dw.removeClass("gen-notification");
				}
				else if(thisContent=="gen-notification"){
					dw.removeClass("fr-notification");
				}				
			}
		}
		
                
        function view_notification()
        {
             $('#noti_budge').hide();
            $.ajax({
                type: 'POST',
                url: '<?php echo Yii::$app->urlManager->createUrl(['notification/view-notification']); ?>',
                success: function (data) {
                 
                }
            
            });
        }
        
         $(document).ready(function() {
        
            setInterval(function () {
               $.ajax({
                   url: '<?php echo Yii::$app->urlManager->createUrl(['notification/new-notification']); ?>',
                   type: 'POST',
                   data: {},
                   success: function (response) { 
                  
                    $('#notifications').html(response);
                    var budge = $("#new_budge").val();
                   
                    if(budge > 0){
                       // alert($("#noti_budge").length+'len');
                        if($("#noti_budge").length)
                        {//alert('if');
                            $('#noti_budge').show();
                            $('#noti_budge').html(budge);
                        }
                        else
                        {//alert('else');
                            $("#glob_budge").append('<span id="noti_budge" class="badge badge-default">'+budge+' </span>');
                        }
                    }


                   }
               });

           }, 10000);
        
         });
	/* END DRAWER SCRIPT */

    /* SEND INVITATION SCRIPT */

    function send_invitation()
    {
		var status = '<?php echo $status = $session->get('status'); ?>';
		
		if(status == 0){
			jq.magnificPopup.open({
			  items: {
				src: '#notverifyemail'
			  },
			  type: 'inline'
			});
			return false;
			exit;
		}

        var email = $('#friend_email').val();
		var pattern = /^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;
		
		
		if(email == '')
		{
			$(".send-msg").html('');
			 $('.invite-form').append('<span class="send-msg" style="color: red">Please Enter Email Address</span>');
			 $(".send-msg").fadeIn(300).fadeOut(5000);
			
			return false;
                    
		}
		
		else if(!pattern.test(email))
		{
			$(".send-msg").html('');
			 $('.invite-form').append('<span class="send-msg" style="color: red">Invalid Email Address</span>');
			 $(".send-msg").fadeIn(300).fadeOut(5000);
			 
			return false;
                    
		}
	
        else
        {
			$(".send-msg").html('');
            $.ajax({
                type: 'POST',
                url: '<?php echo Yii::$app->urlManager->createUrl(['friend/send-invitation']); ?>',
                data: "friend_email=" + email,
                success: function (data) {
                    
                    if (data == 1)
                    {
						
                        $('.invite-form').append('<span class="send-msg" style="color: green">Invitation Sent Successfully</span>');
						$(".send-msg").fadeIn(300).fadeOut(5000);						 
                        $('#friend_email').val('');
                    }
					else
                    {
					    $('.invite-form').append('<span class="send-msg" style="color: red">Email Already Exists</span>');
						$(".send-msg").fadeIn(300).fadeOut(5000);						 
                        $('#friend_email').val('');
                    }
                }
            });
        }
    }

    /* SEND INVITATION SCRIPT END */

    /* POST SCRIPT */

    function hide_post(pid)
    {
        BootstrapDialog.show({
			size: BootstrapDialog.SIZE_SMALL,
            title: 'Hide Post',
            message: 'Are you sure to hide this post ?',
            buttons: [{
                    label: 'Yes',
                    action: function (dialogItself) {
                        $("#hide_" + pid).hide();

                        if (pid != '')
                        {
                            $.ajax({
                                type: 'POST',
                                url: '<?php echo Yii::$app->urlManager->createUrl(['like/hide-post']); ?>',
                                data: "post_id=" + pid,
                                success: function (data) {
                                    if (data)
                                    {
                                       // $("#post-status-list").load("<?php echo Yii::$app->urlManager->createUrl(['site/ajax-status']); ?>");
                                       $('#hide_'+pid).hide();
                                        dialogItself.close();
                                    } else
                                    {
                                    }
                                }
                            });
                        } else {
                        }
                    }
                }, {
                    label: 'No',
                    action: function (dialogItself) {
                        dialogItself.close();
                    }
                }]
        });

    }
    function do_like(pid)
    {
        $.ajax({
            url: '<?php echo Yii::$app->urlManager->createUrl(['like/like-post']); ?>', //Server script to process data
            type: 'POST',
            data: 'post_id=' + pid,
            success: function (data) {
                var result = $.parseJSON(data);
             
                var flag = 0;

                if (result['display_ctr'] == -1 && result['fname'] != '')
                {
                    flag = 1;
                    $('#like_summery_' + pid).html('<a id="like_data_' + pid + '" class="show-pop" href="javascript:void(0)"><span id="like-name-count-' + pid + '"><span class="glyphicon glyphicon-thumbs-up"></span>' + result['fname'] + '</span></a>');
                } else
                {
                    flag = 1;
                    if (result['display_ctr'] > 1)
                        $('#like_summery_' + pid).html('<a id="like_data_' + pid + '" class="show-pop" href="javascript:void(0)"><span class="glyphicon glyphicon-thumbs-up"></span>' + result['names'] + ' and ' + result['display_ctr'] + ' others</a>');
                    else
                        $('#like_summery_' + pid).html('<a id="like_data_' + pid + '" class="show-pop" href="javascript:void(0)"><span class="glyphicon glyphicon-thumbs-up"></span>' + result['names'] + '</a>');
                    if (flag != 1) {
                        if (result['display_ctr'] > 0)
                        {
                            $('#like-name-count-' + pid).html('<span class="glyphicon glyphicon-thumbs-up"></span>' + result['names'] + ' and ' + result['display_ctr'] + ' others');
                        } else
                        {
                            $('#like-name-count-' + pid).html('<span class="glyphicon glyphicon-thumbs-up"></span>' + result['names']);

                            $('#like-name-count-' + pid).html('<span class="glyphicon glyphicon-thumbs-up"></span>' + result['names'] + ' and ' + result['display_ctr'] + ' others');
                        }

                    }

                }
                
                
                if (result['names'] === '')
                {
                    $('#like_summery_' + pid).html('');
                }
                if (result['buddies'] === '')
                {
                    $('#like-name-count-' + pid).html('');
                }
                if (result['like_count'] !== 0)
                    $("#like-ctr-" + pid).html('(' + result['like_count'] + ')');
                else
                    $("#like-ctr-" + pid).html('');
                 $('#like_buddies_' + pid).html(result['buddies']);
               
                $("a.show-pop").on("hover",
                     function () {alert('a');
                });
                 
                $("#like_data_"+pid ).on("hover", function (e) {  
                //$('a.show-pop').each(function(i,item){
                    var settings = {
                        trigger:'hover',
                        title:'',
                        html: true,
                        selector: '[rel="popover"]', //Sepcify the selector here
                        content: function () {
                            var pid =  $(this).data('pid');
                            return $('#like_buddies_'+pid).html();
                        },
                        multi:true,						
                        closeable:false,		
                        delay:300,
                        padding:true			
                    };
                    $('a.show-pop').webuiPopover('destroy').webuiPopover(settings);
                //});
            });  
               
               


            }
        });
    }
    function displaymenus(fromWhere)
    {
        $(".tb-user-icon").show();
		/*
			var tb=this.parents(".toolbox");
			tb.children(".tb-user-icon").show();
		*/
    }
    function showAll(result)
    {
        $("#newPost").hide();
        $("#hiddenCount").val(result);
        $('#ajax-content').hide();
        $('#ajax-content').load("<?php echo Yii::$app->urlManager->createUrl(['site/ajax-status']); ?>", function () {
            // This gets executed when the content is loaded			
            $("#post-status-list").fadeOut(300);
            setTimeout(function () {
                $("#post-status-list").html($('#ajax-content').html());
                $("#post-status-list").fadeIn(300);
                $('#ajax-content').html("");
                $(".post_loadin_img").fadeOut(300);
            }, 300);
        });
    }

    function DoPost() {

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
	
	function confirm_block(e,md){
		var getit = $(e).parents(".closepopup");

		if(getit.length > 0){
			confirm_cancel_post();
		}
		else
		{
			md.preventDefault();
			jq.magnificPopup.close();
		}
	}
	function post_block(e,md){
		
		var modalDiv = $(e).parents(".white-popup-block");
		var modalId=modalDiv.attr("id");
		var btnId=$(e).attr("id");
		
		if(modalId=="add-newpost"){
			
			if(btnId=="cancel_post"){
				
				// empty the new post data
				$('#textInput').val("");
				$("#imageFile1").val("");
				$("#image-holder").hide();
				/*$('#link_title').val("");
				$('#link_url').val("");
				$('#link_description').val("");
				$('#link_image').val("");*/
				$('#area').hide();
				$("#cur_loc").val("");
				$(".addpost-location").hide();
				md.preventDefault();
				jq.magnificPopup.close();
			}
			else{
				var resp=postStatus();
				
				if(resp.length!="undefined"){
				
					if(resp){						

						md.preventDefault();
						jq.magnificPopup.close();
					}
				}
				else{					
					md.preventDefault();
					jq.magnificPopup.close();
				}
			}
		}	
	}

    function delete_post(p_uid, pid)
    {
        BootstrapDialog.show({
            title: 'Delete Post',
            //message: 'Are you sure to delete this post ?',
            message: 'This post will be deleted and you won\'t be able to find it anymore.<br/>Are you sure to delete this post ?',
            buttons: [
                    {
                    label: 'Yes',
                    action: function (dialogItself) {
                        $.ajax({
                            url: '<?php echo Yii::$app->urlManager->createUrl(['site/delete-post']); ?>', //Server script to process data
                            type: 'POST',
                            data: 'post_user_id=' + p_uid + "&pid=" + pid,
                            success: function (data) {

                                //$("#post-status-list").load("<?php echo Yii::$app->urlManager->createUrl(['site/ajax-status']); ?>");
                                $('#hide_'+pid).hide();
                                dialogItself.close();

                            }
                        });
                    }
                }, {
                    label: 'No',
                    action: function (dialogItself) {
                        dialogItself.close();
                    }
                }]
        });

    }
   
    function edit_post()
    {
        var pid = $("#pid").val();
		var post_privacy = $("#post_privacy").val();
		var edit_current_location = $(".edit_current_location").val();
		var formdata;

		
 		var formdata;
        formdata = new FormData($('form')[1]);
		
        for(var i=0, len=storedFiles.length; i<len; i++) {
            formdata.append('imageFilepost[]', storedFiles[i]); 
        }


		if(title == '')
		{
			alert('Add Title');
			return false;
		}
		if (pid != '') {

		var text = document.getElementById('desc').value;

		//var ofile = document.getElementById('imageFilepost').files[0];
		//var ofile1 = document.getElementById('imageFilepost').files[1];
		//alert(ofile+' www '+ofile1);
		

		formdata.append("desc", text);

		//formdata.append("imageFilepost", ofile);
		//formdata.append("imageFilepost", ofile1);
		formdata.append("pid", pid);
		formdata.append("post_privacy", post_privacy);
		formdata.append("edit_current_location", edit_current_location);
                formdata.append("share_setting",$('#share_setting').val());
                formdata.append("comment_setting",$('#comment_setting').val());
		formdata.append("title",$('#title').val());
                formdata.append("posttags",$('#edittaginput'+pid).val());

		$.ajax({
				type: 'POST',
				url: '<?php echo Yii::$app->urlManager->createUrl(['site/editpost']); ?>',
				data: formdata,
				processData: false,
				contentType: false,
				success: function (data) {
				 		if (data)
						{
							$('#hide_'+pid).hide();
							load_last_post(data);
							jq.magnificPopup.close();
						}
						else
						{
								//$('#share_fail').css('display', 'inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
						}
				}
		});
		}
		else
		{
				$('#share_fail').css('display', 'inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
		}
    }

    $('.fp-modal-popup').on('show.bs.modal', function (e) {
    	alert('s');
	})

    function confirm_cancel_post() {
        BootstrapDialog.show({
			size: BootstrapDialog.SIZE_SMALL,
            //title: 'Discard Changes',
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
                        jq.magnificPopup.close();
                    }
                }]
        });
        $("#frm_edit_post")[0].reset();
        $("#image-holder").hide();
        $(".img-row").html("");
        return false;
        exit;
    }
	
    function save_post(postid, posttype) {
        BootstrapDialog.show({
            title: 'Save Post',
            message: 'Are you sure to update the saved list for this post ?',
			cssClass: 'custom-sdialog',
            buttons: [{
                    label: 'Yes',
                    action: function (dialogItself) {
                        if (postid != '' && posttype != '') {
                            $.ajax({
                                type: 'POST',
                                url: '<?php echo Yii::$app->urlManager->createUrl(['like/save-post']); ?>',
                                data: "postid=" + postid + "&posttype=" + posttype,
                                success: function (data) {

                                    var result = $.parseJSON(data);
                                    if (result['status'] === 'true')
                                    {
                                        $('#suceess').css('display', 'inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                                        if (result['saved'] === '1') {
                                            $("#save_post_" + postid).html('Save Post');
                                        } else
                                        {
                                            $("#save_post_" + postid).html('Unsave Post');
                                        }

                                        $("#save_content_" + postid).hide();
                                        dialogItself.close();
                                        
                                    } else
                                    {
                                    }
                                }
                            });
                        }
                    }
                }, {
                    label: 'No',
                    action: function (dialogItself) {
                        dialogItself.close();
                    }
                }]
        });

    }
    function reportpost()
    {
        BootstrapDialog.show({
			size: BootstrapDialog.SIZE_SMALL,
            title: 'Report Post',
            message: 'Are you sure to report to the user ?',
            buttons: [{
                    label: 'Yes',
                    action: function (dialogItself) {
                        var pid = $("#pid").val();
                        var desc = $("#desc").val();
                        $.ajax({
                            type: 'POST',
                            url: '<?php echo Yii::$app->urlManager->createUrl(['site/reportpost']); ?>',
                            data: "pid=" + pid + "&desc=" + desc,
                            success: function (data) {
                                //alert(data);return false;
                                if (data)
                                {
                                    $('#report_success').css('display', 'inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                                    $('#hide_'+pid).hide();
                                    dialogItself.close();
                                    jq.magnificPopup.close();
                                } else
                                {
                                    $('#report_fail').css('display', 'inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                                    dialogItself.close();
                                    jq.magnificPopup.close();
                                }
                            }

                        });
                    }
                }, {
                    label: 'No',
                    action: function (dialogItself) {
                        dialogItself.close();
                    }
                }]
        });

    }
    function delete_post_comment(pcid)
    {
        BootstrapDialog.show({
			size: BootstrapDialog.SIZE_SMALL,
            title: 'Delete Comment',
            message: 'Are you sure to delete this comment ?',
            buttons: [{
                    label: 'Yes',
                    action: function (dialogItself) {
                        $.ajax({
                            url: '<?php echo Yii::$app->urlManager->createUrl(['site/delete-post-comment']); ?>', //Server script to process data
                            type: 'POST',
                            data: 'comment_id=' + pcid,
                            success: function (data) {
                              
                                var result = $.parseJSON(data);
                                $('#cwrapper_'+pcid).hide();
                                if(result['ctr'] != 0)
                                    $('#comment_ctr_'+result['post_id']).html('('+result['ctr']+')');
                                else 
                                    $('#comment_ctr_'+result['post_id']).html('');
                                    
                                dialogItself.close();

                            }
                        });
                    }
                }, {
                    label: 'No',
                    action: function (dialogItself) {
                        dialogItself.close();
                    }
                }]
        });
    }
    function hide_post_comment(pcid)
    {
        BootstrapDialog.show({
			size: BootstrapDialog.SIZE_SMALL,
            title: 'Hide Comment',
            message: 'Are you sure to hide this comment ?',
            buttons: [{
                    label: 'Yes',
                    action: function (dialogItself) {
                        $.ajax({
                            url: '<?php echo Yii::$app->urlManager->createUrl(['site/hide-post-comment']); ?>', //Server script to process data
                            type: 'POST',
                            data: 'comment_id=' + pcid,
                            success: function (data) {

                               // $("#post-status-list").load("<?php echo Yii::$app->urlManager->createUrl(['site/ajax-status']); ?>");
                              // cwrapper_
                              $("#cwrapper_"+pcid).hide();
                                dialogItself.close();

                            }
                        });
                    }
                }, {
                    label: 'No',
                    action: function (dialogItself) {
                        dialogItself.close();
                    }
                }]
        });
    }
    
//    $(".comment_class").keypress(function (e) {
//        comment_event(this, e);
//    });
    function closeedit(id)
    {
        $("#edit_textarea_" + id).hide();
        $("#editcomment_" + id).show();
        $(".add_comment").show();
    }
    function replace_event(obj, e,id)
    {
        if (e.which == 13)
        {
            var text = $("#edit_textarea_value_" + id).val();
            //alert(text);
            $.ajax({
                 url: '<?php echo Yii::$app->urlManager->createUrl(['comment/edit-reply-comment']); ?>',  //Server script to process data
                 type: 'POST',
                 data:'comment_id='+id+'&edit_comment='+text,
                 success: function(data)
                 {
                    $("#edit_textarea_" + id).hide();
                    $("#editcomment_" + id).show();
                    $(".add_comment").show();
                    $("#text_" + id).html(text);
                    $("#edit_textarea_value_" + id).val(text);
                    //$('<div class="comment_text" id="text_'+id+'" onclick="exchange(this,\''+id+'\')">'+text+'</div>').insertBefore("#comment-like-"+id);
                    
                 }
            });
        }
    }
     function replace(id,comment)
    {
        //alert(id);alert(comment);
        $(".add_comment").hide();
        $("#editcomment_" + id).hide();
        $("#edit_textarea_" + id).show();
        $("#edit_textarea_" + id).keypress(function(e){
                replace_event(this, e,id);
        });
    }
    function closesharepopup()
    {
        jq.magnificPopup.close();
    }
    function spwFriend()
    {
        BootstrapDialog.show({
			size: BootstrapDialog.SIZE_SMALL,
            title: 'Share Post',
            message: 'Are you sure to share this post ?',
            buttons: [{
                    label: 'Yes',
                    action: function (dialogItself) {
                        var frndid = $("#frndid").val();
                        var desc = $("#desc").val();
                        var spid = $("#spid").val();
                        var post_privacy = $("#post_privacy").val();
                        var share_current_location = $(".share_current_location").val();
                        var share_setting = $('#share_setting').val();
                        var comment_setting = $('#comment_setting').val();
                        var posttags = $('#sharetag'+spid).val();
                        if (desc === "" && frndid === "undefined")
                        {
                            $("#desc").focus();
                            dialogItself.close();
                            return false;
                        } else
                        {
                            $.ajax({
                                type: 'POST',
                                url: '<?php echo Yii::$app->urlManager->createUrl(['site/sharenowwithfriends']); ?>',
                                data: "posttags=" + posttags + "&spid=" + spid + "&frndid=" + frndid + "&desc=" + desc + "&post_privacy=" + post_privacy + "&current_location=" + share_current_location + "&share_setting=" + share_setting + "&comment_setting=" + comment_setting,
                                success: function (data) {
                                    if (data)
                                    {
                                        load_last_post(data);
                                        $("#frndid").val('');
                                        $(".frnduname").val('');
                                        dialogItself.close();
                                        jq.magnificPopup.close();
                                    } else
                                    {
                                       
                                        //$("#post-status-list").load("<?php //echo Yii::$app->urlManager->createUrl(['site/ajax-status']); ?>");
                                        
                                        dialogItself.close();
                                        jq.magnificPopup.close();
                                    }
                                }

                            });
                        }
                    }
                }, {
                    label: 'No',
                    action: function (dialogItself) {
                        dialogItself.close();
                    }
                }]
        });
    }
    function showDiv(elem)
    {
        if (elem.value == 1)
        {
            document.getElementById('friendlist').style.display = "block";
        }
        if (elem.value == 0)
        {
            document.getElementById('friendlist').style.display = "none";
        }
    }
    // AJAX call for autocomplete 
    $(document).ready(function () {
        $(".on-sel-phone").click(function()
        {
            $("#frndid").val('');
            $(".frnduname").val('');
            document.getElementById('friendlist').style.display = "none";
        });
        $(".on-sel-friends").click(function()
        {
            document.getElementById('friendlist').style.display = "block";
        });
        $(".on-sel-friend").click(function()
        {
            $("#frndid").val('');
            $(".frnduname").val('');
            document.getElementById('friendlist').style.display = "none";
        });
        $(".on-sel-friend-facebook").click(function()
        {
            $("#frndid").val('');
            $(".frnduname").val('');
            document.getElementById('friendlist').style.display = "none";
        });
        $(".frnduname").keyup(function (e) {
            friendnames(this, e);
        });
    });
    
    function friendnames(obj,e)
    {
         $.ajax({
                type: "POST",
                url: "<?php echo \Yii::$app->getUrlManager()->createUrl('site/sharenowwithfriends') ?>",
                data: 'keyword=' + $(obj).val(),
                success: function (data) {
                    $(".suggesstion-box").show();
                    $(".suggesstion-box").html(data);
                    //$(".frnduname").val('');
                    $(".frnduname").css("background", "#FFF");
                }
            });
    }
    //To select Friend name
    function selectName(val,frndid)
    {
        $(".frnduname").val(val);
        $("#frndid").val(frndid);
        $(".suggesstion-box").hide();
    }
    /* END POST SCRIPT */

    /* START PROFILE TIP START */

    $(document).ready(function () {
        var timer, $this;
        $('.my_link a').bind('mouseover', function () {
            clearTimeout(timer);
            $this = $(this);
            timer = setTimeout(function () {
                var anime_title = $this.html();
                var title_width = parseInt($this.width(), 10);
                var next = $this.next();

                /* $.ajax({
                 type: "POST",
                 url: 'ajax/my_info_hover.php',
                 data: {
                 my_title: my_title
                 }
                 }).success(function (data) {
                 //Disable mouseover from this class?
                 $('.my_info_wrap').remove();
                 $(next).html(data).css('left', title_width + 10 + 'px');
                 */
            }, 1000); //function fires after 1000ms = 1s
        }).mouseout(function () {
            //Enable mouseover again?
            clearTimeout(timer);
            $('.my_info_wrap').remove();
		});
	});
      function show(id) {
          $('#profile_'+id).show();
      }
      function hide(id) {
        $('#profile_'+id).hide();
      }
	$(document).ready(function(e){
		 $('.profilelink').hover(
					
			   function () {
				  var pp=$(this).parents(".tb-panel-head").children(".profiletip-holder");								  
				  pp.show();				  
			   }, 
				
			   function (e) {
				
				 var pp=$(this).parents(".tb-panel-head").children(".profiletip-holder");				
				
				 if(!pp.hasClass("pp-hovered")){
					 $(".profiletip-holder").hide();
				 }
			   
			   }
		);
		
		$('.profiletip-holder').hover(
					
			   function () {
				  $(this).addClass("pp-hovered");
				  $(this).show();				  
			   }, 
				
			   function () {				 				 
				 $(this).removeClass("pp-hovered");
				 $(this).hide();
			   }
		);
	});

    function ProfileTip(userid, post, delay, pid) {
        // userid: Unique user ID
        // post: Unique Message/Post ID
        // delay: 0 - on mouse IN; 1 - on mouse OUT;
        var parentID = "#hide_" + pid;

        var checkDisp = $(parentID + ' .profile-tip').css("display");

        //alert(checkDisp);

        if (checkDisp != "block") {
            if (delay == 1) {
                clearInterval(TipTimer);
            } else {
                TipTimer = setInterval(function () {

                    var msgType = 'message';
                    // The position to be increased
                    var height = 58;
                    var left = 20;

                    // Start displaying the profile card with the preloader
                    $(parentID + ' .profile-tip').show();
                    $(parentID + ' .profile-tip').html('<div class="profile-tip-padding"><div class="loader"></div></div>');

                    // Get the position of the parent element
                    var position = $("#" + msgType + post).position();

                    // Store the position into an array
                    var pos = {
                        top: (position.top + height) + 'px',
                        left: (position.left + left) + 'px'
                    };

                    // Set the position of the profile card
                    $(parentID + ' .profile-tip').css(pos);
                    $.ajax({
                        type: "POST",
                        url: '<?php echo Yii::$app->urlManager->createUrl(['site/viewprofile']); ?>',
                        data: "user_id=" + userid,
                        cache: false,
                        success: function (html) {
                            $(parentID + ' .profile-tip').html(html);
                        }
                    });
                    clearInterval(TipTimer);
                }, 500);
            }
        }
    }
    /*$(document).ready(function() {
     $('#profile-tip').mouseleave(function()
     {
     $('#profile-tip').hide();
     });
     });*/
    function ProfileHide(pid) {
        var parentID = "#hide_" + pid;

        //alert("hidden");
        $(parentID + ' .profile-tip').hide();
    }

    /* END PROFILE TIP START */

    /* COMMENT SCRIPT */

    function reply_comment(cid)
    {    
        
        //$('#replyimg_' + cid).hide();
        $('#display_reply_' + cid).show();
    }

    $(".reply_class").keypress(function (e) {
        reply_event(this, e);
    });

    function reply_event(obj, e)
    {
        if (e.which == 13)
        {
            var pid = $(obj).data('postid');
            var cid = $(obj).data('commentid');
            var reply = $('#reply_txt_' + cid).val();
            /*if (reply != '')
            {*/
			if($.trim(reply) == undefined || $.trim(reply) == null || $.trim(reply) == ""){	
			e.preventDefault();
			   return false;
            }
else{			
                $("#reply_txt_" + pid).css('display', 'inline');
                $("#user_img_" + pid).css('display', 'block');
                $("#reply_txt_" + cid).focus();
                $("#commmets_" + pid).show();
                $("#init_comment_display_" + pid).show();
                
               var formdata;
                formdata = new FormData();
                var reply_file =  document.getElementById('imageReply_'+cid).files[0]; //$('#imageComment').files[0];
                formdata.append("imageReplypost", reply_file);
                formdata.append("post_id", pid);
                formdata.append("reply", reply);
                formdata.append("comment_id", cid);
                

                $.ajax({
                    url: '<?php echo Yii::$app->urlManager->createUrl(['comment/reply-comment']); ?>', //Server script to process data
                    type: 'POST',
                    //data: 'post_id=' + pid + '&reply=' + reply + '&comment_id=' + cid,
                    data: formdata,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        $("#reply_txt_" + cid).val('');
                        $(".reply_comments_" + cid).append(data);
                       //  $(".tarrow1 .alink1").on("click", function (e) {
                       $(".tarrow_"+cid+" .alink_"+cid).on("click", function (e) {
                                showDrawer(this, e);
                        });
                    $('#image-holder-reply-'+cid).css('display','none');
                       //$("#reply_txt_" + cid).hide();
                       $('#display_reply_' + cid).hide();
                      // $('#imageReply_'+cid).val('');
                       
                       //$('#replyimg_'+cid).src('');
			setTimeout(function(){setFbBodyHeight()},1000);

                    }
                });
            }
        }

    }

    function show_previous(pid)
    {

        var from_ctr = $('#from_ctr_' + pid).val();
        var to_ctr = 5;
        $.ajax({
            url: '<?php echo Yii::$app->urlManager->createUrl(['comment/load-comment']); ?>', //Server script to process data
            type: 'POST',
            data: 'from_ctr=' + from_ctr + '&to_ctr=' + to_ctr + '&pid=' + pid,
            success: function (data) {
                $("#init_comment_display_" + pid).append(data);

                from_ctr = parseInt($('#from_ctr_' + pid).val()) + parseInt(to_ctr);
                $('#from_ctr_' + pid).val(from_ctr);

                if (parseInt($('#to_ctr_' + pid).val(), 10) < parseInt($('#from_ctr_' + pid).val(), 10))
                {
                    $("#commment-ctr-" + pid).html('');
                } else {
                    $("#commment-ctr-" + pid).html(from_ctr + ' of ' + $('#to_ctr_' + pid).val());
                }
                if (from_ctr == $('#to_ctr_' + pid).val())
                {
                    $("#commment-ctr-" + pid).html('');
                }
                $(".tarrow1 .alink1").on("click", function (e) {
                        showDrawer(this, e);
                });
//                $("#comment_txt_" + last_pid).on("keypress", function (e) {
//                 comment_event(this, e);
//                });
                    $(".reply_class1").on("keypress", function (e) {
                          reply_event(this, e);
                   
                        });

                
            }
        });
    }

    $(".comment_class").keypress(function (e) {
        if (e.which == 13) {
            comment_event(this, e);
        }
    });
	 
    function readURL(input,pid,obj) {
        if (input.files && input.files[0]) {
		var reader = new FileReader();
		
		reader.onload = function (e) {
			obj.attr('src', e.target.result);
                        obj.parent().show();
		}
		
		reader.readAsDataURL(input.files[0]);
	}
    }
	
    $(".imgComment").change(function(){
        comment_reply_stuffs(this,'comment');
    });
    
    $(".imgReply").change(function(){
        comment_reply_stuffs(this,'reply');
    });
    
    function comment_reply_stuffs(obj,flag)
    {
        var pid = $(obj).data('postid');
        if(flag == 'comment')
        {
            $('#image-holder-comment-'+pid).css('display','block');
            readURL(obj,pid,$('#commentimg_'+pid));
        }
        else
        {
            var cid = $(obj).data('commentid');
            $('#image-holder-reply-'+cid).css('display','block');
            readURL(obj,cid,$('#replyimg_'+cid));
        }
        
    }
     
    function comment_event(obj, e)

    {  
	if (e.which == 13)
        {
			var pid = $(obj).data('postid');
            var comment = $('#comment_txt_' + pid).val();
            var comment_file =  document.getElementById('imageComment_'+pid).files[0];
			if(($.trim(comment) == undefined || $.trim(comment) == null || $.trim(comment) == "") && comment_file == undefined || comment_file == ''){	
			e.preventDefault();
			   return false;
            }else{
               
                $("#comment_txt_" + pid).css('display', 'inline');
                $("#user_img_" + pid).css('display', 'block');
                $("#comment_txt_" + pid).focus();
                $("#comment_display_" + pid).show();
                $('#init_commmets_' + pid).show();
               
                var formdata;
                formdata = new FormData();
                 //$('#imageComment').files[0];
                formdata.append("imageCommentpost", comment_file);
                formdata.append("post_id", pid);
                formdata.append("comment", comment);
                
                $.ajax({
                    url: '<?php echo Yii::$app->urlManager->createUrl(['comment/comment-post']); ?>', //Server script to process data
                    type: 'POST',
                   //data: 'post_id=' + pid + '&comment=' + comment+'&comment_file='+comment_file,
                   data:formdata,
                   async: false,
                   processData: false,
                   contentType: false,
                    success: function (data) {
                        
                        $("#init_comment_display_" + pid).append(data);
                       // alert(data);
                        $("#comment_txt_" + pid).val('');
                          var cid = $('#last_comment_id').val(); //result['last_comment_id'];
                          if(cid != '')
                          {
                             $('#comment_count').val(parseInt($('#comment_count').val())+parseInt(1));
                          }
                        $("#comment_ctr_" + pid).html('(' + $('#comment_count_'+pid).val() + ')');
                        $('#image-holder-comment-'+pid).css('display','none');
                       //alert($('#comment_count_'+pid).val());
                       // alert(cid);
                        $("#reply_txt_"+cid ).on("keypress", function (e) {
                           // alert('12');
                            reply_event(this, e);
                   
                        });
                        
                        $(".tarrow_"+cid+" .alink_"+cid).on("click", function (e) {
                                showDrawer(this, e);
                        });
                         $("#edit_textarea_"+cid).on("keypress", function (e) {
                                replace_event(this, e,cid);
                        });
                        
                        //$(".imgReply").change(function(){
                        $(".imgReply").on("change", function (e) {
                            //alert('');
                          //  var pid = $(this).data('postid');
                            readURL(this,cid,$('#replyimg_'+cid));
                        });
                        
                        
                       // $('#commentimg_'+pid).hide();
                        
                        $('#imageComment_'+pid).val('');//replyimg_
                        $('#last_comment_id').remove();
                        $('#comment_count_'+pid).remove();
                        setTimeout(function(){setFbBodyHeight()},1000);
	//					$(".comment_class").attr("placeholder", "Write a comment...").val("");
						$('.comment_class').attr(val,"");
						//$('.comment_class').attr(placeholder, "Write a comment...");

                    }
                });
            }
        }
    }

    function like_comment(cid)
    {
        $.ajax({
            url: '<?php echo Yii::$app->urlManager->createUrl(['like/comment-like']); ?>', //Server script to process data
            type: 'POST',
            data: 'comment_id=' + cid,
            success: function (data) {
                var result = $.parseJSON(data);
                //alert(result['status']);
                if (result['status'] == '1')
                {
                    $("#comment_cls_" + cid).html('Unlike');

                } else
                {
                    $("#comment_cls_" + cid).html('Like');
                }
            }
        });
    }
    function do_comment(pid)
    {
        $("#comment_txt_" + pid).css('display', 'inline');
        $("#user_img_" + pid).css('display', 'block');

        $("#comment_txt_" + pid).focus();
        $("#comment_display_" + pid).css('display', 'block');
        $("#commmets_" + pid).css('display', 'block');
        $("#init_commmets_" + pid).show();
    }

    /* END COMMENT SCRIPT */

    /* REQUEST SCRIPT */

    $("#open_requests").click(function () {
        //$('#pending').toggle();
    });
    /*
     $( "#open_notifications" ).click(function() {
     	//$('#notifications').toggle();
     });
     
     */
	 
     function load_last_post(data)
     {

        // Tag input
		$('.tags-added').html('');
        $('#taginput').val("");
        $('#customin1').val("");
        $('#customin3').val("");
        $('#customchk').prop('checked', false);
        $('.tags-added').html('');
        $('.select2-selection__rendered').html('');
		$('.addpost-tag').hide();
		
        // Custom input
        

        $("#post-status-list").fadeOut(300);
        $("#newPost").hide();
        $('#textInput').val("");
        $('.getplacelocation').val("");
        $("#imageFile1").val("");
        $("#image-holder").hide();
        $(".img-row").html("");
        $('#displayImag').html("");
        $('#displayImag').hide();
        $(".previewLoading").hide();
        $(".previewImage").hide();
        $(".previewDesc").hide();
        $('#link_title').val("");
        $('#link_url').val("");
        $('#link_description').val("");
        $('#link_image').val("");
        $('#area').hide();
        $("#title").val("");
        $('.post-title').hide();
        $("#cur_loc").val("");
        $(".tarrow").removeClass("tarrow1");
        $(".alink").removeClass("alink1");
	    $(".addpost-location").hide();
        $('#share_setting').val("Enable");
        $('#comment_setting').val("Enable");
        $("#imgfilecount").val(0);
        jq.magnificPopup.close();
        toggleAbilityPostButton(HIDE);
        setTimeout(function () {
                
             $('#post-status-list').prepend(data);

             var last_pid = $('#last_inserted').val();

             $("#comment_txt_" + last_pid).on("keypress", function (e) {
                 if (e.which == 13) {
                    comment_event(this, e);
             }
             });
             $(".reply_class").on("keypress", function (e) {
                 reply_event(this, e);
             });
             $(".tarrow1 .alink1").on("click", function (e) {
                 showDrawer(this, e);
             });
             $(".frnduname").on("keyup", function (e) {
                 friendnames(this, e);
             });
             $(".add-loc .add-loc1").on("click", function (e) {
		openLocPost(this, e);
            });
            $(".add tag .add-tag1").on("click", function (e) {
                openLocTag(this, e);
            });
            $(".on-sel-phone").click(function()
            {
                $("#frndid").val('');
                $(".frnduname").val('');
                document.getElementById('friendlist').style.display = "none";
            });
            $(".on-sel-friends").click(function()
            {
                document.getElementById('friendlist').style.display = "block";
            });
            $(".on-sel-friend").click(function()
            {
                $("#frndid").val('');
                $(".frnduname").val('');
                document.getElementById('friendlist').style.display = "none";
            });
            $(".on-sel-friend-facebook").click(function()
            {
                $("#frndid").val('');
                $(".frnduname").val('');
                document.getElementById('friendlist').style.display = "none";
            });
            $(".frnduname").keyup(function (e) {
                friendnames(this, e);
            });
            $('.profilelink').hover(

                function () {
                       var pp=$(this).parents(".tb-panel-head").children(".profiletip-holder");								  
                       pp.show();				  
                }, 

                function (e) {

                      var pp=$(this).parents(".tb-panel-head").children(".profiletip-holder");				

                      if(!pp.hasClass("pp-hovered")){
                              $(".profiletip-holder").hide();
                      }

                }
            );
		
            $('.profiletip-holder').hover(

                function () {
                       $(this).addClass("pp-hovered");
                       $(this).show();				  
                }, 

                function () {				 				 
                      $(this).removeClass("pp-hovered");
                      $(this).hide();
                }
            );
		
             
             jq("area[rel^='prettyPhoto']").prettyPhoto();
             jq(".gallery a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'normal',theme:'light_square',social_tools: ' '});
                
             /*jq('a.popup-modal').on("click", function (e) {
                 jq(this).magnificPopup({
                    type: 'inline',
                    preloader: false,
                    modal: true

                 });
            });*/
            jq(document.body).on('click', "a.popup-modal", function(e){
                jq.magnificPopup.open({
                      items: {
                        src: jq(this).attr('href')
                      },
                      type: 'inline',
                      preloader: false,
                      modal: true
                });
                e.preventDefault();       
            });
            $(".frnduname").on("keyup", function (e) {
                friendnames(this, e);

            });

           $(".imgComment").on("change", function (e) {
               comment_reply_stuffs(this,'comment');
           });

           $(".imgReply").on("change", function (e) {
              comment_reply_stuffs(this,'reply');
           });

           jq("#post-status-list").fadeIn(300);
           jq(".post_loadin_img").fadeOut(300);

            jq(document).on('click', '.popup-modal-dismiss', function (e) {

                var modalDiv = $(this).parents(".white-popup-block");

                if(modalDiv.length > 0){
                        var modalId=modalDiv.attr("id");

                        if(modalId=="add-newpost"){
                                post_block(this,e);	
                        }
                        if(modalId=="custom-share"){
                                e.preventDefault();
                                jq.magnificPopup.close();
                        }
                        if(modalId.indexOf("edit_post_") >= 0){
                                confirm_block(this,e);
                        }
                        if(modalId.indexOf("share_post_") >= 0){
                                confirm_block(this,e);
                        }
                }
            });
                                
            // START EDit Post Link for tag 
            jq(document.body).on('click', '.popup-modal', function() {
                
                 jq("#edittaginput"+editpostid).select2();
                    var editpostid = jq(this).data('editpostid');
                    if(editpostid) {
                            var taginput = jq("#edittaginput"+editpostid).data("taginput");

                            if (taginput != undefined || taginput != null || taginput != '') {
                                    var newtaginput = taginput.split(',');
                                    
                                    console.log(newtaginput);
                                    //alert('in if'+taginput);
                                   // jq(document).ready(function () {
                                        jq("#edittaginput"+editpostid).select2().val(["572b0c5510b4998c4a0000eb"]).trigger("change");
                                   // });
                            }
                    }
            });
                
        }, 300);

		return true;
     }
	
	
	function postStatus() {
		
		
		var status = '<?php echo $status; ?>';
		if(status == 0){
			jq.magnificPopup.open({
			  items: {
				src: '#notverifyemail'
			  },
			  type: 'inline'
			});
			return false;
			exit;
		}	
		
		//var reg = /<(.|\n)*?>/g;
		var reg = /<\s*\/\s*\w\s*.*?>|<\s*br\s*>/g;
        var title = $("#title").val();

        chk = 'off';
        if($('#customchk:checkbox:checked').length > 0) {
            chk = 'on';
        }
        
        var status = document.getElementById('textInput').value;
		
		if (status != "" && reg.test(status) == true)
		{
			 status = status.replace(reg, " ");
		}
		if (title != "" && reg.test(title) == true)
		{
			 title = title.replace(reg, " ");
		}
        var count = document.getElementById('hiddenCount').value;
        var formdata;
        formdata = new FormData($('form')[1]);
		
        for(var i=0, len=storedFiles.length; i<len; i++) {
            formdata.append('imageFile1[]', storedFiles[i]); 
        }
        /*if(title === '')
        {
            BootstrapDialog.show({
                size: BootstrapDialog.SIZE_SMALL,
                title: 'Add Title',
                message: 'Please add post title.'
            });
            return false;
        }*/
        if (/^\s+$/.test(status) && document.getElementById('imageFile1').value == "" )
        {
            toggleAbilityPostButton(HIDE);
            $('#textInput').val("");
            document.getElementById('textInput').focus();
            return false;
        }
        else if (status == "" && document.getElementById('imageFile1').value == "")
        {
            document.getElementById('textInput').focus();
            return false;
        } else if (status != "" && document.getElementById('imageFile1').value == "")
        {
            formdata.append("test", status);
            formdata.append("imageFile1", "");

        } else if (document.getElementById('imageFile1').value != "" && status == "")
        {
            var ofile = document.getElementById('imageFile1').files;
            formdata.append("imageFile1", ofile);
            formdata.append("test", "");

        } else if (document.getElementById('imageFile1').value != "" && status != "")
        {
            var ofile = document.getElementById('imageFile1').files;
            formdata.append("imageFile1", ofile);
            formdata.append("test", status);
        } else
        {
            return false;
        }
        $(".post_loadin_img").css("display","inline-block");
		  
          formdata.append("sharewith",$('#customin1').val());
          formdata.append("sharenot",$('#customin3').val());
          formdata.append("customchk", chk);
          formdata.append("posttags" ,$('#taginput').val());

          formdata.append("current_location",$('#cur_loc').val());
          formdata.append("post_privacy",$('#post_privacy').val());
          formdata.append("link_title",$('#link_title').val());
          formdata.append("link_url",$('#link_url').val());
          formdata.append("link_description",$('#link_description').val());
          formdata.append("link_image",$('#link_image').val());
          formdata.append("title",$('#title').val());
          formdata.append("share_setting",$('#share_setting').val());
          formdata.append("comment_setting",$('#comment_setting').val());
          
         // formdata.append("newpost_flag",'1');
		// alert($('#link_image').val());
		$.ajax({
			url: '<?php echo Yii::$app->urlManager->createUrl(['site/upload']); ?>',  //Server script to process data
			type: 'POST',
			data:formdata,
			async:false,        
			processData: false,
			contentType: false,
			success: function(data) {
				newct = 0;
				lastModified = [];
				storedFiles = [];
				storedFiles.length = 0;
				$("#taginput").val('').trigger('change');
				load_last_post(data);			
			}
		}).done(function(){
           setTimeout(function(){fixImageUI();readmore();},500);
      });

		return true;
	}

	/* END POST SCRIPT */
function addalbum()
{
	var status = '<?php echo $status = $session->get('status'); ?>';		
		if(status == 0){
			jq.magnificPopup.open({
			  items: {
				src: '#notverifyemail'
			  },
			  type: 'inline'
			});
			return false;
			exit;
		}
    var imgs = $("#imageFile1").val();
    if(imgs === '')
    {
        BootstrapDialog.show({
			size: BootstrapDialog.SIZE_SMALL,
            title: 'Add image',
            message: 'Please add images to the album.'
        });
        return false;
    }
    else
    {
        BootstrapDialog.show({
			size: BootstrapDialog.SIZE_SMALL,
            title: 'Add Album',
            message: 'Are you sure to add album ?',
            buttons: [{
                    label: 'Yes',
                    action: function (dialogItself) {
                        var formdata;
                            formdata = new FormData($('form')[0]);
                            $.ajax({ 
                                url: '<?php echo Yii::$app->urlManager->createUrl(['userwall/photos']); ?>',  //Server script to process data
                                type: 'POST',
                                data:formdata,
                                processData: false,
                                contentType: false,
                                success: function(data) {
                                    //$("#noalbum").hide();
                                    $("#album_title").val('');
                                    $("#album_description").val('');
                                    $("#album_place").val('');
                                    $("#imageFile1").val('');
                                    dialogItself.close();
                                    jq.magnificPopup.close();
                                    var result = $.parseJSON(data);
                                    $(".albums").append(result['previewalbumimages']);
                                }
                            });
                    }
                }, {
                    label: 'No',
                    action: function (dialogItself) {
                        dialogItself.close();
                    }
                }]
        });
    }
}
   /* PAGE LOAD SCRIPT */

    $( ".pageLoad" ).click(function() {
            location.reload();
    });
	
	/* END PAGE LOAD SCRIPT */

	 /* PAGE IMAGE POST */
	 
	 
	function setCurrClass(name){curr_cls=name;}	 
	function getCurrClass(){return curr_cls;}

        function addmoreimg_filechange(obj){
            //var resultimage = $("#imageFile1").val().concat(','+$("#imageFile2").val());
            //Get count of selected files
	   var countFiles = $(obj)[0].files.length;
	   
	   / set image class /
	   
	   var file, img;
	   var _URL = window.URL || window.webkitURL;
           
	   / end set image class /
		  
	   var imgPath = $(obj)[0].value;
	   var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
	   var image_holder = $("#image-holder .img-row");

	   if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg" || extn == "tif" || extn == "webp") {
		if (typeof (FileReader) != "undefined") {
		
		var imgCls=[];
		 //loop for each file selected for uploaded.
		 for (var i = 0; i < countFiles; i++) {
		  
		  file = obj.files[i];      
		  
		  var reader = new FileReader();
		  reader.onload = function (e) {
                    $("#imageFile1").append(e.target.result);
                    var img = new Image();
                    img.onload = function() {

		   var curr_cls='';
		   
		   if(obj.width>obj.height){
			 //curr_cls="himg";
			 setCurrClass("himg");
			 imgCls[i]="himg";
			 //alert("if");
		   }
			 else if(obj.width<obj.height){
			  //curr_cls="vimg";
			  setCurrClass("vimg");
			  imgCls[i]="vimg";
			 // alert("else if "+getCurrClass());
			 }
			 else{
			  //curr_cls="himg";
			  setCurrClass("himg");
			  imgCls[i]="himg";
			// alert("else");
			 }
			 //alert(this.width + " " + this.height);
			 
			 for(var j=0;j<imgCls.length;j++){
			 }
		   
		   $("<img />", {
			 "src": e.target.result,
			  "class": "thumb-image "+imgCls[i]
			})
			.add("<a href='#'><i class='fa fa-close'></i></a>") 
			.wrapAll("<div class='uimg_holder'></div>")
			.parent()
			.prependTo(image_holder);
		   toggleAbilityPostButton(SHOW);
		  };
		  img.onerror = function() {
                    //alert( "not a valid file: " + file.type);
		  };
		  img.src = _URL.createObjectURL(file);
		   
		   //alert(getCurrClass());      
		  }

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
		 
			$("#image-holder .img-row").append( "" );
                        
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
        
     // ramani
    $(document.body).on("change", "#imageFile1", function () {
		var cls = $(this).data("class");
        toggleAbilityPostButton(HIDE);
		addimg_filechange(cls, this, true);
        
	 });
         
         
      $(document.body).on('change', '.imageFile2' ,function(){  
        var cls = $(this).data("class");
        console.log(cls);
        addimg_filechange(cls, this, false);
        
	 });
         
	
	$("#imageFile1").change(function(){
		var demo = $("#imageFile1")[0].files;
		 var names = [];
	    for (var i = 0; i < demo.length; ++i) {
	       console.log(demo[i].naturalWidth);

	    }
	});

	var lastModified = [];
	var newct=0;
	function addimg_filechange(cls, obj, t){

		console.log(cls);
		var valde = $("#imgfilecount").val();
        document.getElementById("imgfilecount").value++;
       //Get count of selected files
	    var countFiles = $(obj)[0].files.length;	   
            if(t == true) {
                var formdata;
                formdata = new FormData($('form')[1]);
                var imgcounter=$("#counter").val(countFiles);
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
				 //loop for each file selected for uploaded.
				 for (var i = 0; i < countFiles; i++) {
					  file = obj.files[i];
					  console.log(file.width);
					  //console.log(file);
					  lastModified.push(file.lastModified);
					  //console.log("lastModified= " + file.lastModified);

					  //console.log(lastModified);
					  storedFiles.push(file);
					  	var reader = new FileReader();
						reader.onload = function (e) {
							var img = new Image();
							img.onload = function() {
								var curr_cls='';
								if(obj.width>obj.height){
									setCurrClass("himg");
									imgCls[i]="himg";
								} else if(obj.width<obj.height) {
									setCurrClass("vimg");
									imgCls[i]="vimg";
								} else {
									setCurrClass("himg");
									imgCls[i]="himg";
								}

								$("<img />", {
								"src": e.target.result,
								"class": "thumb-image "+imgCls[i],
								})
								.add("<a href='javascript:void(0)' class='removeclose' data-code='"+lastModified[newct]+"'><i class='fa fa-close' ></i></a>") 
								.wrapAll("<div class='uimg_holder'></div>")
								.parents()
								.prependTo(image_holder);
								toggleAbilityPostButton(SHOW);
								newct++; 
							};
							
							img.onerror = function() { //alert( "not a valid file: " + file.type);
							};
							img.src = _URL.createObjectURL(file); //alert(getCurrClass());     
						}
                    $("#image-holder").show();                              
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
					console.log('log' + cls);
					$(cls).append( "<div class='uimg_holder'><div class='fakeImageBox'>\n\<input type='file' class='imageFile2' data-class='"+cls+"' multiple='true'/>\n\<div class='add-imgbox'><i class='fa fa-plus'></i></div></div></div>" );  
					console.log('log' + cls);
				}
				/*$("#imageFile2").on('change', function (){
				document.getElementById('imgfilecount').value++;
				toggleAbilityPostButton(HIDE);
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
        
	 
      	
	 // ramani

	  $(document.body).on('change', '#imageFilepost' ,function(){ 
	   			storedFiles = [];
                toggleAbilityPostButton(HIDE);
				editaddimg_filechange(this, true);
	 });

      $(document.body).on('change', '.editmoreimage' ,function(){  
        
        editaddimg_filechange(this, false);
        
	 });
         
	function editaddimg_filechange(obj, t){
		
	    var countFiles = $(obj)[0].files.length;	   
            if(t == true) {
                var formdata;
                formdata = new FormData($('form')[1]);
                var imgcounter=$("#counter").val(countFiles);
                formdata.append('counter', imgcounter);
		
            }
	    var file, img; 
	    var _URL = window.URL || window.webkitURL;			  
	    var imgPath = $(obj)[0].value;
	    var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
	     var image_holder = $("#temp  #image-holder .img-row");
	    //image_holder.empty();

	    if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg" || extn == "tif" || extn == "webp") {
			if (typeof (FileReader) != "undefined") {
				
				var imgCls=[];
				 //loop for each file selected for uploaded.
				 for (var i = 0; i < countFiles; i++) {
					  file = obj.files[i];
					  //console.log(file);
					  lastModified.push(file.lastModified);
					  //console.log("lastModified= " + file.lastModified);

					  //console.log(lastModified);
					  storedFiles.push(file);
					  	var reader = new FileReader();
						reader.onload = function (e) {
							var img = new Image();
							img.onload = function() {
								var curr_cls='';
								if(obj.width>obj.height){
									setCurrClass("himg");
									imgCls[i]="himg";
								} else if(obj.width<obj.height) {
									setCurrClass("vimg");
									imgCls[i]="vimg";
								} else {
									setCurrClass("himg");
									imgCls[i]="himg";
								}

								$("<img />", {
								"src": e.target.result,
								"class": "thumb-image "+imgCls[i],
								})
								.add("<a href='javascript:void(0)' class='removeclose' data-code='"+lastModified[newct]+"'><i class='fa fa-close' ></i></a>") 
								.wrapAll("<div class='uimg_holder'></div>")
								.parent()
								.prependTo(image_holder);
								toggleAbilityPostButton(SHOW);
								newct++; 
							};
							
							img.onerror = function() { //alert( "not a valid file: " + file.type);
							};
							img.src = _URL.createObjectURL(file); //alert(getCurrClass());     
						}
                    $("#image-holder").show();                              
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
			
			if(t == true) {				
				$(image_holder).append( "<div class='uimg_holder'><div class='fakeImageBox'>\n\<input type='file' class='editmoreimage' multiple='true'/>\n\<div class='add-imgbox'><i class='fa fa-plus'></i></div></div></div>" );
			}		
		} else {
				BootstrapDialog.show({
				title: 'Invalid Files',
				message: 'Please upload jpg, gif, png, tif and webp image.'
				});
				$("#imageFile1").val("");
					   //return false;
	   }
	}

	
    
    function viewalbum(post_id)
    {
        if (post_id != '') {
            $.ajax({
                type: 'POST',
                url: '<?php echo Yii::$app->urlManager->createUrl(['userwall/viewalbumpics']); ?>',
                data: "post_id="+post_id,
                success: function (data) {
                    var result = $.parseJSON(data);
                    if(result['value'] === '1')
                    {
                        $(".picture-section").html(result['previewalbumimages']);
                    }
                    /*else if(result['value'] === '2')
                    {
                        $(".picture-section .album-row").html('No Post Found.');
                    }
                    else
                    {
                        $(".picture-section .album-row").html('No Images Found.');
                    }*/
                }
            });
        }
    }
    
    function viewprofilepics(u_id)
    {
        if (u_id != '') {
            $.ajax({
                type: 'POST',
                url: '<?php echo Yii::$app->urlManager->createUrl(['userwall/viewprofilepics']); ?>',
                data: "u_id="+u_id,
                success: function (data) {
                    var result = $.parseJSON(data);
                    if(result['value'] === '1')
                    {
                        $(".picture-section").html(result['previewalbumimages']);
                    }
                    /*else if(result['value'] === '2')
                    {
                        $(".picture-section .album-row").html('No Post Found.');
                    }
                    else
                    {
                        $(".picture-section .album-row").html('No Images Found.');
                    }*/
                }
            });
        }
    }
    function viewcoverpics(u_id)
    {
        if (u_id != '') {
            $.ajax({
                type: 'POST',
                url: '<?php echo Yii::$app->urlManager->createUrl(['userwall/viewcoverpics']); ?>',
                data: "u_id="+u_id,
                success: function (data) {
                    var result = $.parseJSON(data);
                    if(result['value'] === '1')
                    {
                        $(".picture-section").html(result['previewalbumimages']);
                    }
                    /*else if(result['value'] === '2')
                    {
                        $(".picture-section .album-row").html('No Post Found.');
                    }
                    else
                    {
                        $(".picture-section .album-row").html('No Images Found.');
                    }*/
                }
            });
        } 
    }
    function delete_image(imagename, image_name, post_id)
    {
        BootstrapDialog.show({
			size: BootstrapDialog.SIZE_SMALL,
            title: 'Delete Image',
            message: 'Are you sure to delete this image ?',
            buttons: [{
                        label: 'Yes',
                        action: function (dialogItself) {
                            if (image_name !== '' && post_id !== '') {
                                $.ajax({
                                    type: 'POST',
                                    //url: '/travbudcode/frontend/web/index.php?r=userwall%2Fdelete-image',
                                    url: '<?php echo Yii::$app->urlManager->createUrl(['userwall/delete-image']); ?>',
                                    data: "image_name=" + image_name + "&post_id=" + post_id,
                                    success: function (data)
                                    {

                                        var result = $.parseJSON(data);
                                        if(result['value'] === '1')
                                        {
                                            $("#imgbox_" + imagename).hide();
                                            dialogItself.close();
                                        }
                                    }
                                });
                            }
                        }
                    }, {
                        label: 'No',
                        action: function (dialogItself)
                        {
                            dialogItself.close();
                        }
                    }]
        });
    }
    function move_album_image(imagename, image_name, from_post_id, to_post_id)
    {
        BootstrapDialog.show({
            size: BootstrapDialog.SIZE_SMALL,
            title: 'Move Image',
            message: 'Are you sure to move this image ?',
            buttons: [{
                    label: 'Yes',
                    action: function (dialogItself) {
                            if (image_name !== '' && from_post_id !== '' && to_post_id !== '' ) {
                                    $.ajax({
                                            type: 'POST',
                                            url: '<?php echo Yii::$app->urlManager->createUrl(['userwall/move-album-image']); ?>',
                                            data: "image_name=" + image_name + "&from_post_id=" + from_post_id + "&to_post_id=" + to_post_id,
                                            success: function (data)
                                            {
                                                    var result = $.parseJSON(data);
                                                    if(result['value'] === '1')
                                                    {
                                                            $("#imgbox_" + imagename).hide();
                                                            dialogItself.close();
                                                    }
                                            }
                                    });
                            }
                    }
            }, {
                    label: 'No',
                    action: function (dialogItself)
                    {
                            dialogItself.close();
                    }
            }]
        });
    }
    function album_cover(uid, image_name, post_id)
    {
        BootstrapDialog.show({
                title: 'Album Cover',
                message: 'Are you sure to make this image as Album Cover ?',
                buttons: [{
                        label: 'Yes',
                        action: function (dialogItself) {
                                if (image_name !== '' && post_id !== '') {
                                        $.ajax({
                                                type: 'POST',
                                                url: '<?php echo Yii::$app->urlManager->createUrl(['userwall/album-cover']); ?>',
                                                data: "image_name=" + image_name + "&post_id=" + post_id,
                                                success: function (data)
                                                {
                                                        var result = $.parseJSON(data);
                                                        if(result['value'] === '1')
                                                        {
                                                            dialogItself.close();
                                                            load_data('photos',uid)
                                                        }
                                                }
                                        });
                                }
                        }
                }, {
                        label: 'No',
                        action: function (dialogItself)
                        {
                            dialogItself.close();
                        }
                }]
        });
    }
    /* END PAGE IMAGE POST SCRIPT */

    /* DRAWER SCRIPT FUNCTION */

    function clearActive() {

        $(".top-menu > li").each(function () {

            var target = $(this).children(".droparea");

            var isOpen = target.hasClass("active");

            if (isOpen) {
                target.removeClass("active");
            }

        });
    }
    function closeTopDrawer() {

        $(".top-menu > li").each(function () {

            var target = $(this).children(".droparea");


            if (target.children(".drawer").length > 0) {


                var isOpen = target.children(".drawer").css("display");

                if (isOpen != "none") {
                    target.children(".drawer").fadeOut(500);
                }
            }

        });
    }
    function closeAllDrawers(e) {
		

		$('.tags-added').html('');
		
        var trgt = $('.tarrow .drawer');

        if (!trgt.is(e.target) & trgt.has(e.target).length === 0) {
            $(".tarrow .drawer").fadeOut(500);
        }

        var trgt1 = $('.droparea .drawer');

        if (!trgt1.is(e.target) & trgt1.has(e.target).length === 0) {

            clearActive();
            $(".droparea .drawer").fadeOut(500);
        }

        var name = $(e).data("name");
        if(name != undefined || name != null || name != '') {
        	if(name == 'cancel_post') {
        		resetAllNewPostInputsAndOther();
        	}
        }
    }

    // Call When cancel post click ans reset all new post inputs ans div / spn etc...
    function resetAllNewPostInputsAndOther() {
    	$('.tags-added').html('');
        $('#taginput').val("");
        $('#customin1').val("");
        $('#customin3').val("");
        $('#customchk').prop('checked', false);
        $('.tags-added').html('');
        $('.select2-selection__rendered').html('');
        $('.addpost-tag').hide();
        $("#newPost").hide();
        $('#textInput').val("");
        $("#imageFile1").val("");
        $("#image-holder").hide();
        $(".img-row").html("");
        $('#displayImag').html("");
        $('#displayImag').hide();
        $(".previewLoading").hide();
        $(".previewImage").hide();
        $(".previewDesc").hide();
        $('#link_title').val("");
        $('#link_url').val("");
        $('#link_description').val("");
        $('#link_image').val("");
        $('#area').hide();
        $("#title").val("");
        $('.post-title').hide();
        $("#cur_loc").val("");
        $(".addpost-location").hide();
        $('#share_setting').val("Enable");
        $('#comment_setting').val("Enable");
        $("#imgfilecount").val(0);
        
	}

    function closeSearchDrawer(e) {

        var trgt = $('#'+e);

        if (!trgt.is(e.target) & trgt.has(e.target).length === 0) {
            trgt.fadeOut(300);
        }
    }
	function closeSearchSection(e) {
		var trgt = $('#sb-search');
        if (!trgt.is(e.target) & trgt.has(e.target).length === 0) {
            
			$("#searchfirst").hide();
			$(".sb-search-input").css("width", "0%");
			setTimeout(function () {
				$(".sb-search-input").css("background-color", "rgba(0,0,0,0)");
			}, 200);
			$(".sb-search").css("width", "0%");
			$(".sb-search").removeClass("opened");
        }

    }

    /* END DRAWER SCRIPT FUNCTION */
	
	/* DRAWER SCRIPT */

	function openLocPost(obj){
	
		var getParent=$(obj).parents(".toolbox");		
		getParent.find('.addpost-location').toggle(300);
	}


	function openLocTag(obj){
	
		var getParent=$(obj).parents(".toolbox");		
		getParent.find('.addpost-tag').toggle(300);
		getParent.find('.tags-added').show(300);
	}

	function openTitle(obj){
	
		var getParent=$(obj).parents(".toolbox");		
		getParent.find('.post-title').toggle("slow");
		
		var slideOut=getParent.find(".sliding-middle-out");
				
		setTimeout(function(){titleUnderline(slideOut);},800);
		
	}

	$(document.body).on('click', '.add-loc', function(e){

		openLocPost(this);
	});

	$(document.body).on('click', '.add-tag', function(e){
		openLocTag(this);
	});

	$(document.body).on('click', '.add-title', function(e){
		openTitle(this);
	});

	
    $(document).click(function (e) {

        closeAllDrawers(e);
        closeSearchDrawer(e);
		closeSearchSection(e);
	});
	$(document.body).click(function (e) {
		closeSearchSection(e);
		/*
		var trgt = $('.addpost-location');
		if (!trgt.is(e.target) & trgt.has(e.target).length === 0) {
			if(trgt.css("display")=="block"){
				trgt.fadeOut(300);
			}
		}
        */

    });

    /* END DRAWER SCRIPT */
	
	$( document ).on( 'keyup', '.search', function(e) {
 	            var id = $(this).attr('data-id');
				var vals = $(this).val();
                if (vals != '') {
                    data = 'key=' + vals;
                    $.ajax({
                        url: "<?php echo \Yii::$app->getUrlManager()->createUrl('site/search') ?>", //Server script to process data
                        type: 'GET',
                        data: data,
                        success: function (data) {


                            $("#"+id).html(data).show();
                        }
                    });
                } else {
                    closeSearchDrawer(id);
					closeSearchSection(e);
                }
        });
        
        $( document ).on( 'keyup', '.searchfriends', function(e) {
                var id = $(this).attr('data-id');
                var vals = $(this).val();
                var uwid = $("#userwallid").val();
                if (vals != '') {
                    data = 'key=' + vals + '&userwallid=' + uwid;
                    $.ajax({
                        url: "<?php echo \Yii::$app->getUrlManager()->createUrl('userwall/searchfriends') ?>", //Server script to process data
                        type: 'GET',
                        data: data,
                        success: function (data) {


                            $("#"+id).html(data).show();
                        }
                    });
                } else {
                    closeSearchDrawer(id);
					closeSearchSection(e);
                }
        });
	
		function UseData() {
			$.Watermark.HideAll();
			$.Watermark.ShowAll();
		}
   

        function isValidURL(url) {
            var RegExp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;

            if (RegExp.test(url)) {
                return true;
            } else {
                return false;
            }
        }
	
    $(document).ready(function () {

        /* PAGE IMAGE POST SCRIPT */

        $("#newPost").hide();
        setInterval(function () {
          //  var count = document.getElementById('hiddenCount').value;
          var count = $('#hiddenCount').val();
            $.ajax({
                url: '<?php echo Yii::$app->urlManager->createUrl(['site/new-post']); ?>',
                type: 'POST',
                data: {},
                success: function (response) {

                    var result = response;
                    var diff = parseInt(result) - parseInt(count); 
                    if ((result > count) && (count > 0))
                    {
                        $("#newPost").show();
                        if(parseInt(diff)>0)
                            $("#newPost").html('<a href="javascript:void(0);" onclick="showAll(' + result + ');">Show new updates (' + diff + ') </a>');
                      
                    }

                }
            });

        }, 20000);

        /* END PAGE IMAGE POST SCRIPT */


        /* SEARCH SCRIPT */
         
		/*
        $("#sb-search").click(function (e) {

            e.stopPropagation();

            closeAllDrawers(e);

            var hasClass = $(this).hasClass("opened");

            if (!hasClass) {
                $(".sb-search-input").css("width", "100%");
                $(".sb-search-input").css("background-color", "#fff");
                $(".sb-search").css("width", "100%");
                $(".sb-search").addClass("opened");
            } else {
                $(".sb-search-input").css("width", "0%");
                setTimeout(function () {
                    $(".sb-search-input").css("background-color", "rgba(0,0,0,0)");
                }, 200);
                $(".sb-search").css("width", "0%");
                $(".sb-search").removeClass("opened");
            }

        });
		*/
		/*
		$( document.body ).on('click', '#search', function() {
  
			$("#searchfirst").hide();
			$(".sb-search-input").css("width", "0%");
			setTimeout(function () {
				$(".sb-search-input").css("background-color", "rgba(0,0,0,0)");
			}, 200);
			$(".sb-search").css("width", "0%");
			$(".sb-search").removeClass("opened");
		
		});
		*/
	  
		$(".sb-icon-search").click(function (e) {
	
			//e.stopPropagation();
			
			var super_parent=$(this).parents(".sb-search");
		  
			var hasClass = super_parent.hasClass("opened");
						
            if (!hasClass) {
                $("#searchfirst").show();
				$(".sb-search-input").css("width", "100%");
                $(".sb-search-input").css("background-color", "#fff");
                $(".sb-search").css("width", "100%");
                $(".sb-search").addClass("opened");
            } else {
				$("#searchfirst").hide();
                $(".sb-search-input").css("width", "0%");
                setTimeout(function () {
                    $(".sb-search-input").css("background-color", "rgba(0,0,0,0)");
                }, 200);
                $(".sb-search").css("width", "0%");
                $(".sb-search").removeClass("opened");
            }
			
        });
		

        /* END SEARCH SCRIPT */

    });

    /* FRIEND SCRIPT */


    function delete_request(rid, from_id, to_id)
    {
        BootstrapDialog.show({
			size: BootstrapDialog.SIZE_SMALL,
            title: 'Delete Request',
            message: 'Are you sure to delete this request ?',
            buttons: [{
                    label: 'Yes',
                    action: function (dialogItself) {
                        $.ajax({
                            url: '<?php echo Yii::$app->urlManager->createUrl(['friend/delete-request']); ?>', //Server script to process data
                            type: 'POST',
                            data: 'from_id=' + from_id + '&to_id=' + to_id,
                            success: function (data) {

                                $('#request_' + rid).hide();
                                dialogItself.close();

                            }

                        });
                    }
                }, {
                    label: 'No',
                    action: function (dialogItself) {
                        dialogItself.close();
                    }
                }]
        });

    }
    function remove_tobe_friend_listing(fid)
    {
        $('#remove_' + fid).hide();
    }
    function acceptfriendrequest(from_id)
    {
        BootstrapDialog.show({
			size: BootstrapDialog.SIZE_SMALL,
            title: 'Accept Friend',
            message: 'Are you sure to accept this request ?',
            buttons: [{
                    label: 'Yes',
                    action: function (dialogItself) {
                        var login_user_id = '<?php echo $user_id; ?>';//$('#login_id').val();
                        $.ajax({
                            url: '<?php echo Yii::$app->urlManager->createUrl(['friend/accept-friend']); ?>', //Server script to process data
                            type: 'POST',
                            data: 'to_id=' + login_user_id + '&from_id=' + from_id,
                            success: function (data) {
                                //alert(data);
                                if(data)
                                {
                                    
                                    $('#accept_'+from_id).hide();
                                    $('#acceptmsg_'+from_id).html(data);
                                    $('#acceptmsg_'+from_id).show();
                                }
                                dialogItself.close();
                            }
                        });
                    }
                }, {
                    label: 'No',
                    action: function (dialogItself) {
                        dialogItself.close();
                    }
                }]
        });

    }
	
	function account_verify(t)
    {
		if(t == 2) {
			$(".notice1 .post_loadin_img").css("display","inline-block");			
		}
		else if(t == 1){
			$(".notice .post_loadin_img").css("display","inline-block");
		}
     
    var email = '<?= $session->get('email_id')?>';
 
  
    if(email != '')
    {
        $.ajax({
            type: 'POST',
            url: '<?php echo Yii::$app->urlManager->createUrl(['site/verify']); ?>',
            data: "email=" + email,
            success: function(data){
               
                 if(data)
                 {
					if(t == 2) {
						$(".notice1 .post_loadin_img").css("display","none");
                     $('.fp-success').css('display','inline-block').html("Mail Sent Successfully Please Verify Your Account From Your Email Id").fadeIn(3000).delay(3000).fadeOut(3000);
					} else {
						$(".notice .post_loadin_img").css("display","none");
						$('.success-note').fadeIn(3000).delay(3000).fadeOut(3000);
					}
                 }
                 else
                 {
					 if(t == 2) {
						
                     $('.fp-error').css('display','inline-block').html("Oops..!! Somthing Went Wrong").fadeIn(3000).delay(3000).fadeOut(3000);
					}
					else{
						$('.error-note').fadeIn(3000).delay(3000).fadeOut(3000);
					}
					
                     
                 }
             }
        });
        }
    }
	

    function addfriend(id)
    {
		var status = '<?php echo $status; ?>';
		if(status == 0){
			jq.magnificPopup.open({
			  items: {
				src: '#notverifyemail'
			  },
			  type: 'inline'
			});
			return false;
			exit;
		}	
		
        var login_user_id = $('#login_id').val();
        $.ajax({
            url: '<?php echo Yii::$app->urlManager->createUrl(['friend/add-friend']); ?>', //Server script to process data
            type: 'POST',
            data: 'to_id=' + id + '&from_id=' + login_user_id,
            success: function (data) {
                //alert(data);
                if(data)
                {
                    $('#people_'+id).hide();
                    $('#sendmsg_'+id).html(data);
                    $('#sendmsg_'+id).show();
                    
                     $('#addfriend_wall').hide();
                }   
            }
        });
    }

    function unfollow_friend(fid,pid)
    {
        BootstrapDialog.show({
			size: BootstrapDialog.SIZE_SMALL,
            title: 'Unfollow Friend',
            message: 'Are you sure to unfollow friend ?',
            buttons: [{
                    label: 'Yes',
                    action: function (dialogItself) {
                        if (fid != '') {
                            $.ajax({
                                type: 'POST',
                                url: '<?php echo Yii::$app->urlManager->createUrl(['site/unfollowfriend']); ?>',
                                data: "fid=" + fid,
                                success: function (data) {
                                    if (data)
                                    {
                                        //$("#post-status-list").load("<?php echo Yii::$app->urlManager->createUrl(['site/ajax-status']); ?>");
                                        //$('#hide_'+pid).hide();
                                        $('.post_user_id_'+fid).hide();
                                      
                                        dialogItself.close();
                                    } else
                                    {
                                    }
                                }
                            });
                        } else {
                        }
                    }
                }, {
                    label: 'No',
                    action: function (dialogItself) {
                        dialogItself.close();
                    }
                }]
        });

    }

    function mute_friend(fid)
    {
        BootstrapDialog.show({
			size: BootstrapDialog.SIZE_SMALL,
            title: 'Mute Friend',
            message: 'Are you sure to mute friend ?',
            buttons: [{
                    label: 'Yes',
                    action: function (dialogItself) {
                        if (fid != '') {
                            $.ajax({
                                type: 'POST',
                                url: '<?php echo Yii::$app->urlManager->createUrl(['site/mutefriend']); ?>',
                                data: "fid=" + fid,
                                success: function (data) {
                                    if (data)
                                    {
                                     //   $("#post-status-list").load("<?php echo Yii::$app->urlManager->createUrl(['site/ajax-status']); ?>");
                                        dialogItself.close();
                                    } else
                                    {
                                    }
                                }
                            });
                        } else {
                        }
                    }
                }, {
                    label: 'No',
                    action: function (dialogItself) {
                        dialogItself.close();
                    }
                }]
        });
        
    }

    /* END FRIEND SCRIPT */

    /* SHARE SCRIPT */

    function sharenow(pid)
    {
        BootstrapDialog.show({
			size: BootstrapDialog.SIZE_SMALL,
            title: 'Share Now',
            message: 'Are you sure to share this post ?',
            buttons: [{
                    label: 'Yes',
                    action: function (dialogItself) {
                        if (pid != '') {
                            $.ajax({
                                type: 'POST',
                                url: '<?php echo Yii::$app->urlManager->createUrl(['site/sharenowwithfriends']); ?>',
                                data: "shareid=" + pid,
                                success: function (data) {
                                  //  alert(data);
                                    if (data)
                                    {
                                       // $("#post-status-list").load("<?php echo Yii::$app->urlManager->createUrl(['site/ajax-status']); ?>");
                                        load_last_post(data);
                                        $("#frndid").val('');
                                        dialogItself.close();
                                    } else
                                    {
                                        dialogItself.close();
                                    }
                                }
                            });
                        } else {
                               dialogItself.close();
                        }
                    }
                }, {
                    label: 'No',
                    action: function (dialogItself) {
                        dialogItself.close();
                    }
                }]
        });

    }
    /* END SHARE SCRIPT */

    /* SECURITY SELECT SCRIPT */

    function removeSelOptions(e) {

        $("ul#" + e + " > li").each(function ()
        {
            $(this).removeClass("selected");
        });
    }
	
	function setQuestionIcon(test) {

        var ulId = $(test).parent().parent().attr("id");

        var pClass = $(test).parent().attr("class");
        pClass = pClass.toLowerCase();
        pClass = pClass.replace('selected', '');
        pClass = pClass.replace('sel-', '');

        var setOption = $(test).parent().attr("class");
        setOption = setOption.replace('selected', '');
        setOption = setOption.replace('sel-', '');

        removeSelOptions($(test).parent().parent().attr("id"));
        $(test).parent().addClass("selected");

        pClass = pClass.replace(/-/g, ' ');// remove hyphen

        var ic = $(test).html().toLowerCase();

        ic = ic.replace(pClass, '');

        setOption = setOption.replace(/-/g, ' ');// remove hyphen
		
        //ic = ic + setOption;

        ic = ic + "<span class='caret'></span>";

        var selSelector = ".custom-select-" + ulId;
        $(selSelector).html(ic);

        var selSpan = ".thisSecurity-" + ulId;
        $(selSpan).html(setOption);
    }

    function setSelectIcon(test) {

        var ulId = $(test).parent().parent().attr("id");

        var pClass = $(test).parent().attr("class");
        pClass = pClass.toLowerCase();
        pClass = pClass.replace('selected', '');
        pClass = pClass.replace('sel-', '');

        var setOption = $(test).parent().attr("class");
        setOption = setOption.replace('selected', '');
        setOption = setOption.replace('sel-', '');

        removeSelOptions($(test).parent().parent().attr("id"));
        $(test).parent().addClass("selected");

        pClass = pClass.replace(/-/g, ' ');// remove hyphen

        var ic = $(test).html().toLowerCase();

        ic = ic.replace(pClass, '');

        setOption = setOption.replace(/-/g, ' ');// remove hyphen

        ic = ic + setOption;

        ic = ic + "<span class='caret'></span>";

        var selSelector = ".custom-select-" + ulId;
        $(selSelector).html(ic);

        var selSpan = ".thisSecurity-" + ulId;
        $(selSpan).html(setOption);
    }
	
    function setSecuritySelect(test, postvalue) {

        var ulId = $(test).parent().parent().attr("id");

        var pClass = $(test).parent().attr("class");
        pClass = pClass.toLowerCase();
        pClass = pClass.replace('selected', '');
        pClass = pClass.replace('sel-', '');

        removeSelOptions($(test).parent().parent().attr("id"));

        var selId = $(test).parent().parent().attr("id");
        $(test).parent().addClass("selected");
        $("#post_privacy").val(postvalue);

        var ic = $(test).html();

        ic = ic + "<span class='caret'></span>";

        var selSelector = ".custom-select-" + ulId;
        $("#" + selId).parent().children(selSelector).html(ic);

    }
    function sendSelId(selId) {
        //alert(selId);
        $(".modalId").html("");
        $(".modalId").html(selId);
    }

    function setCustomSecurity(test) {

        var pClass = $(test).parent().children(".modalId");
        // alert(pClass.html());

        var ic = "<span class='glyphicon glyphicon-cog'></span>  Custom" + "<span class='caret'></span>";

        removeSelOptions(pClass.html());

        $("#" + pClass.html()).parent().children(".custom-select").html(ic);
    }

    /* END SECURITY SELECT SCRIPT */
	
	function close_cimg_preview(parentid, fromWhere){
		
		if(fromWhere == "comment"){			
			$('#image-holder-comment-'+parentid).hide();	
		}
		else{
			$('#image-holder-reply-'+parentid).hide();	
		}
		
	}
			
	$('.showPass').mousedown(function(){
		
		var getParent = $(this).parent();
		
		var obj = getParent.find("input").first();						
		obj.attr("type","text");

		
	}).mouseup(function() {
		
		var getParent = $(this).parent();
		
		var obj = getParent.find("input").first();				
		obj.attr("type","password");
		
	});

			
    /* page loader */

    $('body').show();
    setTimeout(function () {
        NProgress.start();       
    }, 1000);
	
    setTimeout(function () {
        NProgress.done();
        $('.fade').removeClass('out');
    }, 3000);

    /* end page loader */
	
	/* set FB page min-height */
	
	function setFbBodyHeight(){
		
		$(".userwall").css("min-height","0");
		var wheight=$(window).height();		
		var bheight=$('body').height();
		var navheight=$(".left-nav").height();
		var hheight=$(".np-header02.fb-page").height();
		
		if(bheight < wheight){
			
			$(".userwall").css("min-height",wheight -20);
		}
		
		/*
		if($('.np-fotter').hasClass("AbsFbFooter")){$('.np-fotter').removeClass("AbsFbFooter");}
		$('.inner-body-content.fb-page .page-wrapper').css("height","");
		
		var wheight=$(window).height();		
		var bheight=$('body').height();
		var cheight=$(".chatbox").height();
		var fheight=$(".np-fotter").height();
		var content_height=$(".inner-body-content.fb-page .page-wrapper").height();
		
		if( bheight < wheight){			
			
			if( content_height < cheight){
				if(wheight < cheight){
					if(bheight < cheight){
						if(wheight < (cheight+fheight+10)){
							$('.inner-body-content.fb-page .page-wrapper').css("height",cheight + fheight+10);
							$('.np-fotter').addClass("AbsFbFooter");
						}
						else{
							$('.inner-body-content.fb-page .page-wrapper').css("height",cheight);
							$('.np-fotter').addClass("AbsFbFooter");
						}
					}
				}
				else{					
					if(wheight < (cheight+fheight+10)){
						$('.inner-body-content.fb-page .page-wrapper').css("height",wheight + fheight + 10);					
						$('.np-fotter').addClass("AbsFbFooter");
					}
					else{
						$('.inner-body-content.fb-page .page-wrapper').css("height",wheight - fheight - 10);					
						$('.np-fotter').addClass("AbsFbFooter");
					}
				}
			}
		}
		*/
	}
	$(window).resize(function(){
		setTimeout(function(){setFbBodyHeight();},500);
	});
		
    /* end set FB page min-height */
	
	/* sliding middle animation - inputs */
	function clearUnderline(){
		$(".sliding-middle-out").each(function(){
			if($(this).hasClass("focused")) $(this).removeClass("focused");
		});
	}
	function titleUnderline(e){
		
		clearUnderline();
		$(e).toggleClass("focused");
		$(e).children("input[type='text']").focus();
	}
	$(document).ready(function(){
		setFbBodyHeight();
		
		$(".sliding-middle-out").click(function(){			
			titleUnderline(this);			
		});
		
	});
	
	/* end sliding middle animation - inputs */
	
    /* show/hide comment action */
	
	$(document).ready(function(){
		
		if($(".tb-user-post-bottom ul.dropdown-menu > li.sel-friends a").length > 0){
			var selectOption=$(".tb-user-post-bottom ul.dropdown-menu > li.sel-friends a");		
			//setSecuritySelect(selectOption,'Friends');
		}
		$(".outer").hover(
			function() {
				// Called when the mouse enters the element
				
			},
			function() {
				// Called when the mouse leaves the element
			}
		 );

		 $("body *").each(function(){
			
			if($(this).prop("rel")){
				
				var rel=$(this).prop("rel");
				
				if(rel.indexOf("prettyPhoto") != -1){
					jq("area[rel^='prettyPhoto']").prettyPhoto();
					jq(".gallery a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'normal',theme:'light_square',social_tools: ' '});
				}
				
			}
		 });
		
	});
    /* end show/hide comment action */
    
	/* Settings Side Menu */
	
	$(document).ready(function($) {
		
		$("#sidebar-menu ul li.has_sub a.mainlink").click(function(){
		
			var thisParent=$(this).parent("li");
			
			//if((isEnlarged() && !isDesktop()) || isDesktop() || isMobile()){
								
				//if(!thisParent.hasClass("active")){
					
					if(thisParent.hasClass("opened"))
					{
						thisParent.children(".submenu").slideUp(300);
						thisParent.removeClass("opened");
					}
					else{
						closeAllExpandedMenu(thisParent);
						thisParent.children(".submenu").slideDown(300);
						thisParent.addClass("opened");
					}
				//}
			//}
		
		});
		function closeAllExpandedMenu(e){
			
			var thisParent=e;

			$("#sidebar-menu ul li").each(function(){

				var regLi=$(this).parent("li");
			
				if($(this).hasClass("has_sub")){
					
					if($(this).hasClass("opened")){
						
						$(this).children(".submenu").slideUp(300);
						$(this).removeClass("opened");
																						
					}							
				}
			});
			
		}
		  
	});
	
	/* End Settings Side Menu */
        
        $(document).ready(function()
        {
            $(".disable_share").click(function(){
                if($("#share_setting").val() == 'Enable')
                {
                    $(".dis_share").show();
                    $("#share_setting").val('Disable');
                }
                else
                {
                    $(".dis_share").hide();
                    $("#share_setting").val('Enable');
                }
            });
            $(".disable_comment").click(function(){
                if($("#comment_setting").val() == 'Enable')
                {
                    $(".dis_comment").show();
                    $("#comment_setting").val('Disable');
                }
                else
                {
                    $(".dis_comment").hide();
                    $("#comment_setting").val('Enable');
                }
            });
            $(".cancel_post").click(function(e){
                $('.newpost #textInput').val("");
                $(".newpost #imageFile1").val("");
                $(".newpost #image-holder").hide();
                $('.newpost #displayImag').html("");
                $('.newpost #displayImag').hide();
                $(".newpost .preview_wrap").hide();
                $('.newpost #link_title').val("");
                $('.newpost #link_url').val("");
                $('.newpost #link_description').val("");
                $('.newpost #link_image').val("");
                $('.newpost #area').hide();
                $(".newpost #title").val("");
                $('.newpost .post-title').hide();
                $(".newpost #cur_loc").val("");
                $(".newpost .addpost-location").hide("slow");
                $(".newpost .addpost-tag").hide("slow");
                $("#newPost").hide();
                $(".dis_share").hide();
                $("#share_setting").val('Enable');
                $(".dis_comment").hide();
				$("#comment_setting").val('Enable');
                toggleAbilityPostButton(HIDE);
            });
	});
        
        $("#imagephoto").on('change', function () {
	var formdata;
        formdata = new FormData($('form')[1]);
        var ofile = document.getElementById('imagephoto').files;
        formdata.append("imagephoto", ofile);
            $.ajax({
                            url: '<?php echo Yii::$app->urlManager->createUrl(['site/uploadphotographypics']); ?>',
                            type: 'POST',
							data:formdata,
                            processData: false,
                            contentType: false,
                            success: function(data)
                            {
                                var result = $.parseJSON(data);
                                if(result['value'] === '1')
                                {
                                    $(".uploadimages").hide();
                                    $(".imagespreview").html(result['previewphotographyimages']);
                                }
                            }
                    });
    });
	
	/* fix for images */
	
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

		var imgholder_count=0;	
		var pcount=0;
		
		$("#post-status-list .panel-shadow").each(function(){
			pcount++;
			//alert($(this).find(".pimg-holder").length);
			//if(pcount > 3 && pcount<5){
				if($(this).find(".pimg-holder").length > 0 ){				
					imgholder_count++;
					
					//alert(imgholder_count);
					$(this).find(".pimg-box").each(function(e, v){
						
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
	$(document).ready(function(){
			
		setTimeout(function(){fixImageUI();},400);
		
	});

	/* end fix for images */
	
	/* scroll to top */	
	$(document).ready(function(){
		$(".scrollup").hide();				
		$(".addpost-sc").hide();				
	});
	$(window).scroll(function() {
		//clearTimeout($.data(this, 'scrollTimer'));
		//$.data(this, 'scrollTimer', setTimeout(function() {
			// do something
			var fromTop=$(window).scrollTop();
			var winh=$(window).height();
			
			if(fromTop > (winh/2)){
				$(".scrollup").show();								
				$(".addpost-sc").show();								
			}
			else{
				$(".scrollup").hide();
				$(".addpost-sc").hide();
			}
			
			if(fromTop > 0){				
				$(".unreg-notice").addClass("fixed-top");
			}
			else{				
				$(".unreg-notice").removeClass("fixed-top");
			}

		//}, 250));
	});
	
	/* end scroll to top */	
	
	/* page loader */	
	/*
	$(document).ready(function(){
		
		setTimeout(function(){
			
			$("#loader-wrapper").fadeOut(400);
			
		},5000);
	});
	
	/* end page loader */	
	
	$(document).ready(function(){
		
		setTimeout(function(){$('body').addClass("loaded");},1000);
		
		/* set border - background for banner thumb strip */
		//$("#carousel.flexslider").css("position:relative;");
	});

	/* START right click context menu */	
	
	var jq = $.noConflict();
	
	function hideAllPopupOptions(which){
		jq("#change-modal-pp .section").hide();
		if(which=="change-pp"){
			jq("#change-modal-pp .change-pp .section").hide();
		}
		else{
			jq("#change-modal-pp .change-cover .section").hide();
		}
		
	}
	
	function closeAllPopupOptions(which,option){
		if(which=="change-pp"){
			jq("#change-modal-pp .change-pp").show();			
			jq("#change-modal-pp .change-cover").hide();			
		}		
		else{
			jq("#change-modal-pp .change-pp").hide();
			jq("#change-modal-pp .change-cover").show();			
		}
		hideAllPopupOptions(which);
		var option_array=["upload","camera","gallery"];
		for(var i=0;i<option_array.length;i++){
			var thisOption=option_array[i];
			var selector="."+option;
			
			if(thisOption==option){				
				jq("#change-modal-pp ."+which).find(selector).show();
			}
		}	
	}
	
	jq(function() {
		jq.contextMenu({
			selector: '.context-menu-one', 
			callback: function(key, options) {
				var m = "clicked: " + key;
				window.console && console.log(m) || alert(m); 
			},
			items: {
				"edit": {name: "Edit", icon: "edit"},
				"cut": {name: "Cut", icon: "cut"},
			   copy: {name: "Copy", icon: "copy"},
				"paste": {name: "Paste", icon: "paste"},
				"delete": {name: "Delete", icon: "delete"},
				"sep1": "---------",
				"quit": {name: "Quit", icon: function(){
					return 'context-menu-icon context-menu-icon-quit';
				}}
			}
		});

		jq('.context-menu-one').on('click', function(e){
			console.log('clicked', this);
		})    
	});
	
	jq(function() {
		jq.contextMenu({
			selector: '.pp-change', 
			callback: function(key, options) {
				var m = "clicked: " + key;
				//window.console && console.log(m) || alert(m); 
				jq.magnificPopup.open({
					items:{
						src:'#change-modal-pp'
					},
					type: 'inline',
					preloader: false,
					//focus: '#username',
					modal: true
				});
				
				closeAllPopupOptions("change-pp",key);
				if(key=="camera"){
					jq("#change-modal-pp .pp-cover").find("camera").show();
				}
				if(key=="upload"){
					jq("#change-modal-pp .pp-cover").find("upload").show();
				}
				if(key=="gallery"){
					jq("#change-modal-pp .pp-cover").find("gallery").show();
				}
			},
			items: {
				"upload": {name: "Upload", icon: "file"},
				"camera": {name: "Take Photo", icon: "camera"},
			   "gallery": {name: "Choose From My Photos", icon: "gallery"},
				"reposition": {name: "Reposition", icon: "reposition"},
				"remove": {name: "Remove", icon: "remove"}
			}
		});

		jq('.pp-change').on('click', function(e){
			//console.log('clicked', this);
		})    
	});
	
	jq(function() {
		jq.contextMenu({
			selector: '.cover-change', 
			callback: function(key, options) {
				var m = "clicked: " + key;
				//window.console && console.log(m) || alert(m); 
				jq.magnificPopup.open({
					items:{
						src:'#change-modal-pp'
					},
					type: 'inline',
					preloader: false,
					//focus: '#username',
					modal: true
				});
				
				closeAllPopupOptions("change-cover",key);
				if(key=="camera"){
					jq("#change-modal-pp .change-cover").find("camera").show();
				}
				if(key=="upload"){
					jq("#change-modal-pp .change-cover").find("upload").show();
				}
				if(key=="gallery"){
					jq("#change-modal-pp .change-cover").find("gallery").show();
				}
				
			},
			items: {
				"upload": {name: "Upload", icon: "file"},
				"camera": {name: "Take Photo", icon: "camera"},
			   "gallery": {name: "Choose From My Photos", icon: "gallery"},
				"reposition": {name: "Reposition", icon: "reposition"},
				"remove": {name: "Remove", icon: "remove"}
			}
		}); 

		jq('.cover-change').on('click', function(e){
			//console.log('clicked', this);
		})    
	});
	
	/* END right click context menu */	
	
	/* START cover photo slider */	
	
	/*
	function initializeCoverSlider(w_width){
		
		if(w_width < 1024){
			jq('#carousel').flexslider({
				animation: "slide",
				controlNav: false,
				animationLoop: false,
				slideshow: false,
				itemWidth: 130,
				itemMargin: 5,
				asNavFor: '#slider'
			  });

			  jq('#slider').flexslider({
				animation: "slide",
				controlNav: false,
				animationLoop: false,
				slideshow: false,
				sync: "#carousel",
				start: function(slider){
				  $('body').removeClass('loading');
				}
			  });
		}
		else if(w_width < 1200){
			jq('#carousel').flexslider({
				animation: "slide",
				controlNav: false,
				animationLoop: false,
				slideshow: false,
				itemWidth: 150,
				itemMargin: 5,
				asNavFor: '#slider'
			  });

			  jq('#slider').flexslider({
				animation: "slide",
				controlNav: false,
				animationLoop: false,
				slideshow: false,
				sync: "#carousel",
				start: function(slider){
				  $('body').removeClass('loading');
				}
			  });
		}
		else{
		  jq('#carousel').flexslider({
			animation: "slide",
			controlNav: false,
			animationLoop: false,
			slideshow: false,
			itemWidth: 210,
			itemMargin: 5,
			asNavFor: '#slider'
		  });

		  jq('#slider').flexslider({
			animation: "slide",
			controlNav: false,
			animationLoop: false,
			slideshow: false,
			sync: "#carousel",
			start: function(slider){
			  $('body').removeClass('loading');
			}
		  });
		}
		
	}


	jq(window).load(function(){
		var w_width=jq(window).width();		
		initializeCoverSlider(w_width);	 
	});
	var resizeId;
	jq(window).resize(function() {		
		clearTimeout(resizeId);
		resizeId = setTimeout(doneResizing, 300);
	});
	function doneResizing(){
		
		//var w_width=jq(window).width();		
		//initializeCoverSlider(w_width);
		var slider1 = jq('.slider #carousel').data('flexslider');   
		slider1.resize();
		
		var slider2 = jq('.slider #slider').data('flexslider');   
		slider2.resize();
	}
	*/
	/* END cover photo slider */	
	function theme_color(color){
		
		if(color != '') {
			var link = 'color='+color;
			$.ajax({
				url: '<?php echo Yii::$app->urlManager->createUrl(['site/theme-change']); ?>',  //Server script to process data
				type: 'GET',
				data: link,
				async:false,        
				processData: false,
				contentType: false,
				success: function(data) {
								
				},
				 error: function(res) {
				 }  
			});
		}
	}
</script>

<style>
    body{
        background-color:#eaebef;
    }
    .morecontent span{
	display: none;
    }
</style>