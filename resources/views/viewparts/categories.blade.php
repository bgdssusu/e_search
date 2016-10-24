<div class="col-md-12 search-item">
    <h5>Categories</h5>

    <div class="list-group">
        <?php
        $subs=[];
        if($data['brand']!==null) $subs['sub21']=$data['brand'];
        if($data['gender']!==null) $subs['sub22']=$data['gender'];
        if($data['color']!==null) $subs['sub23']=$data['color'];
        ?>
        @foreach ($data['category_all'] as $index=>$item)
            <a href="{{action('NavigationController@navigate', array_merge($subs, ['sub24'=>$item]))}}" class="list-group-item @yield('active'.$item)" role="button" style="min-width:100px;">{{$item}}</a>
        @endforeach

    </div>

</div>