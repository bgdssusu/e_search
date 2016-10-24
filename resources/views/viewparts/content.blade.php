@if($data['content'] !== null)
    <div class="col-md-12 text-center">

        @for ($i = 0; $i < count($data['content']['hits']); $i++)
            <div class="col-md-4 text-center">
                <div class="item-div">
                    <p><b>Item ID:</b>{{$data['content']['hits'][$i]['_id']}}</p>
                    <hr>
                    <p>
                        brand: {{$data['content']['hits'][$i]['_type']}}<br>
                        gender: {{$data['content']['hits'][$i]['_source']['gender']}}<br>
                        category: {{$data['content']['hits'][$i]['_source']['category']}}<br>
                        color: {{$data['content']['hits'][$i]['_source']['color']}}
                    </p><hr>
                    <p>
                        price: {{$data['content']['hits'][$i]['_source']['price']}}
                    </p>
                </div>
            </div>
        @endfor

        @if(count($data['content']['hits']) == 0)
                <div class="col-md-12 text-center">
                    <div class="row">
                        <div class="col-md-4 col-md-offset-4 alert alert-danger">
                            <span><strong>We do not have any data!</strong><br>Try to use different filters!</span>
                        </div>
                    </div>
                </div>
            @endif
    </div>
@else
    <div class="col-md-12 text-center">
        <div class="row">
            <div class="col-md-4 col-md-offset-4 alert alert-danger">
                <span><strong>You use wrong url!</strong></span>
            </div>
        </div>
    </div>
@endif
