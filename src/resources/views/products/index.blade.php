@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/index.css')}}">
<link rel="stylesheet" href="{{ asset('css/pagination.css') }}">
@endsection

@section('content')

<div class="page-header">
    @if(!empty($keyword))
        <h1>“{{$keyword}}”の商品一覧</h1>
    @else
        <h1>商品一覧</h1>
        <a href="{{route('products.register')}}" class="btn create-btn">＋商品を追加</a>
    @endif
</div>

<div class="products-container">
    <aside class="products-filter">

        <form action="{{ route('products.search') }}" method="GET" class="search-form">
            <input type="text" name="keyword" placeholder="商品名で検索"value="{{ request('keyword') }}" class="search-input">
            <button type="submit" class="search-btn">検索</button>
        </form>

        <div class="sort-container">
            <form action="{{ route('products.index') }}" method="GET" class="sort-form">
                <input type="hidden" name="keyword" value="{{ request('keyword') }}">
                <input type="hidden" name="sort" id="selectedSort" value="{{ request('sort') }}">
                <div class="dropdown">
                    <button type="button" class="dropdown-btn">
                        @if(request('sort') === 'desc')
                            <span class="dropdown-label selected">高い順に表示</span>
                        @elseif(request('sort') === 'asc')
                            <span class="dropdown-label selected">低い順に表示</span>
                        @else
                            価格順で並べ替え
                        @endif
                    </button>
                    <ul class="dropdown-menu">
                        <li class="dropdown-item" data-value="desc">高い順に表示</li>
                        <li class="dropdown-item" data-value="asc">低い順に表示</li>
                    </ul>
                </div>
            </form>

            @if(request()->filled('sort'))
            <div class="reset-sort">
                <span class="reset-text">
                    {{ request('sort') === 'desc' ? '高い順に表示' : '安い順に表示' }}
                </span>
                <a href="{{ route('products.search', ['keyword' => request('keyword')]) }}" class="reset-btn">×</a>
            </div>
            @endif
        </div>
    </aside>

    <div class="products-main">
        <section class="products-list">
            @foreach($products as $product)
                <div class="product-card">
                    <a href="{{ route('products.edit', ['productId' => $product->id]) }}">
                        <img src="{{ asset('storage/' . $product->img) }}" alt="{{ $product->name }}">
                        <div class="card-info">
                            <h3>{{ $product->name }}</h3>
                            <p>¥{{ $product->price }}</p>
                        </div>
                    </a>
                </div>
            @endforeach
        </section>

        <div class="pagination-wrapper">
            {{ $products->links('vendor.pagination.custom') }}
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.dropdown-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const menu = btn.nextElementSibling;

            const isOpen = menu.style.display === 'block';
            menu.style.display = isOpen ? 'none' : 'block';

            btn.classList.toggle('open', !isOpen);
        });
    });

    document.querySelectorAll('.dropdown-item').forEach(item => {
        item.addEventListener('click', () => {
            const dropdown = item.closest('.dropdown');
            const button = dropdown.querySelector('.dropdown-btn');

            button.innerText = item.innerText;

            document.getElementById('selectedSort').value = item.dataset.value;

            dropdown.querySelectorAll('.dropdown-item').forEach(i =>
                i.classList.remove('dropdown-item--active')
            );
            item.classList.add('dropdown-item--active');

            item.closest('.dropdown-menu').style.display = 'none';
            button.classList.remove('open');

            dropdown.closest('form').submit();
        });
    });
});

</script>
@endsection
