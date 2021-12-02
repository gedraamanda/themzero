<?php
//Includes
include dirname( __FILE__ ) . '/functions/credibilidade.php';

/*
 * Remove &nbsp dos posts;
*/
function remove_empty_lines( $content ){
    $content = preg_replace("/&nbsp;/", "", $content);
    return $content;
}
add_action('content_save_pre', 'remove_empty_lines');

/**
 * Thumbnails no wordpress
 */
add_theme_support( 'post-thumbnails' );
/**
 * Tamanhos das imagens para thumbs
 */
//add_image_size( 'thumb-card', 330, 248, true );

/**
 * Habilita o Title no wordpress
 */
add_theme_support( 'title-tag' );
/**
 * Remove scripts e estilos nativos do wordpress
 */
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

/**
 * Adiciona os estilos e scripts do tema
 */
function add_estilos_e_scripts() {
	// Estilos
	wp_enqueue_style( 'bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css' );
	wp_enqueue_style( 'css', get_template_directory_uri() . '/style.css');
	wp_enqueue_style( 'tiny-css', 'https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.3/tiny-slider.css');

	// Fontes
	wp_enqueue_style( 'epilogue', 'https://fonts.googleapis.com/css2?family=Epilogue:wght@300;700&display=swap'); //font-family: 'Epilogue', sans-serif;
	wp_enqueue_style( 'inter', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet'); //font-family: 'Inter', sans-serif;
	wp_enqueue_style( 'space', 'https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&display=swap" rel="stylesheet'); //font-family: 'Space Mono', monospace;

	// Scripts
	wp_deregister_script( 'jquery' );
	wp_enqueue_script( 'jquery', '//code.jquery.com/jquery-3.3.1.min.js', array(), '3.3.1', true );
	wp_enqueue_script( 'jquery');
	wp_enqueue_script( 'infinity', 'https://unpkg.com/infinite-scroll@4/dist/infinite-scroll.pkgd.min.js', array(), '', true);
    wp_enqueue_script( 'tiny-js', 'https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.2/min/tiny-slider.js', array(), '', true );
    wp_enqueue_script( 'lazyload', 'https://cdn.jsdelivr.net/npm/vanilla-lazyload@17.5.0/dist/lazyload.min.js' );

    wp_enqueue_script( 'scripts', get_template_directory_uri() . '/scripts.js', array(), '', true );

    //https://simpleparallax.com/#examples
    wp_enqueue_script( 'parallax-js', 'https://cdn.jsdelivr.net/npm/simple-parallax-js@5.6.1/dist/simpleParallax.min.js', array(), '', true );

    if(is_home()) {
	    wp_enqueue_script( 'slide-stories', get_template_directory_uri() . '/assets/js/slide-stories.js', array(), '', true );
    }
}
add_action( 'wp_enqueue_scripts', 'add_estilos_e_scripts' );

function add_scripts_admin()
{
	if (is_admin()) {
		$componentes_version = filemtime(dirname(__FILE__) . '/style_admin.css');
		wp_enqueue_style('css', get_template_directory_uri() . '/style_admin.css', array(), $componentes_version);
	}
}
add_action('admin_init', 'add_scripts_admin');

/**
 * Adiciona div responsiva para oEmbeds
 */
function responsive_embed_html( $html, $url ) {
    if ( preg_match( '/youtube.com/', $url ) || preg_match( '/vimeo.com/', $url ) ) {
        return '<div class="videoWrapper">' . $html . '</div>';
    } else {
        return $html;
    }
}

add_filter( 'embed_oembed_html', 'responsive_embed_html', 10, 3 );
add_filter( 'video_embed_html', 'responsive_embed_html' );

/**
 * Remove o meta generator do Wordpress
 */
remove_action('wp_head', 'wp_generator');

/**
 * Posições de Menu
function register_my_menu() {
	register_nav_menu('header-menu',__( 'Header Menu' ));
}
add_action( 'init', 'register_my_menu' ); */

/**
 * Ajustes do admin bar
 */

function arphabet_widgets_init() {
    register_sidebar( array(
		'name' => 'Home right sidebar',
		'id' => 'home_right_1',
		'before_widget' => '<div>',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="rounded">',
		'after_title' => '</h2>',
	) );
}
add_action( 'widgets_init', 'arphabet_widgets_init' );

function remove_admin_login_header() {
	remove_action('wp_head', '_admin_bar_bump_cb');
}
add_action('get_header', 'remove_admin_login_header');


add_action('init','wpse46108_register_param');
function wpse46108_register_param() {
	global $wp;
	$wp->add_query_var('modelo');
}

function get_image_prod( $post_id, $size) {
	$image_destaque   = get_the_post_thumbnail_url( $post_id, $size );

	if ( $_SERVER['HTTP_HOST'] === 'mural.spacecrane.com.br' ) {
		$image_destaque = str_replace('https://mural.spacecrane.com.br', 'https://agenciamural.org.br', $image_destaque);
		$image_destaque = str_replace('http://mural.spacecrane.com.br', 'https://agenciamural.org.br', $image_destaque);
    } else {
		$image_destaque = str_replace('http://mural.ms:7888', 'https://agenciamural.org.br', $image_destaque);
    }

	return $image_destaque;
}

function mural_assinaturaData( $post_id , $att = null ) {
	$tipo_assinatura = get_field('post_assinatura', $post_id);
	$data = esc_html( get_the_date('d.m.Y', $post_id) );

	$post_type = get_post_type($post_id);

	if ( $post_type === 'post' ) {
		if ( $tipo_assinatura === 'padrao' ) {
            $post_object  = get_post( $post_id );
            $usuario_nome = get_the_author_meta( 'display_name', $post_object->post_author );
            $usuario_link = get_author_posts_url( $post_object->post_author );

            return 'Por <a href="'.$usuario_link.'">'.$usuario_nome.'</a> | '.$data;
        } else {
            return 'Por Redação | '.$data;
        }
	} else {
	    $post_object  = get_post( $post_id );
        $usuario_nome = get_the_author_meta( 'display_name', $post_object->post_author );
        $usuario_link = get_author_posts_url( $post_object->post_author );

        return 'Por <a href="'.$usuario_link.'">'.$usuario_nome.'</a> | '.$data;
	}
}

function changeurl($url) {
	if ( $_SERVER['HTTP_HOST'] === 'mural.spacecrane.com.br' ) {
		$url = str_replace('http://mural.spacecrane.com.br', 'https://agenciamural.org.br', $url);
		$url = str_replace('https://mural.spacecrane.com.br', 'https://agenciamural.org.br', $url);
	} else {
		$url = str_replace('http://mural.ms:7888', 'https://agenciamural.org.br', $url);
	}

	return $url;
}

function mural_restrictMimeTypes($mimes)
{
	//-- Default
	$mimes = array(
		'jpg' => 'image/jpeg',
		'jpeg' => 'image/jpeg',
		'jpe' => 'image/jpeg',
		'gif' => 'image/gif',
		'png' => 'image/png',
		'pdf' => 'application/pdf',
		'zip' => 'application/zip',
		'mp3|m4a|m4b' => 'audio/mpeg',
		'mp4|m4v' => 'video/mp4'
	);
	//-- Add more mime type for specific user groups
	if ((current_user_can('administrator')) || (current_user_can('administration'))) {
		$mimes = array_merge($mimes, array('csv' => 'text/csv', 'svg' => 'image/svg+xml', 'svgz' => 'image/svg+xml'));
	}
	return $mimes;
}
add_filter('upload_mimes', 'mural_restrictMimeTypes');

add_action( 'acf/save_post', 'mural_saveWebstories', 20 );
function mural_saveWebstories($postId) {
    if(get_post_type($postId) != 'webstories') {
        return;
    }

    $zipFile = get_field('webstories_file');

    if ($zipFile) {
        $zipDir = get_attached_file($zipFile['ID']);
        $destination = wp_upload_dir();

        $folder = $destination['basedir'] . '/stories/' . $postId . '/';
        if (!file_exists($destination['basedir'] . '/stories/')) {
            mkdir($destination['basedir'] . '/stories/');
        } else {
            mural_deleteDirectory($folder);
            mkdir($folder);
        }

        $zip      = new ZipArchive;
        $response = $zip->open($zipDir);

        if ($response === true) {
            $path = pathinfo(realpath($folder), PATHINFO_DIRNAME) . '/' . $postId . '/';
            $zip->extractTo($path);
			$zip->close();

			if (file_exists($folder . '/index.html')) {
			    $date = new DateTime();
                $timestamp = $date->getTimestamp();

                $content = file_get_contents($folder . 'index.html');

                $content = str_replace("'", "\"", $content);

                preg_match_all('@rel="canonical" href="([^"]+)"@', $content, $rels);
                $rels = array_unique(array_pop($rels));
                foreach ($rels as $rel) {
                    $content = str_replace($rel, get_permalink($postId), $content);
                }

                preg_match_all('@amp-story-bookend src="([^"]+)"@', $content, $rels);
                $bookends = array_unique(array_pop($rels));
                foreach ($bookends as $bookend) {
                    $content = str_replace($bookend, get_option('siteurl') . '/wp-content/uploads/stories/' . $postId . '/bookend.json?v=' . $timestamp, $content);
                }

                preg_match_all('@(src)="([^"]+)"@', $content, $srcs);
                $srcs = array_unique(array_pop($srcs));
                foreach ($srcs as $src) {
                    if (strstr($src, 'assets/')) {
                        $content = str_replace($src, get_option('siteurl') . '/wp-content/uploads/stories/' . $postId . '/' . $src . '?v=' . $timestamp, $content);
                    }
                }

                // Change to publish, post the_content
				wp_update_post(array('ID' => $postId, 'post_content' => $content, 'post_status' => 'publish'));


            } else {
                // Change to draft, empty content
                wp_update_post(array('ID' => $postId, 'post_content' => '', 'post_status' => 'draft'));
            }

        } else {
            wp_update_post(array('ID' => $postId, 'post_status' => 'draft'));
        }

        wp_delete_attachment($zipFile['ID']);
        delete_field('webstories_file', $postId);
    } else {
        $post = get_post();
        if (!empty($post->post_content)) {
            $content = $post->post_content;
            preg_match_all('@rel="canonical" href="([^"]+)"@', $content, $rels);
            $rels = array_unique(array_pop($rels));
            foreach ($rels as $rel) {
                $content = str_replace($rel, get_permalink($postId), $content);
            }

            wp_update_post(array('ID' => $postId, 'post_content' => $content, 'post_status' => 'publish'));
        } else {
            wp_update_post(array('ID' => $postId, 'post_status' => 'draft'));
        }
    }
}

function mural_deleteDirectory($dirName)
{
	if (is_dir($dirName)) {
		$dirHandle = opendir($dirName);
	}
	if (!$dirHandle) {
		return false;
	}
	while ($file = readdir($dirHandle)) {
		if ($file != "." && $file != "..") {
			if (!is_dir($dirName . "/" . $file)) {
				unlink($dirName . "/" . $file);
			} else {
				mural_deleteDirectory($dirName . '/' . $file);
			}
		}
	}
	closedir($dirHandle);
	rmdir($dirName);

	return true;
}

//Renders
function mural_renderImage( $block_content, $block ) {
	$attr = $block['attrs'];

	if ( ! isset( $attr['caption'] ) ) {
		if ( preg_match( '#((?:<a [^>]+>\s*)?<img [^>]+>(?:\s*</a>)?)(.*)#is', $block_content, $matches ) ) {
			$block_content         = $matches[1];
			$attr['caption'] = trim( $matches[2] );
		}
	}

	$attachment_metadata = wp_get_attachment_metadata( $attr['id'] );

    $legenda = strip_tags( $attr['caption'] );

    if ( get_field( 'midia_creditos', $attr['id'] ) ) {
        $credito = get_field( 'midia_creditos', $attr['id'] );
    } else if ( ! empty( $attachment_metadata->credito_da_imagem ) ) {
        $credito = $attachment_metadata->credito_da_imagem;
    }

	$parallax = get_field('parallax', $attr['id']);
	if ( $parallax ) {
	    $imageFull = wp_get_attachment_image_src($attr['id'], 'full');

		ob_start(); ?>
        </div>
        </div>
        </div>

        <div class="especial__parallax my-5">
            <div class="parallax">
                <img src="<?php echo $imageFull[0] ?>" alt="" class="img-parallax w-100">
            </div>

            <?php if ( ! empty( $credito ) || ! empty( $legenda ) ) { ?>
                <div class="legenda-credito container mt-2 padding-mobile">
                    <div class="col-7 offset-md-3 int">
                        <?php
                        if ( ! empty( $legenda ) && empty( $credito ) ) {
                            echo '<p class="m-0">'.$legenda.'</p>';
                        }

                        if ( ! empty( $credito ) && empty($legenda) ) {
                            echo '<p class="m-0"><span class="credito ms-1">'.$credito.'</span></p>';
                        }

                        if(!empty($legenda) && !empty($credito)) {
                            echo '<p class="m-0">'.$legenda.' <span class="credito ms-1">'.$credito.'</span></p>';
                        }
                        ?>

                    </div>
                </div>
            <?php } ?>

        </div>

       <div class="especial__conteudo container mt-5">
        <div class="row">
			<div class="col-12 col-md-2 padding-mobile">
				<div class="especial-guia d-flex flex-column mt-2 position-sticky-desk">
					<span class="titulo border-bottom border-dark py-2 d-flex">NAVEGUE <i class="d-md-none ms-auto"></i></span>
                    <div class="flex-column navegue">
						<a href="#t-leitura" class="border-bottom border-dark active py-2">Início</a>
                    </div>
                </div>
			</div>

			<div class="col-12 col-md-7 offset-md-1 padding-mobile padding-mobile mt-5 mt-md-0 especial-conteudo-list">


		<?php return ob_get_clean();

    } else {

		$html = '';
		if ( ! empty( $credito ) || ! empty( $legenda ) ) {

			$html .= '<figcaption class="legenda-credito mx-auto text-start" style="width: ' . $attr['width'] . 'px;">';
			if ( ! empty( $legenda ) && empty( $credito ) ) {
				$html .= '<p>' . $legenda . '</p>';
			}

			if ( ! empty( $credito ) && empty($legenda) ) {
				$html .= '<span>' . $credito . '</span>';
			}

			if(!empty($legenda) && !empty($credito)) {
				$html .= '<p>' . $legenda . ' <span>@'.$credito.'</span></p>';
			}

			$html .= '</figcaption>';
		}

		return '<figure class="wp-block-image my-5 text-' . $attr['align'] . '">' . do_shortcode( $block_content ) . '' . $html . '</figure>';
    }



}
add_filter( 'render_block_core/image', 'mural_renderImage', 10, 2 );

//corrige imagens passado
add_shortcode('wp_caption', 'mural_removeWidthCaptionShortcode');
add_shortcode('caption', 'mural_removeWidthCaptionShortcode');
function mural_removeWidthCaptionShortcode($attr, $content = null) {
	if ( is_admin() ) {
		return;
	}

	$img_id = str_replace( 'attachment_', '', $attr['id'] );

	if (!isset($attr['caption'])) {
		if (preg_match('#((?:<a [^>]+>\s*)?<img [^>]+>(?:\s*</a>)?)(.*)#is', $content, $matches)) {
			$attr['caption'] = trim($matches[2]);
		}
	}

	preg_match( '/src="([^"]*)"/i', $content, $array ) ;

    $img_destaqueDesk = wp_get_attachment_image_src( $img_id, 'full' )[0];
    $img_srcMobile = wp_get_attachment_image_src( $img_id, 'medium' )[0];

	$size = wp_get_attachment_image_src( $img_id, 'large' )[1];

	$legenda = $attr['caption'];
	$credito = get_field( 'midia_creditos', $img_id );

	ob_start(); ?>

    <figure class="wp-block-image my-5">
        <picture>
            <source media="(max-width: 799px)" data-srcset="<?php echo changeurl($img_srcMobile)?>">
            <source media="(min-width: 800px)" data-srcset="<?php echo changeurl($img_destaqueDesk) ?>">
            <img data-src="<?php echo changeurl($img_destaqueDesk) ?>" alt="Image" class="lazy">
        </picture>

		<?php if ( ! empty( $credito ) || ! empty( $legenda ) ) { ?>

			<figcaption class="legenda-credito mx-auto text-start" style="width: <?php echo $size ?>px">
				<?php if ( ! empty( $legenda ) && empty( $credito ) ) { ?>
                    <p class="m-0"><?php echo $legenda ?></p>
				<?php }

				if ( ! empty( $credito ) && empty( $legenda ) ) { ?>
                    <p class="m-0"><span><?php echo $credito ?></span></p>
				<?php }

				if ( ! empty( $legenda ) && ! empty( $credito ) ) { ?>
                    <p class="m-0"><?php echo $legenda ?> <span>@<?php echo $credito ?></span></p>
				<?php } ?>

			</figcaption>
		<?php } ?>
    </figure>
	<?php
	return ob_get_clean();
}

add_action( 'after_setup_theme', 'default_attachment_display_settings' );
function default_attachment_display_settings() {
	update_option( 'image_default_link_type', 'none' );
}

if (!amp_is_request()) {
add_shortcode('gallery', 'mural_legadoGaleria');
}
function mural_legadoGaleria( $attr ) {
	if (empty($attr['ids'])) {
		return;
	}

	$imagesIds = explode(',', $attr['ids']);

	ob_start();
    ?>

    <div class="post-galeria-legado mt-4 mb-5 position-relative">
        <div class="legado-controls">
            <span class="prev me-1" data-controls="prev" aria-controls="customize" tabindex="-1"><</span>

            <span class="next" data-controls="next" aria-controls="customize" tabindex="-1">></span>
        </div>

        <div class="legado-slider">
            <?php foreach ( $imagesIds as $img_id ) {
                $legenda = wp_get_attachment_caption( $img_id );
                $credito = get_field( 'midia_creditos', $img_id );

                if(wp_is_mobile()) {
                    $image = wp_get_attachment_image_src($img_id, 'medium');
                } else {
                    $image = wp_get_attachment_image_src($img_id, 'large');
                } ?>

                <div class="bloco-legado">
                    <div class="imagem">
                        <img data-src="<?php echo changeurl($image[0]); ?>" alt="" class="w-100 lazy">
                    </div>

	                <?php if ( ! empty( $legenda ) || ! empty( $credito ) ) { ?>
                        <div class="legenda-credito active mt-2">
	                    <?php if ( ! empty( $legenda ) && empty( $credito ) ) { ?>
                            <p class="m-0 d-grid"><?php echo $legenda ?></p>
	                    <?php }

	                    if ( ! empty( $credito ) && empty( $legenda ) ) { ?>
                            <p class="m-0 d-grid"><span class="credito"><?php echo $credito ?></span></p>
	                    <?php }

	                    if ( ! empty( $legenda ) && ! empty( $credito ) ) { ?>
                            <p class="m-0 d-grid"><?php echo $legenda ?> <span class="credito">@<?php echo $credito ?></span></p>
	                    <?php } ?>
                    </div>
                   <?php  }?>

                </div>


            <?php } ?>
        </div>

    </div>
<?php
	return ob_get_clean();
}

function mural_renderGaleria($block_content, $block ) {
	if (empty($block['attrs']['ids'])) {
		return;
	} ?>

    <div class="post-galeria mb-5 mt-5">
        <div class="galeria-content">
            <?php foreach ( $block['attrs']['ids'] as $i => $img_id ) {
	            $legenda = wp_get_attachment_caption( $img_id );
	            $credito = get_field( 'midia_creditos', $img_id );

	            if(wp_is_mobile()) {
		            $image = wp_get_attachment_image_src($img_id, 'medium');
	            } else {
		            $image = wp_get_attachment_image_src($img_id, 'large');
	            } ?>

                <div class="galeria-imagem <?php echo $i === 0 ? 'active' : '' ?>">
                    <div class="galeria-content">
                        <img src="<?php echo $image[0] ?>" alt="" class="w-100">
                    </div>
                </div>

            <?php } ?>
        </div>

        <div class="galeria-arrow d-flex">
            <button class="left"></button>
            <button class="right ms-2"></button>
        </div>

        <div class="galeria-detalhes">
	        <?php foreach ( $block['attrs']['ids'] as $i => $img_id ) {
		        $legenda = wp_get_attachment_caption( $img_id );
		        $credito = get_field( 'midia_creditos', $img_id ); ?>

                <div class="legenda-credito <?php echo $i === 0 ? 'active' : '' ?>">
                    <?php if ( ! empty( $legenda ) && empty( $credito ) ) { ?>
                        <p class="m-0"><?php echo $legenda ?></p>
                    <?php }

                    if ( ! empty( $credito ) && empty( $legenda ) ) { ?>
                        <p class="m-0"><span class="credito ms-1"><?php echo $credito ?></span></p>
                    <?php }

                    if ( ! empty( $legenda ) && ! empty( $credito ) ) { ?>
                        <p class="m-0"><?php echo $legenda ?> <span class="credito ms-1">@<?php echo $credito ?></span></p>
                    <?php } ?>
                </div>
		    <?php } ?>
        </div>
    </div>

    <?php
}
add_filter( 'render_block_core/gallery', 'mural_renderGaleria', 10, 2 );


function mural_corOpacidade($color, $opacity = false)
{
	$default = 'rgb(0,0,0)';

	if (empty($color)) {
		return $default;
	}

	if ($color[0] == '#') {
		$color = substr($color, 1);
	}

	if (strlen($color) == 6) {
		$hex = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);
	} elseif (strlen($color) == 3) {
		$hex = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
	} else {
		return $default;
	}

	$rgb = array_map('hexdec', $hex);

	if ($opacity) {
		if (abs($opacity) > 1) {
			$opacity = 1.0;
		}
		$output = 'rgba(' . implode(",", $rgb) . ',' . $opacity . ')';
	} else {
		$output = 'rgb(' . implode(",", $rgb) . ')';
	}

	return $output;
}

