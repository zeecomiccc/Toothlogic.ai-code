@extends('frontend::layouts.master')

@section('content') 

@include('frontend::components.section.breadcrumb')

<div class="list-page section-spacing px-0">
    <div class="container">
        <div class="page-title" id="page_title">
            <h6 class="mb-3">All Reviews</h6>
        </div>
        <ul class="list-inline m-0 p-0">
            @foreach($reviews as $review)
            <li class="review-card">
                <div class="review-detail section-bg rounded">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center gap-2 rounded-pill bg-primary-subtle badge">
                                <i class="ph-fill ph-star text-warning"></i>
                                <span class="font-size-14 fw-bold">{{ $review->rating }}</span>
                            </div>
                            <h6 class="m-0">{{ $review->title }}</h6>
                        </div>
                        <span class="bg-secondary-subtle badge rounded-pill">{{ optional($review->clinic_service)->name }}</span>
                    </div>
                    <div class="d-flex justify-content-between flex-column flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ optional($review->user)->profile_image }}" alt="user"
                                class="img-fluid user-img rounded-circle">
                            <div>
                                <h6 class="line-count-1 font-size-14">By {{ optional($review->user)->gender == 'female' ? 'Miss.' : 'Mr.' }} 
                                    {{ optional($review->user)->first_name.' '.optional($review->user)->last_name }}
                                </h6>
                                <small class="mb-0 font-size-14">{{ $review->updated_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        <p class="mb-0 mt-2 font-size-14">{{ $review->review_msg }}</p>
                    </div>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection