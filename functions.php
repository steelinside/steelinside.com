<?php

/* 
 * Функции в functions.php в дочерней теме обёрнутые 
 * в function_exists() будут исполнятся
 * вместо таких же в functions.php родительской темы.
*/

/*--------------------------- HEADER ------------------------------------*/

/*Добавляем Фавикон в header.php*/
add_action( 'wp_head', 'favicon', 5);
function favicon(){
    echo "<link rel='shortcut icon' href='" . get_stylesheet_directory_uri() . "/images/favicon.ico' />" . "\n";
}

add_action('wp_head','google_verification', 10);
function google_verification(){
	echo "<meta name='google-site-verification' content='EeDUH9QOXfKg8_Q1-zbKfNxec51dPOOFWbwbcBlKlE8' />"."\n";
}

/*Добавляем шрифт Roboto Slab в хедер сайта*/
add_action('wp_head', 'add_roboto_slab', 15);
	function add_roboto_slab(){
		echo"<link href='http://fonts.googleapis.com/css?family=Roboto+Slab&subset=latin,cyrillic' rel='stylesheet' type='text/css'>" . "\n";
}

/*--------------------------- FOOTER ------------------------------------*/

/*Добавляем JQuery в футер*/
add_action('wp_footer', 'call_jquery');
function call_jquery(){
	echo '<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js?ver=3.7"></script>'."\n";
}

/*Добавляем вызов функции scrollup в футер*/
function scrolltotop(){
	echo '<script type="text/javascript" src="'. get_stylesheet_directory_uri() .'/js/scrollup.js"></script>' ."\n";
}
add_action('wp_footer','scrolltotop');

/*Добавляем Google Analytics в футер*/
add_action('wp_footer', 'add_googleanalytics');
function add_googleanalytics() { ?>
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-30433132-1']);
  _gaq.push(['_trackPageview']);
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'stats.g.doubleclick.net/dc.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
<?php }

/*Попап для социальных кнопок*/
function social_popup() { ?>
<script>
(function() {
    // связываем селектор и размер окна
    var Config = {
        Link: "a.share",
        Width: 500,
        Height: 500
    };
 
    // добавляемы ссылки
    var slink = document.querySelectorAll(Config.Link);
    for (var a = 0; a < slink.length; a++) {
        slink[a].onclick = PopupHandler;
    }
 
    // попап
    function PopupHandler(e) {
 
        e = (e ? e : window.event);
        var t = (e.target ? e.target : e.srcElement);
 
        // расположение попапа
        var
            px = Math.floor(((screen.availWidth || 1024) - Config.Width) / 2),
            py = Math.floor(((screen.availHeight || 700) - Config.Height) / 2);
 
        // открываем попап
        var popup = window.open(t.href, "social", 
            "width="+Config.Width+",height="+Config.Height+
            ",left="+px+",top="+py+
            ",location=0,menubar=0,toolbar=0,status=0,scrollbars=1,resizable=1");
        if (popup) {
            popup.focus();
            if (e.preventDefault) e.preventDefault();
            e.returnValue = false;
        }
 
        return !!popup;
    }
 
}());
</script>
<?php }

add_action('wp_footer' , 'social_popup');

/*--------------------------- ВИДЖЕТЫ -------------------------------------*/

/*Регистрируем новый сайдбар в футере*/
if (function_exists('register_sidebar')) {
     register_sidebar(array(
      'name' => 'Виджет в футере',
      'id'   => 'footer-widget',
      'description'   => 'Виджет, живущий в футере. В нём копирайт, дисклаймер, контакты и подписка.',
      'before_widget' => '<div class="footer-columns">',
      'after_widget'  => '</div>',
      'before_title'  => '<h3 class = "widget-title">',
      'after_title'   => '</h3>'
     ));
}

/*Регистрируем новый с информацией о себе*/
if (function_exists('register_sidebar')) {
     register_sidebar(array(
	'name' => 'Виджет с информацией',
	'id'   => 'bc-sidebar',
	'description' => __( 'Сюда нужно положить немного информации о себе.', 'twentytwelve' ),
	'before_widget' => '<aside id="%1$s" class="widget %2$s">',
	'after_widget' => '</aside>',
	'before_title' => '<h3 class="widget-title">',
	'after_title' => '</h3>',
     ));
}


/*--------------------------- КОНТЕНТ -------------------------------------*/