// Friendly URI
add_filter( 'query_vars', 'mural_friendlyURIQuery' );
function mural_friendlyURIQuery( $query_vars ) {
	//Quabrada
	$query_vars[] = 'zona';
	$query_vars[] = 'bairro';

	//101 iniciaticas
	$query_vars[] = 'paginationIni';
	$query_vars[] = 'atuacao';



	return $query_vars;
}
add_action( 'init', 'mural_friendlyURI' );
function mural_friendlyURI() {

	// Quebrada
	add_rewrite_rule(
		'sua-quebrada/([^/]*)/?$',
		'index.php?pagename=sua-quebrada&zona=$matches[1]',
		'top');

	add_rewrite_rule(
		'sua-quebrada/([^/]*)/([^/]*)/?$',
		'index.php?pagename=sua-quebrada&zona=$matches[1]&bairro=$matches[2]',
		'top');

	//101 iniciativas ([0-9]{1,})
	add_rewrite_rule(
		'101-iniciativas/pagina/([0-9]{1,})/?$',
		'index.php?pagename=101-iniciativas&paginationIni=$matches[1]',
		'top');

	//delivery
	add_rewrite_rule(
		'a-boa-do-delivery-nas-periferias/pagina/([0-9]{1,})/?$',
		'index.php?pagename=a-boa-do-delivery-nas-periferias&paginationIni=$matches[1]',
		'top');

}

