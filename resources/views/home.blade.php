@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Notification') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    @foreach(auth()->user()->unreadNotifications as $notification)
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        The Quotation: <strong><a href="#" onclick="viewQuotationItem({{$notification->data['quotation_id']}})">{{ str_pad($notification->data['quotation_id'], 4, '0', STR_PAD_LEFT) }} </a> </strong> is
                        {{$notification->data['quotation_status']}} 
                        @if(auth()->user()->type == 'pharmacy')
                        by User: {{$notification->data['user']['name']}}
                        @endif
                        <button type="button" class="btn-close" data-id="{{ $notification->id }}" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</div>

@include('components.quotation-modal')

@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
jQuery.noConflict();
jQuery(document).ready(function($) {
    $('.btn-close').on('click', function() {
        let id = $(this).data('id');
        $.post(`/notifications/mark-read`, { _token: '{{ csrf_token() }}', id: id })
            .done(() => {
                // Optionally do something after marking the notification as read
            });
    });
});
</script>

<script>
$('.btn-close').on('click', function() {
    let id = $(this).data('id');
    $.post(`/notifications/mark-read`, { _token: '{{ csrf_token() }}', id:id })
        .done(() => {
            // Optionally do something after marking the notification as read
        });
});
</script>
</script>
@endsection