export default (selected = false) => ({
    enabled: false,
    selected: selected,
    tabList: {
        ["x-bind:role"]() {
            return this.enabled ? "tablist" : false;
        }
    },
    tabWrapper: {
        ["x-bind:class"]() {
            if (this.enabled) {
                return this.$el.querySelector("a").href.split("#")[1] === this.selected ? "active" : false;
            }
            return false;
        }
    },
    tab: {
        ["x-bind:id"]() {
            return this.enabled ? `${this.$el.href.split("#")[1]}-btn` : false;
        },
        ["x-bind:role"]() {
            return this.enabled ? "tab" : false;
        },
        ["x-bind:aria-controls"]() {
            return this.enabled ? this.$el.href.split("#")[1] : false;
        },
        ["x-bind:aria-selected"]() {
            return this.enabled ? this.$el.href.split("#")[1] === this.selected : false;
        },
        ["x-bind:tabindex"]() {
            if (this.enabled) {
                return this.$el.href.split("#")[1] === this.selected ? 0 : -1;
            }
            return false;
        },
        ["x-on:click.prevent"]() {
            let selection = this.$el.href.split("#")[1];
            this.selected = selection;
            window.location.hash = selection;
        },
        ["x-on:keyup.right"]() {
            const nextTab = this.$el.parentNode.nextElementSibling ? this.$el.parentNode.nextElementSibling.firstChild : false;
            if (nextTab) {
                nextTab.focus();
                this.selected = nextTab.href.split("#")[1];
            }
        },
        ["x-on:keyup.left"]() {
            const previousTab = this.$el.parentNode.previousElementSibling ? this.$el.parentNode.previousElementSibling.firstChild : false;
            if (previousTab) {
                previousTab.focus();
                this.selected = previousTab.href.split("#")[1];
            }
        }
    },
    tabpanel: {
        ["x-bind:aria-labelledby"]() {
            return this.enabled ? this.$el.id : false;
        },
        ["x-bind:role"]() {
            return this.enabled ? "tabpanel" : false;
        },
        ["x-show"]() {
            if (this.enabled) {
                return this.$el.id === this.selected;
            }
            return true;
        }
    },
    init: function () {
        this.enabled = window.innerWidth > 1023;
    }
});
