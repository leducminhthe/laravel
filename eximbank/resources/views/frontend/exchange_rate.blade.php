
<div class="advertiment">
    <div class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            @foreach($getAdvertisingPhotos as $key => $getAdvertisingPhoto)
                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                    <a href="{{ $getAdvertisingPhoto->url }}">
                        <img src="{{ image_file($getAdvertisingPhoto->image) }}" alt="" class="w-100" />
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
    
