<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Search - BRAIN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; background-color: #F8FAFC; }</style>
</head>
<body class="text-slate-800 flex flex-col h-screen overflow-hidden">
    
    <header class="bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center shrink-0 shadow-sm z-10">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}" class="p-2 bg-gray-100 hover:bg-blue-50 hover:text-blue-600 rounded-lg text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <div>
                <h1 class="text-xl font-black text-gray-900">Smart Search Engine</h1>
                <div class="text-[11px] text-gray-500 font-semibold mt-0.5 uppercase tracking-wider">Document Intelligence / <span class="text-blue-600 font-bold">Query</span></div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-black border-2 border-blue-200 text-sm">HN</div>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto p-6 flex flex-col items-center">
        <div class="w-full max-w-5xl mt-6">
            
            <form action="{{ route('smart.search') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-center">
                <div class="flex-1 flex w-full bg-white border-2 {{ $isBooleanUsed ? 'border-purple-500 ring-4 ring-purple-100 shadow-purple-900/10' : 'border-gray-200 focus-within:ring-4 focus-within:ring-blue-100 focus-within:border-blue-500 shadow-sm' }} rounded-xl overflow-hidden transition-all shadow-md">
                    <select class="bg-gray-50 border-r-2 border-gray-200 text-gray-700 text-sm font-bold py-4 px-5 outline-none cursor-pointer">
                        <option>Search by Content</option>
                        <option>Search by Metadata</option>
                    </select>
                    
                    <div class="flex-1 flex items-center bg-white px-4">
                        <svg class="w-6 h-6 {{ $isBooleanUsed ? 'text-purple-600' : 'text-blue-500' }} shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" name="q" class="w-full border-0 focus:ring-0 py-4 px-4 text-base font-semibold text-gray-900 outline-none placeholder-gray-400" placeholder='Try: "HAZOP AND Balongan NOT Tuban"' value="{{ $searchQuery }}">
                    </div>
                </div>

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-black py-4 px-10 rounded-xl shadow-lg shadow-blue-600/30 text-base flex items-center gap-2 transition w-full md:w-auto justify-center">
                    Search
                </button>
            </form>

            @if($isBooleanUsed)
            <div class="mt-5 p-4 bg-gradient-to-r from-purple-50 to-white border border-purple-200 rounded-xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="bg-purple-100 p-2 rounded-lg text-purple-700">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </div>
                    <div>
                        <h4 class="font-black text-purple-900 text-sm">AI Boolean Logic Applied</h4>
                        <p class="text-xs text-purple-700 mt-0.5 font-medium">Sistem mendeteksi dan mengeksekusi operator 
                            @foreach($detectedOperators as $op)
                                <span class="bg-purple-200 text-purple-800 px-1.5 py-0.5 rounded font-bold mx-0.5">{{ $op }}</span>
                            @endforeach
                            secara rekursif.
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <div class="mt-8 bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/80">
                    <h3 class="font-black text-gray-900 text-lg">Search Results</h3>
                    <span class="text-xs font-black text-white bg-blue-600 px-3.5 py-1.5 rounded-lg shadow-sm border border-blue-700">{{ count($results) }} Documents Found</span>
                </div>
                
                @if(count($results) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="text-[11px] text-gray-500 uppercase bg-white border-b-2 border-gray-100 font-black">
                            <tr>
                                <th class="py-4 pl-6">Document Title</th>
                                <th class="py-4">Project Portfolio</th>
                                <th class="py-4">Classification</th>
                                <th class="py-4 pr-6 text-right">Upload Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($results as $doc)
                            <tr class="border-b border-gray-50 hover:bg-blue-50/50 transition-colors cursor-pointer group">
                                <td class="py-4 pl-6 font-bold text-gray-900 flex items-center gap-4 text-base group-hover:text-blue-700 transition-colors">
                                    @if(strtolower($doc['type']) == 'pdf') <div class="w-10 h-10 bg-red-100 border border-red-200 rounded-xl flex items-center justify-center text-red-600 font-black text-[10px] shrink-0 shadow-sm">PDF</div>
                                    @elseif(in_array(strtolower($doc['type']), ['word', 'docx'])) <div class="w-10 h-10 bg-blue-100 border border-blue-200 rounded-xl flex items-center justify-center text-blue-600 font-black text-[10px] shrink-0 shadow-sm">DOC</div>
                                    @elseif(in_array(strtolower($doc['type']), ['excel', 'xlsx'])) <div class="w-10 h-10 bg-green-100 border border-green-200 rounded-xl flex items-center justify-center text-green-600 font-black text-[10px] shrink-0 shadow-sm">XLS</div>
                                    @endif
                                    {{ $doc['title'] }}
                                </td>
                                <td class="py-4 text-gray-700 font-bold">{{ $doc['project'] }}</td>
                                <td class="py-4"><span class="bg-gray-100 text-gray-600 text-[10px] font-bold px-3 py-1.5 rounded-lg border border-gray-200 uppercase shadow-sm">{{ $doc['category'] }}</span></td>
                                <td class="py-4 pr-6 text-right text-gray-500 font-semibold">{{ $doc['date'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="p-16 text-center flex flex-col items-center justify-center bg-gray-50/30">
                    <div class="bg-gray-100 p-4 rounded-full mb-4 border border-gray-200">
                        <svg class="w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="text-xl font-black text-gray-800">No documents found</h3>
                    <p class="text-sm text-gray-500 mt-2 font-medium max-w-md">We couldn't find any documents matching your strict boolean logic query. Try adjusting your parameters.</p>
                </div>
                @endif
            </div>

        </div>
    </main>
</body>
</html>