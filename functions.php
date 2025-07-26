<?php
add_action( 'after_setup_theme', function(){
	add_theme_support( 'custom-logo' );
  } );

add_action( 'wp_head', function() {
	if ( is_front_page() ) {
		echo '<meta name="description" content="' . esc_attr( get_bloginfo( 'description' ) ) . '">' . "\n";
	}
});
// Font Awesome の読み込みを無効化
add_filter( 'lightning_font_awesome_disable', '__return_true' );
add_filter( 'vk_blocks_font_awesome_load', '__return_false' );
//deferにて遅延読み込み
add_filter( 'script_loader_tag', function( $tag, $handle, $src ) {
	// defer を付けたいスクリプトのハンドル名一覧
	$handles_to_defer = [
		'vk-blocks-slider',
		'vk-swiper', // Swiper本体がこのハンドル名で登録されている場合が多い
	];

	if ( in_array( $handle, $handles_to_defer, true ) ) {
		// すでに defer が付いていない場合のみ追加
		if ( false === strpos( $tag, ' defer' ) ) {
			$tag = str_replace( 'src=', 'defer src=', $tag );
		}
	}
	return $tag;
}, 10, 3 );
//GoogleFonts フォント読み込み前に先にDNSなどの接続
function add_google_fonts_preconnect() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}
add_action('wp_head', 'add_google_fonts_preconnect', 1);


function lightning_child_enqueue_styles() {
    wp_enqueue_style(
        'lightning-child-style',
        get_stylesheet_directory_uri() . '/css/style.css',
        array(),
        filemtime(get_stylesheet_directory() . '/css/style.css')
    );
	wp_enqueue_script( 
		'primary-script', 
		get_stylesheet_directory_uri() . '/js/main.js',
		array( )
	);
}
add_action('wp_enqueue_scripts', 'lightning_child_enqueue_styles');


define( 'LIG_G3_DIR', '_g3' );
define( 'LIG_G2_DIR', '_g2' );

define( 'LIG_DEBUG', false );

require_once __DIR__ . '/vendor/autoload.php';

if ( true === LIG_DEBUG ) {
	function lightning_debug_mode() {
		$options = lightning_get_theme_options();
		// $options = get_option( 'lightning_theme_options' );
		// unset( $options['layout'] );
		// update_option( 'lightning_theme_options', $options );
		print '<pre style="text-align:left">';
		print_r( $options );
		print '</pre>';
	}
	add_action( 'lightning_site_header_after', 'lightning_debug_mode' );
}

/**
 * Check is G3
 *
 * @return bool
 */
function lightning_is_g3() {

	$return = true;
	$g       = get_option( 'lightning_theme_generation' );
	$options = get_option( 'lightning_theme_options' );

	if ( '1' === get_option( 'fresh_site' ) ) {
		// 新規サイトの場合はG3に指定.
		update_option( 'lightning_theme_generation', 'g3' );
		$return = true;
	} else if ( 'g3' === $g ) {
		$return = true;
	} elseif ( 'g2' === $g ) {
		$return = false;
	} else {
		$skin    = get_option( 'lightning_design_skin' );
		if ( 'origin2' === $skin ) {
			update_option( 'lightning_theme_generation', 'g2' );
			$return = false;
		} elseif ( 'origin3' === $skin ) {
			update_option( 'lightning_theme_generation', 'g3' );
			$return = true;
		} elseif ( empty( $options ) ) {
			// 後から Lightning をインストールした場合は G3 にする
			// （新規サイトではない && lightning_theme_options が存在しない）
			update_option( 'lightning_theme_generation', 'g3' );
			$return = true;
		} else {
			// これ以外は旧ユーザー（Lightning Pro）の可能性が高いのでG2.
			update_option( 'lightning_theme_generation', 'g2' );
			$return = false;
		}
	}
	return apply_filters( 'lightning_is_g3', $return );
}

require __DIR__ . '/inc/class-ltg-template-redirect.php';

/**
 * 最終的に各Gディレクトリに移動
 */
