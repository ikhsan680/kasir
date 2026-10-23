<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Study Cashier</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-slate-900 via-cyan-900 to-blue-900 min-h-screen flex items-center justify-center p-4 sm:p-6">
    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-3xl shadow-2xl p-4 sm:p-8 max-w-md sm:max-w-xl w-full text-white">
        <div class="text-center mb-6 sm:mb-8">
            <div class="text-4xl sm:text-5xl mb-3 sm:mb-4">🧮</div>
            <h1 class="text-3xl sm:text-4xl font-bold mb-2 sm:mb-3">Study Cashier</h1>
            <p class="text-cyan-100 text-base sm:text-lg">Latihan hitung uang kembalian seperti kasir profesional</p>
        </div>

        <form method="GET" action="{{ route('cashier.start') }}" class="space-y-4 sm:space-y-6">
            <div class="bg-white/5 border border-white/10 rounded-2xl p-4 sm:p-5">
                <h2 class="font-semibold text-lg sm:text-xl mb-3 sm:mb-4">Pilih rentang soal</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="range" value="below_100" checked class="peer sr-only">
                        <div class="rounded-xl border border-cyan-300/40 bg-cyan-500/10 px-3 py-3 text-center text-sm font-medium text-cyan-50 shadow-sm transition duration-200 hover:border-cyan-300 peer-checked:bg-cyan-400 peer-checked:text-slate-900 peer-checked:font-bold peer-checked:shadow-md">
                            Di bawah 100.000
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="range" value="above_100" class="peer sr-only">
                        <div class="rounded-xl border border-violet-300/40 bg-violet-500/10 px-3 py-3 text-center text-sm font-medium text-violet-50 shadow-sm transition duration-200 hover:border-violet-300 peer-checked:bg-violet-400 peer-checked:text-slate-900 peer-checked:font-bold peer-checked:shadow-md">
                            Di atas 100.000
                        </div>
                    </label>
                </div>
            </div>

            <div class="bg-white/5 border border-white/10 rounded-2xl p-4 sm:p-5">
                <h2 class="font-semibold text-lg sm:text-xl mb-3 sm:mb-4">Pilih mode</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="mode" value="practice" checked class="peer sr-only">
                        <div class="rounded-xl border border-emerald-300/40 bg-emerald-500/10 px-3 py-3 text-center text-sm font-medium text-emerald-50 shadow-sm transition duration-200 hover:border-emerald-300 peer-checked:bg-emerald-400 peer-checked:text-slate-900 peer-checked:font-bold peer-checked:shadow-md">
                            Mode latihan
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="mode" value="exam" class="peer sr-only">
                        <div class="rounded-xl border border-amber-300/40 bg-amber-500/10 px-3 py-3 text-center text-sm font-medium text-amber-50 shadow-sm transition duration-200 hover:border-amber-300 peer-checked:bg-amber-300 peer-checked:text-slate-900 peer-checked:font-bold peer-checked:shadow-md">
                            Mode ujian
                        </div>
                    </label>
                </div>
            </div>

            <div class="bg-white/5 border border-white/10 rounded-2xl p-4 sm:p-5">
                <h2 class="font-semibold text-lg sm:text-xl mb-3 sm:mb-4">Jumlah soal</h2>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 mb-4">
                    <label class="cursor-pointer">
                        <input type="radio" name="question_count" value="10" checked class="peer sr-only">
                        <div class="rounded-xl border border-cyan-300/40 bg-cyan-500/10 px-2 py-2 text-center text-sm font-semibold text-cyan-50 peer-checked:bg-cyan-400 peer-checked:text-slate-900">
                            10
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="question_count" value="20" class="peer sr-only">
                        <div class="rounded-xl border border-cyan-300/40 bg-cyan-500/10 px-2 py-2 text-center text-sm font-semibold text-cyan-50 peer-checked:bg-cyan-400 peer-checked:text-slate-900">
                            20
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="question_count" value="30" class="peer sr-only">
                        <div class="rounded-xl border border-cyan-300/40 bg-cyan-500/10 px-2 py-2 text-center text-sm font-semibold text-cyan-50 peer-checked:bg-cyan-400 peer-checked:text-slate-900">
                            30
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="question_count" value="40" class="peer sr-only">
                        <div class="rounded-xl border border-cyan-300/40 bg-cyan-500/10 px-2 py-2 text-center text-sm font-semibold text-cyan-50 peer-checked:bg-cyan-400 peer-checked:text-slate-900">
                            40
                        </div>
                    </label>
                </div>

                <div class="rounded-xl border border-white/10 bg-slate-800/30 px-3 py-2">
                    <label for="custom_question_count" class="mb-1 block text-xs font-medium text-slate-300">Jumlah soal custom</label>
                    <input id="custom_question_count" type="number" name="custom_question_count" min="1" max="200" placeholder="Masukkan jumlah soal" class="w-full rounded-lg border border-white/10 bg-slate-900/40 px-3 py-2 text-sm text-white placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="inline-block bg-cyan-400 hover:bg-cyan-300 text-slate-900 font-bold py-3 px-8 rounded-full transition duration-200 text-lg shadow-lg">
                    Mulai
                </button>
            </div>
        </form>
    </div>
</body>
</html>
