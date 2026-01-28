<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" >
  <meta name="viewport" content="width=device-width, initial-scale=1.0" >
  <title>SewaCare - In-Home Medical Services</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
 <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top border-bottom border-info">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center fw-bold text-info" href={{ route('index') }}>
        <img src="{{ asset('frontend/images/logo.png') }}" alt="SewaCare Logo" class="logo-img me-2">
      </a>
      <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link nav-hover text-dark fw-semibold" href="{{ route('index') }}">Home</a></li>
          <li class="nav-item"><a class="nav-link nav-hover text-dark fw-semibold" href="{{ route('patient.service') }}">Services</a></li>
          <li class="nav-item"><a class="nav-link nav-hover text-dark fw-semibold" href="{{ route('patient.booking') }}">Book</a></li>
          <li class="nav-item"><a class="nav-link nav-hover text-dark fw-semibold" href="#faq">FAQ</a></li>
          <li class="nav-item mb-2 mb-md-0">
            <a class="btn btn-info text-white ms-lg-3" href="{{ route('auth.login') }}">Login</a>
          </li>
                <li class="nav-item"><a class="btn btn-info text-white ms-lg-3" href="{{ route('auth.register') }}">Signup</a></li>
        </ul>
      </div>
    </div>
  </nav>
  <section class="py-5 mt-5 bg-light">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-6 mb-4 mb-md-0">
          <h1 class="fw-bold display-5">Care Comes Home with SewaCare</h1>
          <p class="lead text-muted">Bringing professional healthcare and warm support right to your doorstep. Simple, safe, and trusted by thousands.</p>
          <a href="{{ route('auth.login') }}" class="btn btn-danger btn-lg px-4">Book a Visit</a>
        </div>
        <div class="col-md-6">
          <img src="{{ asset('frontend/images/HomePage.png') }}" alt="Medical Care at Home" class="img-fluid rounded shadow">
        </div>
      </div>
    </div>
  </section>
  <section class="py-5" id="services">
    <div class="container">
      <h2 class="text-center fw-bold mb-5">Our Core Services</h2>
      <div class="row g-4">
        <div class="col-md-4">
          <div class="card h-100 text-center shadow-sm border-0 service-card">
            <div class="card-body">
              <img src="{{ asset('frontend/images/HomeNursing.jpeg') }}" alt="Home Nursing" class="service-img mb-3">
              <h5 class="fw-semibold">Home Nursing</h5>
              <p class="text-muted">Expert nurses for elderly, chronic care, and post-surgery recovery.</p>
              <a href="{{ route('auth.login') }}" class="btn btn-outline-info btn-sm mt-2">Book Now</a>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card h-100 text-center shadow-sm border-0 service-card">
            <div class="card-body">
              <img src="{{ asset('frontend/images/Physiotherapy.jpeg') }}" alt="Physiotherapy" class="service-img mb-3">
              <h5 class="fw-semibold">Physiotherapy</h5>
              <p class="text-muted">Therapy sessions for mobility, recovery, and home-based exercises.</p>
              <a href="{{ route('auth.login') }}" class="btn btn-outline-info btn-sm mt-2">Book Now</a>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card h-100 text-center shadow-sm border-0 service-card">
            <div class="card-body">
              <img src="{{ asset('frontend/images/LabTest.jpeg') }}" alt="Lab Tests" class="service-img mb-3">
              <h5 class="fw-semibold">Lab Tests</h5>
              <p class="text-muted">Home sample collection and fast digital reports within 24 hours.</p>
              <a href="{{ route('auth.login') }}"class="btn btn-outline-info btn-sm mt-2">Book Now</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="py-5 bg-white">
    <div class="container">
      <h2 class="text-center fw-bold mb-5">Why Choose SewaCare?</h2>
      <div class="row g-4">
        <div class="col-md-4 text-center">
          <i class="bi bi-shield-check fs-1 text-info mb-3"></i>
          <h5 class="fw-semibold">Trusted Professionals</h5>
          <p class="text-muted">Certified caregivers and nurses with experience and care.</p>
        </div>
        <div class="col-md-4 text-center">
          <i class="bi bi-house-heart fs-1 text-info mb-3"></i>
          <h5 class="fw-semibold">Home Comfort</h5>
          <p class="text-muted">Get care right from the comfort of your home.</p>
        </div>
        <div class="col-md-4 text-center">
          <i class="bi bi-clock-history fs-1 text-info mb-3"></i>
          <h5 class="fw-semibold">24/7 Support</h5>
          <p class="text-muted">We are just a call or message away, any time of the day.</p>
        </div>
      </div>
    </div>
  </section>
   <section class="py-5 bg-light">
    <div class="container">
      <h2 class="text-center fw-bold mb-5">What Our Clients Say</h2>
      <div class="row g-4">
        <div class="col-md-6">
          <div class="p-4 bg-white shadow rounded">
            <p class="mb-2">"SewaCare made recovery so much easier after surgery. Their team was kind and professional."</p>
            <div class="text-muted small">– Gita Sharma, Kathmandu</div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="p-4 bg-white shadow rounded">
            <p class="mb-2">"The physiotherapist was skilled and helped my father walk again. Thank you, SewaCare!"</p>
            <div class="text-muted small">– Hari KC, Lalitpur</div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="py-5 bg-white">
    <div class="container">
      <h2 class="text-center fw-bold mb-5">How It Works</h2>
      <div class="row text-center g-4">
        <div class="col-md-4">
          <div class="p-3">
            <i class="bi bi-journal-medical fs-1 text-info mb-2"></i>
            <h6 class="fw-semibold">Step 1: Choose Service</h6>
            <p class="small text-muted">Select the service you need — from nursing to lab tests — through our simple booking form.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="p-3">
            <i class="bi bi-person-lines-fill fs-1 text-info mb-2"></i>
            <h6 class="fw-semibold">Step 2: Get Matched</h6>
            <p class="small text-muted">We'll assign a certified caregiver or medical expert based on your requirements and location.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="p-3">
            <i class="bi bi-house-door fs-1 text-info mb-2"></i>
            <h6 class="fw-semibold">Step 3: Receive Care</h6>
            <p class="small text-muted">The caregiver arrives at your home at your selected time to provide quality medical care.</p>
          </div>
        </div>
      </div>
    </div>
  </section>
 <section class="py-5 bg-light">
    <div class="container">
      <h2 class="text-center fw-bold mb-5">Why SewaCare Stands Out</h2>
      <div class="row g-4">
        <div class="col-md-6">
          <div class="d-flex align-items-start">
            <i class="bi bi-check-circle-fill text-info fs-3 me-3"></i>
            <div>
              <h6 class="fw-semibold">Certified and Verified Staff</h6>
              <p class="small text-muted mb-0">All professionals go through rigorous training and background checks.</p>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="d-flex align-items-start">
            <i class="bi bi-cash-coin text-info fs-3 me-3"></i>
            <div>
              <h6 class="fw-semibold">Transparent Pricing</h6>
              <p class="small text-muted mb-0">No hidden charges — you see what you pay for.</p>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="d-flex align-items-start">
            <i class="bi bi-geo-alt-fill text-info fs-3 me-3"></i>
            <div>
              <h6 class="fw-semibold">Widespread Coverage</h6>
              <p class="small text-muted mb-0">Serving across major cities of Nepal, including Kathmandu, Lalitpur, and Bhaktapur.</p>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="d-flex align-items-start">
            <i class="bi bi-emoji-smile text-info fs-3 me-3"></i>
            <div>
              <h6 class="fw-semibold">Satisfaction Guarantee</h6>
              <p class="small text-muted mb-0">Your comfort and recovery is our priority. We ensure timely service and support.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <footer class="bg-white text-info pt-5 pb-3 mt-5 border-top">
    <div class="container">
      <div class="row">
        <div class="col-md-4 mb-4">
          <h5 class="fw-bold d-flex align-items-center"><i class="bi bi-heart-pulse-fill me-2"></i> SewaCare</h5>
          <p class="small">We bring trusted home care services right to your doorstep — nursing, physiotherapy, lab tests and more.</p>
        </div>
        <div class="col-md-4 mb-4">
          <h6 class="fw-semibold mb-3">Quick Links</h6>
          <ul class="list-unstyled small">
            <li><a href="index.html" class="text-info text-decoration-none"><i class="bi bi-house-door me-1"></i>Home</a></li>
            <li><a href="service.html" class="text-info text-decoration-none"><i class="bi bi-activity me-1"></i>Services</a></li>
            <li><a href="booking.html" class="text-info text-decoration-none"><i class="bi bi-calendar-check me-1"></i>Book</a></li>
            <li><a href="#faq" class="text-info text-decoration-none"><i class="bi bi-question-circle me-1"></i>FAQ</a></li>
            <li><a href="login.html" class="text-info text-decoration-none"><i class="bi bi-box-arrow-in-right me-1"></i>Login</a></li>
          </ul>
        </div>
        <div class="col-md-4 mb-4">
          <h6 class="fw-semibold mb-3">Contact Us</h6>
          <p class="small mb-1"><i class="bi bi-geo-alt-fill me-1"></i> Kathmandu, Nepal</p>
          <p class="small mb-1"><i class="bi bi-telephone-fill me-1"></i> +977-9800000000</p>
          <p class="small mb-3"><i class="bi bi-envelope-fill me-1"></i> support@sewacare.com</p>
          <div class="d-flex gap-3">
            <a href="#" class="text-info" aria-label="Facebook"><i class="bi bi-facebook fs-5"></i></a>
            <a href="#" class="text-info" aria-label="Instagram"><i class="bi bi-instagram fs-5"></i></a>
            <a href="#" class="text-info" aria-label="Twitter"><i class="bi bi-twitter fs-5"></i></a>
          </div>
        </div>
      </div>
      <div class="text-center mt-4 pt-3 border-top">
        <small class="text-muted">© 2025 SewaCare. All rights reserved.</small>
      </div>
    </div>
  </footer>