function mural_converteZona($zona) {
    if(empty($zona)) {
        return;
    }

	switch ( $zona ) {
		case 'grande-sp':
			$sigla = 'GDE SP';
			break;
		case 'zona-sul':
			$sigla = 'ZS';
			break;
		case 'zona-norte':
			$sigla = 'ZN';
			break;
		case 'zona-leste':
			$sigla = 'ZL';
			break;
		case 'zona-oeste':
			$sigla = 'ZO';
			break;

		default:
			$sigla = 'N/D';
			break;
	}

    return $sigla;
}

function mural_quebradaPost( $post_id ) {
    $quebradaPost = '';

    $quebrada = get_the_terms($post_id, 'quebrada');

    if ( empty( $quebrada ) ) {
	    return;
    }

	$qtdQuebradas = count($quebrada);

	if ( $qtdQuebradas == 1 ) {
		foreach ( $quebrada as $item ) {
			$zona = get_field( 'quebrada_zona', 'quebrada_' . $item->term_id );

			$quebradaPost .= '<a href="'.get_bloginfo( 'wpurl' ).'/sua-quebrada/'.$zona['value'].'/" class="zona text-uppercase px-2 text-center me-1">'.mural_converteZona($zona['value']).'</a>';
			$quebradaPost .= '<a href="'.get_bloginfo( 'wpurl' ).'/sua-quebrada/'.$zona['value'].'/'.$item->slug.'/" class="bairro text-uppercase border px-2 text-center me-1">'.$item->name.'</a>';
		}
    } else {
		$quebradaPost .= '<a href="'.get_bloginfo( 'wpurl' ).'/sua-quebrada/" class="bairro text-uppercase border px-2 text-center me-1">VÁRIAS QUEBRADAS</a>';
    }

	return $quebradaPost;
}

