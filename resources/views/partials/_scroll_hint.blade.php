@once
<style>
@keyframes scrollBounce {
  0%, 100% { transform: translateX(-50%) translateY(0); opacity: 1; }
  50%       { transform: translateX(-50%) translateY(12px); opacity: 0.55; }
}
.scroll-hint {
  position: absolute;
  bottom: 36px;
  left: 50%;
  transform: translateX(-50%);
  text-align: center;
  color: rgba(255, 255, 255, 0.88);
  animation: scrollBounce 1.7s ease-in-out infinite;
  z-index: 5;
  pointer-events: none;
  white-space: nowrap;
}
@media (max-width: 767.98px) {
  .scroll-hint {
    bottom: 60px;
  }
}
.scroll-hint span {
  display: block;
  font-size: 12px;
  letter-spacing: 2px;
  text-transform: uppercase;
  margin-bottom: 6px;
}
.scroll-hint i {
  font-size: 24px;
}
</style>
@endonce

<div class="scroll-hint">
  <span>Scroll Down</span>
  <i class="ion-ios-arrow-down"></i>
</div>
