@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-xl font-bold mb-4">Branches</h1>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-2 mb-4 rounded">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 text-red-800 p-2 mb-4 rounded">{{ session('error') }}</div>
    @endif

    {{-- Your Branches (Table) --}}
    <div class="mb-8">
        <h2 class="text-lg font-semibold mb-2">Your Branches</h2>

        @if($yourBranch->isNotEmpty())
            <table class="min-w-full border bg-white">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="py-2 px-4 border">Name</th>
                        <th class="py-2 px-4 border">Location</th>
                        <th class="py-2 px-4 border">Code</th>
                        <th class="py-2 px-4 border">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($yourBranch as $branch)
                        <tr>
                            <td class="py-2 px-4 border">{{ $branch->name }}</td>
                            <td class="py-2 px-4 border">{{ $branch->location }}</td>
                            <td class="py-2 px-4 border">{{ $branch->code }}</td>
                            <td class="py-2 px-4 border">
                                <a href="{{ route('branches.manage', $branch->id) }}"
                                        style="background-color: #3b82f6 !important; color: white !important;"
                                        class="py-1 px-3 rounded text-sm hover:bg-blue-600">
                                    Manage
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="mb-2">You are not in any branches yet.</p>
        @endif
    </div>

    {{-- Create Branch --}}
    <div class="mb-8">
        <button onclick="document.getElementById('create-branch-form').style.display='block'"
                style="background-color: #16a34a !important; color: white !important;"
                class="px-4 py-2 rounded hover:bg-green-700">
            Create Branch
        </button>


        <div id="create-branch-form" style="display: none; margin-top: 1em;">
            <form method="POST" action="{{ route('branches.store') }}">
                @csrf
                <label>Branch Name:</label>
                <input type="text" name="name" required class="block border p-1 mb-2">

                <label>Location:</label>
                <input type="text" name="location" required class="block border p-1 mb-2">

                <button type="submit" style="background-color: #16a34a !important; color: white !important;" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Create</button>
            </form>
        </div>
        {{-- Join Branch --}}
        <button onclick="document.getElementById('join-branch-form').style.display='block'"
                style="background-color: #16a34a !important; color: white !important;"
                class="px-4 py-2 rounded hover:bg-green-700">
            Join Branch
        </button>


        <div id="join-branch-form" style="display: none; margin-top: 1em;">
            <form method="POST" action="{{ route('branches.join') }}">
                @csrf
                <label>Enter Branch Code:</label>
                <input type="text" name="code" required class="block border p-1 mb-2">

                <button type="submit" style="background-color: #16a34a !important; color: white !important;" class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700">
                    Join
                </button>
            </form>
        </div>
    </div>

    {{-- Other Branches --}}
    <div>
        <h2 class="text-lg font-semibold mb-2">Other Branches</h2>

        @forelse($otherBranches as $branch)
            <div class="p-4 border rounded mb-2 bg-white">
                <p><strong>Name:</strong> {{ $branch->name }}</p>
                <p><strong>Location:</strong> {{ $branch->location }}</p>
                <p><strong>Owner:</strong> {{ $branch->owner->name ?? 'Unknown' }}</p>
            </div>
        @empty
            <p>No other branches found.</p>
        @endforelse
    </div>
</div>
@endsection