//na hora de salvar o post, salva a taxonomia quebrada + zona pra facilitar o filtro de sua quebrada
add_action( 'acf/save_post', 'mural_saveZona', 10, 4 );

function mural_saveZona ($post_id){
	if ( wp_is_post_revision( $post_id ) ) {
		return;
	}

	$quebradas = get_the_terms($post_id, 'quebrada');

	if ( ! empty( $quebradas ) ) {
	    $zonas = array();
		foreach ( $quebradas as $i => $item ) {
			$zona = get_field( 'quebrada_zona', 'quebrada_' . $item->term_id );
			$zonas[$i] = $zona['value'];
	    }

        if(!empty($zonas)) {
	        $array = array_unique($zonas);

	        delete_post_meta($post_id, 'post_zonas');

            update_post_meta($post_id, 'post_zonas', $array);
        }
    }
}

// leia tambem
wp_embed_register_handler(
	'internal_link',
	'#^(' . home_url() . '/.+)$#i',
	'mural_leiaTbm'
);
function mural_leiaTbm($matches, $attr, $url, $rawattr)
{
    $post_id = url_to_postid($url);

    ob_start();
    ?>

    <div class="leia-tambem d-flex flex-column mx-auto w-75 border-dark border-top border-bottom py-2 my-4">
        <span class="titulo d-block mb-2">LEIA TAMBÉM</span>

        <a href="<?php echo get_permalink($post_id) ?>"><?php echo get_the_title($post_id) ?></a>
    </div>
<?php

    $embed = str_replace(array("\r", "\n"), '', trim(ob_get_clean()));

	return apply_filters('embed_internalink', $embed, $matches, $attr, $url, $rawattr);
}