if ( ! function_exists( 'lightning_get_template_part' ) ) {
	function lightning_get_template_part( $slug, $name = null, $args = array() ) {

		if ( lightning_is_g3() ) {
			$g_dir = '_g3';
		} else {
			$g_dir = '_g2';
		}

		/**
		 * 読み込み優先度
		 *
		 * 1.child g階層 nameあり
		 * 2.child 直下 nameあり
		 * 3.parent g階層 nameあり
		 *
		 * 4.child g階層 nameなし
		 * 5.child 直下 nameなし
		 * 6.parent g階層 nameなし
		 */

		/* Almost the same as the core */
		$template_path_array = array();
		$name                = (string) $name;

		// Child theme G directory
		if ( preg_match( '/^' . $g_dir . '/', $slug ) ) {
			// 1. g階層がもともと含まれている場合
			if ( '' !== $name ) {
				$template_path_array[] = get_stylesheet_directory() . "/{$slug}-{$name}.php";
			}
		} else {
			// g階層が含まれていない場合

			// 1. g階層付きのファイルパス
			if ( '' !== $name ) {
				$template_path_array[] = get_stylesheet_directory() . '/' . $g_dir . "/{$slug}-{$name}.php";
			}
			// 2. 直下のファイルパス
			if ( '' !== $name ) {
				$template_path_array[] = get_stylesheet_directory() . "/{$slug}-{$name}.php";
			}
		}

		if ( preg_match( '/^' . $g_dir . '/', $slug ) ) {
			// 3. g階層がもともと含まれている場合
			if ( '' !== $name ) {
				$template_path_array[] = get_template_directory() . "/{$slug}-{$name}.php";
			}
		} else {
			// 3. g階層がもともと含まれていない場合
			if ( '' !== $name ) {
				$template_path_array[] = get_template_directory() . '/' . $g_dir . "/{$slug}-{$name}.php";
			}
		}

		// Child theme G directory
		if ( preg_match( '/^' . $g_dir . '/', $slug ) ) {
			// 4. g階層がもともと含まれている場合
			$template_path_array[] = get_stylesheet_directory() . "/{$slug}.php";
		} else {
			// g階層が含まれていない場合
			// 4. g階層付きのファイルパス
			$template_path_array[] = get_stylesheet_directory() . '/' . $g_dir . "/{$slug}.php";
			// 5. 直下のファイルパス
			$template_path_array[] = get_stylesheet_directory() . "/{$slug}.php";
		}

		if ( preg_match( '/^' . $g_dir . '/', $slug ) ) {
			// g階層がもともと含まれている場合
			// 6. 親のg階層
			$template_path_array[] = get_template_directory() . "/{$slug}.php";
		} else {
			// 6. 親のg階層
			$template_path_array[] = get_template_directory() . '/' . $g_dir . "/{$slug}.php";
		}

		foreach ( (array) $template_path_array as $template_path ) {
			if ( file_exists( $template_path ) ) {
				$require_once = false;
				load_template( $template_path, $require_once );
				break;
			}
		}
	}
}

if ( lightning_is_g3() ) {
	require __DIR__ . '/' . LIG_G3_DIR . '/functions.php';
} else {
	require __DIR__ . '/' . LIG_G2_DIR . '/functions.php';
}

require __DIR__ . '/inc/customize-basic.php';
require __DIR__ . '/inc/tgm-plugin-activation/tgm-config.php';
require __DIR__ . '/inc/vk-old-options-notice/vk-old-options-notice-config.php';
require __DIR__ . '/inc/admin-mail-checker.php';
require __DIR__ . '/inc/functions-compatible.php';
require __DIR__ . '/inc/font-awesome/font-awesome-config.php';
require __DIR__ . '/inc/old-page-template.php';

require __DIR__ . '/inc/class-ltg-theme-json-activator.php';
new LTG_Theme_Json_Activator();

/**
 * 世代切り替えした時に同時にスキンも変更する処理
 *
 * 世代は lightning_theme_generation で管理している。
 *
 *      generetionに変更がある場合
 *          今の世代でのスキン名を lightning_theme_options の配列の中に格納しておく
 *          lightning_theme_option の中に格納されている新しい世代のスキンを取得
 *          スキンをアップデートする *
 */

function lightning_change_generation( $old_value, $value, $option ) {
	// 世代変更がある場合
	if ( $value !== $old_value ) {

		// 現状のスキンを取得
		$current_skin = get_option( 'lightning_design_skin' );

		if ( $current_skin ) {
			// オプションを取得
			$options = get_option( 'lightning_theme_options' );
			if ( ! $options || ! is_array( $options ) ) {
				$options = array();
			}
			$options[ 'previous_skin_' . $old_value ] = $current_skin;
			// 既存のスキンをオプションに保存
			update_option( 'lightning_theme_options', $options );
		}

		// 前のスキンが保存されている場合
		if ( ! empty( $options[ 'previous_skin_' . $value ] ) ) {
			$new_skin = esc_attr( $options[ 'previous_skin_' . $value ] );

			// 前のスキンが保存されていない場合
		} elseif ( 'g3' === $value ) {
				$new_skin = 'origin3';
		} else {
			$new_skin = 'origin2';
		}
		update_option( 'lightning_design_skin', $new_skin );
	}
}
add_action( 'update_option_lightning_theme_generation', 'lightning_change_generation', 10, 3 );

