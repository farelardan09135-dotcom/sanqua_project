<x-kasir-layout :title="'Setting'">
    <h1 class="text-xl font-bold text-blue-700 mb-6">Setting</h1>
    @include('profile.edit-form', ['user' => auth()->user()])
</x-kasir-layout>