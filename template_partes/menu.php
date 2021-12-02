<?php
$menus = wp_get_nav_menu_items('hamburguer');

?>

<div class="menu position-sticky w-100 top-0 <?php echo is_home() ? 'menu-home py-3 py-md-4' : 'menu-internas py-md-4 py-3' ?>">
    <div class="container">
        <div class="d-flex menu-int">
	        <?php if ( is_home() ) { ?>
                <div class="flex-grow-1 int-resize d-none d-md-block">
                    <a href="<?php echo get_bloginfo( 'wpurl' ) ?>">
                        <img src="<?php echo get_template_directory_uri() ?>/assets/images/logo-grande.jpeg" alt=""
                             id="resize-logo" width="385">
                    </a>
                </div>

                <div class="flex-grow-1 d-md-none">
                    <a href="<?php echo get_bloginfo( 'wpurl' ) ?>">
                        <img src="<?php echo get_template_directory_uri() ?>/assets/images/logo-grande.jpeg" alt=""
                              width="180">
                    </a>
                </div>
	        <?php } else { ?>
                <div class="flex-grow-1">
                    <a href="<?php echo get_bloginfo( 'wpurl' ) ?>">
                        <img src="<?php echo get_template_directory_uri() ?>/assets/images/logo-grande.jpeg" alt=""
                               width="194">
                    </a>
                </div>
	        <?php } ?>

            <div class="int search d-none d-md-flex position-relative">
	            <?php if ( ! is_home() ) { ?>
                    <a href="javascript:;" class="apoie apoie me-3 mt-1 open-modal">
                        <span class="text-uppercase px-2">apoie</span>
                    </a>
                <?php } ?>

                <a href="javascript:;" class="search">
                    <i class="me-2"></i>
                </a>

                <a href="javascript:;" class="open-menu pt-2 ms-2">
                    <span></span>
                    <span></span>
                    <span></span>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="menu-hide">
    <a href="javascript:;" class="close-menu"></a>

    <div class="d-flex">
        <div class="menu-hide__principal d-flex flex-column">
            <a href="<?php echo esc_url(get_bloginfo( 'wpurl' ).'/sua-quebrada/') ?>" class="py-2 open-sub">Sua quebrada <span class="ms-md-5 ms-2">></span></a>
            <?php
            if ( ! empty( $menus ) ) {
	            foreach ( $menus as $menu ) {
		            if ( $menu->type === 'taxonomy' ) {
			            $nome = $menu->title;
			            $link = $menu->url;
		            } elseif ( $menu->type === 'custom' ) {
			            $nome = $menu->post_title;
			            $link = $menu->url;
		            } ?>

		            <a href="<?php echo esc_url($link) ?>" class="py-2 <?php echo $nome === 'Podcast' || $nome === 'Webstories' ? 'c-orange' : '' ?>"><?php echo $nome ?></a>
                <?php }
            }
            ?>

            <a href="javascript:;" class="apoie text-uppercase text-center mt-3 open-modal">apoie a mural</a>

            <div class="menu-hide__social social-mural d-flex mt-4">
                <a href="https://www.instagram.com/agenciamural" target="_blank" class="instagram me-3"><i></i></a>
                <a href="https://twitter.com/agmural/" target="_blank" class="twitter me-3"><i></i></a>
                <a href="https://www.facebook.com/agenciamural" target="_blank" class="facebook me-3"><i></i></a>
                <a href="https://www.linkedin.com/company/ag%C3%AAncia-mural-de-jornalismo-das-periferias" target="_blank" class="linkedin me-3"><i></i></a>
                <a href="https://web.whatsapp.com/send?phone=+5511933491709&text=Oi!%20Quero%20receber%20as%20not%C3%ADcias%20da%20*Mural*%20no%20WhatsApp!" target="_blank" class="whats"><i></i></a>
            </div>
        </div>

        <div class="menu-hide__sub d-flex flex-column ms-4 mt-2">
            <a href="<?php echo esc_url(get_bloginfo( 'wpurl' ).'/sua-quebrada/zona-norte/') ?>" class="py-2 pe-1 text-uppercase">zona norte</a>
            <a href="<?php echo esc_url(get_bloginfo( 'wpurl' ).'/sua-quebrada/zona-sul/') ?>" class="py-2 pe-1 text-uppercase">zona sul</a>
            <a href="<?php echo esc_url(get_bloginfo( 'wpurl' ).'/sua-quebrada/zona-leste/') ?>" class="py-2 pe-1 text-uppercase">zona leste</a>
            <a href="<?php echo esc_url(get_bloginfo( 'wpurl' ).'/sua-quebrada/zona-oeste/') ?>" class="py-2 pe-1 text-uppercase">zona oeste</a>
            <a href="<?php echo esc_url(get_bloginfo( 'wpurl' ).'/sua-quebrada/grande-sp/') ?>" class="py-2 pe-1 text-uppercase">grande sp</a>
        </div>
    </div>
</div>

<div class="menu-mobile d-md-none position-fixed bottom-0">
    <div class="menu-mobile__int d-flex h-100">
        <a href="#" class="tijolo flex-fill pt-1"><i></i></a>
        <a href="javascript:;" class="apoie flex-fill open-modal"><i></i></a>
        <a href="#" class="share flex-fill"><i></i></a>
        <a href="#" class="search flex-fill"><i></i></a>
        <a href="javascript:;" class="open-menu flex-fill">
            <div class="hamburguer">
                <span></span>
                <span></span>
                <span></span>
            </div>

        </a>
    </div>
</div>

<div class="apoiepop position-fixed">
    <div class="apoiepop__int border border-2 border-dark py-4 px-md-5 px-3">
        <a href="javascript:;" class="close close-modal"><i></i></a>

        <div class="d-flex flex-column text-center justify-content-center">
            <h3 class="m-0 mb-2 mt-3 mt-md-0">APOIE A AGÊNCIA MURAL</h3>

            <p class="m-0 desc mb-3">Colabore com o nosso jornalismo independente feito pelas e para as periferias.</p>

            <a href="https://www.catarse.me/periferias" target="_blank" class="btn d-flex mx-auto mb-3">DOE MENSALMENTE PELO CATARSE <i class="mt-2 ms-3 d-none d-md-block"></i></a>

            <p class="m-0 mb-2">OU</p>

            <span class="mb-2">MANDE UM PIX</span>

            <img src="https://www.agenciamural.org.br/wp-content/uploads/2021/11/qrcode.jpg" alt="qrcode" width="160" class="mb-2 mx-auto">

            <p class="m-0 deta mb-1">Escaneie o qr code ou use a Chave pix:</p>

            <p class="color m-0">30.200.721/0001-06 </p>
        </div>
    </div>
</div>


