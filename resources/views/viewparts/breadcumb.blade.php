<div class="row">
    <div class="col-md-12">

        <ol class="breadcrumb">

            @if($data['brand']!==null)
                <li class="breadcrumb-item">{{$data['brand']}}</li>
            @endif
            @if($data['gender']!==null)
                <li class="breadcrumb-item">{{$data['gender']}}</li>
            @endif
            @if($data['category']!==null)
                <li class="breadcrumb-item">{{$data['category']}}</li>
            @endif
            @if($data['color']!==null)
                <li class="breadcrumb-item">{{$data['color']}}</li>
            @endif
        </ol>

    </div>
</div>