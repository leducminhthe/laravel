@php
    $get_unit =  profile();
@endphp
<div class="header banner_logo w-100 banner_frontend">
    <div class="banner_home">
        <div id="carouselSliderControls" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                @if($sliders->count() == 0)
                    {{-- <div class="carousel-item active">
                        <img class="d-block w-100" src="{{ asset('images/design/banner_1920_200.jpg') }}" alt="">
                    </div> --}}
                @else
                    @foreach ($sliders as $key => $slider)
                        @php
                            $check_unit = 0;
                            if (!empty($slider->object) && !empty($get_unit)) {
                                $check_objects = json_decode($slider->object);
                                foreach ($check_objects as $check_object) {
                                    $unit_code = \App\Models\Categories\Unit::find($check_object);
                                    $get_array_childs = \App\Models\Categories\Unit::getArrayChild($unit_code->code);
                                    if( in_array($get_unit->unit_id, $get_array_childs) || ($get_unit->unit_id == $unit_code->id) ) {
                                        $check_unit = 1;
                                    }
                                }
                            }
                        @endphp
                            @if ( (!empty($slider->object) && $check_unit == 1) || empty($slider->object))
                                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                    @if (isset($slider->url))
                                        <a href="{{ $slider->url }}" target="blank">
                                            <img class="d-block w-100" src="{{ image_file($slider->image) }}" alt="">
                                        </a>
                                    @else
                                        <img class="d-block w-100" src="{{ image_file($slider->image) }}" alt="">
                                    @endif
                                    
                                </div>
                            @endif
                    @endforeach
                @endif
            </div>
            @if($sliders->count() > 1)
                <a class="carousel-control-prev" href="#carouselSliderControls" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselSliderControls" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            @endif
          </div>
    </div>
</div>
