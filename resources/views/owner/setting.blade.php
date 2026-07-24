<x-owner-layout :title="'Setting'">
    <h1 class="text-2xl font-bold text-indigo-700 mb-6">Setting</h1>
    @include('profile.edit-form', ['user' => auth()->user()])
</x-owner-layout>