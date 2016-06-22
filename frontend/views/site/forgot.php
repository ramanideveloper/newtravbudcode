<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use frontend\assets\AppAsset;

use yii\widgets\ActiveForm;
use yii\mongodb\ActiveRecord;

use frontend\models\LoginForm;
use yii\validators\Validator;
$asset = frontend\assets\AppAsset::register($this);

$baseUrl = AppAsset::register($this)->baseUrl;
?>
<?php $this->beginBody() ?>

<!-- header section -->
<div class="np-header01">
  <div class="container clearfix" >
    <div class="row">
      <div class="col-sm-4">
          <div class="logo"><a href="<?php echo Yii::$app->urlManager->createUrl(['site/index'])?>" class="pageLoad" ><img src="<?= $baseUrl?>/images/logo.png" width="152" height="43"></a></div>
      </div>
      <div class="col-sm-8 clearfix" id="login">
         
         <!-- <div class="social-section clearfix"> 
		  <a href="#"><img src="<?= $baseUrl?>/images/facebook-icon.png" ></a> <a href="#"><img src="<?= $baseUrl?>/images/google-icon.png"></a> <a href="#"><img src="<?= $baseUrl?>/images/linkedin-icon.png"></a> 
		  </div> -->
        <?php $form = ActiveForm::begin(['action' => ['site/login'],'options' => ['method' => 'post','class' => 'top-form']]) ?>
        <div class="cus-login-section clearfix">
          <div class="cus-login-part1 clearfix">
            <div class="clearfix">
              <!--<input name="1" placeholder="Email Address" type="text" id="1" >-->
              <input type="hidden" name="login" value="1" />
               <?= $form->field($model,'email')->textInput(array('placeholder'=>'Email address'))->label(false)?>
            </div>
            <div class="checkbox has-js">
              <fieldset class="checkboxes">
                <label class="label_check" for="checkbox-01">
                  <input name="remember" id="checkbox-01" value="1" type="checkbox" checked="checked" />Remember Me</label>
             </fieldset>
            </div>
          </div>
          <div class="cus-login-part2 clearfix">
            <div class="clearfix">
               <?= $form->field($model,'password')->passwordInput(array('placeholder'=>'Password'))->label(false)?>
              <!--<input name="1" placeholder="Password" type="text" id="2" >-->
            </div>

            <!--<a href="#" id="m" class="fp-link"><span class="np-right-caret"></span>Forgot Password?</a> -->
            <a href="<?php echo Yii::$app->urlManager->createUrl(['site/forgotpassword'])?>"><span class="np-right-caret"></span>Forgot Password?</a>
          </div>
          <div class="login-link">
		<!--<a class="np-mobile-fix loginbtn" href="#">Login</a>-->
		<!--<input type="submit" class="np-mobile-fix loginbtn" name="Login" value="Login"/> -->
<div class="loginlink"><input type="submit" name="Login" value="Login"/></div>
</div>	
		</div>
       <?php ActiveForm::end() ?>
      </div>
    </div>
  </div>
</div>
<!-- end of header section  -->

<!-- forgot password section -->
<div id="forgotstepone" class="forgotstepone" style="background-color: #E9EAED;height: 400px">
    <?php $form = ActiveForm::begin(['action' => ['site/login'],'options' => ['method' => 'post','class' => 'top-form']]) ?>
    <br/><br/><br/><br/>
    <div style="background-color:#FFFFFF;width: 550px;height: 175px;margin: auto;padding: 0 10px;">
    <h4>Find Your Account</h4>
    <hr/>
    <p>Email</p>
    <div id="forgot_fail" class="form-failuremsg">Oops..!! You Have Entered Wrong Email Id </div>
    <input id="fmail" style="" type="email" name="fmail" placeholder="Enter Your Email Address">
    <br/><br/>
    <div>
        <a href="<?php echo Yii::$app->urlManager->createUrl(['site/index'])?>"><button type="button" class="themebtn">Back</button></a>
        <button type="button" name="sendone" class="themebtn" onclick="forgotone()">Find your account</button>
    </div>
    <br/><br/>
    </div>
    <?php ActiveForm::end() ?>
</div>


