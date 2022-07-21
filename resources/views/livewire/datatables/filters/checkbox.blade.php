<div
    @if (isset($column['tooltip']['text'])) title="{{ $column['tooltip']['text'] }}" @endif
    class="flex flex-col items-center h-full px-6 py-1.5 overflow-hidden text-xs font-medium tracking-wider text-left text-gray-500 uppercase align-top bg-blue-100 border-b border-gray-200 leading-4 space-y-2 focus:outline-none">
    <div>
        <input
        type="checkbox"
        wire:click="toggleSelectAll"
        class="w-4 h-4 mt-1 text-blue-600 form-checkbox transition duration-150 ease-in-out rounded-full"
        @if(count($selected) === $this->results->total()) checked @endif
        />
    </div>
</div>
