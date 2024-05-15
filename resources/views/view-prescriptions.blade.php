@extends('layouts.app')

@section('content')

@php
    $is_pharmacy = false;
    if(auth()->user()->type == 'pharmacy'){
        $is_pharmacy = true;
    }
@endphp

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">View Prescription</div>

                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>User</th>
                                <th>Address</th>
                                <th>Delivery Time</th>
                                <th>Note</th>
                                @if($is_pharmacy)
                                <th>Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($prescriptions as $prescription)
                            <tr>
                                <td>{{$prescription->created_at}}</td>
                                <td>{{ $prescription->user->name }}</td>
                                <td>{{ $prescription->address }}</td>
                                <td>{{ date('g:i A', strtotime($prescription->delivery_time ))}}</td>
                                <td>{{ $prescription->note }}</td>
                                @if($is_pharmacy)
                                <td><a href="/create/{{$prescription->id}}/quotation/">Quotation</a></td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection