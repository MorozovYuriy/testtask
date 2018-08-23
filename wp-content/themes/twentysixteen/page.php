<?php
/**
 * The template for displaying pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<?php
		// Start the loop.
		while ( have_posts() ) : the_post();

			// Include the page content template.
			get_template_part( 'template-parts/content', 'page' );

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}

			// End of the loop.
		endwhile;
		?>


        <?php
        if($_POST) {

            $url = trim($_POST['site']);

            $ip  = trim($_POST['remote_addr']);

            $header[0]  = "Accept: text/xml,application/xml,application/xhtml+xml,";
            $header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
            $header[] = "Cache-Control: max-age=0";
            $header[] = "Connection: keep-alive";
            $header[] = "Keep-Alive: 300";
            $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
            $header[] = "Accept-Language: en-us,en;q=0.5";

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_USERAGENT, 'Googlebot/2.1 (+http://www.google.com/bot.html)');
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
            curl_setopt($curl, CURLOPT_REFERER, 'http://www.google.com');
            curl_setopt($curl, CURLOPT_AUTOREFERER, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_PROXY, $ip);
            curl_setopt($curl, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
            curl_setopt($curl, CURLOPT_TIMEOUT, 10);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
            $response = curl_exec($curl);

            if ($response === false) {

                $to = $_POST['email'];
                $subject = "Error from test site";

                $message = '
                <html>
                <head>
                    <title>Error from Test-site</title>
                </head>
                <body>
                <p><h5>Error '. curl_errno($curl) .': '. curl_error($curl).'</h5></p>
                </body>
                </html>';

                $headers  = "Content-type: text/html; charset=windows-1251 \r\n";
                $headers .= "From: test-site\r\n";

                //mail($to, $subject, $message);

                $m = mail('morozovyuriy11@gmail.com', 'првиет', 'привет');

                echo 'Произошла ошибка, детали на почте '. $to;
                exit;

            } else {

                $result['httpcode'] = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                $result['total_time'] = curl_getinfo($curl, CURLINFO_TOTAL_TIME);

                echo '
                    <p><h4>Ответ сервера: <span>'.$result['httpcode'].'</span></h4></p>
                    <p><h4>Время загрузки: <span>'.$result['total_time'].' секунд</span></h4></p>
                ';
            }

            curl_close($curl);

            exit;
        }
        ?>

	</main><!-- .site-main -->

	<?php get_sidebar( 'content-bottom' ); ?>

</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
