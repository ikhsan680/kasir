<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soal {{ $index + 1 }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-slate-950 via-emerald-950 to-teal-950 min-h-screen flex items-start sm:items-center justify-center p-2 sm:p-6">
    <div class="bg-white/10 backdrop-blur-xl border border-white/15 rounded-[22px] sm:rounded-[28px] shadow-[0_30px_80px_rgba(16,185,129,0.18)] p-3 sm:p-6 max-w-full sm:max-w-2xl w-full text-white ring-1 ring-emerald-400/20">
        <div class="flex flex-col gap-3 sm:gap-4 mb-4 sm:mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <p class="text-sm uppercase tracking-[0.25em] text-emerald-200">Soal</p>
                    <h1 class="text-xl sm:text-3xl font-black">{{ $index + 1 }} / {{ $total }}</h1>
                </div>
                <div class="flex flex-wrap gap-2">
                    <button type="button" onclick="confirmBackToHome()" class="inline-flex items-center justify-center rounded-full border border-cyan-300/40 bg-cyan-500/10 px-4 py-2 text-sm font-semibold text-cyan-100 transition hover:bg-cyan-500/20">
                        Kembali ke halaman awal
                    </button>
                </div>
            </div>

            <div class="inline-flex items-center gap-2 self-start rounded-full bg-emerald-400 text-slate-900 px-4 py-2 font-bold text-sm shadow-lg shadow-emerald-500/20">
                <span>{{ $mode === 'exam' ? 'Mode Ujian' : 'Mode Latihan' }}</span>
                @if ($mode === 'exam')
                    <span class="inline-flex items-center rounded-full bg-slate-900/10 px-2 py-1 text-xs font-black" id="exam-timer">00:00</span>
                @endif
            </div>
        </div>

        <div class="rounded-2xl border border-white/10 bg-slate-900/30 p-3 sm:p-6 mb-4 sm:mb-6 shadow-inner shadow-slate-950/40">
            <p class="text-base sm:text-lg mb-3 sm:mb-4 text-emerald-50">Seseorang membeli barang dengan harga:</p>
            <div class="grid md:grid-cols-2 gap-2 sm:gap-4 text-center">
                <div class="rounded-2xl border border-yellow-300/20 bg-slate-800/70 p-3 sm:p-4 shadow-lg shadow-slate-950/20">
                    <p class="text-sm text-slate-300">Harga barang</p>
                    <p class="text-xl sm:text-3xl font-bold text-yellow-300">{{ 'Rp ' . number_format($current['price'], 0, ',', '.') }}</p>
                </div>
                <div class="rounded-2xl border border-cyan-300/20 bg-slate-800/70 p-3 sm:p-4 shadow-lg shadow-slate-950/20">
                    <p class="text-sm text-slate-300">Uang yang dibayar</p>
                    <p class="text-xl sm:text-3xl font-bold text-cyan-300">{{ 'Rp ' . number_format($current['payment'], 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="mt-3 sm:mt-6 text-center">
                <p class="text-base sm:text-xl font-semibold">Berapa uang kembaliannya?</p>
            </div>
        </div>

        @if ($feedback && ($feedback['index'] ?? null) === $index)
            <div class="mb-4 sm:mb-6 rounded-2xl border p-3 sm:p-5 shadow-lg transform transition-all duration-300 {{ $feedback['is_correct'] ? 'border-emerald-400 bg-emerald-500/10 text-emerald-100 animate-pulse' : 'border-rose-400 bg-rose-500/10 text-rose-100 animate-pulse' }}" data-feedback-status="{{ $feedback['is_correct'] ? 'correct' : 'wrong' }}">
                <div class="flex items-center gap-2 sm:gap-3 mb-2">
                    <span class="inline-flex h-10 w-10 sm:h-12 sm:w-12 items-center justify-center rounded-full text-xl sm:text-2xl {{ $feedback['is_correct'] ? 'bg-emerald-500/20 text-emerald-300' : 'bg-rose-500/20 text-rose-300' }}">
                        {{ $feedback['is_correct'] ? '✓' : '✕' }}
                    </span>
                    <p class="font-black text-xl sm:text-2xl uppercase tracking-wide">{{ $feedback['is_correct'] ? 'Benar' : 'Salah' }}</p>
                </div>
                @if (!$feedback['is_correct'])
                    <p class="mb-2 text-sm opacity-90">Jawaban yang benar adalah:</p>
                    <p class="text-xl sm:text-2xl font-black">{{ 'Rp ' . number_format($feedback['correct_answer'], 0, ',', '.') }}</p>
                @endif
            </div>
        @endif

        @if ($error)
            <div class="mb-4 rounded-xl border border-rose-400/40 bg-rose-500/10 p-3 text-sm text-rose-100">
                {{ $error }}
            </div>
        @endif

        @if (!($mode === 'practice' && $feedback && ($feedback['index'] ?? null) === $index))
            <form method="POST" action="{{ route('cashier.submit') }}" id="cashier-form">
                @csrf
                <label for="answer_display" class="block text-sm font-medium text-emerald-100 mb-2">Jawaban</label>
                <input
                    type="text"
                    id="answer_display"
                    placeholder="Masukkan jawaban"
                    class="w-full rounded-2xl border border-emerald-300/50 bg-slate-950/40 text-white placeholder:text-slate-400 px-4 py-2.5 sm:py-3 text-base sm:text-lg focus:outline-none focus:ring-2 focus:ring-emerald-400"
                    inputmode="numeric"
                >
                <input type="hidden" name="answer" id="answer">
                @if ($mode === 'exam')
                    <input type="hidden" name="elapsed_seconds" id="elapsed_seconds">
                @endif

                <div class="mt-4 sm:mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-2 sm:gap-3">
                    <button type="button" onclick="confirmFinishExam()" class="inline-flex items-center justify-center rounded-full border border-rose-300/50 bg-rose-500/10 px-4 sm:px-5 py-2.5 sm:py-3 text-sm font-semibold text-rose-100 transition hover:bg-rose-500/20">
                        Akhiri tes
                    </button>
                    @if ($mode === 'practice' && $feedback && ($feedback['index'] ?? null) === $index)
                        <a href="{{ route('cashier.next') }}" class="inline-flex items-center justify-center rounded-full bg-gradient-to-r from-emerald-400 via-cyan-400 to-blue-500 px-7 py-3 text-base font-black text-slate-900 shadow-[0_0_30px_rgba(34,211,238,0.35)] transition duration-200 hover:scale-[1.02] hover:shadow-[0_0_40px_rgba(59,130,246,0.45)]">
                            Soal berikutnya
                        </a>
                    @else
                        <button type="submit" class="rounded-full bg-gradient-to-r from-emerald-400 to-cyan-400 px-6 py-2.5 sm:py-3 text-sm sm:text-base font-black text-slate-900 shadow-[0_0_30px_rgba(16,185,129,0.35)] transition duration-200 hover:scale-[1.02] hover:shadow-[0_0_40px_rgba(34,211,238,0.45)]">
                            {{ $mode === 'practice' ? 'Jawab' : 'Jawab & Lanjut' }}
                        </button>
                    @endif
                </div>
            </form>
        @else
            <div class="mt-4 sm:mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-2 sm:gap-3">
                <button type="button" onclick="confirmFinishExam()" class="inline-flex items-center justify-center rounded-full border border-rose-300/50 bg-rose-500/10 px-4 sm:px-5 py-2.5 sm:py-3 text-sm font-semibold text-rose-100 transition hover:bg-rose-500/20">
                    Akhiri tes
                </button>
                <a href="{{ route('cashier.next') }}" class="inline-flex items-center justify-center rounded-full bg-gradient-to-r from-emerald-400 via-cyan-400 to-blue-500 px-6 sm:px-7 py-2.5 sm:py-3 text-sm sm:text-base font-black text-slate-900 shadow-[0_0_30px_rgba(34,211,238,0.35)] transition duration-200 hover:scale-[1.02] hover:shadow-[0_0_40px_rgba(59,130,246,0.45)]">
                    Soal berikutnya
                </a>
            </div>
        @endif
    </div>

    <div id="modern-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/60 backdrop-blur-sm p-4">
        <div class="w-full max-w-md rounded-2xl border border-white/10 bg-slate-900/95 p-5 shadow-2xl shadow-cyan-900/30">
            <div class="mb-4 flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-400/15 text-xl text-amber-300">!</div>
                <div>
                    <p class="text-sm font-medium text-slate-300">Konfirmasi</p>
                    <h3 id="modal-title" class="text-lg font-bold text-white">Peringatan</h3>
                </div>
            </div>
            <p id="modal-message" class="text-sm leading-6 text-slate-200">Apakah Anda yakin?</p>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" id="modal-cancel" class="rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm font-semibold text-slate-200 transition hover:bg-white/10">Batal</button>
                <button type="button" id="modal-confirm" class="rounded-full bg-gradient-to-r from-cyan-400 to-emerald-400 px-4 py-2 text-sm font-bold text-slate-900 transition hover:brightness-110">Lanjut</button>
            </div>
        </div>
    </div>

    <script>
        const displayInput = document.getElementById('answer_display');
        const hiddenInput = document.getElementById('answer');
        const form = document.getElementById('cashier-form');
        const examTimer = document.getElementById('exam-timer');
        const elapsedSecondsInput = document.getElementById('elapsed_seconds');
        const modal = document.getElementById('modern-modal');
        const modalTitle = document.getElementById('modal-title');
        const modalMessage = document.getElementById('modal-message');
        const modalCancel = document.getElementById('modal-cancel');
        const modalConfirm = document.getElementById('modal-confirm');

        let seconds = 0;
        let timerInterval = null;
        let pendingAction = null;
        let audioContext = null;


        function openModal(title, message, action) {
            modalTitle.textContent = title;
            modalMessage.textContent = message;
            pendingAction = action;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function formatRupiah(value) {
            const digits = String(value || '').replace(/\D/g, '');
            if (!digits) return '';

            const number = Number(digits);
            const formatted = new Intl.NumberFormat('id-ID').format(number);
            return 'Rp ' + formatted + '.000';
        }

        function formatTime(value) {
            const minutes = Math.floor(value / 60);
            const secs = value % 60;
            return `${String(minutes).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
        }

        function confirmBackToHome() {
            openModal(
                'Kembali ke halaman utama',
                'Apakah Anda yakin ingin kembali ke halaman utama? Progres latihan akan dibatalkan.',
                () => {
                    window.location.href = '{{ route('cashier.home') }}';
                }
            );
        }

        function confirmFinishExam() {
            openModal(
                'Akhiri tes',
                'Apakah Anda yakin ingin mengakhiri tes sekarang? Hasil sementara akan disimpan.',
                () => {
                    const formElement = document.createElement('form');
                    formElement.method = 'POST';
                    formElement.action = '{{ route('cashier.finishExam') }}';

                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';

                    const elapsed = document.createElement('input');
                    elapsed.type = 'hidden';
                    elapsed.name = 'elapsed_seconds';
                    elapsed.value = seconds;

                    formElement.appendChild(csrf);
                    formElement.appendChild(elapsed);
                    document.body.appendChild(formElement);
                    formElement.submit();
                }
            );
        }

        modalCancel.addEventListener('click', function () {
            closeModal();
            pendingAction = null;
        });

        modalConfirm.addEventListener('click', function () {
            const action = pendingAction;
            closeModal();
            pendingAction = null;
            if (action) action();
        });

        if (examTimer) {
            timerInterval = setInterval(() => {
                seconds += 1;
                examTimer.textContent = formatTime(seconds);
            }, 1000);
        }

        if (displayInput) {
            displayInput.dataset.raw = '';

            displayInput.addEventListener('beforeinput', function (event) {
                const inputType = event.inputType || '';

                if (inputType === 'insertText') {
                    const char = event.data || '';
                    if (!/^\d$/.test(char)) {
                        event.preventDefault();
                        return;
                    }

                    event.preventDefault();
                    const raw = (this.dataset.raw || '') + char;
                    this.dataset.raw = raw;
                    this.value = formatRupiah(raw);
                    return;
                }

                if (inputType === 'deleteContentBackward') {
                    event.preventDefault();
                    const raw = (this.dataset.raw || '').slice(0, -1);
                    this.dataset.raw = raw;
                    this.value = raw ? formatRupiah(raw) : '';
                    return;
                }

                if (inputType === 'deleteContentForward') {
                    event.preventDefault();
                    this.dataset.raw = '';
                    this.value = '';
                }
            });

            displayInput.addEventListener('keydown', function (event) {
                const key = event.key;

                if (/^\d$/.test(key)) {
                    event.preventDefault();
                    const raw = (this.dataset.raw || '') + key;
                    this.dataset.raw = raw;
                    this.value = formatRupiah(raw);
                    return;
                }

                if (key === 'Backspace') {
                    event.preventDefault();
                    const raw = (this.dataset.raw || '').slice(0, -1);
                    this.dataset.raw = raw;
                    this.value = raw ? formatRupiah(raw) : '';
                    return;
                }

                if (key === 'Delete') {
                    event.preventDefault();
                    this.dataset.raw = '';
                    this.value = '';
                }
            });
        }

        if (form) {
            form.addEventListener('submit', function () {
                const rawValue = String(displayInput.dataset.raw || '').replace(/\D/g, '');
                hiddenInput.value = rawValue ? Number(rawValue) * 1000 : '';
                displayInput.value = rawValue ? formatRupiah(rawValue) : '';
                if (elapsedSecondsInput) {
                    elapsedSecondsInput.value = seconds;
                }
                if (timerInterval) {
                    clearInterval(timerInterval);
                }
            });
        }

    </script>
</body>
</html>
