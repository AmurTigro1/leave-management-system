<div id="teamModal" class="fixed inset-0 flex items-center justify-center bg-black/80 hidden block sm:hidden z-[9999] p-4 backdrop-blur-sm">
    <div class="relative rounded-xl shadow-lg w-full max-w-md overflow-hidden bg-white border border-gray-200/80">
        <!-- Close Button -->
        <button onclick="closeTeamModal()" class="absolute top-3 right-3 z-10 text-gray-500 hover:text-red-500 transition-colors p-1 rounded-full bg-white/80 backdrop-blur-sm border border-gray-200/50 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        
        <div class="flex flex-col sm:flex-row h-full">
            <!-- Left Side - Branding -->
            <div class="w-full sm:w-2/5 bg-gradient-to-br from-blue-600 to-blue-500 flex flex-col items-center justify-center p-6 text-white text-center">
                <div class="mb-4">
                    <div class="w-16 h-16 bg-white/20 rounded-lg p-2 mx-auto mb-3 backdrop-blur-sm border border-white/10">
                        <img src="/img/MDC.png" alt="MDC Logo" class="w-full h-full object-contain">
                    </div>
                    <h2 class="text-xl font-bold">MDC Development</h2>
                    <p class="text-blue-100/90 mt-1 text-xs">Innovation Through Collaboration</p>
                </div>
                <div class="mt-auto text-[10px] text-blue-200/70">
                    © 2025 MDC Student Team
                </div>
            </div>
            
            <!-- Right Side - Team Members -->
            <div class="w-full sm:w-3/5 p-4 flex flex-col">
                <h3 class="text-lg font-bold text-gray-800 mb-3">Our Team</h3>

                <div class="space-y-2 flex-grow overflow-y-auto pr-1 custom-scrollbar">
                    <!-- Team Members -->
                    <div class="flex items-start space-x-3 p-3 bg-white hover:bg-gray-50 transition-all rounded-lg border border-gray-100 group">
                        <div class="flex-shrink-0">
                            <div class="h-8 w-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 font-bold text-xs group-hover:scale-110 transition-transform">
                                1
                            </div>
                        </div>
                        <div class="min-w-0">
                            <h4 class="font-semibold text-gray-800 text-sm">John Doe</h4>
                            <p class="text-xs text-gray-600">Frontend Developer</p>
                            <p class="text-[11px] text-gray-500/80 mt-0.5">React & Tailwind CSS</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-3 p-3 bg-white hover:bg-gray-50 transition-all rounded-lg border border-gray-100 group">
                        <div class="flex-shrink-0">
                            <div class="h-8 w-8 rounded-full bg-green-50 flex items-center justify-center text-green-600 font-bold text-xs group-hover:scale-110 transition-transform">
                                2
                            </div>
                        </div>
                        <div class="min-w-0">
                            <h4 class="font-semibold text-gray-800 text-sm">Jane Smith</h4>
                            <p class="text-xs text-gray-600">Backend Developer</p>
                            <p class="text-[11px] text-gray-500/80 mt-0.5">Node.js & Databases</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-3 p-3 bg-white hover:bg-gray-50 transition-all rounded-lg border border-gray-100 group">
                        <div class="flex-shrink-0">
                            <div class="h-8 w-8 rounded-full bg-purple-50 flex items-center justify-center text-purple-600 font-bold text-xs group-hover:scale-110 transition-transform">
                                3
                            </div>
                        </div>
                        <div class="min-w-0">
                            <h4 class="font-semibold text-gray-800 text-sm">Mike Johnson</h4>
                            <p class="text-xs text-gray-600">Database Specialist</p>
                            <p class="text-[11px] text-gray-500/80 mt-0.5">MySQL & MongoDB</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-3 p-3 bg-white hover:bg-gray-50 transition-all rounded-lg border border-gray-100 group">
                        <div class="flex-shrink-0">
                            <div class="h-8 w-8 rounded-full bg-yellow-50 flex items-center justify-center text-yellow-600 font-bold text-xs group-hover:scale-110 transition-transform">
                                4
                            </div>
                        </div>
                        <div class="min-w-0">
                            <h4 class="font-semibold text-gray-800 text-sm">Sarah Williams</h4>
                            <p class="text-xs text-gray-600">UI/UX Designer</p>
                            <p class="text-[11px] text-gray-500/80 mt-0.5">Figma & UX</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-3 p-3 bg-white hover:bg-gray-50 transition-all rounded-lg border border-gray-100 group">
                        <div class="flex-shrink-0">
                            <div class="h-8 w-8 rounded-full bg-red-50 flex items-center justify-center text-red-600 font-bold text-xs group-hover:scale-110 transition-transform">
                                5
                            </div>
                        </div>
                        <div class="min-w-0">
                            <h4 class="font-semibold text-gray-800 text-sm">David Brown</h4>
                            <p class="text-xs text-gray-600">Project Manager</p>
                            <p class="text-[11px] text-gray-500/80 mt-0.5">Agile Leader</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 text-center text-xs text-gray-500/90 font-medium border-t border-gray-100/50 pt-3">
                    Mater Dei College IT Interns
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 3px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 10px;
    }
</style>

<script>
    function openTeamModal() {
        const modal = document.getElementById('teamModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
        }
    }

    function closeTeamModal() {
        const modal = document.getElementById('teamModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.style.display = 'none';
        }
    }

    document.addEventListener('click', function (event) {
        const modal = document.getElementById('teamModal');
        if (modal && event.target === modal) {
            modal.classList.add('hidden');
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('teamModal');
        console.log('Modal hidden on load');
        modal.classList.add('hidden');
        modal.style.display = 'none';
    });
</script>