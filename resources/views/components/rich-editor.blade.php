@props([
    'id' => 'content',
    'name' => 'content',
    'value' => '',
    'placeholder' => 'Tuliskan konten artikel berita atau pengumuman di sini...',
    'minHeight' => '360px'
])

<div x-data="wordPressRichEditor({
        id: @js($id),
        initialContent: @js($value)
     })"
     x-init="initEditor()"
     class="border border-[#EBE5DF] rounded-2xl bg-white shadow-xs overflow-hidden focus-within:border-[#FF6B00] focus-within:ring-2 focus-within:ring-[#FF6B00]/20 transition-all">

    <!-- Top Bar: Header & WordPress-style Tabs (Visual vs Teks) -->
    <div class="flex items-center justify-between px-3.5 py-2.5 bg-[#FAF8F5] border-b border-[#EBE5DF]">
        <div class="flex items-center gap-2 text-xs font-montserrat font-bold text-[#1E1B18]">
            <span class="w-6 h-6 rounded-lg bg-[#FF6B00]/10 text-[#FF6B00] flex items-center justify-center">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </span>
            <span>Editor Teks WordPress</span>
        </div>

        <!-- WordPress Tabs: Visual vs Teks -->
        <div class="inline-flex rounded-xl p-1 bg-[#EBE5DF]/70 text-xs font-montserrat font-bold">
            <button type="button"
                    @click="setMode('visual')"
                    :class="mode === 'visual' ? 'bg-white text-[#FF6B00] shadow-xs' : 'text-[#6E675F] hover:text-[#1E1B18]'"
                    class="px-3 py-1 rounded-lg transition-all flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <span>Visual</span>
            </button>
            <button type="button"
                    @click="setMode('text')"
                    :class="mode === 'text' ? 'bg-white text-[#1E1B18] shadow-xs' : 'text-[#6E675F] hover:text-[#1E1B18]'"
                    class="px-3 py-1 rounded-lg transition-all flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                </svg>
                <span>Teks (HTML)</span>
            </button>
        </div>
    </div>

    <!-- Toolbar (Aktif pada Mode Visual) -->
    <div x-show="mode === 'visual'" class="p-2 bg-white border-b border-[#EBE5DF] flex flex-wrap items-center gap-1">
        <!-- Heading / Format Selector -->
        <select @change="formatBlock($event.target.value); $event.target.value=''"
                class="text-xs py-1.5 px-2.5 rounded-lg border border-[#EBE5DF] bg-[#FAF8F5] text-[#1E1B18] font-montserrat font-semibold focus:ring-1 focus:ring-[#FF6B00] cursor-pointer">
            <option value="">Paragraf (Format Teks)</option>
            <option value="H1">Judul Utama (Heading 1)</option>
            <option value="H2">Sub-Judul (Heading 2)</option>
            <option value="H3">Sub-Judul Sedang (Heading 3)</option>
            <option value="H4">Sub-Judul Kecil (Heading 4)</option>
            <option value="P">Paragraf Standar</option>
            <option value="BLOCKQUOTE">Kutipan Kata (Blockquote)</option>
            <option value="PRE">Kode / Preformatted</option>
        </select>

        <div class="h-5 w-px bg-[#EBE5DF] mx-1"></div>

        <!-- Bold -->
        <button type="button"
                @mousedown.prevent
                @click="execCmd('bold')"
                :class="activeFormats.bold ? 'bg-[#FFF7ED] text-[#FF6B00] border-[#FED7AA]' : 'text-[#1E1B18] hover:bg-[#FAF8F5] border-transparent'"
                class="w-8 h-8 rounded-lg border flex items-center justify-center font-serif font-black text-sm transition"
                title="Tebal / Bold (Ctrl+B)">
            B
        </button>

        <!-- Italic -->
        <button type="button"
                @mousedown.prevent
                @click="execCmd('italic')"
                :class="activeFormats.italic ? 'bg-[#FFF7ED] text-[#FF6B00] border-[#FED7AA]' : 'text-[#1E1B18] hover:bg-[#FAF8F5] border-transparent'"
                class="w-8 h-8 rounded-lg border flex items-center justify-center font-serif italic font-bold text-sm transition"
                title="Miring / Italic (Ctrl+I)">
            I
        </button>

        <!-- Underline -->
        <button type="button"
                @mousedown.prevent
                @click="execCmd('underline')"
                :class="activeFormats.underline ? 'bg-[#FFF7ED] text-[#FF6B00] border-[#FED7AA]' : 'text-[#1E1B18] hover:bg-[#FAF8F5] border-transparent'"
                class="w-8 h-8 rounded-lg border flex items-center justify-center font-serif underline font-bold text-sm transition"
                title="Garis Bawah / Underline (Ctrl+U)">
            U
        </button>

        <!-- Strikethrough -->
        <button type="button"
                @mousedown.prevent
                @click="execCmd('strikeThrough')"
                :class="activeFormats.strikeThrough ? 'bg-[#FFF7ED] text-[#FF6B00] border-[#FED7AA]' : 'text-[#1E1B18] hover:bg-[#FAF8F5] border-transparent'"
                class="w-8 h-8 rounded-lg border flex items-center justify-center line-through font-serif font-bold text-sm transition"
                title="Coret / Strikethrough">
            S
        </button>

        <div class="h-5 w-px bg-[#EBE5DF] mx-1"></div>

        <!-- Color Palette Dropdown -->
        <div class="relative" x-data="{ openColor: false }">
            <button type="button"
                    @mousedown.prevent
                    @click="openColor = !openColor"
                    class="h-8 px-2 rounded-lg border border-transparent hover:bg-[#FAF8F5] flex items-center gap-1 text-xs font-bold font-montserrat text-[#1E1B18]"
                    title="Pilih Warna Teks">
                <span class="underline decoration-4 decoration-[#FF6B00] font-serif font-extrabold text-sm">A</span>
                <svg class="w-3 h-3 text-[#6E675F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="openColor" @click.away="openColor = false" x-cloak
                 class="absolute left-0 mt-1 z-30 p-2.5 bg-white border border-[#EBE5DF] rounded-2xl shadow-xl grid grid-cols-4 gap-2 w-44">
                <button type="button" @mousedown.prevent @click="setTextColor('#1E1B18'); openColor = false" class="w-7 h-7 rounded-lg bg-[#1E1B18] border border-gray-300 hover:scale-110 transition shadow-xs" title="Hitam"></button>
                <button type="button" @mousedown.prevent @click="setTextColor('#4B5563'); openColor = false" class="w-7 h-7 rounded-lg bg-[#4B5563] hover:scale-110 transition shadow-xs" title="Abu-abu"></button>
                <button type="button" @mousedown.prevent @click="setTextColor('#FF6B00'); openColor = false" class="w-7 h-7 rounded-lg bg-[#FF6B00] hover:scale-110 transition shadow-xs" title="Oranye LMS"></button>
                <button type="button" @mousedown.prevent @click="setTextColor('#DC2626'); openColor = false" class="w-7 h-7 rounded-lg bg-[#DC2626] hover:scale-110 transition shadow-xs" title="Merah"></button>
                <button type="button" @mousedown.prevent @click="setTextColor('#16A34A'); openColor = false" class="w-7 h-7 rounded-lg bg-[#16A34A] hover:scale-110 transition shadow-xs" title="Hijau"></button>
                <button type="button" @mousedown.prevent @click="setTextColor('#2563EB'); openColor = false" class="w-7 h-7 rounded-lg bg-[#2563EB] hover:scale-110 transition shadow-xs" title="Biru"></button>
                <button type="button" @mousedown.prevent @click="setTextColor('#9333EA'); openColor = false" class="w-7 h-7 rounded-lg bg-[#9333EA] hover:scale-110 transition shadow-xs" title="Ungu"></button>
                <button type="button" @mousedown.prevent @click="setTextColor('inherit'); openColor = false" class="w-7 h-7 rounded-lg bg-white border border-dashed border-gray-400 text-[10px] flex items-center justify-center font-bold text-gray-500 hover:scale-110 transition shadow-xs" title="Reset Warna">✕</button>
            </div>
        </div>

        <!-- Highlight Color Dropdown -->
        <div class="relative" x-data="{ openHilite: false }">
            <button type="button"
                    @mousedown.prevent
                    @click="openHilite = !openHilite"
                    class="h-8 px-2 rounded-lg border border-transparent hover:bg-[#FAF8F5] flex items-center gap-1 text-xs font-bold font-montserrat text-[#1E1B18]"
                    title="Sorotan Stabilo (Highlight)">
                <span class="bg-amber-200 px-1 py-0.5 rounded text-[11px] font-mono font-bold">BG</span>
                <svg class="w-3 h-3 text-[#6E675F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="openHilite" @click.away="openHilite = false" x-cloak
                 class="absolute left-0 mt-1 z-30 p-2.5 bg-white border border-[#EBE5DF] rounded-2xl shadow-xl grid grid-cols-3 gap-2 w-36">
                <button type="button" @mousedown.prevent @click="setHiliteColor('#FEF08A'); openHilite = false" class="w-7 h-7 rounded-lg bg-[#FEF08A] border border-amber-300 hover:scale-110 transition shadow-xs" title="Kuning Stabilo"></button>
                <button type="button" @mousedown.prevent @click="setHiliteColor('#FED7AA'); openHilite = false" class="w-7 h-7 rounded-lg bg-[#FED7AA] border border-orange-300 hover:scale-110 transition shadow-xs" title="Oranye Muda"></button>
                <button type="button" @mousedown.prevent @click="setHiliteColor('#BBF7D0'); openHilite = false" class="w-7 h-7 rounded-lg bg-[#BBF7D0] border border-emerald-300 hover:scale-110 transition shadow-xs" title="Hijau Muda"></button>
                <button type="button" @mousedown.prevent @click="setHiliteColor('#BFDBFE'); openHilite = false" class="w-7 h-7 rounded-lg bg-[#BFDBFE] border border-blue-300 hover:scale-110 transition shadow-xs" title="Biru Muda"></button>
                <button type="button" @mousedown.prevent @click="setHiliteColor('#FBCFE8'); openHilite = false" class="w-7 h-7 rounded-lg bg-[#FBCFE8] border border-pink-300 hover:scale-110 transition shadow-xs" title="Pink Muda"></button>
                <button type="button" @mousedown.prevent @click="setHiliteColor('transparent'); openHilite = false" class="w-7 h-7 rounded-lg bg-white border border-dashed border-gray-400 text-[10px] flex items-center justify-center font-bold text-gray-500 hover:scale-110 transition shadow-xs" title="Hapus Sorotan">✕</button>
            </div>
        </div>

        <div class="h-5 w-px bg-[#EBE5DF] mx-1"></div>

        <!-- Alignment -->
        <button type="button"
                @mousedown.prevent
                @click="execCmd('justifyLeft')"
                class="w-8 h-8 rounded-lg border border-transparent hover:bg-[#FAF8F5] text-[#1E1B18] flex items-center justify-center transition"
                title="Rata Kiri">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h10M4 18h16"/></svg>
        </button>
        <button type="button"
                @mousedown.prevent
                @click="execCmd('justifyCenter')"
                class="w-8 h-8 rounded-lg border border-transparent hover:bg-[#FAF8F5] text-[#1E1B18] flex items-center justify-center transition"
                title="Rata Tengah">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M7 12h10M4 18h16"/></svg>
        </button>
        <button type="button"
                @mousedown.prevent
                @click="execCmd('justifyRight')"
                class="w-8 h-8 rounded-lg border border-transparent hover:bg-[#FAF8F5] text-[#1E1B18] flex items-center justify-center transition"
                title="Rata Kanan">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M10 12h10M4 18h16"/></svg>
        </button>
        <button type="button"
                @mousedown.prevent
                @click="execCmd('justifyFull')"
                class="w-8 h-8 rounded-lg border border-transparent hover:bg-[#FAF8F5] text-[#1E1B18] flex items-center justify-center transition"
                title="Rata Kiri Kanan / Justify">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>

        <div class="h-5 w-px bg-[#EBE5DF] mx-1"></div>

        <!-- Bullet List -->
        <button type="button"
                @mousedown.prevent
                @click="execCmd('insertUnorderedList')"
                :class="activeFormats.insertUnorderedList ? 'bg-[#FFF7ED] text-[#FF6B00] border-[#FED7AA]' : 'text-[#1E1B18] hover:bg-[#FAF8F5] border-transparent'"
                class="w-8 h-8 rounded-lg border flex items-center justify-center transition"
                title="Daftar Berbutir (Bullet List)">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h.01M4 12h.01M4 18h.01M8 6h12M8 12h12M8 18h12"/></svg>
        </button>

        <!-- Numbered List -->
        <button type="button"
                @mousedown.prevent
                @click="execCmd('insertOrderedList')"
                :class="activeFormats.insertOrderedList ? 'bg-[#FFF7ED] text-[#FF6B00] border-[#FED7AA]' : 'text-[#1E1B18] hover:bg-[#FAF8F5] border-transparent'"
                class="w-8 h-8 rounded-lg border flex items-center justify-center text-xs font-mono font-bold transition"
                title="Daftar Bernomor (Numbered List)">
            1.
        </button>

        <!-- Blockquote -->
        <button type="button"
                @mousedown.prevent
                @click="formatBlock('BLOCKQUOTE')"
                class="w-8 h-8 rounded-lg border border-transparent hover:bg-[#FAF8F5] text-[#1E1B18] flex items-center justify-center font-serif text-base font-bold transition"
                title="Kutipan / Blockquote">
            &ldquo;
        </button>

        <!-- Horizontal Rule -->
        <button type="button"
                @mousedown.prevent
                @click="execCmd('insertHorizontalRule')"
                class="w-8 h-8 rounded-lg border border-transparent hover:bg-[#FAF8F5] text-[#1E1B18] flex items-center justify-center font-mono font-bold transition"
                title="Garis Pemisah (Horizontal Line)">
            ―
        </button>

        <div class="h-5 w-px bg-[#EBE5DF] mx-1"></div>

        <!-- Link Insertion Modal Trigger -->
        <button type="button"
                @mousedown.prevent
                @click="openLinkModal()"
                class="w-8 h-8 rounded-lg border border-transparent hover:bg-[#FAF8F5] text-[#1E1B18] flex items-center justify-center transition"
                title="Sisipkan / Edit Tautan (Link)">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
        </button>

        <!-- Unlink -->
        <button type="button"
                @mousedown.prevent
                @click="execCmd('unlink')"
                class="w-8 h-8 rounded-lg border border-transparent hover:bg-[#FAF8F5] text-[#6E675F] flex items-center justify-center transition"
                title="Hapus Tautan (Unlink)">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
        </button>

        <!-- Callout Box -->
        <button type="button"
                @mousedown.prevent
                @click="insertCallout()"
                class="h-8 px-2 rounded-lg border border-transparent hover:bg-[#FFF7ED] text-[#FF6B00] flex items-center gap-1 text-xs font-montserrat font-bold transition"
                title="Sisipkan Kotak Info / Pengumuman Penting">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="hidden sm:inline">Kotak Info</span>
        </button>

        <div class="h-5 w-px bg-[#EBE5DF] mx-1"></div>

        <!-- Undo & Redo -->
        <button type="button"
                @mousedown.prevent
                @click="execCmd('undo')"
                class="w-8 h-8 rounded-lg border border-transparent hover:bg-[#FAF8F5] text-[#1E1B18] flex items-center justify-center transition"
                title="Batal / Undo (Ctrl+Z)">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a5 5 0 015 5v2m0 0l-4-4m4 4l4-4"/></svg>
        </button>
        <button type="button"
                @mousedown.prevent
                @click="execCmd('redo')"
                class="w-8 h-8 rounded-lg border border-transparent hover:bg-[#FAF8F5] text-[#1E1B18] flex items-center justify-center transition"
                title="Ulangi / Redo (Ctrl+Y)">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10H11a5 5 0 00-5 5v2m0 0l4-4m-4 4l-4-4"/></svg>
        </button>

        <!-- Clear Format -->
        <button type="button"
                @mousedown.prevent
                @click="execCmd('removeFormat')"
                class="w-8 h-8 rounded-lg border border-transparent hover:bg-[#FEF2F2] text-[#DC2626] flex items-center justify-center font-serif text-xs font-bold transition"
                title="Hapus Format (Clear Formatting)">
            T<sub class="text-[9px]">x</sub>
        </button>
    </div>

    <!-- Hidden Native Form Field for Laravel Form Submission -->
    <textarea name="{{ $name }}"
              id="{{ $id }}"
              x-ref="realTextarea"
              class="hidden"
              required>{{ $value }}</textarea>

    <!-- Visual Editor (WYSIWYG Mode) -->
    <div x-show="mode === 'visual'" class="relative bg-white">
        <div x-ref="visualArea"
             contenteditable="true"
             @input="onVisualChange()"
             @keyup="onSelectionEvent()"
             @mouseup="onSelectionEvent()"
             @paste="onPaste($event)"
             data-placeholder="{{ $placeholder }}"
             class="wysiwyg-editor p-5 text-sm sm:text-base text-[#1E1B18] font-quicksand focus:outline-none leading-relaxed overflow-y-auto"
             style="min-height: {{ $minHeight }}; max-height: 650px;">
        </div>
    </div>

    <!-- Text / HTML Code Editor (Teks Mode) -->
    <div x-show="mode === 'text'" class="p-0 bg-[#FAF8F5]">
        <textarea x-ref="htmlArea"
                  x-model="htmlString"
                  @input="onTextareaChange()"
                  rows="15"
                  placeholder="Ketik atau sunting markup HTML artikel di sini..."
                  class="w-full p-5 font-mono text-xs text-[#1E1B18] bg-[#FAF8F5] border-0 focus:ring-0 focus:outline-none leading-relaxed resize-y"
                  style="min-height: {{ $minHeight }};"></textarea>
    </div>

    <!-- Status Bar (Word Count & Character Count like WordPress) -->
    <div class="px-4 py-2 bg-[#FAF8F5] border-t border-[#EBE5DF] flex items-center justify-between text-xs text-[#6E675F] font-quicksand">
        <div class="flex items-center gap-4">
            <span>Jumlah Kata: <strong class="font-montserrat text-[#1E1B18]" x-text="wordCount">0</strong></span>
            <span>&bull;</span>
            <span>Karakter: <strong class="font-montserrat text-[#1E1B18]" x-text="charCount">0</strong></span>
        </div>
        <div class="text-[11px] text-[#A8A29E] font-mono hidden sm:block">
            Pintasan: Ctrl+B (Tebal) &bull; Ctrl+I (Miring) &bull; Ctrl+U (Garis Bawah)
        </div>
    </div>

    <!-- Link Insertion Modal -->
    <div x-show="linkModalOpen"
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs"
         @keydown.escape.window="linkModalOpen = false">
        <div @click.away="linkModalOpen = false"
             class="bg-white rounded-2xl border border-[#EBE5DF] shadow-2xl max-w-md w-full p-6 space-y-4 animate-in fade-in zoom-in-95 duration-150">
            <div class="flex items-center justify-between border-b border-[#EBE5DF] pb-3">
                <h3 class="font-montserrat font-bold text-sm text-[#1E1B18] flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#FF6B00]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    <span>Sisipkan Tautan (Link)</span>
                </h3>
                <button type="button" @click="linkModalOpen = false" class="text-[#6E675F] hover:text-[#1E1B18]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="space-y-3 font-quicksand text-xs">
                <div>
                    <label class="block font-montserrat font-bold text-[11px] uppercase tracking-wider text-[#1E1B18] mb-1">
                        URL Tujuan <span class="text-rose-500">*</span>
                    </label>
                    <input type="url"
                           x-model="linkUrl"
                           placeholder="https://contoh.com/artikel"
                           class="w-full px-3 py-2 rounded-xl border border-[#EBE5DF] bg-[#FAF8F5] text-xs text-[#1E1B18] focus:bg-white focus:ring-2 focus:ring-[#FF6B00]">
                </div>

                <div>
                    <label class="block font-montserrat font-bold text-[11px] uppercase tracking-wider text-[#1E1B18] mb-1">
                        Teks Tautan
                    </label>
                    <input type="text"
                           x-model="linkText"
                           placeholder="Teks yang akan diklik..."
                           class="w-full px-3 py-2 rounded-xl border border-[#EBE5DF] bg-[#FAF8F5] text-xs text-[#1E1B18] focus:bg-white focus:ring-2 focus:ring-[#FF6B00]">
                </div>

                <div class="flex items-center gap-2 pt-1">
                    <input type="checkbox" id="link-new-tab" x-model="linkNewTab" class="w-4 h-4 rounded text-[#FF6B00] border-[#EBE5DF] focus:ring-[#FF6B00]">
                    <label for="link-new-tab" class="font-medium text-[#1E1B18] cursor-pointer">
                        Buka tautan di tab baru (<code class="font-mono text-[10px]">target="_blank"</code>)
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-[#EBE5DF]">
                <button type="button" @click="linkModalOpen = false" class="px-4 py-2 text-xs font-montserrat font-bold text-[#6E675F] hover:text-[#1E1B18]">
                    Batal
                </button>
                <button type="button" @click="applyLink()" class="px-5 py-2 rounded-xl font-montserrat font-bold text-xs bg-[#FF6B00] text-white hover:bg-[#EA580C] transition shadow-xs">
                    Terapkan Tautan
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Styling Typography untuk Editor & Preview Berita */
    .wysiwyg-editor:empty:before {
        content: attr(data-placeholder);
        color: #A8A29E;
        pointer-events: none;
        display: block;
    }
    .wysiwyg-editor h1, .news-content h1 {
        font-family: 'Montserrat', sans-serif !important;
        font-size: 1.875rem !important;
        line-height: 2.25rem !important;
        font-weight: 800 !important;
        color: #1E1B18 !important;
        margin-top: 1.5rem !important;
        margin-bottom: 0.75rem !important;
    }
    .wysiwyg-editor h2, .news-content h2 {
        font-family: 'Montserrat', sans-serif !important;
        font-size: 1.5rem !important;
        line-height: 2rem !important;
        font-weight: 800 !important;
        color: #1E1B18 !important;
        margin-top: 1.25rem !important;
        margin-bottom: 0.5rem !important;
    }
    .wysiwyg-editor h3, .news-content h3 {
        font-family: 'Montserrat', sans-serif !important;
        font-size: 1.25rem !important;
        line-height: 1.75rem !important;
        font-weight: 700 !important;
        color: #1E1B18 !important;
        margin-top: 1rem !important;
        margin-bottom: 0.5rem !important;
    }
    .wysiwyg-editor h4, .news-content h4 {
        font-family: 'Montserrat', sans-serif !important;
        font-size: 1.125rem !important;
        line-height: 1.5rem !important;
        font-weight: 700 !important;
        color: #1E1B18 !important;
        margin-top: 0.75rem !important;
        margin-bottom: 0.25rem !important;
    }
    .wysiwyg-editor p, .news-content p {
        margin-bottom: 0.85rem !important;
        line-height: 1.75 !important;
    }
    .wysiwyg-editor ul, .news-content ul {
        list-style-type: disc !important;
        padding-left: 1.75rem !important;
        margin-bottom: 1rem !important;
    }
    .wysiwyg-editor ol, .news-content ol {
        list-style-type: decimal !important;
        padding-left: 1.75rem !important;
        margin-bottom: 1rem !important;
    }
    .wysiwyg-editor li, .news-content li {
        margin-bottom: 0.35rem !important;
        line-height: 1.6 !important;
    }
    .wysiwyg-editor blockquote, .news-content blockquote {
        border-left: 4px solid #FF6B00 !important;
        padding: 0.75rem 1.25rem !important;
        margin: 1.25rem 0 !important;
        background-color: #FAF8F5 !important;
        border-radius: 0 1rem 1rem 0 !important;
        font-style: italic !important;
        color: #6E675F !important;
    }
    .wysiwyg-editor a, .news-content a {
        color: #FF6B00 !important;
        text-decoration: underline !important;
        font-weight: 600 !important;
    }
    .wysiwyg-editor a:hover, .news-content a:hover {
        color: #EA580C !important;
    }
    .wysiwyg-editor hr, .news-content hr {
        border: 0 !important;
        border-top: 1px solid #EBE5DF !important;
        margin: 1.5rem 0 !important;
    }
    .wysiwyg-editor .callout-box, .news-content .callout-box {
        padding: 1rem 1.25rem !important;
        margin: 1rem 0 !important;
        background-color: #FFF7ED !important;
        border: 1px solid #FED7AA !important;
        border-radius: 1rem !important;
        color: #9A3412 !important;
    }
</style>

@pushOnce('scripts')
<script>
    function wordPressRichEditor(config) {
        return {
            mode: 'visual', // 'visual' | 'text'
            htmlString: '',
            wordCount: 0,
            charCount: 0,
            savedRange: null,
            linkModalOpen: false,
            linkUrl: '',
            linkText: '',
            linkNewTab: true,
            activeFormats: {
                bold: false,
                italic: false,
                underline: false,
                strikeThrough: false,
                insertUnorderedList: false,
                insertOrderedList: false,
            },

            initEditor() {
                this.htmlString = config.initialContent || '';
                this.$refs.visualArea.innerHTML = this.htmlString;
                this.updateCounts();

                // Sinkronisasi otomatis sebelum formulir dikirim
                const form = this.$refs.realTextarea.closest('form');
                if (form) {
                    form.addEventListener('submit', () => {
                        this.syncToRealTextarea();
                    });
                }
            },

            setMode(newMode) {
                if (this.mode === newMode) return;

                if (newMode === 'text') {
                    // Berpindah dari Visual ke Teks (HTML)
                    this.htmlString = this.$refs.visualArea.innerHTML;
                    this.mode = 'text';
                    this.$nextTick(() => {
                        this.$refs.htmlArea.focus();
                    });
                } else {
                    // Berpindah dari Teks ke Visual
                    this.$refs.visualArea.innerHTML = this.htmlString;
                    this.mode = 'visual';
                    this.$nextTick(() => {
                        this.$refs.visualArea.focus();
                    });
                }
                this.syncToRealTextarea();
                this.updateCounts();
            },

            execCmd(command, value = null) {
                if (this.mode !== 'visual') return;
                this.$refs.visualArea.focus();
                document.execCommand(command, false, value);
                this.onVisualChange();
                this.onSelectionEvent();
            },

            formatBlock(tag) {
                if (!tag || this.mode !== 'visual') return;
                this.$refs.visualArea.focus();
                document.execCommand('formatBlock', false, tag);
                this.onVisualChange();
                this.onSelectionEvent();
            },

            setTextColor(color) {
                this.execCmd('foreColor', color);
            },

            setHiliteColor(color) {
                this.execCmd('hiliteColor', color);
            },

            insertCallout() {
                if (this.mode !== 'visual') return;
                this.$refs.visualArea.focus();
                const calloutHtml = `<div class="callout-box"><strong>Catatan Penting:</strong> Tuliskan keterangan atau instruksi khusus pengumuman di sini...</div><p><br></p>`;
                document.execCommand('insertHTML', false, calloutHtml);
                this.onVisualChange();
            },

            openLinkModal() {
                if (this.mode !== 'visual') return;
                this.saveSelection();

                // Ambil teks yang sedang disorot
                const selection = window.getSelection();
                this.linkText = selection ? selection.toString() : '';
                this.linkUrl = '';
                this.linkNewTab = true;
                this.linkModalOpen = true;
            },

            saveSelection() {
                const sel = window.getSelection();
                if (sel.getRangeAt && sel.rangeCount) {
                    this.savedRange = sel.getRangeAt(0);
                }
            },

            restoreSelection() {
                if (this.savedRange) {
                    const sel = window.getSelection();
                    sel.removeAllRanges();
                    sel.addRange(this.savedRange);
                }
            },

            applyLink() {
                if (!this.linkUrl) {
                    alert('Silakan masukkan URL tujuan tautan.');
                    return;
                }

                this.linkModalOpen = false;
                this.$refs.visualArea.focus();
                this.restoreSelection();

                let url = this.linkUrl.trim();
                if (!/^https?:\/\//i.test(url) && !url.startsWith('#') && !url.startsWith('mailto:') && !url.startsWith('tel:')) {
                    url = 'https://' + url;
                }

                const targetAttr = this.linkNewTab ? ' target="_blank" rel="noopener noreferrer"' : '';
                const displayText = this.linkText.trim() || url;
                const linkHtml = `<a href="${url}"${targetAttr}>${displayText}</a>`;

                document.execCommand('insertHTML', false, linkHtml);
                this.onVisualChange();
            },

            onVisualChange() {
                this.htmlString = this.$refs.visualArea.innerHTML;
                this.syncToRealTextarea();
                this.updateCounts();
            },

            onTextareaChange() {
                this.syncToRealTextarea();
                this.updateCounts();
            },

            onSelectionEvent() {
                if (this.mode !== 'visual') return;
                this.activeFormats.bold = document.queryCommandState('bold');
                this.activeFormats.italic = document.queryCommandState('italic');
                this.activeFormats.underline = document.queryCommandState('underline');
                this.activeFormats.strikeThrough = document.queryCommandState('strikeThrough');
                this.activeFormats.insertUnorderedList = document.queryCommandState('insertUnorderedList');
                this.activeFormats.insertOrderedList = document.queryCommandState('insertOrderedList');
            },

            onPaste(event) {
                // Biarkan paste berjalan secara alami namun bersihkan jika ada format rusak
                this.$nextTick(() => {
                    this.onVisualChange();
                });
            },

            syncToRealTextarea() {
                let content = this.mode === 'visual' ? this.$refs.visualArea.innerHTML : this.htmlString;

                // Jika hanya menyisakan tag kosong standar, jadikan string kosong
                const stripped = content.replace(/<[^>]*>/g, '').trim();
                if (!stripped && !content.includes('<img') && !content.includes('<hr')) {
                    content = '';
                }

                this.$refs.realTextarea.value = content;
            },

            updateCounts() {
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = this.htmlString;
                const text = tempDiv.textContent || tempDiv.innerText || '';
                const cleanText = text.trim();

                this.charCount = cleanText.length;
                this.wordCount = cleanText ? cleanText.split(/\s+/).filter(Boolean).length : 0;
            }
        };
    }
</script>
@endPushOnce
