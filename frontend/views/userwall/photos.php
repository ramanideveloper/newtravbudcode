<?php
include('includes/header-wall.php');
use yii\widgets\ActiveForm;
use frontend\models\Friend;
use yii\helpers\Url;
use frontend\models\PostForm;
$session = Yii::$app->session;
$user_id = $session->get('user_id');  

$posts = PostForm::getUserPost($user_id);
$albums = PostForm::getAlbums($user_id);
//echo '<pre/>';print_r($albums);

?>
<div class="container-fluid clearfix inner-fix01 userwall">
     <div class="row fb-pagecontent sm-fix">
		 <div  class="user_data">
                     <div class="col-lg-8 col-md-8 col-sm-8 secondcol data-section-about">
				
                        <?php $form = ActiveForm::begin(['options' => ['method' => 'post','enctype'=>'multipart/form-data']]) ?>

                            <div class="form-group">

                             <input type="text" class="form-control" name="title" placeholder="Untitled Album" name="album_title"> 
                            </div>
                            <div class="form-group">

                           <input type="text" class="form-control" name="description" placeholder="Say Somthing about this" name="album_description"> 
                            </div>
                            <div class="form-group">

                           <input type="text" class="form-control getplacelocation" name="place" id="autocomplete" onFocus="geolocate()" placeholder="Where were this taken?" name="album_place"> 
                            </div>
                         <div id="image-holder"></div>
                            <div class="form-group">          
                                      <input type="file" id="imageFile1" name="imageFile1[]" required="" multiple="true"/>
                            </div>          
                            <div class='input-group date' id='datetimepicker2' onkeydown = "return false;">
                         <input type='text' class="form-control" id='datetimepicker' placeholder="date of image"  name="album_img_date"/> 
                        
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar " style="color:#0071BD;"></i></span>
                        </div>

                            <div class="form-group" style="margin-bottom:2px;">

                                <input type="submit" class="home-submit" value="Add Album" onclick="addalbum()">
                            </div>
                   <?php ActiveForm::end() ?> 
			<div class="photos">
                                 <?php 
                                         if(count($albums)>0){
                                                 //$ctr = 0;
                                                 ?>
                                                <h3>Album List</h3>
                                                 <div class="photos-row">
                                                         <?php 
                                                         foreach($albums as $album){
                                                                 if(isset($album['image']) && !empty($album['image'])){
                                                                 $eximgs = explode(',',$album['image'],-1);

                                                                 //if(!empty($post['image']) && empty($post['post_text'])) {
                                                                 //if(!empty($post['image']) && empty($post['post_text']) && $ctr < 14) {
                                                                 ?>
                                                                 <?php //foreach ($eximgs as $eximg) {?>
                                                                 <div class="photos-col">
                                                                 <div class="user-pics">
                                                                 <a href="javascript:void(0)" onclick="viewalbum('<?php echo $album['_id']?>')" title="<?php echo $album['album_title']?>"><img src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?><?= $eximgs[0] ?>" alt=""></a>
                                                                 </div>
                                                                 </div>
                                                                 <?php //}?>
                                                                 <?php
                                                                 //$ctr++;
                                                                 }
                                                         }
                                                         ?>
                                                 </div>
                                                 <?php
                                         }
                                         else
                                         {
                                             echo 'No Album Added.';
                                         }
                                 ?>
                         </div>	 
                         
                         
                         
                         <div class="album_pics">
                            <div class="photos-views">
                                
                            </div>
                         </div>
                         
                         
                         
			 </div>
                     

                             
                     <?php include('includes/people_you_may_know.php');?>
		 
		<!--Right Part End -->
		
		 </div>  
	</div>
	<?php include('includes/chat_section.php');?>
</div>
<!-- Left Navigation and Footer -->   
 <?php 
    include('includes/left-menu.php');
    include('includes/footer.php');
?>

<script type="text/javascript">
(function () {
  //  $("#test").hide();
  $('#datetimepicker2').datetimepicker({
        format: "DD-MM-YYYY",
        maxDate: new Date
    });
    $('#datetimepicker').datetimepicker({
        format: "DD-MM-YYYY",
        maxDate: new Date
    });

})(jQuery); 
</script> 
<script>
     function setCurrClass(name){curr_cls=name;}	 
	 function getCurrClass(){return curr_cls;}

	 $("#imageFile1").on('change', function () {

		 //Get count of selected files
		 var countFiles = $(this)[0].files.length;
		 
		 /* set image class */
		 
		 var file, img;
		 var _URL = window.URL || window.webkitURL;
		
		 /* end set image class */
		 		 
		 var imgPath = $(this)[0].value;
		 var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
		 var image_holder = $("#image-holder");
		 image_holder.empty();

		 if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") {
			 if (typeof (FileReader) != "undefined") {
				
				var imgCls=[];
				 //loop for each file selected for uploaded.
				 for (var i = 0; i < countFiles; i++) {
					 
					 file = this.files[i];					 
					 
					 var reader = new FileReader();
					 reader.onload = function (e) {
						 
						 //console.log("images loaded");
						 
						  $("#imageFile1").append(e.target.result);
						 
						 
						var img = new Image();
						img.onload = function() {
							
							var curr_cls='';
							
							if(this.width>this.height){
							  //curr_cls="himg";
							  setCurrClass("himg");
							  imgCls[i]="himg";
							  //alert("if");
							}
						   else if(this.width<this.height){
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
							  console.log(imgCls[j]);
						   }
						  
						   $("<img />", {
								 "src": e.target.result,
									 "class": "thumb-image "+imgCls[i]
							 }).appendTo(image_holder).wrapAll("<div class='uimg_holder'></div>");							
							 
							
						};
						img.onerror = function() {
						   //alert( "not a valid file: " + file.type);
						};
						img.src = _URL.createObjectURL(file);
						 
						 //alert(getCurrClass());						
					 }

					 image_holder.show();
					 reader.readAsDataURL($(this)[0].files[i]);
				 }

			 } else {
				 alert("This browser does not support FileReader.");
			 }
		 } else {
			 alert("Please select gif, png, jpg and jpeg images.");
		 }
	 });
</script>