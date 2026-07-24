@props(['title' => null])
<x-slot:title>{{ $title }}</x-slot:title>
@include('layouts.owner', ['slot' => $slot])