/*Меняем состав Entry Meta*/
if ( ! function_exists( 'twentytwelve_entry_meta' ) ) :
function twentytwelve_entry_meta() {
	// После запятой стоит пробел, имей ввиду.
	$tag_list = get_the_tag_list( '', __( ', ', 'twentytwelve' ) );

	$date = sprintf( '<span style="font-size:.89rem"><time class="entry-date" datetime="%3$s">%4$s</time></span>',
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);

	// 1 — это раздел
	// 2 — это тэг
	// 3 — это дата
	// 4 — автор.
	// Иконка комментариев прячется в content.php
	if ( $tag_list ) {
		$utility_text = __( '
		<span class="meta-part"><span class="icon-clock-o small"></span>&thinsp;%3$s</span>
		<span class="meta-part"><span class = "icon-tag small"></span>&thinsp;%2$s</span>
		', 'twentytwelve' );
	}
	printf(
		$utility_text,
		$categories_list,
		$tag_list,
		$date,
		$author
	);
}
endif;

/*Говорим YARP'у, какого размера должны быть превьюшки*/
add_image_size( 'yarpp-thumbnail', 230, 153, true );

/*Регистрируем социальные кнопки*/
add_action('social_buttons' , '' , 25);
function social_buttons(){ ?>
	<!--Социальные кнопки-->
	<h4>Поделиться с друзьями:</h4>
	<div class="social">
		<!--VK-->
		<a class = "vk share" href = "https://vk.com/share.php?url=<?php the_permalink();?>&title=<?php the_title(); ?>&" title="ВКонтакте"><span class = "icon-vk"></span>&nbsp;<span class = "social-name">ВКонтакте</span></a>
		<!--Facebook-->
		<a class="facebook share" href="http://www.facebook.com/sharer.php?u=<?php the_permalink();?>&amp;t=<?php the_title(); ?>" title="Поделиться на Фейсбуке"><span class = "icon-facebook"></span>&nbsp;<span class = "social-name">Facebook</span></a>	<!--Twitter-->
		<a class="twitter share" href="http://twitter.com/home?status=Читаю: <?php the_permalink(); ?>" title="Затвитить!"><span class = "icon-twitter"></span>&nbsp;<span class = "social-name">Twitter</span></a>
	</div><?php;
}

/*-------------------------------- ПРОЧЕЕ ------------------------------*/

/*Перенаправляем RSS на Feedburner*/
add_action('template_redirect', 'rss_redirect');
function rss_redirect() {
	if ( is_feed() && !preg_match('/feedburner|feedvalidator/i', $_SERVER['HTTP_USER_AGENT'])){
		header('Location: http://feeds.feedburner.com/steelinside/feed');
		header('HTTP/1.1 302 Temporary Redirect');
	}
}

/*Меняем логотип на экране входа*/
add_action( 'login_head', 'st_custom_login');
function st_custom_login() {
	echo '<style type="text/css">
	h1 a { background-image:url('. get_stylesheet_directory_uri() . '/images/logo.svg' . ') !important; background-size:325px !important; height:55px !important; width:auto !important;}
	padding: 20px;}
	</style>
	<script type="text/javascript">window.onload = function(){document.getElementById("login").getElementsByTagName("a")[0].href = "'. home_url() . '";document.getElementById("login").getElementsByTagName("a")[0].title = "Перейти на steelinside.com";}</script>';
}

/*Убираем Open Sans из темы*/
function remove_open_sans() {
   wp_dequeue_style( 'twentytwelve-fonts' );
}
add_action('wp_print_styles','remove_open_sans');

/*Удаляем часть стандартных виджетов*/
function remove_default_widget() {
	unregister_widget('WP_Widget_Archives'); // Архивы
	unregister_widget('WP_Widget_Calendar'); // Календарь
	unregister_widget('WP_Widget_Categories'); // Рубрики
	unregister_widget('WP_Widget_Meta'); // Мета
	unregister_widget('WP_Widget_Pages'); // Страницы
	unregister_widget('WP_Widget_Recent_Comments'); // Свежие комментарии
	unregister_widget('WP_Widget_Recent_Posts'); // Свежие записи
	unregister_widget('WP_Widget_RSS'); // RSS
	unregister_widget('WP_Widget_Search'); // Поиск
}
add_action( 'widgets_init', 'remove_default_widget', 20 );

/*Убираем сайдбары домашней страницы*/
function st_remove_sidebar(){
    unregister_sidebar( 'sidebar-2' );
    unregister_sidebar( 'sidebar-3' );
}
add_action( 'widgets_init', 'st_remove_sidebar', 11 );

?>