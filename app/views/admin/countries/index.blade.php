@extends('layouts.master')
@section('content')

@include('shared.errors-main')

<h3>List of Countries</h3>
<h5> {{ link_to_route('admin.countries.create','Create',null,  array('id'=> 'create-countries', 'class' => 'btn  btn-xs btn-primary')) }}</h5>
{{ pagination_links($countries)  }}
<table class="table table-bordered table-hover table-condensed">
    <thead>
        <tr>
            <th>{{ sort_by('name','Name','admin.countries.index') }}</th>
            <th>{{ sort_by('code_a3','A3','admin.countries.index') }}</th>
            <th>{{ sort_by('code_a2','A2','admin.countries.index') }}</th>
            <th>{{ sort_by('active','Status','admin.countries.index') }}</th>
            <th>Action</th>
        </tr>
    </thead>
    @foreach($countries as $country)
        <?php $status = $country->active == TRUE ? 'active' : 'inactive'?>
        <tr>
            <td> {{{ $country->name }}} </td>
            <td> {{{ $country->code_a3 }}}</td>
            <td> {{{ $country->code_a2 }}}</td>
            <td> {{ link_to_route('admin.countries.changestatus',ucfirst($country->status) ,array( 'id' => $country->id, 'status' => $country->convertStatus ), array('class' => 'btn btn-xs btn-'.$country->status )) }}</td>
            <td>
            {{ link_to_route('admin.countries.edit','Edit',array( 'id' => $country->id ), array('class' => 'btn btn-xs btn-success')) }}
            {{ link_to_route('admin.countries.destory','Delete',array( 'id' => $country->id), array( 'id'=> "delete_".$country->name , 'class' => 'btn btn-xs btn-danger')) }} </td>
        </tr>
    @endforeach
</table>
{{ pagination_links($countries)  }}
@stop
