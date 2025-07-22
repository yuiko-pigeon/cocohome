=== Lightning Child Theme ===

Contributors: yuiko
Requires at least: 5.0
Tested up to: 6.5
Requires PHP: 7.4
Tags: lightning, child theme, wordpress
License: GNU General Public License v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

== 説明 ==
このテーマは、WordPress公式テーマ「Lightning」の子テーマです。  
親テーマの機能を継承しつつ、デザインのカスタマイズや追加CSS・JSの実装を目的として作成しています。

本子テーマでは、親テーマのアップデートによる影響を受けずに、以下のような調整・機能追加が可能です。

- 独自のスタイル（CSS/SCSS）の適用
- 固有のJavaScriptの読み込み
- テンプレートファイル（例: header.php, footer.php）の上書き
- functions.phpによる機能追加

== 使用方法 ==
1. WordPressの `wp-content/themes/` 以下に本テーマを設置
2. 管理画面の「外観」→「テーマ」から「cocohome」を有効化
3. 必要に応じて `style.css`, `functions.php` を編集

※ 本テーマを使用するには、親テーマ「Lightning」がインストールされている必要があります。

== ディレクトリ構成 ==
- `style.css` ： テーマ情報記述
- `css/style.css` ： テーマ情報記述。コンパイル先。
- `scss/` ： 子テーマのスタイルを記述。
- `functions.php` ： 子テーマ用の関数を追加
- `screenshot.png` ： テーマ選択画面用のスクリーンショット（1200×900）
- `js/main.js` ： 子テーマ用のJavaScript



    lightning-children                                                                  # 
    ├─ _g2                                                                                                                          
    ├─ _g3                                                                              #  
    ├─ css                                                                              #                                                               # 
    │   └─ style.css                                                            　　　　　# テーマ情報記述
    │   
    ├─ inc   
    ├─ js                                                                               # 
    │   └─ main.js                                                                      # 子テーマ用のJavaScript
    │   
    ├─ picture                                                                          # 
    │   ├─ Autoptimize_ 追加 Autoptimize ‹ 株式会社ココホーム — WordPress_files             # 
    │   ├─ exomzkfz.png                                                                 # 
    │   ├─ fv-woodRC.webp                                                               # 
    │   └─ wood-rc-FV.webp                                                              # 
    │   
    ├─ scss                                                                             # 子テーマのスタイルを記述。
    │   ├─ global                                                                       # 
    │   │   ├─ _color.scss                                                              # 色指定
    │   │   ├─ _index.scss                                                              # 
    │   │   └─ _mixin.scss                                                              # ブレイクポイント指定
    │   │   
    │   ├─ _acordion.scss                                                               # アコーディオンメニュー
    │   ├─ _block-style-company.scss                                                    # "会社概要"ページ
    │   ├─ _block-style-contact.scss                                                    # "お問い合わせ"ページ
    │   ├─ _block-style-contents.scss                                                   # "弊社について"ページ
    │   ├─ _block-style-front.scss                                                      # フロントページ
    │   ├─ _block-style-price.scss                                                      # ”料金表”ページ
    │   ├─ _footer.scss                                                                 # フッター
    │   ├─ _header.scss                                                                 # ヘッダー
    │   ├─ _layout.scss                                                                 # 
    │   ├─ _navmenu.scss                                                                # ヘッダーナビメニュー
    │   ├─ _page-404.scss                                                               # 404ページ
    │   ├─ _root.scss                                                                   # ルート
    │   ├─ _text.scss                                                                   # 
    │   └─ style.scss                                                                   # 
    │   
    ├─ vendor                                                                           # 
    ├─ 404.php                                                                          # 
    ├─ LICENSE                                                                          # 
    ├─ comments.php                                                                     # 
    ├─ favicon.webp                                                                     # 
    ├─ footer.php                                                                       # 
    ├─ functions.php                                                                    # 子テーマ用の関数を追加
    ├─ header.php                                                                       # 
    ├─ index.php                                                                        # 
    ├─ readme.txt                                                                       # 
    ├─ screenshot.png                                                                   # テーマ選択画面用のスクリーンショット（1200×900）
    ├─ sidebar.php                                                                      # 
    ├─ style.css                                                                        # テーマ情報記述
    └─ theme.json                                                                       # 

== 補足 ==
本子テーマは `Template: lightning` を指定しているため、Lightningテーマの構成・設計に準拠しています。  
大規模なテンプレート上書きが必要な場合は、親テーマの構造変更に注意してください。

== 免責 ==
本子テーマの使用による不具合や損害について、制作者は一切の責任を負いかねます。  
バックアップを取り、安全な環境でのご利用を推奨いたします。
