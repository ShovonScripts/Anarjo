@extends('errors::layout')

@section('title', __('Service Unavailable'))
@section('code', '503')
@section('message', __('We\'re temporarily offline for maintenance. Please check back soon.'))
