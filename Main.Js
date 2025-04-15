// Wait for DOM to be fully loaded before executing script
document.addEventListener("DOMContentLoaded", function () {
  // Mobile menu functionality with animation
  const mobileMenu = document.getElementById("mobile-menu");
  const navList = document.querySelector(".nav-list");

  if (mobileMenu) {
    mobileMenu.addEventListener("click", () => {
      navList.classList.toggle("active");
      // Animate hamburger to X
      mobileMenu.classList.toggle("active");
    });
  }

  // Enhanced scroll animation with different effects
  const scrollElements = document.querySelectorAll(".scroll-animation");

  const elementInView = (el, dividend = 1) => {
    const elementTop = el.getBoundingClientRect().top;
    return (
      elementTop <=
      (window.innerHeight || document.documentElement.clientHeight) / dividend
    );
  };

  const displayScrollElement = (element) => {
    // Get data attribute for animation type or use default
    const animationType = element.getAttribute("data-animation") || "fade-up";
    element.classList.add("visible");
    element.classList.add(animationType);
  };

  const handleScrollAnimation = () => {
    scrollElements.forEach((el, index) => {
      if (elementInView(el, 1.25)) {
        // Add delay based on index for cascade effect
        setTimeout(() => {
          displayScrollElement(el);
        }, index * 150); // 150ms delay between each element
      }
    });
  };

  // Run animation on scroll and on page load
  window.addEventListener("scroll", handleScrollAnimation);
  window.addEventListener("load", handleScrollAnimation);

  // Contact form interactive validation with visual feedback
  const contactForm = document.getElementById("contactForm");

  if (contactForm) {
    const formMessage = document.getElementById("formMessage");
    const formInputs = document.querySelectorAll("input, textarea");
    const submitButton = contactForm.querySelector('button[type="submit"]');

    // Input animation effect
    if (formInputs.length > 0) {
      formInputs.forEach((input) => {
        // Add focus effects
        input.addEventListener("focus", () => {
          input.classList.add("input-focused");
        });

        input.addEventListener("blur", () => {
          if (input.value.trim() !== "") {
            input.classList.add("input-filled");
          } else {
            input.classList.remove("input-filled");
          }
          input.classList.remove("input-focused");
        });

        // Live validation feedback
        input.addEventListener("input", () => {
          if (input.checkValidity()) {
            input.classList.remove("invalid");
            input.classList.add("valid");
          } else {
            input.classList.remove("valid");
            input.classList.add("invalid");
          }
        });
      });
    }

    // Form submission handling
    contactForm.addEventListener("submit", (event) => {
      event.preventDefault(); // Prevent form submission

      // Animated button during submission
      if (submitButton) {
        submitButton.classList.add("submitting");
        submitButton.textContent = "Mengirim...";
      }

      // Get input values
      const name = document.getElementById("name").value;
      const email = document.getElementById("email").value;
      const message = document.getElementById("message").value;

      // Simulate server delay
      setTimeout(() => {
        // Simple validation
        if (name && email && message) {
          formMessage.style.display = "block";
          formMessage.innerHTML =
            '<div class="success-message">✓ Pesan Anda telah dikirim!</div>';
          formMessage.classList.add("message-appear");
          contactForm.reset(); // Reset form after submission

          // Reset input styling
          formInputs.forEach((input) => {
            input.classList.remove("input-filled");
            input.classList.remove("valid");
          });
        } else {
          formMessage.style.display = "block";
          formMessage.innerHTML =
            '<div class="error-message">⚠ Silakan lengkapi semua field.</div>';
          formMessage.classList.add("message-appear");
        }

        // Reset button
        if (submitButton) {
          submitButton.classList.remove("submitting");
          submitButton.textContent = "Kirim Pesan";
        }
      }, 1000);
    });
  }

  // Enhanced image gallery with smooth transitions
  const modal = document.getElementById("myModal");
  const modalImg = document.getElementById("img01");
  const images = document.querySelectorAll(".gallery-image");
  const closeModal = document.querySelector(".close");

  // Image gallery functionality
  if (images.length > 0 && modal) {
    images.forEach((image) => {
      // Click event for modal
      image.addEventListener("click", () => {
        modal.style.display = "block";
        modalImg.src = image.src;
        modalImg.classList.add("modal-image-appear");
      });
    });

    if (closeModal) {
      closeModal.onclick = function () {
        modal.classList.add("modal-closing");
        setTimeout(() => {
          modal.style.display = "none";
          modal.classList.remove("modal-closing");
        }, 300);
      };
    }

    // Close modal when clicking outside
    window.onclick = function (event) {
      if (event.target == modal) {
        modal.classList.add("modal-closing");
        setTimeout(() => {
          modal.style.display = "none";
          modal.classList.remove("modal-closing");
        }, 300);
      }
    };
  }

  // Enhanced blog "Read More" functionality with smooth animation
  const readMoreButtons = document.querySelectorAll(".read-more");

  if (readMoreButtons.length > 0) {
    readMoreButtons.forEach((button) => {
      button.addEventListener("click", () => {
        const moreContent = button.nextElementSibling;

        if (
          moreContent.style.display === "none" ||
          moreContent.style.display === ""
        ) {
          // Show more content with animation
          moreContent.style.display = "block";
          moreContent.style.maxHeight = "0";
          moreContent.style.opacity = "0";

          // Get content height
          const height = moreContent.scrollHeight;

          // Transition to full height and opacity
          setTimeout(() => {
            moreContent.style.transition =
              "max-height 0.5s ease, opacity 0.5s ease";
            moreContent.style.maxHeight = height + "px";
            moreContent.style.opacity = "1";
            button.textContent = "Baca Lebih Sedikit";
            button.classList.add("active");
          }, 10);
        } else {
          // Hide with animation
          moreContent.style.maxHeight = "0";
          moreContent.style.opacity = "0";

          // After animation completes, hide element
          setTimeout(() => {
            moreContent.style.display = "none";
            button.textContent = "Baca Selengkapnya";
            button.classList.remove("active");
          }, 500);
        }
      });
    });
  }

  // Animate heading text with typewriter effect
  const mainHeading = document.querySelector("header h1");
  if (mainHeading) {
    const text = mainHeading.textContent;
    mainHeading.textContent = "";
    let i = 0;

    function typeWriter() {
      if (i < text.length) {
        mainHeading.textContent += text.charAt(i);
        i++;
        setTimeout(typeWriter, 100);
      } else {
        mainHeading.classList.add("heading-complete");
      }
    }

    setTimeout(typeWriter, 500);
  }

  // Page transition effects
  window.addEventListener("beforeunload", function () {
    document.body.classList.add("page-transition-out");
  });

  // On page load, add entrance animation
  document.body.classList.add("page-transition-in");
  setTimeout(() => {
    document.body.classList.remove("page-transition-in");
  }, 1000);
});
