@props(['name' , 'width', 'height'])

<div class="fixed z-50 inset-0"
    x-data="{show : false, name : '{{$name}}'}"
    x-show="show"
    x-on:open-modal.window="console.log($event.detail); show = ($event.detail.name === '{{ $name }}')"
    x-on:close-modal.window="show = false"
    x-on:keydown.escape.window="show = false"
    x-transition
    style="display:none;">
    <!-- Be present above all else. - Naval Ravikant -->
    <div class="fixed inset-0 bg-gray-800 opacity-70" x-on:click="$dispatch('close-modal')"></div>
    <div class="bg-white rounded m-auto fixed inset-0 {{$width}} grid-rows-3" style="max-height: {{$height}}px">
        <div class="row-span-1 flex justify-between items-center p-3">
            {{ $header }}
            <button x-on:click="$dispatch('close-modal')" class="text-black">
                X
            </button>
        </div>

        <div class="row-span-2 p-3">

            {{ $body }}

        </div>
    </div>
</div>
