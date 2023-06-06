import "./bootstrap";

import Alpine from "alpinejs";
import mask from "@alpinejs/mask";
import "wicg-inert";

import confirmPassword from "./confirmPassword.js";
import datePicker from "./datePicker.js";
import enhancedCheckboxes from "./enhancedCheckboxes.js";
import modal from "./modal.js";
import "./vimeoPlayer.js";

window.Alpine = Alpine;
Alpine.plugin(mask);
Alpine.data("confirmPassword", confirmPassword);
Alpine.data("datePicker", datePicker);
Alpine.data("enhancedCheckboxes", enhancedCheckboxes);
Alpine.data("modal", modal);
Alpine.start();
