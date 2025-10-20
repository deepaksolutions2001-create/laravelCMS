<script src="https://cdn.tailwindcss.com"></script>

<section class="w-full bg-white py-16 px-6 md:px-12 overflow-hidden relative font-sans">
  <div class="max-w-7xl mx-auto flex flex-col lg:flex-row items-center gap-12 lg:gap-8 relative">

    <!-- LEFT: large circle + trophy + years -->
    <div class="w-full lg:w-[55%] flex justify-center lg:justify-start relative">
      <!-- Big circular background -->
      <div
        class="absolute rounded-full bg-center bg-no-repeat bg-cover opacity-95"
        style="background-image:url('https://craftohtml.themezaa.com/images/demo-corporate-03.png');"
        id="big-circle"
      ></div>

      <!-- Trophy (placed visually slightly left of circle center) -->
      <img
        src="https://craftohtml.themezaa.com/images/demo-corporate-01.png"
        alt="Trophy"
        id="trophy-img"
        class="relative z-20 drop-shadow-2xl"
      />

      <!-- Years overlay centered on circle -->
      <div id="years-wrap" class="absolute z-30 flex flex-col items-center justify-center text-center select-none">
        <span id="years-count" class="font-extrabold text-[#222F43] leading-none"></span>
        <span id="years-plus" class="font-extrabold text-[#222F43] leading-none"></span>
        <span class="mt-4 px-3 py-1 text-sm md:text-base font-medium text-[#444b58] bg-[#ffe066] rounded-sm shadow">
          Years working experience
        </span>
      </div>
    </div>

    <!-- RIGHT: heading, badge, copy, CTA -->
    <div class="w-full lg:w-[45%] flex flex-col items-center lg:items-start text-center lg:text-left relative">
      <!-- small badge sits visually above/overlapping heading -->
      <img src="https://craftohtml.themezaa.com/images/demo-corporate-02.png"
           alt="Creative Vision"
           id="vision-badge"
           class="absolute z-40"
      />

      <span class="inline-block bg-indigo-100 text-indigo-700 font-semibold text-xs rounded-full px-6 py-2 mb-4 tracking-widest">CREATIVE APPROACH</span>

      <h2 class="font-extrabold text-3xl sm:text-4xl md:text-5xl text-[#222F43] leading-tight mb-4">
        Powerful agency for <br class="hidden md:block"/> corporate business.
      </h2>

      <p class="text-[#747C8E] text-base sm:text-lg mb-6 leading-relaxed">
        We strive to develop real-world web solutions that are ideal for small to large projects with bespoke project requirements.
        <br class="hidden md:block"/>
        We create compelling web designs, which are the right-fit for your target groups and also deliver optimized.
      </p>

      <div class="flex flex-col sm:flex-row items-center gap-4">
        <a href="#" class="bg-[#222F43] hover:bg-indigo-700 text-white font-semibold px-8 py-4 rounded-full shadow-lg transition-all duration-300 flex items-center">
          Read about us
          <svg class="w-5 h-5 ml-2" fill="none" stroke="white" stroke-width="3" viewBox="0 0 24 24"><path d="M13 5l7 7-7 7M5 12h14"></path></svg>
        </a>
        <span class="text-[#222F43] font-semibold flex items-center gap-2 text-base">
          <svg class="w-5 h-5" fill="none" stroke="#222F43" stroke-width="2.5" viewBox="0 0 24 24"><path d="M3 5h18M8 17v-4H6V9h12v4h-2v4"></path></svg>
          1800 222 000
        </span>
      </div>
    </div>
  </div>

  <!-- STATS (kept below, non-overlapping) -->
  <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-12 md:mt-16 max-w-5xl mx-auto text-center">
    <div>
      <span id="stat1" class="text-5xl md:text-6xl font-extrabold text-[#222F43] border-b-[6px] border-[#ffe066] pb-1 px-4">0</span>
      <p class="mt-2 text-[#222F43] font-bold text-sm md:text-base tracking-wider">TELEPHONIC TALK</p>
    </div>
    <div>
      <span id="stat2" class="text-5xl md:text-6xl font-extrabold text-[#222F43] border-b-[6px] border-[#ffe066] pb-1 px-4">0</span>
      <p class="mt-2 text-[#222F43] font-bold text-sm md:text-base tracking-wider">CASES SOLVED</p>
    </div>
    <div>
      <span id="stat3" class="text-5xl md:text-6xl font-extrabold text-[#222F43] border-b-[6px] border-[#ffe066] pb-1 px-4">0</span>
      <p class="mt-2 text-[#222F43] font-bold text-sm md:text-base tracking-wider">COFFEE CUPS</p>
    </div>
    <div>
      <span id="stat4" class="text-5xl md:text-6xl font-extrabold text-[#222F43] border-b-[6px] border-[#ffe066] pb-1 px-4">0</span>
      <p class="mt-2 text-[#222F43] font-bold text-sm md:text-base tracking-wider">HAPPY CLIENTS</p>
    </div>
  </div>

  <!-- Styles & responsive positioning -->
  <style>
    /* Desktop-first absolute sizes */
    #big-circle {
      width: 520px;
      height: 520px;
      left: 40px;
      top: 0;
      filter: drop-shadow(0 20px 40px rgba(34,47,67,0.06));
    }
    #trophy-img {
      width: 380px;
      transform: translateX(-18%);
      top: 12px;
      position: relative;
      z-index: 22;
      animation: trophy 2s ease-in-out infinite;
    }
    #years-wrap { 
      left: 40px;
      top: 50%;
      transform: translateY(-50%);
      position: absolute;
      z-index: 30;
      text-align: center;
    }
    #years-count { font-size: 120px; }
    #years-plus { font-size: 60px; margin-top: -46px; margin-left: 40px; display: inline-block; }

    /* badge position near top-right of circle and overlaps heading */
    #vision-badge {
      width: 84px;
      right: calc(50% - 60px);
      top: -18px;
      transform: translateX(48%);
      position: absolute;
      z-index: 40;
      animation: badge 2.2s ease-in-out infinite;
    }

    /* Animations */
    @keyframes trophy { 0%,100%{transform: translateX(-18%) rotate(-6deg) } 50%{ transform: translateX(-18%) rotate(6deg) } }
    @keyframes badge { 0%,100%{ transform: translateX(48%) translateY(-6px) } 50%{ transform: translateX(48%) translateY(6px) } }

    /* count animation style size */
    .animate-count { animation: count 0.9s both; }
    @keyframes count { from{opacity:0; transform:scale(.95)} to{opacity:1; transform:scale(1)} }

    /* Responsive adjustments */
    @media (max-width: 1280px) {
      #big-circle { width: 480px; height: 480px; left: 20px; }
      #trophy-img { width: 350px; transform: translateX(-14%); }
      #years-count { font-size: 105px; }
      #years-plus { font-size: 56px; margin-left: 34px; margin-top: -40px; }
      #vision-badge { width: 72px; transform: translateX(42%); top: -14px; }
      #years-wrap { left: 20px; }
    }
    @media (max-width: 1024px) {
      /* center circle & trophy more on tablets */
      #big-circle { left: 50%; transform: translateX(-50%); top: -10px; }
      #trophy-img { transform: translateX(-50%); }
      #years-wrap { left: 50%; transform: translate(-50%,-50%); top: 50%; }
      #vision-badge { right: 36%; transform: translateX(36%); top: -8px; }
    }
    @media (max-width: 768px) {
      /* stack layout: circle centered, smaller sizes */
      #big-circle { width: 420px; height: 420px; left: 50%; transform: translateX(-50%); top: -6px; }
      #trophy-img { width: 300px; transform: translateX(-50%); top: 0; }
      #years-count { font-size: 88px; }
      #years-plus { font-size: 46px; margin-top: -36px; margin-left: 26px; }
      #vision-badge { display: none; } /* hide badge on very small screens to avoid overlap */
      /* ensure right column stacks under the circle visually */
      .max-w-7xl > .flex { align-items: center; }
    }
    @media (max-width: 420px) {
      #big-circle { width: 320px; height: 320px; top:-4px; }
      #trophy-img { width: 240px; }
      #years-count { font-size: 64px; }
      #years-plus { font-size: 36px; margin-top: -28px; margin-left: 18px; }
    }
  </style>

  <!-- JS: count-up (keeps same behavior) -->
  <script>
    function countUp(el, target, duration) {
      const start = 0;
      const range = target - start;
      const minTimer = 20;
      let current = start;
      const stepTime = Math.max(Math.floor(duration / (target/10)), minTimer);
      const startTime = performance.now();

      function step(now) {
        const elapsed = now - startTime;
        const progress = Math.min(elapsed / duration, 1);
        current = Math.floor(start + range * progress);
        el.textContent = current;
        if (progress < 1) requestAnimationFrame(step);
        else el.textContent = target;
      }
      requestAnimationFrame(step);
    }

    document.addEventListener('DOMContentLoaded', function() {
      // years plus display
      const yearsEl = document.getElementById('years-count');
      const plusEl = document.getElementById('years-plus');

      // initialize text
      yearsEl.classList.add('animate-count');
      plusEl.classList.add('animate-count');

      // run counters
      countUp(yearsEl, 28, 900);
      plusEl.textContent = '+';

      countUp(document.getElementById('stat1'), 4586, 1500);
      countUp(document.getElementById('stat2'), 583, 1600);
      countUp(document.getElementById('stat3'), 6548, 1900);
      countUp(document.getElementById('stat4'), 836, 1200);
    });
  </script>
</section>
