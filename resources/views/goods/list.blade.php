@foreach($list as $k=>$v)

    <div>
        商品ID ： {{ $v->goods_id }}<br>
        商品名： {{ $v->goods_name }}
        <hr>
    </div>

@endforeach
