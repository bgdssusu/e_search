<div class="col-md-12 search-item">
    <h5>color</h5>
    <?php
    $subs=[];
    if($data['brand']!==null) $subs['sub31']=$data['brand'];
    if($data['gender']!==null) $subs['sub32']=$data['gender'];
    if($data['category']!==null) $subs['sub33']=$data['category'];
    ?>
    <div>
        @foreach ($data['color_all'] as $index=>$item)
            <a class="col-md-3 color-box {{$item}} @yield('active'.$item)" href="{{action('NavigationController@navigate', array_merge($subs, ['sub34'=>$item]))}}"></a>
        @endforeach
    </div>

</div>