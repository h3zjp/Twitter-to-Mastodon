#!/usr/bin/php
<?php
/* ライブラリを読み込み */
require '[twitteroauth Full Path] /autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

/* OAuth認証 */
$consumer_key = '[Twittter Consumer Key]';
$consumer_secret = '[Twittter Consumer Secret]';
$access_token = '[Twittter Access Token]';
$access_token_secret = '[Twittter Access Token Secret]';

/* Mastodon 設定 */
$mastodon_url = '[Mastodon URL](e.g. https://mstdn.h3z.jp)';
$mastodon_access_token = '[Mastodon Access Token]';

/* Twitter のユーザー名 */
$twitter_screen_name = '[Twitter Username](e.g. h3zjp)';

/* 取得数 */
$tweets_count = 20;

/* ファイル保存場所 */
$file = '[Folder Full Path]';
$file = $file + 'data.csv';

/* オブジェクトを生成 */
$connection = new TwitterOAuth ($consumer_key, $consumer_secret, $access_token, $access_token_secret);

/* ハッシュタグを検索 */
$tweets_params = [ 'screen_name' => $twitter_screen_name , 'count' => $tweets_count , 'exclude_replies' => false , 'include_rts' => true ];
$tweets = $connection->get('statuses/user_timeline', $tweets_params);

/* データ読み込み */
$filer = fopen($file, 'r');
for ($i = 0; $i < $tweets_count; $i++) {
	$rcsv[$i] = fgetcsv($filer);
}
fclose($filer);

/* 保存用データ生成とファイルに保存 */
for ($i = 0; $i < $tweets_count; $i++) {
	$tweet_text = $tweets[$i]->text;
	$tweet_url = $tweets[$i]->entities->urls[0]->url;
	$tweet_url_original = $tweets[$i]->entities->urls[0]->expanded_url;

	$wcsv = array($tweet_text, $tweet_url, $tweet_url_original);

	if ($i == 0) {
		$filew = fopen($file, 'w');
		$tweet_post_text = $tweets[$i]->text;
		$tweet_post_url = $tweets[$i]->entities->urls[0]->url;
		$tweet_post_url_original = $tweets[$i]->entities->urls[0]->expanded_url;
	} else {
		$filew = fopen($file, 'a');
	}

	fputcsv($filew, $wcsv);
	fclose($filew);
}

/* $rcsv[0][0] != $tweet_post_text のみcURLで送信 */
if ($rcsv[0][0] != $tweet_post_text) {

	/* t.co の置き換え */
	if ($tweet_post_url !== '') {
		$post_text = str_replace($tweet_post_url , $tweet_post_url_original , $tweet_post_text);
	}

	/* 文字列の検索 */
	$word = 0;
	if (strpos($post_text, 'animetick.net') !== false){
		$word = 1;
	}
	if (strpos($post_text, '#bookmeter') !== false){
		$word = 1;
	}
	if (strpos($post_text, 'swarmapp.com') !== false){
		$word = 1;
	}

	/* 文字列が含まれていた場合、Mastodon に投稿する */
	if ($word == 1){
		$curl_post = array(
			'access_token' => $mastodon_access_token,
			'status' => $post_text,
			'visibility' => 'public'
		);
		$mstdn_post_url = $mastodon_url + '/api/v1/statuses';
		$curl = curl_init($mstdn_post_url);
		curl_setopt($curl,CURLOPT_POST, TRUE);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($curl_post));
		curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl,CURLOPT_RETURNTRANSFER, TRUE);
		curl_exec($curl);

		/* ページ表示 */
		echo '<p>Twitter→Mastodon 送信済</p>';
		echo $text;
	}else{
		/* ページ表示 */
		echo '<p>Twitter→Mastodon 未送信</p>';

	}
} else {
	echo '<p>Twitter 新ツイート無し</p>';
}
?>
