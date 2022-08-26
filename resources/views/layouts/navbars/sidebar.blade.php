@if(\Illuminate\Support\Facades\Auth::check())
@if($class!="login-page")
    <div class="sidebar">
    <div class="sidebar-wrapper">

        <ul class="nav">
            <li @if ($pageSlug == 'dashboard') class="active " @endif>
                <a href="{{route('home')}}">
                    <i class="tim-icons icon-chart-pie-36"></i>
                    <p>{{ __('Dashboard') }}</p>
                </a>
            </li>

            <li>
                <a data-toggle="collapse" href="#laravel-examples" aria-expanded="true">
                    <i class="fab fa-laravel" ></i>
                    <span class="nav-link-text" >{{ __('Vendas') }}</span>
                    <b class="caret mt-1"></b>
                </a>

                <div class="collapse" id="laravel-examples">
                    <ul class="nav pl-4">
                        <li @if ($pageSlug == 'resumo') class="active " @endif>
                            <a href="">
                                <i class="tim-icons icon-components"></i>
                                <p>{{ __('Resumo') }}</p>
                            </a>
                        </li>
                        <li @if ($pageSlug == 'users') class="active " @endif>
                            <a href="">
                                <i class="tim-icons icon-alert-circle-exc"></i>
                                <p>{{ __('Novidades') }}</p>
                            </a>
                        </li>
                        <li @if ($pageSlug == 'users') class="active " @endif>
                            <a href="{{route('advertisement.list')}}">
                                <i class="tim-icons icon-spaceship"></i>
                                <p>{{ __('Anúncios') }}</p>
                            </a>
                        </li>

                        <li @if ($pageSlug == 'users') class="active " @endif>
                            <a href="">
                                <i class="tim-icons icon-chat-33"></i>
                                <p>{{ __('Perguntas') }}</p>
                            </a>
                        </li>
                        <li @if ($pageSlug == 'users') class="active " @endif>
                            <a href="">
                                <i class="tim-icons icon-coins"></i>
                                <p>{{ __('Vendas') }}</p>
                            </a>
                        </li>
                        <li @if ($pageSlug == 'users') class="active " @endif>
                            <a href="">
                                <i class="tim-icons icon-chart-bar-32"></i>
                                <p>{{ __('Métricas') }}</p>
                            </a>
                        </li>
                        <li @if ($pageSlug == 'users') class="active " @endif>
                            <a href="">
                                <i class="tim-icons icon-trophy"></i>
                                <p>{{ __('Reputação') }}</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li>
                <a data-toggle="collapse" href="#compras" aria-expanded="true">
                    <i class="tim-icons icon-money-coins" ></i>
                    <span class="nav-link-text" >{{ __('Compras') }}</span>
                    <b class="caret mt-1"></b>
                </a>

                <div class="collapse" id="compras">
                    <ul class="nav pl-4">
                        <li @if ($pageSlug == 'compras') class="active " @endif>
                            <a href="">
                                <i class="tim-icons icon-components"></i>
                                <p>{{ __('Compras') }}</p>
                            </a>
                        </li>
                        <li @if ($pageSlug == 'users') class="active " @endif>
                            <a href="">
                                <i class="tim-icons icon-alert-circle-exc"></i>
                                <p>{{ __('Perguntas') }}</p>
                            </a>
                        </li>
                        <li @if ($pageSlug == 'users') class="active " @endif>
                            <a href="">
                                <i class="tim-icons icon-spaceship"></i>
                                <p>{{ __('Favoritos') }}</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li @if ($pageSlug == 'icons') class="active " @endif>
                <a href="">
                    <i class="tim-icons icon-chart-pie-36"></i>
                    <p>{{ __('Faturamento') }}</p>
                </a>
            </li>
            <li @if ($pageSlug == 'profile') class="active " @endif>
                <a href="{{route('user.preedit')}}">
                    <i class="tim-icons icon-single-02"></i>
                    <p>{{ __('Meu Perfil') }}</p>
                </a>
            </li>
        </ul>
    </div>
</div>
@endif
@endif
