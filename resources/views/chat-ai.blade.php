<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BRAIN AI Assistant</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #343541; color: #ececf1; }
        .bg-user { background-color: #343541; }
        .bg-ai { background-color: #444654; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .msg-enter { animation: fadeIn 0.4s ease-out forwards; }
        .typing-dot { animation: typing 1.4s infinite ease-in-out both; }
        .typing-dot:nth-child(1) { animation-delay: -0.32s; }
        .typing-dot:nth-child(2) { animation-delay: -0.16s; }
        @keyframes typing { 0%, 80%, 100% { transform: scale(0); } 40% { transform: scale(1); } }
        
        /* TRANSISI KELUAR MASUK HALAMAN */
        @keyframes pageFadeIn { from { opacity: 0; } to { opacity: 1; } }
        .animate-page { animation: pageFadeIn 0.2s ease-out forwards; }
    </style>
</head>
<body class="flex h-screen overflow-hidden animate-page">

    <aside class="w-64 bg-gray-900 border-r border-gray-700 flex flex-col p-3">
        <button onclick="window.location='{{ route('dashboard') }}'" class="flex items-center gap-2 border border-gray-600 rounded-lg p-3 hover:bg-gray-800 transition mb-4 text-sm font-semibold">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Back to Dashboard
        </button>
        <div class="text-xs text-gray-500 font-bold mb-2 px-2">Today</div>
        <div class="p-2 text-sm text-gray-300 hover:bg-gray-800 rounded cursor-pointer truncate">Analisa metrik dashboard</div>
        <div class="p-2 text-sm text-gray-300 hover:bg-gray-800 rounded cursor-pointer truncate">Summary HAZOP Tuban</div>
    </aside>

    <main class="flex-1 flex flex-col relative">
        <div class="flex-1 overflow-y-auto p-4 md:p-8 pb-32" id="chat-container">
            <div class="max-w-3xl mx-auto flex gap-4 msg-enter mb-8">
                <div class="w-8 h-8 rounded bg-green-600 flex items-center justify-center shrink-0 font-bold text-white">AI</div>
                <div class="pt-1">
                    <p class="text-sm md:text-base leading-relaxed">Halo, Eksekutif PT Pertamina Patra Niaga. Saya BRAIN Assistant.<br>
                    Saya telah terhubung dengan data <strong>Dashboard Pipeline secara real-time</strong>. Anda bisa bertanya tentang total dokumen, proyek tertinggi, atau ringkasan dokumen. Ada yang bisa saya bantu analisa hari ini?</p>
                </div>
            </div>
        </div>

        <div class="absolute bottom-0 w-full bg-gradient-to-t from-[#343541] via-[#343541] to-transparent pt-6 pb-6 px-4 md:px-8">
            <div class="max-w-3xl mx-auto relative flex items-center bg-[#40414F] rounded-xl border border-gray-600 shadow-xl overflow-hidden focus-within:border-gray-500 focus-within:ring-1 focus-within:ring-gray-500">
                <input type="text" id="prompt-input" class="w-full bg-transparent border-0 text-white p-4 focus:ring-0 outline-none" placeholder="Tanya AI: 'Berapa total dokumen di sistem?' atau 'Proyek apa yang dokumennya terbanyak?'" onkeypress="handleEnter(event)">
                <button onclick="sendPrompt()" class="p-2 mr-2 text-gray-400 hover:bg-gray-600 hover:text-white rounded-md transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path></svg>
                </button>
            </div>
            <p class="text-center text-xs text-gray-500 mt-3">BRAIN AI is evaluating Dashboard Context locally via Front-End LLM Simulation.</p>
        </div>
    </main>

    <script>
        const chatBox = document.getElementById('chat-container');
        const input = document.getElementById('prompt-input');

        function handleEnter(e) { if(e.key === 'Enter') sendPrompt(); }

        function appendMessage(role, text, id = null) {
            const isUser = role === 'user';
            const bgClass = isUser ? '' : 'bg-ai rounded-lg p-4 -mx-4';
            const icon = isUser ? '<div class="w-8 h-8 rounded bg-blue-600 flex items-center justify-center shrink-0 text-white font-bold">HN</div>' : '<div class="w-8 h-8 rounded bg-green-600 flex items-center justify-center shrink-0 text-white font-bold">AI</div>';
            
            const msgDiv = document.createElement('div');
            msgDiv.className = `max-w-3xl mx-auto flex gap-4 msg-enter mb-6 ${bgClass}`;
            if(id) msgDiv.id = id;
            
            msgDiv.innerHTML = `${icon}<div class="pt-1 w-full text-sm md:text-base leading-relaxed break-words">${text}</div>`;
            chatBox.appendChild(msgDiv);
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        function sendPrompt() {
            const text = input.value.trim();
            if(!text) return;
            appendMessage('user', text);
            input.value = '';
            const typingId = 'typing-' + Date.now();
            appendMessage('ai', '<div class="flex gap-1 pt-1.5"><div class="w-2 h-2 bg-gray-400 rounded-full typing-dot"></div><div class="w-2 h-2 bg-gray-400 rounded-full typing-dot"></div><div class="w-2 h-2 bg-gray-400 rounded-full typing-dot"></div></div>', typingId);

            setTimeout(() => {
                document.getElementById(typingId).remove();
                const response = generateAIResponse(text.toLowerCase());
                appendMessage('ai', response);
            }, 1200); 
        }

        function generateAIResponse(query) {
            const totalDocs = localStorage.getItem('brain_kpi_total_docs') || '0';
            const activeUsers = localStorage.getItem('brain_kpi_active_users') || '0';
            const topProject = localStorage.getItem('brain_top_project') || 'Tidak diketahui';

            if (query.includes('total') && (query.includes('dokumen') || query.includes('document') || query.includes('file'))) {
                return `Berdasarkan integrasi real-time dengan PIPMS, saat ini terdapat total **${totalDocs} dokumen** yang telah terindeks secara penuh ke dalam sistem BRAIN.`;
            } 
            else if (query.includes('proyek') || query.includes('project') || query.includes('terbanyak')) {
                return `Untuk metrik proyek saat ini, **${topProject}** memimpin volume penyimpanan dokumen terbesar di dashboard kita.`;
            } 
            else if (query.includes('user') || query.includes('aktif') || query.includes('pengguna')) {
                return `Traffic dashboard menunjukkan ada **${activeUsers} pengguna aktif** dalam 30 hari terakhir dari berbagai fungsi direktorat infrastruktur.`;
            }
            else if (query.includes('hazop')) {
                return `Saya mendeteksi kata kunci HAZOP. Beberapa dokumen HAZOP (seperti HAZOP Balongan Tahap 1) telah di-tag sebagai **Restricted** oleh sistem keamanan karena mengandung mitigasi risiko kilang Obvitnas. Anda ingin saya merangkum rekomendasinya?`;
            }
            else {
                return `Maaf, sebagai AI Mockup, saya dikonfigurasi untuk menjawab konteks metrik dashboard (seperti "Total Dokumen", "Proyek Terbanyak", atau "User Aktif"). Silakan ajukan pertanyaan terkait metrik tersebut.`;
            }
        }
    </script>
</body>
</html>