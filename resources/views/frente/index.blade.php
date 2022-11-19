@extends('frente.layout')
@section('slide')
@endsection


@section('detail')
    <!-- Banner -->


    <div class="sec-banner bg0 p-t-80 p-b-50">
        <div class="container">
            <section class="section-slide">
                <div class="wrap-slick1">
                    <div class="slick1">
                        <div class="item-slick1" style="background-image: url(images/teste2.png);">
                            <div class="container h-full">
                                <div class="flex-col-l-m h-full p-t-100 p-b-30 respon5">
                                    <div class="layer-slick1 animated visible-false" data-appear="fadeInDown" data-delay="0">
								<span class="ltext-101 cl2 respon2" >
									Ecomoda
								</span>
                                    </div>

                                    <br>
                                    <div class="layer-slick1 animated visible-false" data-appear="fadeInUp" data-delay="800">
                                        <h2 class="ltext-201 cl2 p-t-19 p-b-43 respon1">
                                            Apoie  <br/>  essa <br/> ideia!
                                        </h2>
                                    </div>

                                    <div class="layer-slick1 animated visible-false" data-appear="zoomIn" data-delay="1600">
                                        <a href="product.html" class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04">
                                            Compre agora
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item-slick1" style="background-image: url(images/slide-01.jpg);">
                            <div class="container h-full">
                                <div class="flex-col-l-m h-full p-t-100 p-b-30 respon5">
                                    <div class="layer-slick1 animated visible-false" data-appear="fadeInDown" data-delay="0">
								<span class="ltext-101 cl2 respon2">
									Coleção 2022
								</span>
                                    </div>

                                    <div class="layer-slick1 animated visible-false" data-appear="fadeInUp" data-delay="800">
                                        <h2 class="ltext-201 cl2 p-t-19 p-b-43 respon1">
                                            Nova Temporada
                                        </h2>
                                    </div>

                                    <div class="layer-slick1 animated visible-false" data-appear="zoomIn" data-delay="1600">
                                        <a href="product.html" class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04">
                                           Compre agora
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="item-slick1" style="background-image: url(images/slide-03.jpg);">
                            <div class="container h-full">
                                <div class="flex-col-l-m h-full p-t-100 p-b-30 respon5">
                                    <div class="layer-slick1 animated visible-false" data-appear="rotateInDownLeft" data-delay="0">
								<span class="ltext-101 cl2 respon2">
									Coleçã 2022
								</span>
                                    </div>

                                    <div class="layer-slick1 animated visible-false" data-appear="rotateInUpRight" data-delay="800">
                                        <h2 class="ltext-201 cl2 p-t-19 p-b-43 respon1">
                                            Novos Produtos
                                        </h2>
                                    </div>

                                    <div class="layer-slick1 animated visible-false" data-appear="rotateIn" data-delay="1600">
                                        <a href="product.html" class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04">
                                            Compre agora!
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>




                </div>
            </section>




        </div>
    </div>
    <section class="bg0 p-t-23 p-b-140">
        <div class="container">
            <div class="p-b-10">
                <h3 class="ltext-103 cl5">
                    Produtos Disponíveis
                </h3>
            </div>

            <div class="flex-w flex-sb-m p-b-52">
                <div class="flex-w flex-l-m filter-tope-group m-tb-10">
                    <button class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5 how-active1" data-filter="*">
                        Todos os Produtos
                    </button>

                    <?php
                    $allTypes = \App\Models\TipoAnuncio::all();
                    ?>
                    @foreach($allTypes as $type)
                    <button class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5" data-filter="{{".".$type->id}}">
                        {{$type->descricao}}
                    </button>
                    @endforeach

<!--
                    <button class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5" data-filter=".men">
                        Bordados
                    </button>

                    <button class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5" data-filter=".bag">
                        Tecidos
                    </button>