add_filter( 'script_loader_tag', 'catracalivre_enqueueAsyncAndDefer', 10, 3 );
function catracalivre_enqueueAsyncAndDefer( $tag, $handle, $src ) {
	 // if the unique handle/name of the registered script has 'async' in it
	if ( strpos( $handle, 'async' ) !== false ) {
		// return the tag with the async attribute
		return str_replace( '<script ', '<script async ', $tag );
	}
	// if the unique handle/name of the registered script has 'defer' in it
	elseif ( strpos( $handle, 'defer' ) !== false ) {
		// return the tag with the defer attribute
		return str_replace( '<script ', '<script defer ', $tag );
	}
	// otherwise skip
	else {
		return $tag;
	}
}

/**
 * Feed RSS customizado
 */
//function custom_rss() {
//	get_template_part( 'feed', 'rss2' );
//}
//remove_all_actions( 'do_feed_rss2' );
//add_action( 'do_feed_rss2', 'custom_rss', 10, 1 );
//
//// New feed for MSN
//add_action('init', 'mural_customRss');
//function mural_customRss()
//{
//	add_feed('msn', function () {
//		get_template_part('feed', 'msn');
//	});
//}


//Consulta de mais lidos
function mural_googleSheets() {
	$uri = 'https://sheets.googleapis.com/v4/spreadsheets/1taRz7s44fHhPdfj4eSD2wb7P7cqCu-AOlffQsjUEYB0/values/mural!A22:A30?majorDimension=COLUMNS&key=AIzaSyB2m6JJbDXeCpWtVrKhbIviK6Qf60_JgnU';

	$request = wp_remote_get( $uri );

	if ( is_wp_error( $request ) ) {
		return false;
	} else {
		$json = json_decode( wp_remote_retrieve_body( $request ) );
		$rows = $json->values;

		$maisLidos = array();
		//limpa amp e valida se é post
		if(!empty($rows)) {
		    foreach ($rows[0] as $row) {
		        $removeAmp = str_replace('amp/', '', $row);

		        $slug = explode('/', $removeAmp);
				$slug = $slug[1];

				if (!empty($slug)) {
					$validadePost = get_page_by_path( $slug, OBJECT, 'post' );

					if(!empty($validadePost) && !in_array($validadePost, $maisLidos)) {
					    array_push($maisLidos, $slug);
					}
				}
		    }
		}

		return $maisLidos;
	}
}