<div id="forgotsteptwo" class="forgotsteptwo" style="background-color: #E9EAED;height: 400px;display: none;">    
    <?php $form = ActiveForm::begin(['action' => ['site/login'],'options' => ['method' => 'post','class' => 'top-form']]) ?>
    <br/><br/><br/><br/>
    <input id="fhmail" name="fhmail" type="hidden" value="">
    <div style="background-color:#FFFFFF;width: 550px;height: 300px;margin: auto;padding: 0 10px;">
    <div id="forgott_success" class="form-successmsg">Please confirm account.</div>
    <div id="forgott_fail" class="form-failuremsg">Oops..!! Something went wrong. Please try later.</div>
    <h4>Reset Your Password</h4>
    <hr/>
    <p>How would you like to reset your password?</p>
    <input type="radio" checked="true" readonly="true"/>Email me a link to reset my password<br/>
    <p id="ftmail"></p>
    <span id="ftphoto"></span>
    <p id="ftname"></p>
    <span>TravBud User</span>
    <br/><br/>
    <div>
        <button type="button" name="sendtwo" class="themebtn" onclick="forgottwo()">Continue</button>
        <a href="<?php echo Yii::$app->urlManager->createUrl(['site/forgotpassword'])?>"><button type="button" class="themebtn">Not You</button></a>
    </div>
    <br/><br/>
    </div>
    <?php ActiveForm::end() ?>
</div>

<div id="forgotstepthree" class="forgotstepthree" style="background-color: #E9EAED;height: 400px;display: none;">    
    <?php $form = ActiveForm::begin(['action' => ['site/login'],'options' => ['method' => 'post','class' => 'top-form']]) ?>
    <br/><br/><br/><br/>
    <input id="fhhmail" name="fhmail" type="hidden" value="">
    <div style="background-color:#FFFFFF;width: 550px;height: 175px;margin: auto;padding: 0 10px;">
        <div id="forgottt_success" class="form-successmsg">Please enter 6-digit code as per your email.</div>
        <div id="forgottt_fail" class="form-failuremsg">Oops..!! You Have Entered Wrong Code.</div>
    <h4>Enter Security Code</h4>
    <hr/>
    <p>Please check your email for a message with your code. Your code is 6 digits long.</p>
    <input type="text" placeholder="######" name="forgotfinalcode" id="forgotfinalcode" maxlength="6"/>
    We sent your code to:<br/><p id="fttmail"></p>
    <br/><br/>
    <div>
        <button type="button" name="sendthree" class="themebtn" onclick="forgotthree()">Continue</button>
        <a href="<?php echo Yii::$app->urlManager->createUrl(['site/index'])?>"><button type="button" class="themebtn">Cancel</button></a>
    </div>
    <br/><br/>
    </div>
    <?php ActiveForm::end() ?>
</div>

<div id="forgotstepfour" class="forgotstepfour" style="background-color: #E9EAED;height: 400px;display: none;">    
    <?php $form = ActiveForm::begin(['action' => ['site/login'],'options' => ['method' => 'post','class' => 'top-form']]) ?>
    <br/><br/><br/><br/>
    <input id="finalmail" name="finalmail" type="hidden" value="">
    <div style="background-color:#FFFFFF;width: 550px;height: 175px;margin: auto;padding: 0 10px;">
        <div id="forgotttt_success" class="form-successmsg">Please Enter New Password.</div>
        <div id="forgottt_fail" class="form-failuremsg">Oops..!! You Have Entered Wrong Code.</div>
    <h4>Choose a new password</h4>
    <hr/>
    <p>A strong password is a combination of letters and punctuation marks. It must be at least 6 characters long.</p>
    New Password<input type="password" placeholder="" name="newpass" id="newpass"/>
    <br/><br/>
    <div>
        <button type="button" name="sendthree" class="themebtn" onclick="forgotfour()">Continue</button>
    </div>
    <br/><br/>
    </div>
    <?php ActiveForm::end() ?>
</div>

<div id="forgotstepfive" class="forgotstepfive" style="background-color: #E9EAED;height: 400px;display: none;">    
    <?php $form = ActiveForm::begin(['action' => ['site/login'],'options' => ['method' => 'post','class' => 'top-form']]) ?>
    <br/><br/><br/><br/>
    <div style="background-color:#FFFFFF;width: 550px;height: 175px;margin: auto;padding: 0 10px;">
        <div id="forgottttt_success" class="form-successmsg">Password has been changed successfully.</div>
    <h4>Congratulations !!!</h4>
    <hr/>
    <p>Keep your password safe with you. Don't share it with others.</p>
    <a href="<?php echo Yii::$app->urlManager->createUrl(['site/index'])?>">Login Here</a>
    <br/><br/>
    <br/><br/>
    </div>
    <?php ActiveForm::end() ?>
</div>
<!-- end of forgot password section -->

