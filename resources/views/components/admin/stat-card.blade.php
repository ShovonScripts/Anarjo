@props(['label' => '', 'value' => ''])

<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $label }}</p>
    <p class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ $value }}</p>
</div>
