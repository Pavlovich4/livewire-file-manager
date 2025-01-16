@props(['src', 'alt' => ''])

<div
    x-data="{
        zoom: false,
        zoomLevel: 2,
        imgRect: null,
        cursorX: 0,
        cursorY: 0,

        init() {
            this.imgRect = this.$refs.image.getBoundingClientRect()
        },

        handleMouseMove(e) {
            if (!this.zoom) return

            const rect = this.$refs.image.getBoundingClientRect()
            this.cursorX = e.clientX - rect.left
            this.cursorY = e.clientY - rect.top

            const x = (this.cursorX / rect.width) * 100
            const y = (this.cursorY / rect.height) * 100

            this.$refs.zoomedImage.style.transform = `translate(${-x}%, ${-y}%) scale(${this.zoomLevel})`
        }
    }"
    class="relative overflow-hidden rounded-lg"
    @mousemove="handleMouseMove"
    @mouseenter="zoom = true"
    @mouseleave="zoom = false"
>
    <img
        x-ref="image"
        src="{{ $src }}"
        alt="{{ $alt }}"
        class="w-full h-full object-cover"
    >

    <div
        x-show="zoom"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="absolute inset-0 bg-gray-900 bg-opacity-50"
    >
        <div class="absolute inset-0 overflow-hidden">
            <img
                x-ref="zoomedImage"
                src="{{ $src }}"
                alt="{{ $alt }}"
                class="absolute w-full h-full object-cover origin-top-left transition-transform duration-75"
            >
        </div>
    </div>
</div>
