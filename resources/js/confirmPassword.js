/* global axios */

export default (confirmedPasswordStatusRoute = false, confirmPasswordRoute = false) => ({
    routes: {
        confirmedPasswordStatus: confirmedPasswordStatusRoute,
        confirmPassword: confirmPasswordRoute
    },
    locale: false,
    confirmedPassword: false,
    targetForm: false,
    showingModal: false,
    validationError: false,
    init() {
        axios.get(this.routes.confirmedPasswordStatus).then(response => {
            if (response.data.confirmed) {
                this.confirmedPassword = true;
            }
        });
    },
    submitForm: function (event) {
        if (this.confirmedPassword === true) {
            event.target.submit();
        } else {
            this.targetForm = event.target;
            this.showModal();
        }
    },
    showModal: function () {
        this.showingModal = true;
        const scrollY = window.scrollY;
        document.body.style.position = "fixed";
        document.body.style.top = `-${scrollY}px`;
        this.$nextTick(() => {
            const elems = document.querySelectorAll("a, button, input, select, textarea, [contenteditable]");
            Array.prototype.forEach.call(elems, elem => {
                if (!elem.closest(".modal")) {
                    elem.setAttribute("tabindex", "-1");
                }
            });
            this.$refs.password.focus();
        });
    },
    hideModal: function () {
        this.showingModal = false;
        const scrollY = document.body.style.top;
        document.body.style.position = "";
        document.body.style.top = "";
        window.scrollTo(0, parseInt(scrollY || "0") * -1);
        const elems = document.querySelectorAll("a, button, input, select, textarea, [contenteditable]");
        Array.prototype.forEach.call(elems, elem => {
            if (!elem.closest(".modal")) {
                elem.removeAttribute("tabindex", "-1");
            }
        });
    },
    confirmPassword: function () {
        axios.post(this.routes.confirmPassword, {
            password: this.$refs.password.value
        }).then(() => {
            this.hideModal();
            this.confirmPassword = true;
            this.validationError = false;
            this.targetForm.submit();
        })["catch"](() => {
            this.$refs.password.focus();
            this.validationError = true;
        });
    },
    cancel: function () {
        this.hideModal();
    }
});
