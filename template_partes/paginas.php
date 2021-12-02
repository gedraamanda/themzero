<?php

get_header();

$post = get_post( get_the_ID() );
$slug = get_permalink();
$img_destaquemobile = get_image_prod( get_the_ID(), 'medium' );
$img_destaqueDesk = get_image_prod( get_the_ID(), 'large' );


?>

<header class="header-peq mt-4 mt-md-5">
	<div class="container">
		<div class="row">
			<div class="col-12 col-md-6 offset-md-3 texto flex-column text-center padding-mobile">
				<h1 class="m-0 mt-md-4 mt-3 mx-auto"><?php echo get_the_title(get_the_ID()) ?></h1>
			</div>

			<div class="col-12 col-md-6 offset-md-3 imagem mt-5 padding-mobile">
				<picture>
					<source media="(max-width: 799px)" data-srcset="<?php echo $img_destaquemobile ?>">
					<source media="(min-width: 800px)" data-srcset="<?php echo $img_destaqueDesk ?>">
					<img data-src="<?php echo $img_destaqueDesk ?>" alt="Image" class="w-100 lazy">
				</picture>
			</div>
		</div>
	</div>
</header>


<div class="single paginas mb-5">
    <div class="single__int container mt-5">
        <div class="row conteudo">
            <div class="col-12 col-md-9 padding-mobile mx-auto conteudo__texto">
	            <?php
                if ( has_blocks( $post->post_content ) ) {
		            $blocks = parse_blocks( $post->post_content );

		            foreach ( $blocks as $block ) {
			            echo apply_filters( 'the_content', render_block( $block ) );
		            }
	            } else { //legado
		            echo apply_filters( 'the_content', $post->post_content );
	            }
	            ?>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();
?>
