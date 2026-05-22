import './bootstrap';
import 'flowbite';

import Alpine from 'alpinejs';

// ── Global Audio Registry ─────────────────────────────────────────────────────
// Ensures only one audio instance plays at a time across the entire page.
// Each audioPlayer registers itself here on play and stops any previous one.
window.__sorakaroAudioRegistry = {
    current: null,

    // Called when an audio instance wants to start playing.
    // Stops the current active instance if it's different.
    activate(instance) {
        if (this.current && this.current !== instance) {
            this.current.stopPlayback();
        }
        this.current = instance;
    },

    // Called when an instance is destroyed (Alpine cleanup).
    deactivate(instance) {
        if (this.current === instance) {
            this.current = null;
        }
    },
};

// ── Audio Player Alpine.js Component ─────────────────────────────────────────
// Used in: learning/guidebook, learn/guidebook, learning/pretest, learning/posttest
// Usage: x-data="audioPlayer('/storage/path/to/file.mp3')"
//
// Features:
//   - Single global instance: only one audio plays at a time (via registry)
//   - Error state: shows graceful fallback if audio cannot be loaded/played
//   - Retry: user can retry after an error
//   - Progress bar + time display
Alpine.data('audioPlayer', (src) => ({
    audio: null,
    playing: false,
    loading: false,
    audioError: false,
    progress: 0,
    timeDisplay: '0:00',

    init() {
        if (!src) {
            this.audioError = true;
            return;
        }

        this.audio = new Audio(src);
        this.audio.preload = 'none';

        this.audio.addEventListener('loadstart',  () => { this.loading = true; this.audioError = false; });
        this.audio.addEventListener('canplay',    () => { this.loading = false; });
        this.audio.addEventListener('ended',      () => { this.playing = false; this.progress = 0; });
        this.audio.addEventListener('timeupdate', () => {
            if (this.audio.duration) {
                this.progress    = (this.audio.currentTime / this.audio.duration) * 100;
                this.timeDisplay = this.formatTime(this.audio.currentTime);
            }
        });

        // Handle network / decode errors gracefully
        this.audio.addEventListener('error', () => {
            this.loading  = false;
            this.playing  = false;
            this.audioError = true;
        });

        // Cleanup: remove from registry when Alpine destroys this component
        this.$cleanup(() => {
            if (this.audio) {
                this.audio.pause();
                this.audio.src = '';
            }
            window.__sorakaroAudioRegistry.deactivate(this);
        });
    },

    // Stop playback without resetting progress (called by registry)
    stopPlayback() {
        if (this.audio) {
            this.audio.pause();
        }
        this.playing = false;
    },

    toggle() {
        if (this.audioError) return;

        if (this.playing) {
            this.audio.pause();
            this.playing = false;
        } else {
            // Register as the active player — stops any other playing audio
            window.__sorakaroAudioRegistry.activate(this);

            this.loading = true;
            this.audio.play()
                .then(() => {
                    this.playing  = true;
                    this.loading  = false;
                    this.audioError = false;
                })
                .catch(() => {
                    this.loading  = false;
                    this.playing  = false;
                    // Don't set audioError here — autoplay policy rejections are transient;
                    // user can simply tap again on mobile.
                });
        }
    },

    retry() {
        if (!this.audio) return;
        this.audioError = false;
        this.audio.load(); // reload the source
    },

    seek(event) {
        if (!this.audio || !this.audio.duration) return;
        const rect = event.currentTarget.getBoundingClientRect();
        this.audio.currentTime = ((event.clientX - rect.left) / rect.width) * this.audio.duration;
    },

    formatTime(seconds) {
        const m = Math.floor(seconds / 60);
        const s = Math.floor(seconds % 60).toString().padStart(2, '0');
        return `${m}:${s}`;
    },
}));

window.Alpine = Alpine;
Alpine.start();
