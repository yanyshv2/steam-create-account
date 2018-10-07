<?php

$hostname = 'store.steampowered.com';


$ch = curl_init('https://'.$hostname.'/join');
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt ($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)");
curl_setopt ($ch, CURLOPT_REFERER, 'https://'.$hostname.'/join');
curl_setopt ($ch, CURLOPT_POST, 0);
curl_exec ($ch);
$result = curl_multi_getcontent ($ch);
// echo $result;

curl_close($ch);







$result_arr_1 = explode('id="captchaImg"', $result);  $result_arr_2 = explode('src="', $result_arr_1[1]);  $result_arr_3 = explode('"', $result_arr_2[1]);
$capcha_file_url = $result_arr_3[0];  // gid=
$capcha_imagedata = file_get_contents($capcha_file_url); // alternatively specify an URL, if PHP settings allow
$capcha_base64 = base64_encode($capcha_imagedata);

$result_arr_31 = explode('gid=', $capcha_file_url);
$captchagid = $result_arr_31[1];  // echo $captchagid.' ---- <br>';

$rucaptcha_api = '07d30d1625b1560e6e63dd00ed600754';
$ch_capcha = curl_init('http://rucaptcha.com/in.php');
curl_setopt ($ch_capcha, CURLOPT_POST, 1);
$capcha_post_data = array(
    'method' => 'base64',
    'key' => $rucaptcha_api,
    'body' => $capcha_base64,
    'regsense' => 1,
    // 'header_acao' => 1,
);
curl_setopt ($ch_capcha, CURLOPT_POSTFIELDS, http_build_query($capcha_post_data));
curl_setopt ($ch_capcha, CURLOPT_RETURNTRANSFER, 1);
$capcha_result = curl_exec ($ch_capcha);  // echo $capcha_result;
$capcha_result_arr2 = explode('|', $capcha_result);  $capcha_result_id = $capcha_result_arr2[1]; // echo $capcha_result_id;
curl_close($ch_capcha);




?> <br> <br>


<h2> Create Steam account </h2>
<form id="create_account_20" method="post" action="ajaxverifyemail.php">
    <input type="hidden" name="captchagid" value="<?php echo $captchagid ?>" />
    <div> <input type="text" name="captcha_text" placeholder="captcha text" readonly /> <span>(wait until the field fills in automatically)</span> </div> <br>
    <div> <label>E-mail</label> <input type="text" name="email" /> <span>Only Gmail. IMAP must be enabled.</span> </div> <br>
    <div> <label>E-mail password</label> <input type="password" name="email_password" /> </div> <br>
    <div> <label>Steam login</label> <input type="text" name="accountname" /> </div> <br>
    <div> <label>Steam login password</label> <input type="password" name="password" /> </div> <br>
    <div> <input type="submit" value="Create account" disabled /> </div>
</form>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script>
function form_captcha_7() {
    var field_captcha = document.querySelector('#create_account_20 input[name="captcha_text"]');
    var button_submit = document.querySelector('#create_account_20 input[type="submit"]');

    jQuery(document).ready(function($) {
    $.ajax({
        url: "<?php echo 'http://rucaptcha.com/res.php?key='.$rucaptcha_api.'&action=get&json=1&header_acao=1&id='.$capcha_result_id ?>",
        type: 'GET',
        // crossDomain: true,
    }).done(function( data ) {
        if(data['status'] !== 1) {
            // alert(data['request'] + ' -- ' + data['status']); // data['request']
            setTimeout( "form_captcha_7()", 5000 );
        } else {
            field_captcha.value = data['request'];
            button_submit.disabled = false;
        }
        });
    }); /// jQuery(document).ready(function($)
}

   setTimeout( "form_captcha_7()", 5000 );

</script>
