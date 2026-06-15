<div class="bg-white dark:bg-gray-950 p-6 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm space-y-4">
    <div class="flex items-center justify-between border-b border-gray-150 dark:border-gray-850 pb-3">
        <h3 class="text-base font-bold text-gray-900 dark:text-white">Document Preview</h3>
        <span class="text-xs text-gray-400 dark:text-gray-500">File Type: {{ strtoupper(pathinfo($record->file_path, PATHINFO_EXTENSION)) }}</span>
    </div>
    
    <div class="w-full overflow-hidden rounded-lg">
        {!! $previewHtml !!}
    </div>
</div>
