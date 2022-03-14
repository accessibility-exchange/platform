/**
 * @module cover-l
 * @description
 * A custom element for covering a block-level element horizontally,
 * with a max-width value representing the typographic measure
 * @property {String} [centered=h1] A simple selector such an element or class selector, representing the centered (main) element in the cover
 * @property {String} [space=var(--size-5)] The minimum space between and around all of the child elements
 * @property {String} [minHeight=100vh] The minimum height (block-size) for the **Cover**
 * @property {Boolean} [noPad=false] Whether the spacing is also applied as padding to the container element
 */
export default class Cover extends HTMLElement {
    constructor() {
        super();
        this.render = () => {
            this.i = `Cover-${[this.centered, this.space, this.minHeight, this.noPad].join("")}`;
            this.dataset.i = this.i;
            if (!document.getElementById(this.i)) {
                let styleEl = document.createElement("style");
                styleEl.id = this.i;
                styleEl.innerHTML = `
          [data-i="${this.i}"] {
            min-height: ${this.minHeight};
            padding: ${!this.noPad ? this.space : "0"};
          }

          [data-i="${this.i}"] > * {
            margin-block: ${this.space};
          }

          [data-i="${this.i}"] > :first-child:not(${this.centered}) {
            margin-block-start: 0;
          }

          [data-i="${this.i}"] > :last-child:not(${this.centered}) {
            margin-block-end: 0;
          }

          [data-i="${this.i}"] > ${this.centered} {
            margin-block: auto;
          }
        `.replace(/\s\s+/g, " ").trim();
                document.head.appendChild(styleEl);
            }
        };
    }

    get centered() {
        return this.getAttribute("centered") || "h1";
    }

    set centered(val) {
        return this.setAttribute("centered", val);
    }

    get space() {
        return this.getAttribute("space") || "var(--size-5)";
    }

    set space(val) {
        return this.setAttribute("space", val);
    }

    get minHeight() {
        return this.getAttribute("minHeight") || "100vh";
    }

    set minHeight(val) {
        return this.setAttribute("minHeight", val);
    }

    get noPad() {
        return this.hasAttribute("noPad");
    }

    set noPad(val) {
        if (val) {
            return this.setAttribute("noPad", "");
        } else {
            return this.removeAttribute("noPad");
        }
    }

    static get observedAttributes() {
        return ["centered", "space", "minHeight", "noPad"];
    }

    connectedCallback() {
        this.render();
    }

    attributeChangedCallback() {
        this.render();
    }
}

if ("customElements" in window) {
    customElements.define("cover-l", Cover);
}
