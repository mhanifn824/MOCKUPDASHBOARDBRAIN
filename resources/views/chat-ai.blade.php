<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BRAIN AI Assistant</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-[#F9FAFB] flex h-screen overflow-hidden text-slate-800">

    <aside class="w-64 bg-[#F2F4F7] border-r border-gray-200 flex flex-col h-full flex-shrink-0">
        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-800 transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <span class="text-xs font-bold text-gray-500">Back to Dashboard</span>
        </div>
        <div class="p-4">
            <button class="w-full bg-[#18754b] hover:bg-[#125a3a] text-white text-sm font-semibold py-2.5 rounded-md flex items-center justify-center gap-2 transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                New chat
            </button>
        </div>
        
        <div class="px-4 py-2 text-xs font-semibold text-gray-500">Chat Histories</div>
        <div class="flex-1 overflow-y-auto px-2 pb-4 space-y-1">
            <div class="flex items-center gap-3 px-3 py-2.5 hover:bg-gray-200 rounded-lg cursor-pointer transition text-sm text-gray-700">
                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
                Tes
            </div>
            <div class="flex items-center gap-3 px-3 py-2.5 hover:bg-gray-200 rounded-lg cursor-pointer transition text-sm text-gray-700">
                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
                Tes2
            </div>
        </div>
    </aside>

    <main class="flex-1 flex flex-col items-center justify-center relative bg-white">
        <div class="flex flex-col items-center justify-center opacity-80 pointer-events-none select-none">
             <svg class="w-64 h-64 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="10" rx="2" ry="2"></rect>
                <circle cx="12" cy="5" r="2"></circle>
                <path d="M12 7v4"></path>
                <line x1="8" y1="16" x2="8" y2="16"></line>
                <line x1="16" y1="16" x2="16" y2="16"></line>
                <path d="M21 15c0-2-1-3-2-3s-2 1-2 3"></path>
                <path d="M3 15c0-2 1-3 2-3s2 1 2 3"></path>
                <path d="M6 5h2M6 8h4" stroke="currentColor" stroke-width="2"/>
                <path d="M16 5h.01" stroke="#ef4444" stroke-width="3"/>
            </svg>
            <h2 class="text-xl font-bold text-gray-600 mt-4">BRAIN AI Assistant</h2>
            <p class="text-sm text-gray-400">Ask me anything about your project documents.</p>
        </div>

        <div class="absolute bottom-10 w-full max-w-3xl px-4">
            <div class="bg-white border border-gray-200 shadow-lg rounded-2xl p-2 relative flex items-end">
                <textarea rows="2" class="w-full bg-transparent border-0 focus:ring-0 resize-none text-sm text-gray-700 p-2" placeholder="Ask me anything..."></textarea>
                <div class="flex gap-2 p-2 absolute bottom-1 right-2">
                    <button class="p-2 text-gray-400 hover:text-gray-600 transition"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg></button>
                    <button class="p-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-sm"><svg class="w-4 h-4 transform rotate-90" fill="currentColor" viewBox="0 0 20 20"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path></svg></button>
                </div>
            </div>
            <p class="text-[10px] text-center text-gray-400 mt-4">© 2026 - Copyright PT Pertamina Patra Niaga. All Right Reserved</p>
        </div>
    </main>
</body>
</html>