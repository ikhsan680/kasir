<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil {{ $mode === 'exam' ? 'Ujian' : 'Latihan' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-slate-950 via-violet-950 to-indigo-950 min-h-screen flex items-center justify-center p-4 sm:p-6">
    <div class="bg-white/10 backdrop-blur-xl border border-white/15 rounded-[30px] shadow-[0_30px_80px_rgba(167,139,250,0.22)] p-4 sm:p-8 max-w-full sm:max-w-5xl w-full text-white ring-1 ring-violet-400/20">
        <div class="text-center mb-6 sm:mb-8">
            <div class="inline-flex h-16 w-16 sm:h-20 sm:w-20 items-center justify-center rounded-2xl bg-gradient-to-br from-violet-400 to-indigo-500 text-3xl sm:text-4xl shadow-lg shadow-violet-500/30 mb-4 ring-4 ring-white/10">🎉</div>
            <h1 class="text-3xl sm:text-4xl font-black tracking-tight mb-2">Hasil {{ $mode === 'exam' ? 'Ujian' : 'Latihan' }}</h1>
            <p class="text-purple-100 text-base sm:text-lg">Skor anda</p>
            <div class="mt-4 text-4xl sm:text-5xl font-black text-yellow-300">{{ $score }} / {{ $total }}</div>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-white/10 bg-slate-950/30">
            <table class="min-w-full text-left text-sm">
                <thead class="bg-white/5 text-purple-100">
                    <tr>
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Harga</th>
                        <th class="px-4 py-3">Bayar</th>
                        <th class="px-4 py-3">Kembali</th>
                        <th class="px-4 py-3">Jawaban</th>
                        <th class="px-4 py-3">Benar</th>
                        <th class="px-4 py-3">Status</th>
                @if ($mode === 'exam')
                    <th class="px-4 py-3">Waktu</th>
                @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($summary as $item)
                        <tr class="border-t border-white/10">
                            <td class="px-4 py-3">{{ $item['number'] }}</td>
                            <td class="px-4 py-3">{{ 'Rp ' . number_format($item['price'], 0, ',', '.') }}</td>
                            <td class="px-4 py-3">{{ 'Rp ' . number_format($item['payment'], 0, ',', '.') }}</td>
                            <td class="px-4 py-3">{{ 'Rp ' . number_format($item['change'], 0, ',', '.') }}</td>
                            <td class="px-4 py-3">{{ $item['answered'] ? 'Rp ' . number_format($item['answer'], 0, ',', '.') : '-' }}</td>
                            <td class="px-4 py-3">{{ 'Rp ' . number_format($item['correct_answer'], 0, ',', '.') }}</td>
                            <td class="px-4 py-3">
                                @if ($item['is_correct'])
                                    <span class="text-emerald-300 font-bold">Benar</span>
                                @elseif ($item['answered'])
                                    <span class="text-rose-300 font-bold">Salah</span>
                                @else
                                    <span class="text-slate-300 font-bold">Belum dijawab</span>
                                @endif
                            </td>
                            @if ($mode === 'exam')
                                <td class="px-4 py-3">{{ gmdate('i:s', $item['time_taken']) }}</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-8 flex flex-col sm:flex-row justify-center gap-3 sm:gap-4">
            <a href="{{ route('cashier.restartSameQuiz') }}" class="bg-cyan-400 hover:bg-cyan-300 text-slate-900 font-bold py-3 px-6 rounded-full transition duration-200 text-center">
                Ulangi
            </a>
            <a href="{{ route('cashier.home') }}" class="bg-white/10 hover:bg-white/20 text-white font-bold py-3 px-6 rounded-full transition duration-200 border border-white/20 text-center">
                Kembali ke Menu
            </a>
        </div>
    </div>
</body>
</html>
