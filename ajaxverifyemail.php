<?php

$hostname = 'store.steampowered.com';

$ch = curl_init('https://'.$hostname.'/join/ajaxverifyemail');
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt ($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)");
curl_setopt ($ch, CURLOPT_REFERER, 'https://'.$hostname.'/join');
curl_setopt ($ch, CURLOPT_POST, 1);
$post_data = array(
    'email' => $_POST['email'],
    'captchagid' => $_POST['captchagid'],
    'captcha_text' => $_POST['captcha_text']
);
curl_setopt ($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
curl_exec ($ch);
$result = curl_multi_getcontent ($ch);

curl_close($ch);


$u46 = json_decode($result);
$sessionid = $u46->sessionid;
if($sessionid) {
print_r($sessionid);
echo '<br> Please, wait ...';
} else { echo '<br> ERROR ...'; }
?>




<?php if($sessionid) { ?>

<form id="create_account_21" method="post" action="completesignup.php">
    <input type="hidden" name="sessionid" value="<?php echo $sessionid ?>" />
    <input type="hidden" name="email" value="<?php echo $_POST['email'] ?>" />
    <input type="hidden" name="email_password" value="<?php echo $_POST['email_password'] ?>" />
    <input type="hidden" name="accountname" value="<?php echo $_POST['accountname'] ?>" />
    <input type="hidden" name="password" value="<?php echo $_POST['password'] ?>" />
</form>

<script>
    function submit_form_21() {
        document.getElementById("create_account_21").submit();
    }

    setTimeout( "submit_form_21()", 6000 );
</script>

<?php } ?>
