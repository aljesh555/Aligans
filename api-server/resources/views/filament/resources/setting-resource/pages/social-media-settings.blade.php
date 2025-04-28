<!-- <x-filament::page>
    <!-- <x-filament::form wire:submit="save">
        {{ $this->form }}
        
        <div class="mt-6">
            <x-filament::button type="submit" class="mt-3">
                Save Changes
            </x-filament::button>
        </div>
    </x-filament::form>
    
    <div class="mt-8 border-t pt-6">
        <h3 class="text-lg font-medium">Current Social Media Links</h3>
        <p class="text-sm text-gray-500 mb-4">These links will be used in the footer of your website.</p>
        
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6">
                <div class="space-y-4">
                    @php
                        $socialLinks = \App\Models\Setting::get('social_links', []);
                    @endphp
                    
                    @if(count($socialLinks) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Platform</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">URL</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($socialLinks as $platform => $url)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ ucfirst($platform) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <a href="{{ $url }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">{{ $url }}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M8.485 3.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 3.495zM10 6a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 6zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">No social media links found</h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <p>Add your first social media link using the form above.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-filament::page>  -->
<div>
    <h1>Social Media Settings</h1>
</div>