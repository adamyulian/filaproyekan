<h1>Upload File</h1>

<div class="max-w-md mx-auto mt-8 p-6 bg-white rounded-md shadow-md">
    @if (session()->has('message'))
        <div class="bg-green-200 text-green-800 p-3 mb-3 rounded-md">{{ session('message') }}</div>
    @endif

    <form wire:submit.prevent="store" enctype="multipart/form-data">
        <div class="mb-4">
            <label for="file" class="block text-gray-700 text-sm font-bold mb-2">Choose a file:</label>
            <input type="file" id="file" wire:model="file"
                   class="border border-gray-300 p-2 w-full focus:outline-none focus:border-indigo-500">
            @error('file') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md">Upload</button>
    </form>
</div>
