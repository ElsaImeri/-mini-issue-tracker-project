@foreach($tags as $tag)
    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mr-1 mb-1 border 
                transition-colors duration-200 ease-in-out hover:shadow-md"
          style="background-color: {{ $tag->color }}20; color: {{ $tag->color }}; border-color: {{ $tag->color }}40;">
        <span class="w-2 h-2 rounded-full mr-1.5" style="background-color: {{ $tag->color }}"></span>
        {{ $tag->name }}
    </span>
@endforeach

@if($tags->isEmpty())
    <span class="text-gray-400 text-sm italic">No tags assigned</span>
@endif