<div id="age-verification-modal" class="fixed inset-0 z-[100] flex items-center justify-center"
     style="background: rgba(0,0,0,0.9); backdrop-filter: blur(10px);">

    <div class="absolute inset-0" onclick="closeModal()"></div>

    <div class="relative max-w-md w-full mx-4">
        <div id="bg-image-left"
             class="absolute -left-32 top-1/2 w-64 h-64 opacity-0"
             style="transform: translateY(-50%) rotate(-15deg);">
            <img src="{{ asset('images/wine-left.png') }}" class="w-full h-full object-contain">
        </div>

        <div id="bg-image-right"
             class="absolute -right-32 top-1/2 w-64 h-64 opacity-0"
             style="transform: translateY(-50%) rotate(15deg);">
            <img src="{{ asset('images/wine-right.png') }}" class="w-full h-full object-contain">
        </div>

        <div class="bg-[#1a1a1a] border border-[#b91c1c]/20 rounded-2xl p-8 text-center relative z-10">
            <div class="w-20 h-20 mx-auto mb-6 rounded-full border-2 border-[#b91c1c] flex items-center justify-center">
                <span class="text-4xl font-bold text-[#b91c1c]">18+</span>
            </div>

            <h2 class="text-3xl text-white mb-4">Внимание!</h2>
            <p class="text-gray-400 mb-8">Наш сайт содержит информацию, предназначенную только для лиц, достигших 18 лет.</p>

            <button onclick="closeModal()" class="bg-[#b91c1c] text-white px-8 py-3 rounded-lg hover:bg-[#991b1b] transition">
                Мне есть 18 лет
            </button>
        </div>
    </div>
</div>

<style>
@keyframes floatLeft {
    from { opacity: 0; transform: translateY(-50%) translateX(-50px) rotate(-5deg); }
    to { opacity: 1; transform: translateY(-50%) translateX(0) rotate(-15deg); }
}
@keyframes floatRight {
    from { opacity: 0; transform: translateY(-50%) translateX(50px) rotate(5deg); }
    to { opacity: 1; transform: translateY(-50%) translateX(0) rotate(15deg); }
}
.animate-left { animation: floatLeft 1s forwards; }
.animate-right { animation: floatRight 1s forwards; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    if (!sessionStorage.getItem('age_warning_shown')) {
        document.body.style.overflow = 'hidden';
        document.getElementById('main-content').style.opacity = '0';
    } else {
        document.getElementById('age-verification-modal').style.display = 'none';
        document.body.style.overflow = 'auto';
        document.getElementById('main-content').style.opacity = '1';
    }
});

function closeModal() {
    const modal = document.getElementById('age-verification-modal');
    const mainContent = document.getElementById('main-content');

    const leftImg = document.getElementById('bg-image-left');
    const rightImg = document.getElementById('bg-image-right');

    leftImg.classList.add('animate-left');
    rightImg.classList.add('animate-right');

    setTimeout(() => {
        modal.style.transition = 'opacity 0.5s';
        modal.style.opacity = '0';
        
        setTimeout(() => {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
            
            sessionStorage.setItem('age_warning_shown', 'true');
            
            mainContent.style.opacity = '1';
            mainContent.style.transition = 'opacity 1s';
        }, 500);
    }, 500);
}
</script>