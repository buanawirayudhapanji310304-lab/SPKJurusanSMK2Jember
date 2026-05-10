<footer id="kontak" class="footer text-white pt-12 pb-6">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">

            {{-- KOLOM 1 --}}
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center shadow-md overflow-hidden flex-shrink-0 p-1">
                        <img src="{{ asset('Assets/Logo_SMKN2Jember.png') }}" alt="Logo" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <div class="font-bold text-sm">SMK Negeri 2 Jember</div>
                        <div class="text-blue-300 text-xs">Est. 1970</div>
                    </div>
                </div>
                <p class="text-blue-200 text-xs leading-relaxed mb-4">
                    Mencetak generasi kompeten, berkarakter, dan siap bersaing di era industri 4.0.
                </p>
                {{-- Sosial Media --}}
                <div class="flex gap-3">
                    <a href="https://www.instagram.com/smkn2.jember" target="_blank"
                       class="w-9 h-9 rounded-lg bg-white/10 hover:bg-pink-600 flex items-center justify-center transition-all duration-300" title="Instagram">
                        <svg class="w-5 h-5 text-white fill-current" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                    <a href="https://youtube.com/@smkn2jember" target="_blank"
                       class="w-9 h-9 rounded-lg bg-white/10 hover:bg-red-600 flex items-center justify-center transition-all duration-300" title="YouTube">
                        <svg class="w-5 h-5 text-white fill-current" viewBox="0 0 24 24">
                            <path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- KOLOM 2 --}}
            <div>
                <h4 class="font-bold text-sm mb-4 text-amber-300">Tentang SMKN 2</h4>
                <ul class="space-y-2 text-xs text-blue-200">
                    <li><a href="https://smkn2jember.sch.id/" class="hover:text-white transition-colors">Profil Sekolah</a></li>
                    <li><a href="https://smkn2jember.sch.id/visi-dan-misi/" class="hover:text-white transition-colors">Visi & Misi</a></li>
                    <li><a href="https://monitorguru.smkn2jember.sch.id/siswa" class="hover:text-white transition-colors">Tenaga Pendidik</a></li>
                    <li><a href="https://smkn2jember.sch.id/fasilitas/" class="hover:text-white transition-colors">Fasilitas Sekolah</a></li>
                    <li><a href="https://smkn2jember.sch.id/prestasi/" class="hover:text-white transition-colors">Prestasi</a></li>
                </ul>
            </div>

            {{-- KOLOM 3 --}}
            <div>
                <h4 class="font-bold text-sm mb-4 text-amber-300">Link Tautkan Kami</h4>
                <ul class="space-y-2 text-xs text-blue-200">
                    <li><a href="https://dindik.jatimprov.go.id" target="_blank" class="hover:text-white transition-colors">Dinas Pendidikan Jatim</a></li>
                    <li><a href="https://kemendikdasmen.go.id/" target="_blank" class="hover:text-white transition-colors">Kemendikdasmen</a></li>
                    <li><a href="https://spmbjatim.net/" target="_blank" class="hover:text-white transition-colors">PPDB Online Jatim</a></li>
                </ul>
            </div>

            {{-- KOLOM 4 --}}
            <div>
                <h4 class="font-bold text-sm mb-4 text-amber-300">Informasi Kontak</h4>
                <ul class="space-y-3 text-xs text-blue-200">
                    <li class="flex gap-2">
                        <span>📍</span>
                        <a href="https://maps.google.com/?q=SMK+Negeri+2+Jember" target="_blank" class="hover:text-white transition-colors">
                            Jl. Tawangmangu No. 52, Jember, Jawa Timur 68121
                        </a>
                    </li>
                    <li class="flex gap-2">
                        <span>📞</span>
                        <a href="#" class="hover:text-white transition-colors">0331337930</a>
                    </li>
                    <li class="flex gap-2">
                        <span>✉️</span>
                        <a href="mailto:smknegeri2jember@gmail.com" class="hover:text-white transition-colors">smkn2jember@gmail.com</a>
                    </li>
                    <li class="flex gap-2">
                        <span>🌐</span>
                        <a href="https://smkn2jember.sch.id/" target="_blank" class="hover:text-white transition-colors">www.smkn2jember.sch.id</a>
                    </li>
                </ul>
            </div>

        </div>

        <div class="border-t border-white/10 pt-5 flex flex-col sm:flex-row justify-between items-center gap-2 text-xs text-blue-300">
            <span>© {{ date('Y') }} SMK Negeri 2 Jember. All Rights Reserved.</span>
            <span>Powered by <a href="{{ route('landing.home') }}" class="text-amber-300 hover:underline">Sistem SPK Pemilihan Jurusan</a></span>
        </div>
    </div>
</footer>