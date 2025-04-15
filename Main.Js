// Mobile menu functionality with animation
document.addEventListener("DOMContentLoaded", function () {
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

  // Parallax effect for header background
  const header = document.querySelector("header");
  if (header) {
    window.addEventListener("scroll", function () {
      const offset = window.pageYOffset;
      header.style.backgroundPositionY = offset * 0.5 + "px";
    });
  }

  // Contact form interactive validation with visual feedback
  const contactForm = document.getElementById("contactForm");
  const formMessage = document.getElementById("formMessage");

  // Input animation effect
  const formInputs = document.querySelectorAll("input, textarea");
  if (formInputs.length > 0) {
    formInputs.forEach((input) => {
      // Add focus effects
      input.addEventListener("focus", () => {
        input.parentElement.classList.add("input-focused");
      });

      input.addEventListener("blur", () => {
        if (input.value.trim() !== "") {
          input.parentElement.classList.add("input-filled");
        } else {
          input.parentElement.classList.remove("input-filled");
        }
        input.parentElement.classList.remove("input-focused");
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

  if (contactForm) {
    // Add button animation
    const submitButton = contactForm.querySelector('button[type="submit"]');
    if (submitButton) {
      submitButton.addEventListener("mousedown", function () {
        this.classList.add("button-pressed");
      });

      submitButton.addEventListener("mouseup", function () {
        this.classList.remove("button-pressed");
      });
    }

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
            input.parentElement.classList.remove("input-filled");
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

  // Image hover zoom effect
  if (images.length > 0) {
    images.forEach((image) => {
      // Create image wrapper for hover effects
      const wrapper = document.createElement("div");
      wrapper.className = "image-wrapper";
      image.parentNode.insertBefore(wrapper, image);
      wrapper.appendChild(image);

      // Add overlay with icon
      const overlay = document.createElement("div");
      overlay.className = "image-overlay";
      overlay.innerHTML = '<span class="zoom-icon">🔍</span>';
      wrapper.appendChild(overlay);

      // Click event for modal
      wrapper.addEventListener("click", () => {
        modal.style.display = "block";
        modalImg.src = image.src;

        // Add animation class
        modalImg.classList.add("modal-image-appear");

        // Remove animation class after transition
        setTimeout(() => {
          modalImg.classList.remove("modal-image-appear");
        }, 500);
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

    // Arrow key navigation for gallery
    document.addEventListener("keydown", (e) => {
      if (modal.style.display === "block") {
        const currentSrc = modalImg.src;
        const imageUrls = Array.from(images).map((img) => img.src);
        const currentIndex = imageUrls.indexOf(currentSrc);

        if (e.key === "ArrowRight") {
          // Next image
          const nextIndex = (currentIndex + 1) % imageUrls.length;
          modalImg.classList.add("slide-out-left");
          setTimeout(() => {
            modalImg.src = imageUrls[nextIndex];
            modalImg.classList.remove("slide-out-left");
            modalImg.classList.add("slide-in-right");
            setTimeout(() => {
              modalImg.classList.remove("slide-in-right");
            }, 300);
          }, 300);
        } else if (e.key === "ArrowLeft") {
          // Previous image
          const prevIndex =
            (currentIndex - 1 + imageUrls.length) % imageUrls.length;
          modalImg.classList.add("slide-out-right");
          setTimeout(() => {
            modalImg.src = imageUrls[prevIndex];
            modalImg.classList.remove("slide-out-right");
            modalImg.classList.add("slide-in-left");
            setTimeout(() => {
              modalImg.classList.remove("slide-in-left");
            }, 300);
          }, 300);
        } else if (e.key === "Escape") {
          // Close modal
          closeModal.click();
        }
      }
    });
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

  // Add smooth scrolling to all internal links
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault();

      const targetId = this.getAttribute("href");
      const targetElement = document.querySelector(targetId);

      if (targetElement) {
        window.scrollTo({
          top: targetElement.offsetTop - 70, // Offset for fixed header if needed
          behavior: "smooth",
        });
      }
    });
  });

  // Add a fancy cursor effect
  const cursorDot = document.createElement("div");
  cursorDot.className = "cursor-dot";
  document.body.appendChild(cursorDot);

  const cursorOutline = document.createElement("div");
  cursorOutline.className = "cursor-outline";
  document.body.appendChild(cursorOutline);

  document.addEventListener("mousemove", function (e) {
    cursorDot.style.transform = `translate(${e.clientX}px, ${e.clientY}px)`;

    // Add delay to outline for trailing effect
    setTimeout(() => {
      cursorOutline.style.transform = `translate(${e.clientX}px, ${e.clientY}px)`;
    }, 100);
  });

  // On links, add hover effect to cursor
  document.querySelectorAll("a, button").forEach((item) => {
    item.addEventListener("mouseenter", () => {
      cursorDot.classList.add("cursor-hover");
      cursorOutline.classList.add("cursor-hover");
    });

    item.addEventListener("mouseleave", () => {
      cursorDot.classList.remove("cursor-hover");
      cursorOutline.classList.remove("cursor-hover");
    });
  });

  // Page transition effects
  window.addEventListener("beforeunload", function () {
    document.body.classList.add("page-transition-out");
  });

  // On page load, add entrance animation
  document.body.classList.add("page-transition-in");
  setTimeout(() => {
    document.body.classList.remove("page-transition-in");
  }, 1000);

  // Add animated counter for numbers
  const counters = document.querySelectorAll(".counter");
  if (counters.length > 0) {
    counters.forEach((counter) => {
      const target = parseInt(counter.getAttribute("data-target"), 10);
      const duration = parseInt(
        counter.getAttribute("data-duration") || "2000",
        10
      );
      let startTime = null;

      function countUp(timestamp) {
        if (!startTime) startTime = timestamp;
        const progress = timestamp - startTime;
        const percentage = Math.min(progress / duration, 1);

        const value = Math.floor(percentage * target);
        counter.textContent = value;

        if (percentage < 1) {
          requestAnimationFrame(countUp);
        } else {
          counter.textContent = target;
        }
      }

      const observer = new IntersectionObserver(
        (entries) => {
          entries.forEach((entry) => {
            if (entry.isIntersecting) {
              requestAnimationFrame(countUp);
              observer.unobserve(entry.target);
            }
          });
        },
        { threshold: 0.5 }
      );

      observer.observe(counter);
    });
  }
});
