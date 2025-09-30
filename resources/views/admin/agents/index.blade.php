@extends('layouts.admin')

@section('title', 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">All Agents</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">
                        <ul class="nav nav-pills ml-auto">
                            <li class="nav-item">
                            <a class="nav-link active" href="{{route('admin.agents.create')}}">Create</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card-body table-responsive">
                 <x-message/>
                    <table class="table table-stripped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Company</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Admin</th>
                                <th class="text-right">Option</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($agents as $item)
                            <tr>
                                <td>{{$item->id}}</td>
                                <td>{{$item->name}}</td>
                                <td>{{$item->company}}</td>
                                <td>{{$item->contact}}</td>
                                <td>{{$item->email}}</td>
                                <td class="text-center">{!! $item->showActiveStatus() !!}</td>
                                <td class="text-center">{!! $item->showAdminStatus() !!}</td>
                                <td class="text-right">
                                    <a href="{{route('admin.agents.show', $item->id)}}" class="btn btn-primary btn-sm">Show</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-center">
                    {{$agents->links()}}
                </div>
            </div>
        </div>
    </div>
@stop