// Writers Marquee with drag/swipe support
export class WritersMarquee {
  constructor(selector = '.writers-marquee') {
    this.marquee = document.querySelector(selector);
    this.track = this.marquee?.querySelector('.writers-marquee-track');
    this.isDragging = false;
    this.startX = 0;
    this.scrollLeft = 0;
    this.animationPaused = false;
    this.rafId = null;

    if (this.marquee && this.track) {
      this.init();
    }
  }

  init() {
    this.bindEvents();
    this.startAutoScroll();
  }

  bindEvents() {
    this.marquee.addEventListener('mousedown', this.onDragStart.bind(this));
    window.addEventListener('mousemove', this.onDragMove.bind(this));
    window.addEventListener('mouseup', this.onDragEnd.bind(this));
    window.addEventListener('mouseleave', this.onDragEnd.bind(this));

    this.marquee.addEventListener('touchstart', this.onDragStart.bind(this), { passive: true });
    window.addEventListener('touchmove', this.onDragMove.bind(this), { passive: false });
    window.addEventListener('touchend', this.onDragEnd.bind(this));

    this.marquee.addEventListener('mouseenter', () => this.pauseAutoScroll());
    this.marquee.addEventListener('mouseleave', () => this.resumeAutoScroll());

    this.marquee.addEventListener('focusin', () => this.pauseAutoScroll());
    this.marquee.addEventListener('focusout', () => this.resumeAutoScroll());

    window.addEventListener('resize', () => this.resetPosition());
  }

  onDragStart(e) {
    if (e.type === 'mousedown' && e.button !== 0) return;

    this.isDragging = true;
    this.marquee.classList.add('dragging');
    this.pauseAutoScroll();

    const clientX = e.touches ? e.touches[0].clientX : e.clientX;
    this.startX = clientX;
    this.scrollLeft = this.getCurrentTranslateX();
    this.track.style.animation = 'none';
    this.track.style.transition = 'none';
  }

  onDragMove(e) {
    if (!this.isDragging) return;

    e.preventDefault();

    const clientX = e.touches ? e.touches[0].clientX : e.clientX;
    const deltaX = clientX - this.startX;
    const newTranslateX = this.scrollLeft + deltaX;

    this.setTranslateX(newTranslateX);
  }

  onDragEnd() {
    if (!this.isDragging) return;

    this.isDragging = false;
    this.marquee.classList.remove('dragging');
    this.resumeAutoScroll();
  }

  getCurrentTranslateX() {
    const style = window.getComputedStyle(this.track);
    const matrix = style.transform.match(/matrix\((.+)\)/);
    if (matrix) {
      return parseFloat(matrix[1].split(', ')[4]) || 0;
    }
    return 0;
  }

  setTranslateX(x) {
    this.track.style.transform = `translateX(${x}px)`;
  }

  startAutoScroll() {
    if (this.rafId) return;

    const animate = () => {
      if (!this.animationPaused && !this.isDragging) {
        const currentX = this.getCurrentTranslateX();
        const trackWidth = this.track.scrollWidth / 2;
        const newX = currentX - 0.5;

        if (Math.abs(newX) >= trackWidth) {
          this.setTranslateX(newX + trackWidth);
        } else {
          this.setTranslateX(newX);
        }
      }
      this.rafId = requestAnimationFrame(animate);
    };

    animate();
  }

  pauseAutoScroll() {
    this.animationPaused = true;
  }

  resumeAutoScroll() {
    this.animationPaused = false;
  }

  resetPosition() {
    const trackWidth = this.track.scrollWidth / 2;
    const currentX = this.getCurrentTranslateX();
    if (Math.abs(currentX) >= trackWidth) {
      this.setTranslateX(currentX % trackWidth);
    }
  }
}

export function initWritersMarquee() {
  new WritersMarquee();
}