@props(['items' => []])

<nav class="flex mb-4" aria-label="Breadcrumb">
  <ol class="inline-flex items-center space-x-1 md:space-x-2 text-sm font-medium">
    @foreach($items as $i => $item)
      <li class="inline-flex items-center">
        @if($i > 0)
          <svg class="w-4 h-4 mx-1 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path d="M7.05 4.05a.5.5 0 01.7 0l4.2 4.2a.5.5 0 010 .7l-4.2 4.2a.5.5 0 01-.7-.7L10.29 9 7.05 5.76a.5.5 0 010-.7z"/>
          </svg>
        @endif

        @if(isset($item['url']))
          <a href="{{ $item['url'] }}" class="text-gray-700 hover:text-gray-900">
            {{ $item['label'] }}
          </a>
        @else
          <span class="text-gray-500">
            {{ $item['label'] }}
          </span>
        @endif
      </li>
    @endforeach
  </ol>
</nav>

