<div class="modal-title graytitle clearfix">										
		<a href="javascript:void(0)" class="popup-modal-dismiss popup-modal-close close-top"><i class="fa fa-close"></i></a>
	</div>
	<div class="tb-panel-body toolbox">   
		<div class="modal-detail">		
			
			<div class="post_loadin_img"></div>
			<div class="newpost-topbar">
				<div class="post-title">
					<div class="tb-user-post-middle">											
						<div class="sliding-middle-out full-width">											
							<input type="text" placeholder="Title of this post" id="title"/>
						</div>
					</div>
				</div>
				<div class="tarrow dotarrow">
					<a href="javascript:void(0)" class="alink">
						<span class="more-option"><svg height="100%" width="100%" viewBox="0 0 50 50"><path class="Ce1Y1c" d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg></a></span>&nbsp;
					<div class="drawer">

						<ul class="sort-ul">
							<li class="disable_share"><span class="dis_share check-option">✓</span><a href="javascript:void(0)">Disable Sharing</a></li>																		
							<li class="disable_comment"><span class="dis_comment check-option">✓</span><a href="javascript:void(0)">Disable Comments</a></li>																		
							<li class="cancel_post"><a href="javascript:void(0)"  data-name="cancel_post" onclick="closeAllDrawers(this)">Cancel Post</a></li>																		
						</ul>	
					</div>
				</div>
			</div>
			
			<div class="post-holder">
			
				<div class="postarea">
					<div class="post-pic">
						<img src="<?= $login_img;?>" class="img-responsive" alt="user-photo">
					</div>
					<div class="post-desc">								
						<div class="tb-user-post-middle">												

							<textarea rows="2" title="Add a comment" class="form-control textInput" id="textInput" name="textInput" onclick="displaymenus()"   placeholder="<?= ucfirst($result['fname']); ?>, what's new?"></textarea>           

						</div>	
					</div>
					<div style="clear: both;"></div>
					<!--<div>
						<div class='tagcontent-area'>
					
						</div>
						<div style="clear: both;"></div>		
						<div class='taginpt-area'>
							<input type="text" id="taginput" name="taginput" class="js-example-theme-multiple" style="width: 100%;"/>
						</div>
					</div>-->										
				</div>
				<div id="popuppostgenerate">
				<div id="image-holder">
					<div class="img-row">

					</div> 
				</div>
				</div>
				<div style="clear: both;"></div>
				
				<div class="tags-added"></div>
				<div id="tag" class="addpost-tag">
					
					<span class="ltitle">With</span>
					<div class="tag-holder">
						<select id="taginput" class="taginput" placeholder="Who are with you?" multiple="multiple"></select>		
					</div>
				</div>
				<div style="clear: both;"></div>
				<div id="area" class="addpost-location">
					<span class="ltitle">At</span>
					<div class="loc-holder">
						<input type="text" name="current_location" class="getplacelocation" id="cur_loc" placeholder="Where are you?" />
					</div>
				</div>						
			</div>
			
			<input type="text" name="url" size="64" id="url"  style="display: none"/>
			<input type="button" name="attach" value="Attach" id="mark" style="display: none" />
			<form enctype="multipart/form-data" name="imageForm">
				
				<div class="tb-user-post-bottom clearfix">
					<div class="tb-user-icon" style="display:none"> 
						<label class="myLabel">
							<!-- uplaod image -->
							<input type="file" id="imageFile1" name="imageFile1[]"  data-class="#popuppostgenerate #image-holder .img-row" required="" multiple="true"/>
							<input type="hidden" id="counter" name="counter">
							<span aria-hidden="true" class="glyphicon glyphicon-camera tb-icon-fix001"></span>
						</label>
						<a href="javascript:void(0)" class="tb-icon-fix001 add-tag"><span aria-hidden="true"  class="glyphicon glyphicon-user"></span></a>

						<a href="javascript:void(0)" class="tb-icon-fix001 add-loc"><span aria-hidden="true" class="glyphicon glyphicon-map-marker"></span></a>
						<a href="javascript:void(0)" class="tb-icon-fix001 add-title"><span aria-hidden="true" class="glyphicon glyphicon-text-size"></span></a> 
					</div>
						
					<div class="user-post fb-btnholder">
						<div class="dropdown tb-user-post">

							<button aria-expanded="false" data-toggle="dropdown" type="button" class="btn btn-default dropdown-toggle btn-sm custom-select custom-select-posting-security"><span class="glyphicon glyphicon-<?= $post_dropdown_class ?>"></span> <?= $my_post_view_status ?> <span class="caret"></span></button>

							<ul class="dropdown-menu" id="posting-security">
								<li class="sel-private"><a href="javascript:void(0)" onClick="setSecuritySelect(this, 'Private')"><span class="glyphicon glyphicon-lock"></span> Private</a></li>
								<li class="divider"></li>
								<li class="sel-friends"><a href="javascript:void(0)" onClick="setSecuritySelect(this, 'Friends')"><span class="glyphicon glyphicon-user"></span> Friends</a></li>
								<li class="divider"></li>
								<li class="sel-public"><a href="javascript:void(0)" onClick="setSecuritySelect(this, 'Public')"><span class="glyphicon glyphicon-globe"></span> Public</a></li>
								<li class="divider"></li>
								<li class="sel-custom"><a href="#custom-share" class="popup-modal" onClick="sendSelId('posting-security')"><span class="glyphicon glyphicon-cog"></span> Custom</a></li>
							</ul>
																			<input type="hidden" name="imgfilecount" id="imgfilecount" value="0" />
																			<input type="hidden" name="post_privacy" id="post_privacy" value="<?= $my_post_view_status ?>"/>
																			<input type="hidden" name="share_setting" id="share_setting" value="Enable"/>
							<input type="hidden" name="comment_setting" id="comment_setting" value="Enable"/>
							<input type="hidden" name="link_title" id="link_title" />
							<input type="hidden" name="link_url" id="link_url" />
							<input type="hidden" name="link_description" id="link_description" />
							<input type="hidden" name="link_image" id="link_image" />

							<button class="btn btn-primary btn-sm ml-5"  onclick="postStatus();" type="button" name="post" id="post"><span class="glyphicon glyphicon-send"></span>Post</button>

						</div>
					</div>
				</div>
			</form>
		
			
		</div>

	</div>
<style>
.pac-container
{
    z-index: 1051 !important;
}
</style>
<!--<script type="text/javascript">
    google.maps.event.addDomListener(window, 'load', function () {
        new google.maps.places.Autocomplete(document.getElementById('popuplocation'));
    });
    function openLocPostPopup(obj)
    {
        var getParent=$(obj).parents(".popup-post");		
        getParent.find('.addpost-location').toggle(300);
    }


    function openLocTagPopup(obj)
    {
        var getParent=$(obj).parents(".popup-post");		
        getParent.find('.addpost-tag').toggle(300);
        getParent.find('.tags-added').show(300);
    }

    function openTitlePopup(obj)
    {
        var getParent=$(obj).parents(".popup-post");		
        getParent.find('.post-title').toggle("slow");
        var slideOut=getParent.find(".sliding-middle-out");
        setTimeout(function(){titleUnderline(slideOut);},800);
    }

    jq(".popup-add-loc").click(function(e){
        openLocPostPopup(this);
    });

    jq(".popup-add-tag").click(function(e){
        openLocTagPopup(this);
    });

    jq(".popup-add-title").click(function(e){
        openTitlePopup(this);
    });

    jq("#imageFile12").on('change', function () {
        addimg_filechange(this, true);
     });
</script>-->