function add_cors_http_header(){
	header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
}
add_action('init','add_cors_http_header');

/**
 * Funções config amp
 */
add_action( 'amp_post_template_css', 'mural_amp_style' );
function mural_amp_style(){ ?>
    html{
    background:#000;
    }

    .amp-wp-header{
    background: #000;
    }

    .amp-wp-meta.amp-wp-tax-category a {
    color: #ff8226;
    }

    .amp-wp-meta.amp-wp-tax-tag a {
    color: #ff8226;
    }

    .amp-wp-footer{
    display:none;
    }

    .amp-wp-byline {
    display:none;
    }

    .amp-wp-article-header .amp-wp-meta:last-of-type {
    text-align:left;
    margin:0px
    }


<?php }

add_filter('the_content', function ($content) {
	if(amp_is_request()) {
		$autores = get_field('post_assinatura', get_the_ID());
		if ( !empty( $autores ) ) {
			if ( $autores === 'compartilhada' ) {
				$usuarios_assinando = get_field( 'post_autores', get_the_ID() );

				$autor_post = '<div class="amp-wp-author amp-wp-meta">Por: ';
				$qtd = count($usuarios_assinando);
				foreach ( $usuarios_assinando as $i => $usuarios ) {
					$usuario_nome = get_the_author_meta( 'display_name', $usuarios->ID );

					$autor_post .= $usuario_nome;

					if ($i+1 != $qtd) { //ultimo loop
						$autor_post .= ' e ';
					}

				}
				$autor_post .= '</div>';

				$content = $autor_post.$content;
			} else {
				$author_id = get_post_field ('post_author', get_the_ID());
				$usuario_nome = get_the_author_meta( 'display_name', $author_id );

				$autor_post = '<div class="amp-wp-author amp-wp-meta">Por: '.$usuario_nome.'</div>';

				$content = $autor_post.$content;
			}
		}
	}

	return $content;
});

