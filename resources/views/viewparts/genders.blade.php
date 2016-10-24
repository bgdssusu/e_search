<div class="col-md-12 search-item fi text-center">

    <h5>gender</h5>

    <?php
    $subs=[];
    if($data['brand']!==null) $subs['sub11']=$data['brand'];
    if($data['category']!==null) $subs['sub12']=$data['category'];
    if($data['color']!==null) $subs['sub13']=$data['color'];
    ?>
    <div>
        @foreach ($data['gender_all'] as $index=>$item)
            <a href="{{action('NavigationController@navigate', array_merge($subs, ['sub14'=>$item]))}}" class="btn btn-info btn-sm @yield('active'.$item)" role="button" style="min-width:100px;">{{$item}}</a>
        @endforeach
    </div>

</div>