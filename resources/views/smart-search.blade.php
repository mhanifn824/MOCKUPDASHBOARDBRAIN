<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Search - BRAIN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script type="module" src="https://cdn.skypack.dev/@hotwired/turbo"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #F8FAFC; animation: fadeIn 0.3s ease-in-out; transition: opacity 0.2s ease-in-out; }
        .turbo-progress-bar { height: 3px; background-color: #005596; }
        .fade-out { opacity: 0 !important; }
        @keyframes fadeIn { from { opacity: 0.3; } to { opacity: 1; } }
    </style>
</head>
<body class="text-slate-800 flex flex-col h-screen overflow-hidden">
    <header class="bg-white border-b px-6 py-4 flex justify-between items-center shadow-sm z-10">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}" class="p-2 bg-gray-100 hover:bg-blue-50 hover:text-blue-600 rounded-lg text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <h1 class="text-xl font-black">Smart Search Engine</h1>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto p-6 flex flex-col items-center">
        <div class="w-full max-w-5xl mt-4">
            <form action="{{ route('smart.search') }}" method="GET" class="flex gap-4">
                <div class="flex-1 flex bg-white border-2 border-gray-200 rounded-xl overflow-hidden focus-within:border-blue-500 shadow-sm">
                    <input type="text" name="q" class="w-full border-0 py-4 px-4 text-base font-semibold outline-none" value="{{ $searchQuery }}" placeholder="Cari: HAZOP AND Balongan">
                </div>
                <button type="submit" class="bg-blue-600 text-white font-black px-8 rounded-xl shadow-md hover:bg-blue-700">Analyze</button>
            </form>

            <div class="mt-6 bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">
                    <h3 class="font-black">Search Results</h3>
                    <span class="text-xs bg-blue-600 text-white px-3 py-1 rounded">{{ count($results) }} Found</span>
                </div>
                
                @if(count($results) > 0)
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="text-xs uppercase bg-white border-b-2"><tr><th class="py-4 pl-6">Title</th><th>Project</th><th>Category</th></tr></thead>
                    <tbody>
                        @foreach($results as $doc)
                        <tr class="border-b hover:bg-blue-50 cursor-pointer transition">
                            <td class="py-4 pl-6 font-bold text-gray-900"><a href="{{ route('document.preview', ['doc' => $doc['title']]) }}" class="block">{{ $doc['title'] }}</a></td>
                            <td class="py-4 font-semibold">{{ $doc['project'] }}</td>
                            <td class="py-4"><span class="bg-gray-100 px-2 py-1 rounded text-xs">{{ $doc['category'] }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const links = document.querySelectorAll('a[href^="{{ url('/') }}"]');
            links.forEach(link => {
                link.addEventListener('click', function(e) {
                    if (this.target === '_blank') return;
                    e.preventDefault();
                    const targetUrl = this.href;
                    document.body.classList.add('fade-out');
                    setTimeout(() => { window.location.href = targetUrl; }, 200);
                });
            });
        });
    </script>
</body>
</html>