add_filter( 'amp_post_template_data', function ( $data ) {
	$data['amp_component_scripts'] = array(
		'amp-analytics'      => 'https://cdn.ampproject.org/v0/amp-analytics-0.1.js',
		'amp-audio'          => 'https://cdn.ampproject.org/v0/amp-audio-0.1.js',
		'amp-image-lightbox' => 'https://cdn.ampproject.org/v0/amp-image-lightbox-0.1.js',
		'amp-iframe'         => 'https://cdn.ampproject.org/v0/amp-iframe-0.1.js',
		'amp-carousel'       => 'https://cdn.ampproject.org/v0/amp-carousel-0.1.js',
		'amp-anim'           => 'https://cdn.ampproject.org/v0/amp-anim-0.1.js',
		'amp-font'           => 'https://cdn.ampproject.org/v0/amp-font-0.1.js',
		'amp-sidebar'		 => 'https://cdn.ampproject.org/v0/amp-sidebar-0.1.js',
		'amp-ad'		 => 'https://cdn.ampproject.org/v0/amp-ad-0.1.js',
		'amp-sticky-ad' => 'https://cdn.ampproject.org/v0/amp-sticky-ad-1.0.js',
		'amp-auto-ads' => 'https://cdn.ampproject.org/v0/amp-auto-ads-0.1.js'
	);

	if ( preg_match( '/instagram/', $data['post_amp_content'] ) ) { //insta
		$data['amp_component_scripts'] = array_merge(
			$data['amp_component_scripts'],
			array(
				'amp-instagram' => 'https://cdn.ampproject.org/v0/amp-instagram-0.1.js',
			)
		);
	}

	if ( preg_match( '/twitter/', $data['post_amp_content'] ) ) { //twitter
		$data['amp_component_scripts'] = array_merge(
			$data['amp_component_scripts'],
			array(
				'amp-twitter'        => 'https://cdn.ampproject.org/v0/amp-twitter-0.1.js',
			)
		);
	}

	if ( preg_match( '/youtube/', $data['post_amp_content'] ) ) { //youtube
		$data['amp_component_scripts'] = array_merge(
			$data['amp_component_scripts'],
			array(
				'amp-youtube'        => 'https://cdn.ampproject.org/v0/amp-youtube-0.1.js',
			)
		);
	}

	if ( preg_match( '/vine.com/', $data['post_amp_content'] ) ) { //vine
		$data['amp_component_scripts'] = array_merge(
			$data['amp_component_scripts'],
			array(
				'amp-vine'           => 'https://cdn.ampproject.org/v0/amp-vine-0.1.js',
			)
		);
	}

	if ( preg_match( '/facebook/', $data['post_amp_content'] ) ) { //facebook
		$data['amp_component_scripts'] = array_merge(
			$data['amp_component_scripts'],
			array(
				'amp-facebook'       => 'https://cdn.ampproject.org/v0/amp-facebook-0.1.js',
			)
		);
	}

	if ( preg_match( '/pinterest/', $data['post_amp_content'] ) ) { //facebook
		$data['amp_component_scripts'] = array_merge(
			$data['amp_component_scripts'],
			array(
				'amp-pinterest'      => 'https://cdn.ampproject.org/v0/amp-pinterest-0.1.js',
			)
		);
	}

	if ( preg_match( '/vimeo/', $data['post_amp_content'] ) ) { //facebook
		$data['amp_component_scripts'] = array_merge(
			$data['amp_component_scripts'],
			array(
				'amp-vimeo'		     => 'https://cdn.ampproject.org/v0/amp-vimeo-0.1.js',
			)
		);
	}

	$data['site_icon_url']         = 'https://www.agenciamural.org.br/wp-content/uploads/2018/06/cropped-FAVICON-1-2-32x32.png';
	$data['blog_name']             = 'Agência Mural';

	return $data;

});

