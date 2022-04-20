export default () => ({
    checkboxes: null,
    init() {
        this.checkboxes = this.$el.querySelectorAll("input[type='checkbox']");
    },
    selectAll() {
        [...this.checkboxes].forEach(el => {
            el.checked = true;
        });
    },
    selectNone() {
        [...this.checkboxes].forEach(el => {
            el.checked = false;
        });
    }
});
