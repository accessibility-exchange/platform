<div class="stack" role="alert" x-data="{
    visible: false,
    init() {
        this.visible = true;
        setTimeout(() => this.visible = false, 5000)
    }
}">
    {{ $slot }}
</div>