add_filter( 'amp_post_template_metadata', 'mural_amp_insert_logo', 10, 2 );
function mural_amp_insert_logo( $metadata, $post ) {
	$metadata['publisher']['logo'] = array(
		'@type'  => 'ImageObject',
		'url'    => 'https://www.agenciamural.org.br/wp-content/uploads/2018/06/maca-mural-site-org-300x111.png',
		'height' => 300,
		'width'  => 111
	);

	return $metadata;
}

add_filter( 'amp_post_article_footer_meta', 'mural_amp_change_footer' );

function mural_amp_change_footer( $meta_parts ) {
	foreach ( array_keys( $meta_parts, 'meta-comments-link', true ) as $key ) {
		unset( $meta_parts[ $key ] );
	}

	return $meta_parts;
}

add_action( 'amp_post_template_footer', function () { ?>

    <amp-analytics type="googleanalytics" id="analytics1">
        <script type="application/json">
            {
                "triggers": {
                    "trackPageview": {
                        "on": "visible",
                        "request": "pageview",
                        "vars": {
                            "account": "UA-60520414-5",
                            "ampdocUrl": "https://agenciamural.org.br/"
                        }
                    }
                }
            }
        </script>
    </amp-analytics>

    <?php
} );

/**
 * Facebook pixel
 */
add_action('wp_head',function (){ ?>
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({
            google_ad_client: "ca-pub-8169398702573597",
            enable_page_level_ads: true
        });
    </script>


    <!-- Facebook Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '513856689462538');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
                   src="https://www.facebook.com/tr?id=513856689462538&ev=PageView&noscript=1"
        /></noscript>
    <!-- End Facebook Pixel Code -->
<?php });

//ads
add_action('wp_head', function () {
    if(is_single()) { ?>
        <script async src="https://securepubads.g.doubleclick.net/tag/js/gpt.js"></script>
        <script>
            window.googletag = window.googletag || {cmd: []};
            googletag.cmd.push(function() {
                googletag.defineSlot('/130882887/mural_arroba_sidebar', [300, 250], 'div-gpt-ad-1584544963046-0').addService(googletag.pubads());
                googletag.pubads().enableSingleRequest();
                googletag.pubads().collapseEmptyDivs();
                googletag.enableServices();
            });
        </script>
    <?php }
});

