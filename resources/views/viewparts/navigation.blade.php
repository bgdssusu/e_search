<nav class="navbar navbar-default">
    <div class="container">
        <div class="navbar-header"><!-- navbar-header -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <a class="navbar-brand" href="{{action('NavigationController@navigate',['brand'=>null])}}">BGDS</a>

        </div><!-- /.navbar-header -->

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1"><!-- navbar-collapse -->

            <ul class="nav navbar-nav"><!-- menu -->

                @foreach ($data['brand_all'] as $item=>$value)
                    <li  @yield('active'.$value)><a href="{{action('NavigationController@navigate',['brand'=>$value])}}">{{$value}}</a></li>
                @endforeach

            </ul><!-- /.menu -->

            <!--form class="navbar-form navbar-right">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Search">
                </div>
                <button type="submit" class="btn btn-default">Submit</button>
            </form><!-- /.search form -->

        </div><!-- /.navbar-collapse -->
    </div><!-- /.container -->
</nav>
