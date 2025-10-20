<script src="https://cdn.tailwindcss.com"></script>
<link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

<section class="bg-white py-12">
  <div class="max-w-6xl mx-auto text-center">
    <div class="flex flex-col items-center mb-6">
      <span class="uppercase text-[#335086] font-semibold tracking-wider text-lg mb-2 flex items-center gap-2">
        <span class="w-9 border-t border-[#335086] inline-block align-middle"></span>
        Testimonial
        <span class="w-9 border-t border-[#335086] inline-block align-middle"></span>
      </span>
      <h2 class="text-5xl font-bold text-[#151c2b] mb-0 mt-2">Our Clients Say!!!</h2>
    </div>
    <div class="relative w-full flex justify-center items-center pt-3 pb-12">
      <button class="absolute left-8 md:left-0 top-1/2 -translate-y-1/2 bg-white border border-[#335086] rounded-full w-14 h-14 flex items-center justify-center text-2xl hover:bg-[#335086] hover:text-white transition" onclick="slideTesti(-1)">
        <i class="fa-solid fa-arrow-left"></i>
      </button>
      <!-- Slider Track -->
      <div id="testi-track" class="flex justify-center gap-10 w-full transition-all duration-700" style="transform:translateX(0)">
        <!-- Card 1 (inactive/left) -->
        <div class="w-[370px] max-w-[90vw] min-h-[270px] rounded-xl p-7 flex flex-col bg-[#f4f4f4] items-center justify-between shadow transition-all duration-700">
          <div class="mb-12 text-[#7b8794] text-[1.1rem] leading-snug tracking-wide text-center">
            Lorem ipsum dolor, sit amet consectetur adipisicing elit. Quis nostrum cupiditate, eligendi repellendus saepe illum earum architecto dicta quisquam quasi porro officiis. Vero reiciendis,
          </div>
          <div class="flex flex-col items-center -mt-14">
            <div class="relative">
              <img class="w-24 h-24 rounded-full object-cover border-4 border-white shadow absolute left-1/2 -translate-x-1/2 -top-12 z-10" src="image.jpg" alt="User"/>
              <span class="absolute left-1/2 -translate-x-1/2 -top-13 block w-28 h-28 rounded-full border-2 border-dotted border-[#335086]"></span>
            </div>
            <div class="mt-12 text-[1.28rem] font-bold text-[#151c2b]">John Abraham</div>
            <div class="text-[#9ca4b2] text-base mb-2">New York, USA</div>
            <div class="flex justify-center gap-1 mb-2">
              <i class="fa-solid fa-star text-[#335086]"></i>
              <i class="fa-solid fa-star text-[#335086]"></i>
              <i class="fa-solid fa-star text-[#335086]"></i>
              <i class="fa-solid fa-star text-[#335086]"></i>
              <i class="fa-solid fa-star text-[#335086]"></i>
            </div>
          </div>
        </div>
        <!-- Card 2 (active/center) -->
        <div class="w-[370px] max-w-[90vw] min-h-[270px] rounded-xl p-7 flex flex-col bg-[#173970] items-center justify-between shadow-2xl transition-all duration-700 scale-105">
          <div class="mb-12 text-white text-[1.13rem] font-semibold leading-snug tracking-wide text-center">
            Lorem ipsum dolor, sit amet consectetur adipisicing elit. Quis nostrum cupiditate, eligendi repellendus saepe illum earum architecto dicta quisquam quasi porro officiis. Vero reiciendis,
          </div>
          <div class="flex flex-col items-center -mt-14">
            <div class="relative">
              <img class="w-24 h-24 rounded-full object-cover border-4 border-white shadow absolute left-1/2 -translate-x-1/2 -top-12 z-10" src="image.jpg" alt="User"/>
              <span class="absolute left-1/2 -translate-x-1/2 -top-13 block w-28 h-28 rounded-full border-2 border-dotted border-white"></span>
            </div>
            <div class="mt-12 text-[1.28rem] font-bold text-white">John Abraham</div>
            <div class="text-[#a6bde0] text-base mb-2">New York, USA</div>
            <div class="flex justify-center gap-1 mb-2">
              <i class="fa-solid fa-star text-[#fad356]"></i>
              <i class="fa-solid fa-star text-[#fad356]"></i>
              <i class="fa-solid fa-star text-[#fad356]"></i>
              <i class="fa-solid fa-star text-[#fad356]"></i>
              <i class="fa-solid fa-star text-[#fad356]"></i>
            </div>
          </div>
        </div>
        <!-- Card 3 (inactive/right) -->
        <div class="w-[370px] max-w-[90vw] min-h-[270px] rounded-xl p-7 flex flex-col bg-[#f4f4f4] items-center justify-between shadow transition-all duration-700">
          <div class="mb-12 text-[#7b8794] text-[1.1rem] leading-snug tracking-wide text-center">
            Lorem ipsum dolor, sit amet consectetur adipisicing elit. Quis nostrum cupiditate, eligendi repellendus saepe illum earum architecto dicta quisquam quasi porro officiis. Vero reiciendis,
          </div>
          <div class="flex flex-col items-center -mt-14">
            <div class="relative">
              <img class="w-24 h-24 rounded-full object-cover border-4 border-white shadow absolute left-1/2 -translate-x-1/2 -top-12 z-10" src="image.jpg" alt="User"/>
              <span class="absolute left-1/2 -translate-x-1/2 -top-13 block w-28 h-28 rounded-full border-2 border-dotted border-[#335086]"></span>
            </div>
            <div class="mt-12 text-[1.28rem] font-bold text-[#151c2b]">John Abraham</div>
            <div class="text-[#9ca4b2] text-base mb-2">New York, USA</div>
            <div class="flex justify-center gap-1 mb-2">
              <i class="fa-solid fa-star text-[#335086]"></i>
              <i class="fa-solid fa-star text-[#335086]"></i>
              <i class="fa-solid fa-star text-[#335086]"></i>
              <i class="fa-solid fa-star text-[#335086]"></i>
              <i class="fa-solid fa-star text-[#335086]"></i>
            </div>
          </div>
        </div>
      </div>
      <button class="absolute right-8 md:right-0 top-1/2 -translate-y-1/2 bg-white border border-[#335086] rounded-full w-14 h-14 flex items-center justify-center text-2xl hover:bg-[#335086] hover:text-white transition" onclick="slideTesti(1)">
        <i class="fa-solid fa-arrow-right"></i>
      </button>
    </div>
    <!-- Dots -->
    <div class="flex gap-4 justify-center items-center mt-2">
      <button id="dot1" class="w-4 h-4 rounded-full bg-white border border-[#335086]"></button>
      <button id="dot2" class="w-9 h-4 rounded-full bg-[#173970]"></button>
      <button id="dot3" class="w-4 h-4 rounded-full bg-white border border-[#335086]"></button>
    </div>
  </div>
  <script>
    // SLIDES handled by shifting the track
    let current = 1;
    function slideTesti(dir) {
      const track = document.getElementById('testi-track');
      const cards = Array.from(track.children);
      let max = cards.length-1;
      current = Math.max(1, Math.min(max-1, current+dir));
      track.style.transform = `translateX(-${current-1}00vw)`;
      // DOT/ACTIVE card transition for demo, update as needed
      document.getElementById('dot1').className = current===1 ? "w-9 h-4 rounded-full bg-[#173970]" : "w-4 h-4 rounded-full bg-white border border-[#335086]";
      document.getElementById('dot2').className = current===2 ? "w-9 h-4 rounded-full bg-[#173970]" : "w-4 h-4 rounded-full bg-white border border-[#335086]";
      document.getElementById('dot3').className = current===3 ? "w-9 h-4 rounded-full bg-[#173970]" : "w-4 h-4 rounded-full bg-white border border-[#335086]";
    }
    slideTesti(0);
  </script>
</section>