-->

                </div>

                <div class="flex-w flex-c-m m-tb-10">
                    <div class="flex-c-m stext-106 cl6 size-104 bor4 pointer hov-btn3 trans-04 m-r-8 m-tb-4 js-show-filter">
                        <i class="icon-filter cl2 m-r-6 fs-15 trans-04 zmdi zmdi-filter-list"></i>
                        <i class="icon-close-filter cl2 m-r-6 fs-15 trans-04 zmdi zmdi-close dis-none"></i>
                        Filtro
                    </div>

                    <div class="flex-c-m stext-106 cl6 size-105 bor4 pointer hov-btn3 trans-04 m-tb-4 js-show-search">
                        <i class="icon-search cl2 m-r-6 fs-15 trans-04 zmdi zmdi-search"></i>
                        <i class="icon-close-search cl2 m-r-6 fs-15 trans-04 zmdi zmdi-close dis-none"></i>
                        Pesquisa
                    </div>
                </div>

                <!-- Search product -->
                <div class="dis-none panel-search w-full p-t-10 p-b-15">
                    <div class="bor8 dis-flex p-l-15">
                        <button class="size-113 flex-c-m fs-16 cl2 hov-cl1 trans-04">
                            <i class="zmdi zmdi-search"></i>
                        </button>

                        <input class="mtext-107 cl2 size-114 plh2 p-r-15" type="text" name="search-product" placeholder="Search">
                    </div>
                </div>

                <!-- Filter -->
                <div class="dis-none panel-filter w-full p-t-10">
                    <div class="wrap-filter flex-w bg6 w-full p-lr-40 p-t-27 p-lr-15-sm">
                        <div class="filter-col1 p-r-15 p-b-27">
                            <div class="mtext-102 cl2 p-b-15">
                                Ordenado
                            </div>

                            <ul>
                                <li class="p-b-6">
                                    <a href="#" class="filter-link stext-106 trans-04">
                                        Padrão
                                    </a>
                                </li>

                                <li class="p-b-6">
                                    <a href="#" class="filter-link stext-106 trans-04">
                                        Popularidade
                                    </a>
                                </li>

                                <li class="p-b-6">
                                    <a href="#" class="filter-link stext-106 trans-04">
                                        Média
                                    </a>
                                </li>

                                <li class="p-b-6">
                                    <a href="#" class="filter-link stext-106 trans-04 filter-link-active">
                                        Novidade
                                    </a>
                                </li>

                                <li class="p-b-6">
                                    <a href="#" class="filter-link stext-106 trans-04">
                                        Preço: Mais barato para mais caro
                                    </a>
                                </li>

                                <li class="p-b-6">
                                    <a href="#" class="filter-link stext-106 trans-04">
                                        Preço: Mais caro para mais barato
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="filter-col2 p-r-15 p-b-27">
                            <div class="mtext-102 cl2 p-b-15">
                                Preço
                            </div>

                            <ul>
                                <li class="p-b-6">
                                    <a href="#" class="filter-link stext-106 trans-04 filter-link-active">
                                        Todos
                                    </a>
                                </li>

                                <li class="p-b-6">
                                    <a href="#" class="filter-link stext-106 trans-04">
                                        R$ 0.00 - R$ 10.00
                                    </a>
                                </li>

                                <li class="p-b-6">
                                    <a href="#" class="filter-link stext-106 trans-04">
                                        R$ 10.00 - R$ 50.00
                                    </a>
                                </li>

                                <li class="p-b-6">
                                    <a href="#" class="filter-link stext-106 trans-04">
                                        R$ 50,00 - R$ 100,00
                                    </a>
                                </li>

                                <li class="p-b-6">
                                    <a href="#" class="filter-link stext-106 trans-04">
                                        R$ 100,00 - R$ 150,00
                                    </a>
                                </li>

                                <li class="p-b-6">
                                    <a href="#" class="filter-link stext-106 trans-04">
                                        R$ 150,00+
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="filter-col3 p-r-15 p-b-27">
                            <div class="mtext-102 cl2 p-b-15">
                                Cor
                            </div>

                            <ul>
                                <?php $cores = \App\Models\CorAnuncio::all() ?>

                                @foreach($cores as $cor)
                                <li class="p-b-6">
									<span class="fs-15 lh-12 m-r-6" style="color: {{$cor->cod}};">
										<i class="zmdi zmdi-circle"></i>
									</span>

                                    <a href="#" class="filter-link stext-106 trans-04">
                                        {{$cor->descricao}}
                                    </a>
                                </li>
                                @endforeach

                            </ul>
                        </div>

                        <div class="filter-col4 p-b-27">
                            <div class="mtext-102 cl2 p-b-15">
                                Tags
                            </div>
                            <div class="flex-w p-t-4 m-r--5">
                                <?php $tags = \App\Models\TagsAnuncio::all();?>
                                @foreach($tags as $tag)

                                <a href="#" class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
                                    {{$tag->descricao}}
                                </a>
                                @endforeach


                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row isotope-grid">

                <?php $anuncios = \App\Models\Anuncio::where('ativo','=','1')->get();

                ?>

                @foreach($anuncios as $anuncio)
                <div class="col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item {{$anuncio->type_id}}">
                    <!-- Block2 -->
                    <div class="block2">
                        <div class="block2-pic hov-img0" style="width: auto; max-height: 380px;">
                            <?php $pathImage = \App\Models\FileAnuncio::where('anuncio_id','=',$anuncio->id)->where("path","!=","")->orderby('updated_at','desc')->
                            orderBy('id','asc')->first(); ?>
                            <img src={{"/storage/products/".$pathImage->path}} alt="IMG-PRODUCT" style="width: auto; max-height: 380px;" >

                            <a href="{{route('advertisement.detail',$anuncio->id_anuncio)}}" class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04">
                                Ver Detalhes
                            </a>
                        </div>

                        <div class="block2-txt flex-w flex-t p-t-14">
                            <div class="block2-txt-child1 flex-col-l ">
                                <a href="{{route('advertisement.detail',$anuncio->id_anuncio)}}" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
                                    {{$anuncio->titulo}}
                                </a>

                                <span class="stext-105 cl3">
									@money($anuncio->preco)
								</span>
                            </div>

                            <div class="block2-txt-child2 flex-r p-t-3">
                                <a href="{{route('advertisement.addfavorito',$anuncio->id)}}" class="btn-addwish-b2 dis-block pos-relative">
                                    <img class="icon-heart1 dis-block trans-04" src="images/icons/icon-heart-01.png" alt="ICON">
                                    <img class="icon-heart2 dis-block trans-04 ab-t-l" src="images/icons/icon-heart-02.png" alt="ICON">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>


                        <div class="wrap-modal1 js-modal{{$anuncio->id}} p-t-60 p-b-20">
                            <div class="overlay-modal1 js-hide-modal1"></div>

                            <div class="container">
                                <div class="bg0 p-t-60 p-b-30 p-lr-15-lg how-pos3-parent">
                                    <button class="how-pos3 hov3 trans-04 js-hide-modal1">
                                        <img src="images/icons/icon-close.png" alt="CLOSE">
                                    </button>

                                    <div class="row">
                                        <div class="col-md-6 col-lg-7 p-b-30">
                                            <div class="p-l-25 p-r-30 p-lr-0-lg">
                                                <div class="wrap-slick3 flex-sb flex-w">
                                                    <div class="wrap-slick3-dots"></div>
                                                    <div class="wrap-slick3-arrows flex-sb-m flex-w"></div>

                                                    <div class="slick3 gallery-lb">
                                                        <div class="item-slick3" data-thumb="/images/produtos/img_17.png">
                                                            <div class="wrap-pic-w pos-relative">
                                                                <img src="/images/produtos/img_17.png" alt="IMG-PRODUCT">

                                                                <a class="flex-c-m size-108 how-pos1 bor0 fs-16 cl10 bg0 hov-btn3 trans-04" href="/images/produtos/img_17.png">
                                                                    <i class="fa fa-expand"></i>
                                                                </a>
                                                            </div>
                                                        </div>

                                                        <div class="item-slick3" data-thumb="/images/produtos/img_17.png">
                                                            <div class="wrap-pic-w pos-relative">
                                                                <img src="/images/produtos/img_17.png" alt="IMG-PRODUCT">

                                                                <a class="flex-c-m size-108 how-pos1 bor0 fs-16 cl10 bg0 hov-btn3 trans-04" href="/images/produtos/img_17.png">
                                                                    <i class="fa fa-expand"></i>
                                                                </a>
                                                            </div>
                                                        </div>

                                                        <div class="item-slick3" data-thumb="/images/produtos/img_17.png">
                                                            <div class="wrap-pic-w pos-relative">
                                                                <img src="/images/produtos/img_17.png" alt="IMG-PRODUCT">

                                                                <a class="flex-c-m size-108 how-pos1 bor0 fs-16 cl10 bg0 hov-btn3 trans-04" href="/images/produtos/img_17.png">
                                                                    <i class="fa fa-expand"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-lg-5 p-b-30">
                                            <div class="p-r-50 p-t-5 p-lr-0-lg">
                                                <h4 class="mtext-105 cl2 js-name-detail p-b-14">
                                                    {{$anuncio->titulo}}
                                                </h4>

                                                <span class="mtext-106 cl2">
								                @money($anuncio->preco)
							                    </span>

                                                <p class="stext-102 cl3 p-t-23">
                                                    {{$anuncio->descricao}}
                                                </p>

                                                <!--  -->
                                                <div class="p-t-33">

                                                    <div class="flex-w flex-r-m p-b-10">

                                                        <div class="size-204 respon6-next">
                                                        </div>
                                                    </div>

                                                    <div class="flex-w flex-r-m p-b-10">
                                                        <div class="size-204 flex-w flex-m respon6-next">
                                                            <div class="wrap-num-product flex-w m-r-20 m-tb-10">
                                                                <div class="btn-num-product-down cl8 hov-btn3 trans-04 flex-c-m">
                                                                    <i class="fs-16 zmdi zmdi-minus"></i>
                                                                </div>

                                                                <input class="mtext-104 cl3 txt-center num-product" type="number" name="num-product" value="1">

                                                                <div class="btn-num-product-up cl8 hov-btn3 trans-04 flex-c-m">
                                                                    <i class="fs-16 zmdi zmdi-plus"></i>
                                                                </div>
                                                            </div>

                                                            <button class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04 js-addcart-detail">
                                                                Adicionar ao carrinho
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!--  -->
                                                <div class="flex-w flex-m p-l-100 p-t-40 respon7">
                                                    <div class="flex-m bor9 p-r-10 m-r-11">
                                                        <a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 js-addwish-detail tooltip100" data-tooltip="Add to Wishlist">
                                                            <i class="zmdi zmdi-favorite"></i>
                                                        </a>
                                                    </div>

                                                    <a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 m-r-8 tooltip100" data-tooltip="Facebook">
                                                        <i class="fa fa-facebook"></i>
                                                    </a>

                                                    <a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 m-r-8 tooltip100" data-tooltip="Twitter">
                                                        <i class="fa fa-twitter"></i>
                                                    </a>

                                                    <a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 m-r-8 tooltip100" data-tooltip="Google Plus">
                                                        <i class="fa fa-google-plus"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                @endforeach



            </div>

            <!-- Load more -->
            <div class="flex-c-m flex-w w-full p-t-45">
                <a href="#" class="flex-c-m stext-101 cl5 size-103 bg2 bor1 hov-btn1 p-lr-15 trans-04">
                    Carregar mais
                </a>
            </div>
        </div>
    </section>



@endsection