<button type="button" class="btn btn-info rounded-circle position-fixed bottom-0 end-0 m-4" id="chatToggle" aria-label="Open Chat">
    <i class="bi bi-chat-dots-fill text-white fs-5"></i>
  </button>

  <!-- Chat Box -->
  <div id="chatBox" class="card position-fixed bottom-0 end-0 m-4 d-none" style="width: 300px; max-height: 400px;">
    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center py-2 px-3">
      <strong>SewaBot</strong>
      <button type="button" class="btn-close btn-close-white" id="closeChat" aria-label="Close Chat"></button>
    </div>
    <div class="card-body overflow-auto" id="chatBody" style="height: 250px;">
      <small><strong>Bot:</strong> Hello! How can I assist you today?</small><br>
    </div>
    <div class="card-footer p-2">
      <form id="chatForm">
        <div class="input-group input-group-sm">
          <input type="text" class="form-control" id="userInput" placeholder="Type a message..." required>
          <button type="submit" class="btn btn-info text-white">Send</button>
        </div>
      </form>
    </div>
  </div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
  const chatToggle = document.getElementById("chatToggle");
  const chatBox = document.getElementById("chatBox");
  const closeChat = document.getElementById("closeChat");
  const chatForm = document.getElementById("chatForm");
  const chatBody = document.getElementById("chatBody");
  const userInput = document.getElementById("userInput");

  chatToggle.addEventListener("click", () => {
    chatBox.classList.toggle("d-none");
    userInput.focus();
  });

  closeChat.addEventListener("click", () => {
    chatBox.classList.add("d-none");
  });

  function appendMessage(sender, message) {
    chatBody.innerHTML += `<small><strong>${sender}:</strong> ${message}</small><br>`;
    chatBody.scrollTop = chatBody.scrollHeight;
  }

  function showTyping() {
    chatBody.innerHTML += `<small id="typing"><em>Bot is typing...</em></small><br>`;
    chatBody.scrollTop = chatBody.scrollHeight;
  }

  function removeTyping() {
    const typing = document.getElementById("typing");
    if (typing) typing.remove();
  }

  async function sendMessage(message) {
    appendMessage("You", message);
    showTyping();

    try {
      const response = await fetch("{{ route('chat.send') }}", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ message })
      });

      const data = await response.json();
      removeTyping();
      appendMessage("Bot", data.response || "Sorry, I didn’t understand that.");

    } catch (error) {
      console.error(error);
      removeTyping();
      appendMessage("Bot", "Sorry, something went wrong.");
    }
  }

  chatForm.addEventListener("submit", function (e) {
    e.preventDefault();
    const message = userInput.value.trim();
    if (!message) return;

    userInput.value = "";
    sendMessage(message);
  });
</script>


</body>
</html>
