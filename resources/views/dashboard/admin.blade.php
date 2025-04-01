@extends('layouts.app')

@section('title', 'Admin Panel')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-2xl font-bold mb-6">System Administration</h2>
    
    <div class="grid grid-cols-3 gap-6 mb-8">
        <div class="bg-blue-50 p-4 rounded">
            <h3 class="font-semibold">Users</h3>
            <p class="text-3xl">{{ $users->total() }}</p>
        </div>
        <div class="bg-green-50 p-4 rounded">
            <h3 class="font-semibold">Storage</h3>
            <p>{{ number_format($systemStats['storage'] / 1024 / 1024 / 1024, 2) }} GB Free</p>
        </div>
        <div class="bg-purple-50 p-4 rounded">
            <h3 class="font-semibold">Memory</h3>
            <p>{{ number_format($systemStats['memory'] / 1024 / 1024, 2) }} MB Used</p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-6 py-3 text-left">Name</th>
                    <th class="px-6 py-3 text-left">Email</th>
                    <th class="px-6 py-3 text-left">Role</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr class="border-t">
                    <td class="px-6 py-4">{{ $user->name }}</td>
                    <td class="px-6 py-4">{{ $user->email }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 bg-blue-100 rounded-full text-sm">{{ $user->role }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @if($user->id !== auth()->id())
                            <form class="inline-block" method="POST" 
                                  action="{{ route('users.update', $user) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" name="role" value="admin"
                                        class="text-blue-600 hover:text-blue-900">
                                    Make Admin
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $users->links() }}
    </div>
</div>
@endsection
