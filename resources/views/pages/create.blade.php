<!DOCTYPE html>
<html lang="en">
@include('viewparts.head')
<body>
<div class="col-md-12 text-center" style="margin-top:100px;">
    <div class="row">

        @if($id == 'danger')
            <div class="col-md-4 col-md-offset-4 alert alert-danger">
                You don't have any data in your database, or you don't have this database at all.<br>
                <a href="{{action('NavigationController@createDB')}}" class="alert-link">Please generate a new one!</a>
            </div>
        @elseif($id == 'success')
            <div class="col-md-4 col-md-offset-4 alert alert-success">
                You have created the database successfully.<br>
                <a href="{{action('NavigationController@navigate')}}" class="alert-link">Go to the search page!</a>
            </div>
        @else
            <div class="col-md-4 col-md-offset-4 alert alert-info">
                You have deleted the database successfully.<br>
                <a href="{{action('NavigationController@createDB')}}" class="alert-link">Create a new one!</a>
            </div>
        @endif

    </div>
</div>
@include('viewparts.footer')
<script>
    $(document).ready(function() {
        $('.alert-link').on('click', function(){
            $(this).bind('click', false);
            $(this).css('cursor', 'default');
            $(this).preventDefault();
        });

    });
</script>
</body>
</html>