<!-- footer section -->
<div class="np-fotter clearfix">
  <div class="container np-fotter-link"><a href="#">About</a> <span>|</span> <a href="#">Privacy</a> <span>|</span> <a href="#">Invite</a> <span>|</span> <a href="#">Terms</a> <span>|</span> <a href="#">Contact Us</a> <span>|</span> <a href="#">Features</a> <span>|</span> <a href="#">Mobile</a> <span>|</span> <a href="#">Developers</a></div>
</div>
<!-- end of footer section -->

<script>
function forgotone(){
    var fmail = $("#fmail").val();
    if(fmail != ''){
           $.ajax({
               type: 'POST',
               url: '<?php echo Yii::$app->urlManager->createUrl(['site/forgotstepone']); ?>',
               data: "fmail=" + fmail,
               success: function(data){ 
                   //alert(data);return false;
                    var result = $.parseJSON(data);
                    //alert(result['email']);return false;
                    if(result['value'] == '1')
                    {
                         $("#forgotstepone").hide();
                         $("#forgotsteptwo").show();
                         $("#fhmail").val(result['email']);
                         document.getElementById("ftname").innerHTML = result['username'];
                         document.getElementById("ftmail").innerHTML = result['email'];
                         var dppic = result['photo'];
                         $("#ftphoto").html('<img id="ftphoto" style="height: 50px;width: 50px;" src="profile/' + dppic + '"/>');
                         $('#forgott_success').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                    }
                    else if(result['value'] == '0')
                    {
                       $('#forgot_fail').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                    }
                    else
                    {
                        $('#forgot_fail').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                    }
                }

           }); 
           }else{
                $('#forgot_fail').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
           }
    }

function forgottwo(){
    var fhmail = $("#fhmail").val();
    //alert(fhmail);return false;
    if(fhmail != ''){
           $.ajax({
               type: 'POST',
               url: '<?php echo Yii::$app->urlManager->createUrl(['site/forgotsteptwo']); ?>',
               data: "fhmail=" + fhmail,
               success: function(data){ 
                   //alert(data);return false;
                    var result = $.parseJSON(data);
                    //alert(result['email']);return false;
                    if(result['value'] == '1')
                    {
                         $("#forgotsteptwo").hide();
                         $("#forgotstepthree").show();
                         $("#fhhmail").val(result['email']);
                         document.getElementById("fttmail").innerHTML = result['email'];
                         $('#forgottt_success').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                    }
                    else
                    {
                        $('#forgott_fail').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                    }
                }

           }); 
           }else{
                $('#forgott_fail').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
           }
    }
    
function forgotthree(){
    var fhhmail = $("#fhhmail").val();
    var forgotfinalcode = $("#forgotfinalcode").val();
    //alert(fhhmail);alert(forgotfinalcode);return false;
    if(forgotfinalcode != ''){
           $.ajax({
               type: 'POST',
               url: '<?php echo Yii::$app->urlManager->createUrl(['site/forgotstepthree']); ?>',
               data: "fhhmail=" + fhhmail +"&fhcode=" + forgotfinalcode,
               success: function(data){ 
                    //alert(data);return false;
                    var result = $.parseJSON(data);
                    //alert(result['value']);return false;
                    if(result['value'] == '1')
                    {
                         $("#forgotstepthree").hide();
                         $("#forgotstepfour").show();
                         $("#finalmail").val(result['email']);
                         $('#forgotttt_success').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                    }
                    else
                    {
                        $('#forgottt_fail').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                    }
                }

           }); 
           }else{
                $('#forgott_fail').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
           }
    }
    
function forgotfour(){
    var finalmail = $("#finalmail").val();
    var newpass = $("#newpass").val();
    if(forgotfinalcode != ''){
           $.ajax({
               type: 'POST',
               url: '<?php echo Yii::$app->urlManager->createUrl(['site/forgotstepfour']); ?>',
               data: "finalmail=" + finalmail +"&newpass=" + newpass,
               success: function(data){
                    //alert(data);return false;
                    var result = $.parseJSON(data);
                    //alert(result['value']);return false;
                    if(result['value'] == '1')
                    {
                         $("#forgotstepfour").hide();
                         $("#forgotstepfive").show();
                         $('#forgottttt_success').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                    }
                    else
                    {
                        $('#forgottt_fail').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
                    }
                }

           }); 
           }else{
                $('#forgott_fail').css('display','inline-block').fadeIn(3000).delay(3000).fadeOut(3000);
           }
    }
</script>