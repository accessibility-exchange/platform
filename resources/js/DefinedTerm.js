import { createPopper } from "@popperjs/core";

class DefinedTerm extends HTMLElement {
    constructor() {
        super();

        const term = this.innerText;
        const definition = this.dataset.definition;

        const wrapper = document.createElement("span");
        wrapper.setAttribute("class", "defined-term");

        const button = document.createElement("button");
        button.setAttribute("type", "button");
        button.setAttribute("data-definition", definition);
        button.innerHTML = `${term} <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`;

        const status = document.createElement("span");
        status.setAttribute("role", "status");

        const bubble = document.createElement("span");
        bubble.setAttribute("class", "definition");
        bubble.innerText = definition;

        this.innerHTML = "";
        this.appendChild(wrapper);
        wrapper.appendChild(button);
        wrapper.appendChild(status);

        button.addEventListener("click", function () {
            status.innerHTML = "";
            window.setTimeout(function () {
                status.appendChild(bubble);
                createPopper(button, bubble, {
                    modifiers: [
                        {
                            name: "offset",
                            options: {
                                offset: [0, 10]
                            }
                        }
                    ]
                });
            }, 100);
        });

        document.addEventListener("click", function (e) {
            if (button !== e.target.closest("button")) {
                status.innerHTML = "";
            }
        });

        button.addEventListener("keydown", function (e) {
            if ((e.key) === "Escape") {
                status.innerHTML = "";
            }
        });

        button.addEventListener("blur", function () {
            status.innerHTML = "";
        });
    }
}

customElements.define("defined-term", DefinedTerm);

