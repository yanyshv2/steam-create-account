<?php
$cookie_file = 'cookie.txt';
$cookie_file_create = fopen($cookie_file, "w+");  // echo realpath($cookie_file).'<br><br>';
$user_agent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)";

$sessionid = $_POST['sessionid'];

$hostname = 'store.steampowered.com';
$hostname_2 = 'steamcommunity.com';




$mailbox = '{imap.gmail.com:993/imap/ssl}';  // '{imap.gmail.com:993/imap/ssl}INBOX'
$user_email = $_POST['email'];
$password = $_POST['email_password'];

$imapResource = imap_open($mailbox, $user_email, $password) or die('Cannot connect to Gmail: ' . imap_last_error());
/*
$mailboxes = imap_list($imapResource, $mailbox, '*');
foreach($mailboxes as $box){
    $box_4 = mb_convert_encoding($box, "UTF-8", "UTF7-IMAP");
    echo $box_4 . '<br>';
}
imap_reopen($imapResource, $mailbox.'[Gmail]/spam');
 */
$search = 'ALL FROM "Steam" SINCE "' . date("j F Y", strtotime("-1 days")) . '"';  // Steam
$emails_steam = imap_search($imapResource, $search);


if(!empty($emails_steam)) :


    /** verify email */
    arsort($emails_steam);  $emails_steam = array_values($emails_steam);
    // $message = imap_qprint(imap_body($imapResource, $emails_steam[0]));
    $message = imap_body($imapResource, $emails_steam[0]);

    $frag_1 = '/newaccountverification?stoken=';
    $message_arr_1 = explode($frag_1, $message);  $message_arr_2 = explode('&creationid=', $message_arr_1[1]);  $frag_2 = $message_arr_2[0];
    $link_verify = 'https://'.$hostname.'/account/newaccountverification?stoken='.$frag_2.'&creationid='.$sessionid;

    $ch = curl_init($link_verify);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
    curl_setopt ($ch, CURLOPT_REFERER, 'https://'.$hostname.'/join');
    curl_exec ($ch);
    $result = curl_multi_getcontent ($ch);
    // echo $result.'<br><br>';
    curl_close($ch);





/** Steam create account */

$ch = curl_init('https://'.$hostname.'/join/createaccount');

curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt ($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
curl_setopt ($ch, CURLOPT_REFERER, 'https://'.$hostname.'/join/completesignup?creationid='.$sessionid);
curl_setopt ($ch, CURLOPT_POST, 1);
$post_data = array(
    'accountname' => $_POST['accountname'],
    'password' => $_POST['password'],
    'count' => 15,
    'lt' => 0,
    'creation_sessionid' => $sessionid,
);
curl_setopt ($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
    curl_setopt($ch, CURLOPT_COOKIESESSION, true);
    curl_setopt ($ch, CURLOPT_COOKIEJAR, realpath($cookie_file));
    curl_setopt ($ch, CURLOPT_COOKIEFILE, realpath($cookie_file));
    curl_setopt ($ch, CURLOPT_TIMEOUT, 3);
    curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_exec ($ch);
$result = curl_multi_getcontent ($ch);
// echo '<br><br>'.$result; ///


    /** Log in account */
curl_setopt($ch, CURLOPT_URL, 'http://'.$hostname.'/');  // 'http://'.$hostname.'/account/'
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt ($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
curl_setopt ($ch, CURLOPT_REFERER, 'http://'.$hostname.'/');
curl_setopt ($ch, CURLOPT_POST, 0);
curl_exec ($ch);
$result = curl_multi_getcontent ($ch);
// echo $result.'<br><br>';

   curl_close($ch);


$kuu_501 = explode('/steamcommunity.com/profiles/', $result);  $kuu_502 = explode('/', $kuu_501[1]);
$profile_id = $kuu_502[0];
$tradeoffers_url = 'https://'.$hostname_2.'/profiles/'.$profile_id.'/tradeoffers/privacy/';


    $cookie_file_1 = 'cookie.txt';
    $cookie_file_2 = 'cookie-2.txt';
    copy($cookie_file_1, $cookie_file_2);
    $cookie_file_2_text = file_get_contents($cookie_file_2);
    $cookie_file_2_text_new = str_replace($hostname, $hostname_2, $cookie_file_2_text);
    file_put_contents($cookie_file_2, $cookie_file_2_text_new);




    /** get Steam trade token */

 $ch = curl_init('https://'.$hostname_2.'/profiles/'.$profile_id.'/');

    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt ($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
curl_setopt ($ch, CURLOPT_REFERER, 'http://'.$hostname.'/');
curl_setopt ($ch, CURLOPT_POST, 0);
    curl_setopt ($ch, CURLOPT_COOKIEFILE, realpath($cookie_file_2));
    curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_exec ($ch);
$result = curl_multi_getcontent ($ch);
    echo $result.'<br><br>';


    curl_setopt($ch, CURLOPT_URL, $tradeoffers_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
    curl_setopt ($ch, CURLOPT_REFERER, 'http://'.$hostname.'/');
    curl_setopt ($ch, CURLOPT_POST, 0);
    curl_exec ($ch);
    $result = curl_multi_getcontent ($ch);

$kuu_501 = explode('"https://steamcommunity.com/tradeoffer/new/', $result);  $kuu_502 = explode('"', $kuu_501[1]);
$frag_5 = $kuu_502[0];
$trade_token_link = 'https://'.$hostname_2.'/tradeoffer/new/'.$frag_5;
    echo '<div style="padding: 100px 50px; color: #FFF; background: red;">';
echo 'Steam trade token: '.$trade_token_link;
    echo '</div>';

// <input size="45" type="text" value="https://steamcommunity.com/tradeoffer/new/?partner=903687218&amp;token=5JsjMugm" readonly="">


curl_close($ch);







else:
    echo 'ERROR ...';


endif; // (!empty($emails_steam))

?>






<script>
    alert('<?php echo 'Steam trade token: '.$trade_token_link; ?>');
</script>




