<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview: {{ $metadata['title'] }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #E2E8F0; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .animate-fade-in { animation: fadeIn 0.3s ease-out forwards; }
    </style>
</head>
<body class="h-screen flex flex-col overflow-hidden animate-fade-in">

    <header class="bg-white border-b border-gray-300 h-16 flex justify-between items-center px-4 shrink-0 z-10 shadow-sm">
        <div class="flex items-center gap-4">
            <button onclick="history.back()" class="p-2 hover:bg-gray-100 rounded-full text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </button>
            <div class="flex items-center gap-2">
                <span class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded shadow-sm">PDF</span>
                <h1 class="font-bold text-gray-800 truncate max-w-lg">{{ $metadata['full_name'] }}</h1>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <button class="px-4 py-1.5 text-sm font-semibold text-gray-600 border border-gray-300 rounded hover:bg-gray-50 flex gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg> Share</button>
            <button class="px-4 py-1.5 text-sm font-semibold text-white bg-blue-600 rounded shadow hover:bg-blue-700 flex gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg> Download</button>
        </div>
    </header>

    <div class="flex-1 flex overflow-hidden">
        <main class="flex-1 overflow-y-auto p-8 flex justify-center items-start">
            <div class="w-full max-w-4xl bg-white shadow-2xl min-h-[1100px] p-12 border border-gray-300 relative">
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-[0.03]">
                    <span class="text-8xl font-black uppercase rotate-[-45deg] whitespace-nowrap">{{ $metadata['security'] }} DOCUMENT</span>
                </div>
                
                <div class="h-8 w-1/3 bg-gray-200 rounded mb-8"></div>
                <div class="space-y-4">
                    <div class="h-4 bg-gray-100 rounded w-full"></div>
                    <div class="h-4 bg-gray-100 rounded w-5/6"></div>
                    <div class="h-4 bg-gray-100 rounded w-full"></div>
                    <div class="h-4 bg-gray-100 rounded w-4/6"></div>
                    <div class="h-48 bg-gray-100 rounded w-full my-8 flex items-center justify-center text-gray-400 font-medium">[ Content Image / Table Placeholder ]</div>
                    <div class="h-4 bg-gray-100 rounded w-full"></div>
                    <div class="h-4 bg-gray-100 rounded w-full"></div>
                </div>
            </div>
        </main>

        <aside class="w-80 bg-white border-l border-gray-200 overflow-y-auto shrink-0 shadow-[-5px_0_15px_rgba(0,0,0,0.05)] z-20">
            <div class="p-5 border-b border-gray-100">
                <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Document Details</h2>
            </div>
            
            <div class="p-5 space-y-5">
                <div>
                    <h3 class="text-xs text-gray-500 font-semibold mb-3 border-b pb-1">System Metadata</h3>
                    <div class="space-y-3 text-sm">
                        <div><span class="block text-[10px] text-gray-400 font-bold uppercase">Document Title</span><span class="font-semibold text-gray-800">{{ $metadata['title'] }}</span></div>
                        <div><span class="block text-[10px] text-gray-400 font-bold uppercase">Document Number</span><span class="font-semibold text-blue-600">{{ $metadata['no_doc'] }}</span></div>
                        <div><span class="block text-[10px] text-gray-400 font-bold uppercase">Revision</span><span class="font-semibold text-gray-800 bg-gray-100 px-2 py-0.5 rounded">Rev. 0A (Approved)</span></div>
                        <div><span class="block text-[10px] text-gray-400 font-bold uppercase">Project / Facility</span><span class="font-semibold text-gray-800">{{ $metadata['project'] }}</span></div>
                    </div>
                </div>

                <div>
                    <h3 class="text-xs text-gray-500 font-semibold mb-3 border-b pb-1">Classification</h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <span class="block text-[10px] text-gray-400 font-bold uppercase mb-1">Security Level</span>
                            @if($metadata['security'] == 'Restricted') <span class="bg-red-100 text-red-700 px-2.5 py-1 rounded text-xs font-bold border border-red-300">Restricted</span>
                            @elseif($metadata['security'] == 'Confidential') <span class="bg-orange-100 text-orange-700 px-2.5 py-1 rounded text-xs font-bold border border-orange-300">Confidential</span>
                            @else <span class="bg-blue-100 text-blue-700 px-2.5 py-1 rounded text-xs font-bold border border-blue-300">Internal</span> @endif
                        </div>
                        <div><span class="block text-[10px] text-gray-400 font-bold uppercase">Document Type</span><span class="font-semibold text-gray-800">{{ $metadata['type'] }}</span></div>
                    </div>
                </div>

                <div>
                    <h3 class="text-xs text-gray-500 font-semibold mb-3 border-b pb-1">File Information</h3>
                    <div class="space-y-2 text-sm text-gray-600">
                        <div class="flex justify-between"><span>Size:</span> <span class="font-medium text-gray-900">{{ $metadata['size'] }}</span></div>
                        <div class="flex justify-between"><span>Uploaded:</span> <span class="font-medium text-gray-900">{{ $metadata['date'] }}</span></div>
                        <div class="flex justify-between"><span>Pages:</span> <span class="font-medium text-gray-900">14 Pages</span></div>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</body>
</html>