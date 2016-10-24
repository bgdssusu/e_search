<!DOCTYPE html>
<html lang="en">
@include('viewparts.head')
<body>

@include('viewparts.navigation')
@include('viewparts.breadcumb')
<div class="row">

    <div class="col-md-3 left-side"><!-- left-side -->
        @include('viewparts.genders')
        @include('viewparts.categories')
        @include('viewparts.colors')
        @include('viewparts.prices')
    </div><!-- /.left-side -->

    <div class="col-md-9 content-side"><!-- content-side -->
        @include('viewparts.content')
        @include('viewparts.pagination')
    </div><!-- /.content-side -->
</div>

@include('viewparts.footer')
</body>
</html>
