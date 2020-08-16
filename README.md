# Twitter to Mastodon
Twitter である特定の言葉を含んだツイートがされると、Mastodon にも同じものを投稿するための PHP スクリプト

## 必要なもの
- [twitteroauth](https://github.com/abraham/twitteroauth)
- [Twitter API Key](https://developer.twitter.com/apps)
- Mastodon API Key

## 使い方
1. twitteroauth のソースをダウンロードし、適当な場所に置く。
1. 4行目の ｢require｣ のところに、twitteroauth の autoload.php までのフルパスを入れる。
1. ｢OAuth認証｣ と書かれているところに、それぞれのキーを入れる。
1. ｢Mastodon 設定｣ と書かれているところに、サーバーのアドレス (末尾の / は入れない) とAPIのアクセストークンを入れる。
1. ｢Twitter のユーザー名｣ と書かれているところに、Twitter のユーザー名を入れる。
1. ｢ファイル保存場所｣ と書かれているところに、設置場所のディレクトリのフルパスを入れる。(末尾は / で終わるように)
1. 70行目くらいのところに記載されている	｢文字列の検索｣ にて、対象の文字列を指定する。(複数指定可。その場合、if を適宜増やす)
1. 動作テストを行い、問題なければ、cron で動かすなりお好きにどうぞ。

## ライセンス
MIT
