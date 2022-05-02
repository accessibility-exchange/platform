
export default () => ({
    showingModal: false,
    showModal: function () {
        this.showingModal = true;
        const scrollY = window.scrollY;
        document.body.style.position = "fixed";
        document.body.style.top = `-${scrollY}px`;
        document.body.style.width = "100%";
        this.$nextTick(() => {
            const elems = document.querySelectorAll("body > *");
            Array.prototype.forEach.call(elems, elem => {
                if (elem.className !== "modal-wrapper") {
                    elem.setAttribute("inert", "");
                }
            });
        });
    },
    hideModal: function () {
        this.showingModal = false;
        const scrollY = document.body.style.top;
        document.body.style.position = "";
        document.body.style.top = "";
        document.body.style.width = "";
        window.scrollTo(0, parseInt(scrollY || "0") * -1);
        const elems = document.querySelectorAll("body > *");
        Array.prototype.forEach.call(elems, elem => {
            if (elem.className !== "modal-wrapper") {
                elem.removeAttribute("inert");
            }
        });
    }
});
