<div>
    <div class="student_reviews">
        <div class="reviews_left">
            <h3>@lang('app.rating')</h3>
            <div class="total_rating">
                <div class="_rate001 rating_badge">{{ $avg_star }}</div>
                <div class="rating-box">
                    @php
                        $isRating = \Modules\Online\Entities\OnlineRating::getRating($course_id, auth()->id());
                    @endphp

                    @for ($i = 1; $i < 6; $i++)
                        <span class="rating-star
                            @if(!$isRating) empty-star rating
                            @elseif($isRating && $isRating->num_star >= $i) full-star
                            @endif" data-value="{{ $i }}">
                        </span>
                    @endfor
                </div>
                <div class="_rate002">{{ $isRating ? trans('laother.you_rated') : "" }}</div>
            </div>

            <div class="_rate003">
                <div class="_rate004">
                    @php
                        $star5 = \Modules\Online\Entities\OnlineRating::getRatingValue($course_id,5);
                    @endphp
                    <div class="progress progress1">
                        <div class="progress-bar w-{{ $star5 }}" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="rating-box">
                        @for ($i = 0; $i < 5; $i++)
                            <span class="rating-star full-star"></span>
                        @endfor
                    </div>
                    <div class="_rate002">{{ $star5 }}%</div>
                </div>
                <div class="_rate004">
                    <div class="progress progress1">
                        @php
                            $star4 = \Modules\Online\Entities\OnlineRating::getRatingValue($course_id,4);
                        @endphp
                        <div class="progress-bar w-{{ $star4 }}" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="rating-box">
                        @for ($i = 0; $i < 5; $i++)
                            <span class="rating-star @if ($i < 4)
                                full-star
                            @else
                                empty-star
                            @endif "></span>
                        @endfor
                    </div>
                    <div class="_rate002">{{ $star4 }}%</div>
                </div>
                <div class="_rate004">
                    <div class="progress progress1">
                        @php
                            $star3 = \Modules\Online\Entities\OnlineRating::getRatingValue($course_id,3);
                        @endphp
                        <div class="progress-bar w-{{ $star3 }}" role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="rating-box">
                        @for ($i = 0; $i < 5; $i++)
                            <span class="rating-star @if ($i < 3)
                                full-star
                            @else
                                empty-star
                            @endif "></span>
                        @endfor
                    </div>
                    <div class="_rate002">{{ $star3 }}%</div>
                </div>

                <div class="_rate004">
                    <div class="progress progress1">
                        @php
                            $star2 = \Modules\Online\Entities\OnlineRating::getRatingValue($course_id,2);
                        @endphp
                        <div class="progress-bar w-{{ $star2 }}" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="rating-box">
                        @for ($i = 0; $i < 5; $i++)
                            <span class="rating-star @if ($i < 2)
                                full-star
                            @else
                                empty-star
                            @endif "></span>
                        @endfor
                    </div>
                    <div class="_rate002">{{ $star2 }}%</div>
                </div>
                <div class="_rate004">
                    <div class="progress progress1">
                        @php
                            $star1 = \Modules\Online\Entities\OnlineRating::getRatingValue($course_id,1);
                        @endphp
                        <div class="progress-bar w-{{ $star1 }}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="rating-box">
                        @for ($i = 0; $i < 5; $i++)
                            <span class="rating-star @if ($i < 1)
                                full-star
                            @else
                                empty-star
                            @endif "></span>
                        @endfor
                    </div>
                    <div class="_rate002">{{ $star1 }}%</div>
                </div>
            </div>
        </div>
    </div>
</div>

