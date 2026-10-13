@extends('errors::layout')

@section('title', __('Forbidden'))
@section('code', '403')
@section('message', __('You don\'t have permission to view this page. If you believe this is a mistake, please contact support.'))
