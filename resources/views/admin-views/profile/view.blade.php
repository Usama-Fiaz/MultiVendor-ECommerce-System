@extends('layouts.back-end.app')


@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{translate('dashboard')}}</a></li>
            <li class="breadcrumb-item" aria-current="page">{{translate('my_profile')}}</li>
        </ol>
    </nav>

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-2">
        <h3 class="h3 mb-0 text-black-50">{{translate('my_profile')}}  </h3>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <img  onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'" src="{{asset('storage/app/public/admin/'.$data->image)}}" class="rounded-circle border"
                         height="200" width="200" alt="">
                    <div class="p-4">
                    <h4>{{translate('name')}} : {{$data->name}}</h4>
                    <h6>{{translate('phone')}} : {{$data->phone}}</h6>
                    <h6>{{translate('email')}} : {{$data->email}}</h6>
                    <a class="btn btn-success" href="{{route('admin.profile.update',[$data->id])}}">{{translate('edit')}}</a>
                </div>

                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <!-- Page level plugins -->
@endpush
