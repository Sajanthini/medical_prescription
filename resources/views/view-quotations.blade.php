@extends('layouts.app')

@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">View Quotation</div>

                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Prescription #</th>
                                <th>User</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($quotations as $quotation)
                            <tr>
                                <td>{{$quotation->created_at}}</td>
                                <td>{{ str_pad($quotation->prescription_id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $quotation->user->name }}</td>
                                <td>{{ $quotation->total }}</td>
                                <td>
                                    <div class="dropdown">
                                        <a class="btn btn-secondary dropdown-toggle" id="status_{{$quotation->id}}"
                                            href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            {{ucfirst($quotation->status)}}
                                        </a>

                                        <ul class="dropdown-menu">
                                            @if(auth()->user()->type=='user')
                                            <li><a class="dropdown-item" data-id="{{$quotation->id}}"
                                                    data-status="accepted" href="#">Accepted</a></li>
                                            <li><a class="dropdown-item" data-id="{{$quotation->id}}"
                                                    data-status="rejected" href="#">Rejected</a></li>
                                            @else
                                            <li><a class="dropdown-item" data-id="{{$quotation->id}}"
                                                    data-status="pending" href="#">Pending</a></li>
                                            <li><a class="dropdown-item" data-id="{{$quotation->id}}"
                                                    data-status="delivered" href="#">Delivered</a></li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                                <td><button onclick="viewQuotationItem({{$quotation->id}})"
                                        class="btn btn-sm btn-info">View</button></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@include('components.quotation-modal')

@endsection

@section('script')
<script>
$(document).ready(function() {
    $('.dropdown-item').on('click', function(event) {
        event.preventDefault();
        // Get the status and ID data attributes from the dropdown item
        let status = $(this).data('status');
        let id = $(this).data('id');
        // Make a POST request to the server to update the quotation status

        $(`#status_${id}`).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...')

        $.post('/quotation/status', {
                id: id,
                status: status,
                _token: '{{ csrf_token() }}'
            })
            .done(function(response) {
                // Display an alert to the user indicating the status update was successful
                alert('Status updated as ' + status);
                // Update the quotation status displayed on the page
                $(`#status_${id}`).html(status.charAt(0).toUpperCase() + status.slice(1));
            })
            .fail(function(xhr, status, error) {
                console.log(error);
            });
    });
})

</script>
@endsection