// $argsがオブジェクトかつtheme_locationが設定されているかをチェック
function add_menu_headerLink_class($atts, $item, $args, $depth) {
    // グローバルカウンタを使う（事前に初期化しておく）
    global $menu_item_counter;

    // 対象ナビゲーションが「Header Nav」の場合
    if (is_object($args) && isset($args->theme_location) && $args->theme_location === 'Header Nav') {
        if ($depth == 0) {
            // 6番目のメニューにだけクラスを追加
            if ($menu_item_counter == 6) {
                $custom_class = 'contact';

                if (isset($atts['class'])) {
                    $atts['class'] .= ' ' . $custom_class;
                } else {
                    $atts['class'] = $custom_class;
                }
            }

            // カウンターを進める
            $menu_item_counter++;
        }
    }

    return $atts;
}
add_filter('nav_menu_link_attributes', 'add_menu_headerLink_class', 10, 4);

//フッターのウィジェットエリアの数を増やす
// Lightning Pro や G3 Pro Unit の Lカスタマイズ > ightning フッター設定 から指定できるカラム数を上書きするので注意
add_filter('lightning_footer_widget_area_count','lightning_footer_widget_area_count_custom',11);
function lightning_footer_widget_area_count_custom($footer_widget_area_count){
    $footer_widget_area_count = 2; // ← 1~4の半角数字で設定してください。
    return $footer_widget_area_count;
}


//独自スタイルの追加
function custom_block_styles() {
    // 独自のブロックスタイルを登録する
    register_block_style(
        'core/heading', // 事業内容
        array(
            'name'         => 'title-round', // スタイル名
            'label'        => '事業内容丸角タイトル', // スタイルの表示名
        )
    );
    register_block_style(
        'core/list', // 事業内容
        array(
            'name'         => 'list-step', // スタイル名
            'label'        => 'ステップ', // スタイルの表示名
        )
    );
    register_block_style(
        'core/group', // 事業内容
        array(
            'name'         => 'background-orange', // スタイル名
            'label'        => '背景オレンジ', // スタイルの表示名
        )
    );
    register_block_style(
        'core/columns', // フロントページ
        array(
            'name'         => 'colums-center', // スタイル名
            'label'        => '右が見切れないように中央よせ', // スタイルの表示名
        )
    );
    register_block_style(
        'core/column', // ブフロントページ
        array(
            'name'         => 'colum-center', // スタイル名
            'label'        => '中央よせ', // スタイルの表示名
        )
    );
    register_block_style(
        'core/group', // フロントページ
        array(
            'name'         => 'sptb-row', // スタイル名
            'label'        => 'レスポンシブ時に縦並び', // スタイルの表示名
        )
    );
    register_block_style(
        'core/group', // フロントページ
        array(
            'name'         => 'sptb-row-reverse', // スタイル名
            'label'        => 'レスポンシブ時に縦並び(逆)', // スタイルの表示名
        )
    );
    register_block_style(
        'core/group', // 料金表
        array(
            'name'         => 'price-group', // スタイル名
            'label'        => '料金表グループ', // スタイルの表示名
        )
    );
	register_block_style(
        'core/columns', // 会社概要
        array(
            'name'         => 'colums-company', // スタイル名
            'label'        => 'レスポンシブ時会社情報→mapの順に縦並び', // スタイルの表示名
        )
    );
	register_block_style(
        'core/column', // 会社概要
        array(
            'name'         => 'colum-company', // スタイル名
            'label'        => '会社情報中央よせ', // スタイルの表示名
        )
    );
}
add_action( 'init', 'custom_block_styles' );

//404
// メインセクションテンプレートを制御するためのフィルターを追加
add_filter(
	'lightning_is_main_section_template',
	function( $return ) {
		// もし現在のページが404エラーページならば
		if ( is_404() ) {
			// メインセクションテンプレートを無効にする
			$return = false;
		}
		// フィルターの結果を返す
		return $return;
	}
);
// メインセクションに内容を追加するためのアクションを追加
add_action(
	'lightning_main_section_append',
	function () {
		// もし現在のページが404エラーページならば
		if ( is_404() ) :
			$home_url = esc_url( home_url( '/' ) );
			// カスタマイズした404ページの内容を表示する
			echo '<div class="page404__title--area">
                            <h1 class="hero__title--menu">404</h1>
                            <span>ページが見つかりません</span>
                        </div>
                        <article class="page404__text--area lineheight page404__margin--bottom">
                            お探しのページは、削除されたか、名前が変更された可能性があります。<br class="page404__br--tb">
                            直接アドレスを入力された場合は<br class="page404__br--tb">アドレスが正しく入力されているか<span class="page404__text--sp">、</span>もう一度ご確認ください。<br>
                            <br>
                            ブラウザの再読み込みを行ってもこのページが表示される場合は<span class="page404__text--sp">、</span><br class="page404__br--tb">
                            <a href="' . $home_url . '" class="page404__link--bottom page404__link">TOPページ</a>から目的のページをお探しください。
                        </article> ';
		endif;
	}
);
